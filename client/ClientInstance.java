

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
        final int TIME_LIMIT_MINUTES = 10;

        long challengeStartTime = System.currentTimeMillis(); // Start timing for the entire challenge

        // Create a list of all question indices
        List<Integer> allQuestionIndices = new ArrayList<>();
        for (int i = 0; i < questionsArray.length(); i++) {
            allQuestionIndices.add(i);
        }

        for (int attempt = 1; attempt <= MAX_ATTEMPTS; attempt++) {
            System.out.println("\n-------------------------------------------------------------------------");
            System.out.println(" ___ Mathematics Challenge 2024 - Attempt " + attempt + " of " + MAX_ATTEMPTS + " ___");
            System.out.println("\nTime: " + TIME_LIMIT_MINUTES + " minutes");
            System.out.println("Instructions:");
            System.out.println("Attempt all the " + QUESTIONS_PER_ATTEMPT + " questions given below.");
            System.out.println("For every correct answer you earn allocated marks, Every wrong answer " +
                    "\nyou are deducted 3 marks and no mark shall be allocated to unattempted question.  ");
            System.out.println("---------------------------------------------------------------------------\n");

            JSONArray solutions = new JSONArray();

               // Shuffle all question indices
            Collections.shuffle(allQuestionIndices);

            // Randomly select QUESTIONS_PER_ATTEMPT indices
            List<Integer> selectedIndices = new ArrayList<>();
            Random random = new Random();
            for (int i = 0; i < QUESTIONS_PER_ATTEMPT && !allQuestionIndices.isEmpty(); i++) {
                int randomIndex = random.nextInt(allQuestionIndices.size());
                selectedIndices.add(allQuestionIndices.get(randomIndex));
                allQuestionIndices.remove(randomIndex);
            }


            long startTime = System.currentTimeMillis();
            long endTime = startTime + (TIME_LIMIT_MINUTES * 60 * 1000);

            for (int i = 0; i < selectedIndices.size(); i++) {
                JSONObject question = questionsArray.getJSONObject(selectedIndices.get(i));
                JSONObject answer = new JSONObject();

                long currentTime = System.currentTimeMillis();
                if (currentTime >= endTime) {
                    System.out.println("Time's up!");
                    break;
                }

                int remainingTime = (int)((endTime - currentTime) / 60000);
                int remainingQuestions = selectedIndices.size() - i;

                System.out.println("\nQuestion " + (i + 1) + " of " + selectedIndices.size());
                System.out.println("Remaining time: " + remainingTime + " minutes");
                System.out.println("Remaining questions: " + remainingQuestions);

                int questionId;
                try {
                    questionId = Integer.parseInt(question.getString("id"));
                } catch (JSONException | NumberFormatException e) {
                    System.err.println("Question ID not found or invalid for question: " + question.toString());
                    continue;
                }

                String questionText = question.optString("question", "Question text not found");
                int marks = question.optInt("marks", 3);

                System.out.println((i + 1) + ". " + questionText + " (" + marks + " Marks)");

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

            finalSolutions = solutions; // Store the last attempt's solutions

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

        return finalSolutions;
    }

    public void start() throws IOException {
        // Todo: create a parent menu

        // execute code for interacting with the server
        try (
                Socket socket = new Socket(hostname, port);
                BufferedReader input = new BufferedReader(new InputStreamReader(socket.getInputStream()));
                PrintWriter output = new PrintWriter(socket.getOutputStream(), true);
                BufferedReader consoleInput = new BufferedReader(new InputStreamReader(System.in));
        ) {
            this.clientId = (String) socket.getInetAddress().getHostAddress();
            Serializer serializer = new Serializer(this.user);

            printMenu();

            System.out.print("[" + this.clientId + "] (" + this.user.username + ") -> ");
            // read command line input

            // Continuously read from the console and send to the server
            ClientController clientController = new ClientController(user);
            String regex = "^\\{.*\\}$";
            Pattern pattern = Pattern.compile(regex);


            String userInput;
            while ((userInput = consoleInput.readLine()) != null) {
                // send command to the server

                if (userInput.equals("logout") && (this.user.isAuthenticated)) {
                    System.out.println("Session successfully logged out");
                    this.user.logout();
                    System.out.print("[" + this.clientId + "] (" + (!this.user.username.isBlank() ? this.user.username : null) + ") -> ");
                    continue;
                }

                String serializedCommand = serializer.serialize(userInput);

                if (isValid(serializedCommand)) {
                    output.println(serializedCommand);

                    // read response here from the server
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
                // prompt for the next instruction
                System.out.print("[" + this.clientId + "] (" + this.user.username + ") -> ");
            }
        } catch (Exception e) {
            e.printStackTrace();
        } finally {
            System.out.println("Connection with the server timeout");
        }
    }

    private void printMenu() {
        System.out.println("\n *****  MATHEMATICS  CHALLENGE  MENU   *****");

        System.out.println("Register your details in this format: \n\n" +
                "(register username firstname lastname emailAddress date_of_birth school_registration_number path_to_image):\n");
        System.out.println("Enter login to access this menu:\n");
        System.out.println("viewChallenges");
        System.out.println("attemptChallenge");
        System.out.println("viewApplicants");
        System.out.println("confirm yes/no \n\n");
    }

//    public JSONObject submitAttempt(JSONObject challengeResult) {
//        return ;
//    }
}
