package org.example.server;

import com.itextpdf.io.exceptions.IOException;
import com.itextpdf.io.source.ByteArrayOutputStream;
import com.itextpdf.kernel.pdf.PdfDocument;
import com.itextpdf.kernel.pdf.PdfWriter;
import com.itextpdf.layout.Document;
import com.itextpdf.kernel.pdf.PdfWriter;
import com.itextpdf.layout.element.Paragraph;
import com.itextpdf.layout.element.Table;
import com.itextpdf.layout.element.Cell;
import com.itextpdf.layout.properties.TextAlignment;
import com.itextpdf.layout.properties.UnitValue;
import org.json.JSONArray;
import org.json.JSONObject;

import java.awt.image.BufferedImage;
import java.io.File;
import javax.imageio.ImageIO;
import javax.imageio.ImageReader;
import javax.mail.MessagingException;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.sql.*;
import java.util.Iterator;


public class DbConnection {
    // database connection parameters
    String url = "jdbc:mysql://localhost:3306/math-challenge";
    String username = "root";
    String password = "";
    Statement statement;
    private Connection connection;
    private File file;

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



    public void createParticipantRejected(String username, String firstname, String lastname, String emailAddress, String dob, String regNo) throws SQLException {
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

    public int getAttemptScore(JSONArray attempt, int participant, String username, String email, int challengeId) throws SQLException {
        long totalTimeTakenMillis = 0;
        int totalScore = 0;
        int totalQuestions = 0;
        int correctAnswers = 0;
        int incorrectAnswers = 0;
        int unattemptedQuestions = 0;
        int totalPossibleMarks = 0;

        String pdfFilePath = "results_report_" + username + "_challenge_" + challengeId + ".pdf";
        File pdfFile = new File(pdfFilePath);
        System.out.println("Attempting to create PDF: " + pdfFilePath);

        try (PdfWriter writer = new PdfWriter(new FileOutputStream(pdfFile));
             PdfDocument pdfDoc = new PdfDocument(writer);
             Document document = new Document(pdfDoc)) {

            document.add(new Paragraph("Results Report for Participant")
                    .setFontSize(18)
                    .setBold()
                    .setTextAlignment(TextAlignment.CENTER));
            document.add(new Paragraph("Username: " + username));
            document.add(new Paragraph("Email: " + email));
            document.add(new Paragraph("Challenge ID: " + challengeId));
            document.add(new Paragraph(""));

            Table table = new Table(UnitValue.createPercentArray(new float[]{1, 2, 2, 1, 2, 2}));
            table.setWidth(UnitValue.createPercentValue(100));

            table.addHeaderCell(new Cell().add(new Paragraph("Question_ID").setBold()));
            table.addHeaderCell(new Cell().add(new Paragraph("Your Answer").setBold()));
            table.addHeaderCell(new Cell().add(new Paragraph("Correct Answer").setBold()));
            table.addHeaderCell(new Cell().add(new Paragraph("Marks").setBold()));
            table.addHeaderCell(new Cell().add(new Paragraph("Result").setBold()));
            table.addHeaderCell(new Cell().add(new Paragraph("Time-Taken").setBold()));

            // Check if the first element is a JSONArray
            if (attempt.length() > 0 && attempt.get(0) instanceof JSONArray) {
                attempt = attempt.getJSONArray(0); // Get the nested JSONArray
            }

            totalQuestions = attempt.length();
            System.out.println("Received attempt array: " + attempt.toString());

            for (int i = 0; i < attempt.length(); i++) {
                JSONObject obj = attempt.getJSONObject(i);

                if (!obj.has("question_id") || !obj.has("time_taken")) {
                    System.err.println("JSONObject at index " + i + " is missing required keys: " + obj.toString());
                    continue;
                }

                String userAnswer = obj.optString("answer_text", "");
                int questionId = obj.getInt("question_id");
                long questionTimeTaken = obj.getLong("time_taken");
                totalTimeTakenMillis += questionTimeTaken;

                String sql = "SELECT answer_text, marks FROM answers WHERE question_id = ?";
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

                            table.addCell(new Cell().add(new Paragraph(String.valueOf(questionId))));
                            table.addCell(new Cell().add(new Paragraph(userAnswer)));
                            table.addCell(new Cell().add(new Paragraph(correctAnswer)));
                            table.addCell(new Cell().add(new Paragraph(String.valueOf(scoredMarks))));
                            table.addCell(new Cell().add(new Paragraph(result)));
                            table.addCell(new Cell().add(new Paragraph(questionTimeTaken / 1000 + " seconds")));

                            this.addAttempt(participant, questionId, result.equals("Correct"));
                        } else {
                            System.out.println("Question ID: " + questionId + " not found in the database.");
                        }
                    }
                }
            }

            document.add(table);

            double percentageScore = (totalPossibleMarks > 0) ? (Math.max(0, totalScore) * 100.0 / totalPossibleMarks) : 0;

            document.add(new Paragraph(""));
            document.add(new Paragraph("Summary").setFontSize(16).setBold());
            document.add(new Paragraph("Correct Answers: " + correctAnswers));
            document.add(new Paragraph("Incorrect Answers: " + incorrectAnswers));
            document.add(new Paragraph("Unattempted Questions: " + unattemptedQuestions));
            document.add(new Paragraph("Total Score: " + Math.max(0, totalScore) + " out of " + totalPossibleMarks));
            document.add(new Paragraph("Percentage Score: " + String.format("%.2f%%", percentageScore)));

            long totalMinutes = (totalTimeTakenMillis / 1000) / 60;
            long totalSeconds = (totalTimeTakenMillis / 1000) % 60;
            document.add(new Paragraph("Total Time Taken: " + totalMinutes + " minutes and " + totalSeconds + " seconds"));

        } catch (IOException e) {
            System.err.println("Error generating PDF: " + e.getMessage());
            e.printStackTrace();
            return Math.max(0, totalScore); // Return score even if PDF generation fails
        } catch (FileNotFoundException e) {
            throw new RuntimeException(e);
        } catch (java.io.IOException e) {
            throw new RuntimeException(e);
        }

        // Check if the PDF was created successfully
        if (!pdfFile.exists() || pdfFile.length() == 0) {
            System.err.println("PDF file was not created or is empty: " + pdfFilePath);
            return Math.max(0, totalScore); // Return score even if PDF is empty
        }

        // Send email with attachment
        try {
            String subject = "Your Challenge Results";
            String body = "Dear " + username + ",\n\nPlease find attached your results report for Challenge " + challengeId + ".";

            Email emailSender = new Email(); // Ensure this is properly initialized
            emailSender.sendEmailWithAttachment(email, subject, body, pdfFile);

            System.out.println("PDF generated and email sent successfully to: " + email);
        } catch (MessagingException e) {
            System.err.println("Error sending email: " + e.getMessage());
            e.printStackTrace();
        }

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
        String sqlCommand = "SELECT * FROM schools WHERE school_registration_number = ?";
        PreparedStatement preparedStatement = this.connection.prepareStatement(sqlCommand);
        preparedStatement.setString(1, regNo);
        return preparedStatement.executeQuery();
    }

    public ResultSet getSchool(String regNo) throws SQLException {
        String sqlCommand = "SELECT school_name FROM `schools` WHERE school_registration_number = ?";
        PreparedStatement pstmt = this.connection.prepareStatement(sqlCommand);
        pstmt.setString(1, regNo);
        return pstmt.executeQuery();
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
}
