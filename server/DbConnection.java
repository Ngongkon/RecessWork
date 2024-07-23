

import org.json.JSONArray;
import org.json.JSONObject;

import java.sql.*;

public class DbConnection {
    // database connection parameters
    String url = "jdbc:mysql://localhost:3306/math-challenge";
    String username = "root";
    String password = "";
    Statement statement;
    private Connection connection;

    public DbConnection() throws SQLException, ClassNotFoundException {
        Class.forName("com.mysql.cj.jdbc.Driver");

        this.connection = DriverManager.getConnection(this.url, this.username, this.password);
        this.statement = connection.createStatement();
    }

    public Connection getConnection() {
        return this.connection;
    }

    public void create(String sqlCommand) throws SQLException {
        this.statement.execute(sqlCommand);
    }

    public ResultSet read(String sqlCommand) throws SQLException {
        return this.statement.executeQuery(sqlCommand);
    }

    public void update(String sqlCommand) throws SQLException {
        this.statement.execute(sqlCommand);
    }

    public void delete(String sqlCommand) throws SQLException {
        this.statement.execute(sqlCommand);
    }

    public void close() throws SQLException {
        if (this.statement != null) this.statement.close();
        if (this.connection != null) this.connection.close();
    }

    public void createParticipant(String username, String firstname, String lastname, String emailAddress, String dob, String regNo, String imagePath) throws SQLException {
        String sql = "INSERT INTO `participants` (`username`, `firstname`, `lastname`, `email`, `date_of_birth`, `school_registration_number`, `image_file`) VALUES (?, ?, ?, ?, ?, ?, ?)";
        try (PreparedStatement stmt = this.connection.prepareStatement(sql)) {
            stmt.setString(1, username);
            stmt.setString(2, firstname);
            stmt.setString(3, lastname);
            stmt.setString(4, emailAddress);
            stmt.setString(5, dob);
            stmt.setString(6, regNo);
            stmt.setString(7, imagePath);
            stmt.executeUpdate();
        }
    }

    public void createParticipantRejected(String username, String firstname, String lastname, String emailAddress, String dob, String regNo, String imagePath) throws SQLException {
        String sql = "INSERT INTO `rejected_participants` (`username`, `firstname`, `lastname`, `email`, `date_of_birth`, `school_registration_number`) VALUES (?, ?, ?, ?, ?, ?)";
        try (PreparedStatement stmt = this.connection.prepareStatement(sql)) {
            stmt.setString(1, username);
            stmt.setString(2, firstname);
            stmt.setString(3, lastname);
            stmt.setString(4, emailAddress);
            stmt.setString(5, dob);
            stmt.setString(6, regNo);
            stmt.executeUpdate();
        }
    }

    public ResultSet getChallenges() throws SQLException {
        String sql = "SELECT * FROM `math-challenge`.`challenges` WHERE `start_date` <= CURRENT_DATE AND `end_date` >= CURRENT_DATE;";
        return this.statement.executeQuery(sql);
    }

    public ResultSet getChallengeQuestions(int challenge_id) throws SQLException {
        String sql = "SELECT q.id, q.question_text, a.id AS answer_id, a.answer_text, a.marks " +
                "FROM `math-challenge`.questions q " +
                "JOIN (SELECT id, num_questions FROM `math-challenge`.challenges WHERE id = ?) c ON q.id <= c.num_questions " +
                "LEFT JOIN `math-challenge`.answers a ON q.id = a.question_id " +
                "ORDER BY q.id";
        PreparedStatement preparedStatement = this.connection.prepareStatement(sql);
        preparedStatement.setInt(1, challenge_id);
        return preparedStatement.executeQuery();
    }
    public int getAttemptScore(JSONArray attempt, int participant) throws SQLException {
        long totalTimeTakenMillis = 0;

        // Calculate total time taken from the attempt data
        for (int i = 0; i < attempt.length(); i++) {
            JSONObject obj = attempt.getJSONObject(i);
            if (obj.has("time_taken")) {
                totalTimeTakenMillis += obj.getLong("time_taken");
            }
        }

        int totalScore = 0;
        int totalQuestions = attempt.length();
        int correctAnswers = 0;
        int incorrectAnswers = 0;
        int unattemptedQuestions = 0;
        int totalPossibleMarks = 0;

        System.out.println("\n--- Results Report for the Participant ---");
        System.out.println("Question| Your Answer | Correct Answer | Marks | Result | Time-Taken");
        System.out.println("-------------------------------------------------------------------------");

        for (int i = 0; i < attempt.length(); i++) {
            JSONObject obj = attempt.getJSONObject(i);

            if (!obj.has("question_id") || !obj.has("time_taken")) {
                System.err.println("JSONObject at index " + i + " is missing required keys: " + obj.toString());
                continue;
            }


            String userAnswer = obj.optString("answer_text", "");
            int questionId = obj.getInt("question_id");
            long questionTimeTaken = obj.getLong("time_taken");

            //sql query to selct correct answers from the database
            String sql = "SELECT `answer_text`, `marks` FROM `answers` WHERE `question_id` = ?";
            try (PreparedStatement pstmt = this.connection.prepareStatement(sql)) {
                pstmt.setInt(1, questionId);

                try (ResultSet rs = pstmt.executeQuery()) {
                    if (rs.next()) {
                        String correctAnswer = rs.getString("answer_text");
                        int marks = rs.getInt("marks");
                        totalPossibleMarks += marks;

                        int scoredMarks;
                        String result;

                        if (userAnswer.isEmpty()) {
                            scoredMarks = 0;
                            result = "Unattempted";
                            unattemptedQuestions++;
                        } else {
                            boolean isCorrect = userAnswer.trim().equalsIgnoreCase(correctAnswer.trim());
                            if (isCorrect) {
                                scoredMarks = marks;
                                result = "Correct";
                                correctAnswers++;
                            } else {
                                scoredMarks = -3;
                                result = "Incorrect";
                                incorrectAnswers++;
                            }
                        }

                        totalScore += scoredMarks;

                        System.out.printf("%-9d| %-12s| %-15s| %-6d| %-11s| %d seconds%n",
                                questionId, userAnswer, correctAnswer, scoredMarks, result, questionTimeTaken / 1000);

                        this.addAttempt(participant, questionId, result.equals("Correct"));
                    } else {
                        System.out.println("Question ID: " + questionId + " not found in the database.");
                    }
                }
            }
        }

        double percentageScore = (totalPossibleMarks > 0) ? (Math.max(0, totalScore) * 100.0 / totalPossibleMarks) : 0;

        System.out.println("-------------------------------------------------------------------------");
        System.out.printf("Correct Answers: %d%n", correctAnswers);
        System.out.printf("Incorrect Answers: %d%n", incorrectAnswers);
        System.out.printf("Unattempted Questions: %d%n", unattemptedQuestions);
        System.out.printf("Total Score: %d out of %d%n", Math.max(0, totalScore), totalPossibleMarks);
        System.out.printf("Percentage Score: %.2f%%%n", percentageScore);
        System.out.println();

        long totalMinutes = (totalTimeTakenMillis / 1000) / 60;
        long totalSeconds = (totalTimeTakenMillis / 1000) % 60;
        System.out.printf("Total Time Taken: %d minutes and %d seconds%n", totalMinutes, totalSeconds);

        return Math.max(0, totalScore);
    }

    public void createChallengeAttempt(JSONObject obj) throws SQLException {
        String sql = "INSERT INTO `participant_challenges` (`participant`, `challenge`, `marks`, `total`) VALUES (?, ?, ?, ?)";
        try (PreparedStatement ps = this.connection.prepareStatement(sql)) {
            ps.setInt(1, obj.getInt("participant_id"));
            ps.setInt(2, obj.getInt("challenge_id"));
            ps.setInt(3, obj.getInt("score"));
            ps.setInt(4, obj.getInt("total_score"));
            ps.executeUpdate();
        }
    }

    public String getSchoolName(String regNo) throws SQLException {
        String sqlCommand = "SELECT school_name FROM `schools` WHERE school_registration_number = ?";
        try (PreparedStatement pstmt = this.connection.prepareStatement(sqlCommand)) {
            pstmt.setString(1, regNo);
            try (ResultSet rs = pstmt.executeQuery()) {
                if (rs.next()) {
                    return rs.getString("school_name");
                }
            }
        }
        return "Unknown School";
    }

    public ResultSet getRepresentative(String regNo) throws SQLException {
        String sqlCommand = "SELECT * FROM `schools` WHERE school_registration_number = " + regNo + ";";
        return this.statement.executeQuery(sqlCommand);
    }

    public ResultSet getSchool(String regNo) throws SQLException {
        String sqlCommand = "SELECT school_name FROM `schools` WHERE school_registration_number = " + regNo + ";";
        return this.statement.executeQuery(sqlCommand);
    }

    public void addAttempt(int participant, int question, boolean status) throws SQLException {
        String sql = "INSERT INTO `attempts` (`status`, `question`, `participant`) VALUES (?, ?, ?)";
        try (PreparedStatement pstmt = this.connection.prepareStatement(sql)) {
            pstmt.setString(1, status ? "correct" : "wrong");
            pstmt.setInt(2, question);
            pstmt.setInt(3, participant);
            pstmt.executeUpdate();
        }
    }

}
