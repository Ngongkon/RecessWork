package org.example.client;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.*;
import java.net.Socket;
import java.sql.SQLException;
import java.util.*;
import java.util.regex.Pattern;

public class ClientInstance {
    // define attributes for the ClientInstance object
    String hostname;
    int port;
    String clientId;
    User user;
    byte cache;
    boolean isStudent;
    boolean isAuthenticated;

    public ClientInstance(String hostname, int port, User user) {
        // constructor class for the client instance
        this.hostname = hostname;
        this.port = port;
        this.user = user;
    }

    public static boolean isValid(String input) {
        String regex = "^\\{.*\\}$";
        Pattern pattern = Pattern.compile(regex, Pattern.DOTALL);
        return pattern.matcher(input).matches();
    }
    public JSONArray displayQuestionSet(JSONObject challengeObj) throws SQLException {
        System.out.println("CHALLENGE: " + challengeObj.toString());
        Scanner scanner = new Scanner(System.in);
        JSONArray finalSolutions = new JSONArray();
        JSONArray questionsArray = challengeObj.getJSONArray("questions");

        final int MAX_ATTEMPTS = 3;
        final int QUESTIONS_PER_ATTEMPT = 10;
        final int challengeDuration = challengeObj.optInt("duration", 10);

        if (!challengeObj.has("duration")) {
            System.out.println("Warning: 'duration' not found in challenge object. Using default value of 10 minutes.");
        }
        long challengeStartTime = System.currentTimeMillis();

        int totalScore = 0;

        for (int attempt = 1; attempt <= MAX_ATTEMPTS; attempt++) {
            System.out.println("\n-------------------------------------------------------------------------");
            System.out.println(" ___ Mathematics Challenge 2024 - Attempt " + attempt + " of " + MAX_ATTEMPTS + " ___");
            System.out.println("\nTime: " + challengeDuration + " minutes");
            System.out.println("Instructions:");
            System.out.println("Attempt all the " + QUESTIONS_PER_ATTEMPT + " questions given below.");
            System.out.println("For every correct answer, you earn allocated marks. For every wrong answer, " +
                    "you are deducted 3 marks, and no mark shall be allocated to an unattempted question.");
            System.out.println("---------------------------------------------------------------------------\n");

            JSONArray solutions = new JSONArray();

            // Shuffle the list of question indices anew for each attempt
            List<Integer> allQuestionIndices = new ArrayList<>();
            for (int i = 0; i < questionsArray.length(); i++) {
                allQuestionIndices.add(i);
            }
            Collections.shuffle(allQuestionIndices);

            List<Integer> selectedIndices = allQuestionIndices.subList(0, Math.min(QUESTIONS_PER_ATTEMPT, allQuestionIndices.size()));
            System.out.println("Selected question indices for attempt " + attempt + ": " + selectedIndices);

            long startTime = System.currentTimeMillis();
            long endTime = startTime + (challengeDuration * 60 * 1000);

            int attemptScore = 0;

            for (int i = 0; i < selectedIndices.size(); i++) {
                JSONObject question = questionsArray.getJSONObject(selectedIndices.get(i));
                JSONObject answer = new JSONObject();

                long currentTime = System.currentTimeMillis();
                if (currentTime >= endTime) {
                    System.out.println("Time's up!");
                    break;
                }

                int remainingTime = (int) ((endTime - currentTime) / 60000);
                int remainingQuestions = selectedIndices.size() - i;

                System.out.println("\nQuestion " + (i + 1) + " of " + selectedIndices.size());
                System.out.println("Remaining time: " + remainingTime + " minutes");
                System.out.println("Remaining questions: " + remainingQuestions);

                // Extract question ID properly
                int questionId = question.optInt("id", -1);
                if (questionId == -1) {
                    System.err.println("Question ID not found or invalid for question: " + question.toString());
                    continue;
                }

                String questionText = question.optString("question", "Question text not found");
                int marks = question.optInt("marks", 3);

                System.out.println((i + 1) + ". " + questionText + " (" + marks + " marks)");

                long questionStartTime = System.currentTimeMillis();
                System.out.print(" - Your answer: ");
                String userAnswer = scanner.nextLine();
                long questionEndTime = System.currentTimeMillis();
                long questionTimeTaken = questionEndTime - questionStartTime;

                answer.put("question_id", questionId);
                answer.put("answer_text", userAnswer);
                answer.put("time_taken", questionTimeTaken);

                solutions.put(answer);
                System.out.println();
            }

            totalScore += attemptScore;
            finalSolutions.put(solutions);

            System.out.println("Attempt " + attempt + " Completed.");

            if (attempt < MAX_ATTEMPTS) {
                System.out.print("Do you want to try again? (yes/no): ");
                if (!scanner.nextLine().trim().equalsIgnoreCase("yes")) {
                    break;
                }
            }
        }

        long challengeEndTime = System.currentTimeMillis();
        long totalTimeTakenMillis = challengeEndTime - challengeStartTime;

        System.out.println("Challenge Completed. Thank you for participating!!");
        System.out.println("Your Total Score: " + totalScore);

        return finalSolutions;
    }

    public void start() throws IOException {
        try (
                Socket socket = new Socket(hostname, port);
                BufferedReader input = new BufferedReader(new InputStreamReader(socket.getInputStream()));
                PrintWriter output = new PrintWriter(socket.getOutputStream(), true);
                BufferedReader consoleInput = new BufferedReader(new InputStreamReader(System.in));
        ) {
            this.clientId = (String) socket.getInetAddress().getHostAddress();
            Serializer serializer = new Serializer(this.user);
            ClientController clientController = new ClientController(user);
            String regex = "^\\{.*\\}$";
            Pattern pattern = Pattern.compile(regex);

            while (true) {
                printMenu();
                System.out.print("[" + this.clientId + "] (" + (this.user.username != null ? this.user.username : "Not logged in") + ") -> ");

                String userInput;
                while ((userInput = consoleInput.readLine()) != null) {
                    if (userInput.equals("logout") && (this.user.isAuthenticated)) {
                        System.out.println("Session successfully logged out");
                        this.user.logout();
                        break;  // Break the inner loop to show the menu again
                    }

                    if (userInput.equalsIgnoreCase("exit")) {
                        System.out.println("Exiting the program. Goodbye!");
                        return;  // Exit the method, which will close the program
                    }

                    String serializedCommand = serializer.serialize(userInput);

                    if (isValid(serializedCommand)) {
                        output.println(serializedCommand);
                        String response = input.readLine();
                        this.user = clientController.exec(response);

                        if (!pattern.matcher(this.user.output).matches()) {
                            System.out.println("\n" + user.output + "\n");
                        } else {
                            JSONObject questions = new JSONObject(this.user.output);
                            JSONArray answerSet = displayQuestionSet(questions);

                            JSONObject obj = new JSONObject();
                            obj.put("attempt", answerSet);
                            obj.put("participant_id", this.user.id);
                            obj.put("command", "attempt");
                            obj.put("challenge_id", questions.getInt("challenge_id"));
                            obj.put("total_score", this.cache);

                            String inp = obj.toString();
                            output.println(inp);
                        }
                    } else {
                        System.out.println(serializedCommand);
                    }

                    System.out.print("[" + this.clientId + "] (" + (this.user.username != null ? this.user.username : "Not logged in") + ") -> ");
                }
            }
        } catch (Exception e) {
            e.printStackTrace();
        } finally {
            System.out.println("Connection with the server timeout");
        }
    }

    private void printMenu() {
        System.out.println("------------------------------------------------------------------------------------------------------");
        System.out.println("\n *****  MATHEMATICS  CHALLENGE  MENU   *****");
        System.out.println("Register your details in this format: \n\n" +
                "(register username firstname lastname emailAddress date_of_birth school_registration_number path_to_image):\n");
        System.out.println("Enter login to access this menu:\n");
        System.out.println("viewChallenges");
        System.out.println("attemptChallenge");
        System.out.println("viewApplicants");
        System.out.println("confirm yes/no");
        System.out.println("logout");
        System.out.println("-----------------------------------------------------------------------------------------------------");

    }
}