package org.example.server;

import org.json.*;

import javax.mail.MessagingException;
import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.nio.file.Paths;
import java.sql.Blob;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import com.itextpdf.kernel.pdf.PdfDocument;
import com.itextpdf.kernel.pdf.PdfWriter;
import com.itextpdf.layout.Document;
import com.itextpdf.layout.element.Paragraph;
import com.itextpdf.layout.element.Text;

public class Controller {
    JSONObject obj;

    public Controller(JSONObject obj) {
        this.obj = obj;
    }
    private JSONObject login(JSONObject obj) throws SQLException, ClassNotFoundException {
        // logic to log in a student this can work with isAuthenticated == false only (!isAuthenticated)
        DbConnection dbConnection = new DbConnection();

        JSONArray tokens = obj.getJSONArray("tokens");

        String username = tokens.get(1).toString();
        String email = tokens.get(2).toString();

        JSONObject clientResponse = new JSONObject();
        clientResponse.put("command", "login");
        clientResponse.put("username", username);
        clientResponse.put("email", email);

        String readParticipantQuery = "SELECT * FROM participants";
        ResultSet participantResultSet = dbConnection.read(readParticipantQuery);
        while (participantResultSet.next()) {
            if (username.equals(participantResultSet.getString("username")) && email.equals(participantResultSet.getString("email"))) {
                // there is a match here

                String regNo = participantResultSet.getString("school_registration_number");

                clientResponse.put("participant_id", participantResultSet.getInt("id"));
                clientResponse.put("school_registration_number", regNo);
                clientResponse.put("school_name","undefined");
                clientResponse.put("isStudent", true);
                clientResponse.put("isAuthenticated", true);
                clientResponse.put("status", true);

                return  clientResponse;
            }
        }


        String readRepresentativeQuery = "SELECT * FROM schools";
        ResultSet representativeResultSet = dbConnection.read(readRepresentativeQuery);
        while (representativeResultSet.next()) {
            if (username.equals(representativeResultSet.getString("representative_name")) && email.equals(representativeResultSet.getString("representative_email"))) {
                // there is a match

                String schoolName = representativeResultSet.getString("school_name");
                String regNo = representativeResultSet.getString("school_registration_number");

                clientResponse.put("participant_id", 0);
                clientResponse.put("school_name", schoolName);
                clientResponse.put("school_registration_number", regNo);
                clientResponse.put("isStudent", false);
                clientResponse.put("isAuthenticated", true);
                clientResponse.put("status", true);

                return clientResponse;
            }
        }

        clientResponse.put("isStudent", false);
        clientResponse.put("isAuthenticated", false);
        clientResponse.put("status", false);
        clientResponse.put("reason", "Invalid credentials. check the details provided");

        return clientResponse;
    }

    private JSONObject register(JSONObject obj) throws IOException, MessagingException, SQLException, ClassNotFoundException {
        // logic to register student this can work with isAuthenticated == false only (!isAuthenticated)
        Email emailAgent = new Email();
        DbConnection dbConnection = new DbConnection();

        JSONArray tokens = obj.getJSONArray("tokens");
        JSONObject participantObj = new JSONObject();
        participantObj.put("username", tokens.get(1));
        participantObj.put("firstname", tokens.get(2));
        participantObj.put("lastname", tokens.get(3));
        participantObj.put("email", tokens.get(4));
        participantObj.put("date_of_birth", tokens.get(5));
        participantObj.put("school_registration_number", tokens.get(6));
        participantObj.put("image_file", tokens.get(7));
        participantObj.put("tokenized_image", obj.getJSONObject("tokenized_image"));

        JSONObject clientResponse = new JSONObject();
        clientResponse.put("command", "register");

        ResultSet rs = dbConnection.getRepresentative(participantObj.getString("school_registration_number"));
        String representativeEmail;
        if (rs.next()) {
            representativeEmail = rs.getString("representative_email");
        } else {
            clientResponse.put("status", false);
            clientResponse.put("reason", "school does not exist in our database");

            return clientResponse;
        }


        LocalStorage localStorage = new LocalStorage("participants.json");
        if (!localStorage.read().toString().contains(participantObj.toString())) {
            localStorage.add(participantObj);
            clientResponse.put("status", true);
            clientResponse.put("reason", "Participant created successfully awaiting representative approval");

            emailAgent.sendParticipantRegistrationRequestEmail(representativeEmail, participantObj.getString("email"), participantObj.getString("username"));

            return clientResponse;
        }

        clientResponse.put("status", false);
        clientResponse.put("reason", "Participant creation failed found an existing participant object");

        return clientResponse;
    }

    private JSONObject attemptChallenge(JSONObject obj) throws SQLException, ClassNotFoundException {
        // logic to attempt a challenge respond with the random questions if user is eligible (student, isAuthenticated)
        JSONObject clientResponse = new JSONObject();
        JSONArray questions = new JSONArray();

        DbConnection dbConnection = new DbConnection();


//        dbConnection.read("SELECT `challenge_name` FROM `mtchallenge`")

        int challengeId = Integer.parseInt((String) new JSONArray(obj.get("tokens").toString()).get(1));
        ResultSet challengeQuestions;
        challengeQuestions = dbConnection.getChallengeQuestions(challengeId);


        while (challengeQuestions.next()) {
            JSONObject question = new JSONObject();
            question.put("id", challengeQuestions.getString("id"));
            question.put("question", challengeQuestions.getString("question_text"));
//            question.put("score", challengeQuestions.getString("score"));

            questions.put(question);
        }

        clientResponse.put("command", "attemptChallenge");
        clientResponse.put("questions", questions);
        clientResponse.put("challenge_id", challengeId);
        clientResponse.put("challenge_name", challengeId);
        return clientResponse;
    }

    private JSONObject viewChallenges(JSONObject obj) throws SQLException, ClassNotFoundException {
        JSONObject clientResponse = new JSONObject();

        DbConnection dbConnection = new DbConnection();
        ResultSet availableChallenges = dbConnection.getChallenges();
        JSONArray challenges = new JSONArray();

        while (availableChallenges.next()) {
            JSONObject challenge = new JSONObject();
            challenge.put("id", availableChallenges.getInt("id"));
            challenge.put("challenge_name", availableChallenges.getString("challenge_name"));
            challenge.put("duration", availableChallenges.getInt("duration"));
            challenge.put("start_date", availableChallenges.getDate("start_date"));
            challenge.put("end_date", availableChallenges.getDate("end_date"));

            challenges.put(challenge);
        }

        clientResponse.put("command", "viewChallenges");
        clientResponse.put("challenges", challenges.toString());

        return clientResponse;
    }

    private JSONObject confirm(JSONObject obj) throws IOException, SQLException, ClassNotFoundException, MessagingException {
        // Initialize file storage for participants
        LocalStorage fileStorage = new LocalStorage("participants.json");

        // Extract username from object
        String username = obj.getString("username");
        JSONObject participant = fileStorage.readEntryByUserName(username);

        // Prepare client response JSON object
        JSONObject clientResponse = new JSONObject();
        clientResponse.put("command", "confirm");

        // Check if participant exists
        if (participant.isEmpty()) {
            clientResponse.put("status", false);
            clientResponse.put("reason", "Invalid command. Check the username provided");
            return clientResponse;
        }

        // Initialize database connection
        DbConnection dbConnection = new DbConnection();
        if (obj.getBoolean("confirm")) {
            String pic_path = participant.getString("username") + "_" + participant.getString("school_registration_number") + ".jpg";
            JSONObject tokenObj = participant.getJSONObject("tokenized_image");
            saveProfileImage(tokenObj, pic_path);


            dbConnection.createParticipant(participant.getString("username"), participant.getString("firstname"),
                    participant.getString("lastname"), participant.getString("email"),
                    participant.getString("date_of_birth"), participant.getString("school_registration_number"), participant.getString("image_file"));
            fileStorage.deleteEntryByUserName(username);
            clientResponse.put("reason", participant.getString("firstname") + " " + participant.getString("lastname") +
                    " (" + participant.getString("email") + ") confirmed successfully");

            Email email = new Email();
            ResultSet rs = dbConnection.getSchool(participant.getString("school_registration_number"));

            if (rs.next()) {}
            String schoolName = rs.getString("school_name");
            email.sendParticipantConfirmedEmail(participant.getString("email"), participant.getString("username"), schoolName);

        }
        else

        {
            dbConnection.createParticipantRejected(participant.getString("username"), participant.getString("firstname"),
                    participant.getString("lastname"), participant.getString("email"),
                    participant.getString("date_of_birth"), participant.getString("school_registration_number"));
            fileStorage.deleteEntryByUserName(username);
            clientResponse.put("reason", participant.getString("firstname") + " " + participant.getString("lastname") +
                    " (" + participant.getString("email") + ") rejected successfully");


        }
        clientResponse.put("status", true);
        return clientResponse;
    }
    private static void saveProfileImage(JSONObject s, String pic_path) {
        File file = Paths.get("C:", "Users", "DELL", "IdeaProjects", "G-2", "imgs", pic_path).toFile();

        // Create parent directories if they don't exist
        if (!file.getParentFile().exists() && !file.getParentFile().mkdirs()) {
            System.err.println("Failed to create directory structure for: " + file.getParentFile());
            return;
        }

        try (FileOutputStream fileOutputStream = new FileOutputStream(file)) {
            JSONArray arr = s.getJSONArray("data");

            for (int i = 0; i < arr.length(); i++) {
                JSONObject o = arr.getJSONObject(i);

                if (!o.has("buffer") || !o.has("size")) {
                    System.err.println("Error: Buffer or size key is missing in the image data at index " + i);
                    continue;
                }

                byte[] buffer = jsonArrayToBytes(o.getJSONArray("buffer"));
                int size = o.getInt("size");

                if (buffer.length < size) {
                    System.err.println("Warning: Declared size is larger than actual buffer length at index " + i);
                    size = buffer.length;
                }

                fileOutputStream.write(buffer, 0, size);
            }

            System.out.println("File saved as: " + file.getAbsolutePath());
        } catch (IOException e) {
            System.err.println("IO error while saving file: " + e.getMessage());
            e.printStackTrace();
        } catch (JSONException e) {
            System.err.println("JSON parsing error: " + e.getMessage());
            System.err.println("JSON content: " + s.toString());
            e.printStackTrace();
        }
    }

    public static byte[] jsonArrayToBytes(JSONArray array) {
        byte[] bytes = new byte[array.length()];
        for (int i = 0; i < array.length(); i++) {
            bytes[i] = (byte) array.getInt(i);
        }
        return bytes;
    }


    private JSONObject viewApplicants(JSONObject obj) throws IOException {
        // logic to confirm registered students (representatives, isAuthenticated)
        String regNo = obj.getString("school_registration_number");

        LocalStorage localStorage = new LocalStorage("participants.json");

        String participants = localStorage.filterParticipantsByRegNo(regNo);

        JSONObject clientResponse = new JSONObject();
        clientResponse.put("command", "viewApplicants");
        clientResponse.put("applicants", participants);


        return clientResponse;
    }
    public JSONObject attempt(JSONObject obj) throws SQLException, ClassNotFoundException, IOException, MessagingException {
        JSONArray attempt = obj.getJSONArray("attempt");
        DbConnection dbConnection = new DbConnection();

        // Retrieve additional information
        int participantId = obj.getInt("participant_id");
        int challengeId = obj.getInt("challenge_id");

        // Check if the participant has already attempted the challenge
        if (hasAttemptedChallenge(participantId, challengeId, dbConnection)) {
            JSONObject result = new JSONObject();
            result.put("message", "You have already attempted this challenge.");
            return result;
        }

        // Fetch participant details from the database
        String username = "";
        String email = "";
        try (PreparedStatement pstmt = dbConnection.getConnection().prepareStatement(
                "SELECT username, email FROM participants WHERE id = ?")) {
            pstmt.setInt(1, participantId);
            try (ResultSet rs = pstmt.executeQuery()) {
                if (rs.next()) {
                    username = rs.getString("username");
                    email = rs.getString("email");
                }
            }
        }

        // Call getAttemptScore with the new parameters
        int score = dbConnection.getAttemptScore(attempt, participantId, username, email, challengeId);

        JSONObject attemptEvaluation = new JSONObject();
        attemptEvaluation.put("score", score);
        attemptEvaluation.put("participant_id", participantId);
        attemptEvaluation.put("challenge_id", challengeId);
        attemptEvaluation.put("total_score", obj.getInt("total_score"));

        dbConnection.createChallengeAttempt(attemptEvaluation);

        // Return some information about the attempt
        JSONObject result = new JSONObject();
        result.put("score", score);
        result.put("message", "Attempt recorded successfully and email sent.");

        return result;
    }

    private boolean hasAttemptedChallenge(int participantId, int challengeId, DbConnection dbConnection) throws SQLException {
        String query = "SELECT COUNT(*) FROM participant_challenges WHERE participant = ? AND challenge = ?";
        try (PreparedStatement pstmt = dbConnection.getConnection().prepareStatement(query)) {
            pstmt.setInt(1, participantId);
            pstmt.setInt(2, challengeId);
            try (ResultSet rs = pstmt.executeQuery()) {
                if (rs.next()) {
                    return rs.getInt(1) > 0;
                }
            }
        }
        return false;
    }

    public JSONObject run() throws IOException, SQLException, ClassNotFoundException, MessagingException {
        switch (this.obj.get("command").toString()) {
            case "login":
                // call login logic
                return this.login(this.obj);

            case "register":
                // call login logic
                return this.register(this.obj);

            case "viewChallenges":
                // call login logic
                return this.viewChallenges(this.obj);

            case "attemptChallenge":
                // call login logic
                return this.attemptChallenge(this.obj);

            case "confirm":
                // call login logic
                return this.confirm(this.obj);

            case "viewApplicants":
                return this.viewApplicants(this.obj);

            case "attempt":
                // handle attempts here
                return this.attempt(this.obj);

            default:
                // command unresolved
                JSONObject outputObj = new JSONObject();
                outputObj.put("command", "exception");
                outputObj.put("reason", "Invalid command");

                return outputObj;
        }
    }

}

