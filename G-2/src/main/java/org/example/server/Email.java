package org.example.server;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import javax.activation.DataHandler;
import javax.activation.DataSource;
import javax.activation.FileDataSource;
import javax.mail.*;
import javax.mail.internet.*;
import java.io.File;
import java.util.Properties;

public class Email {
    private static final Logger logger = LoggerFactory.getLogger(Email.class);
    String host = "smtp.gmail.com";
    String username = "group2confirmation@gmail.com";
    String from = "group2confirmation@gmail.com";
    String password = "alty tjrh sqex xlpt";
    Session session;

    public Email() {
        Properties properties = new Properties();
        properties.put("mail.smtp.host", host);
        properties.put("mail.smtp.port", "465");
        properties.put("mail.smtp.ssl.enable", "true");
        properties.put("mail.smtp.auth", "true");

        this.session = Session.getInstance(properties, new javax.mail.Authenticator() {
            protected PasswordAuthentication getPasswordAuthentication() {
                return new PasswordAuthentication(username, password);
            }
        });
        this.session.setDebug(true);
    }

    public void sendParticipantRegistrationRequestEmail(String to, String participantEmail, String username) throws MessagingException {
        MimeMessage message = new MimeMessage(this.session);
        message.setFrom(new InternetAddress(this.from));
        message.addRecipient(Message.RecipientType.TO, new InternetAddress(to));
        message.setSubject("Notification of registration under your school");

        StringBuilder emailMessage = new StringBuilder();
        emailMessage.append("New participant notification\n\n");
        emailMessage.append("This message is to notify you of a new participant's request to register under your school\n\n");
        emailMessage.append("The participant details are as below\n");
        emailMessage.append("\tUsername: ").append(username).append("\n");
        emailMessage.append("\temail: ").append(participantEmail).append("\n");
        emailMessage.append("\nTo verify this participant please login into the command line and confirm with the commands\n");
        emailMessage.append("\tconfirm with:-> confirm yes/no ").append(username).append("\n");

        message.setText(emailMessage.toString());
        Transport.send(message);
    }

    public void sendParticipantConfirmedEmail(String to, String participantName, String school) throws MessagingException {
        MimeMessage message = new MimeMessage(this.session);
        message.setFrom(new InternetAddress(this.from));
        message.addRecipient(Message.RecipientType.TO, new InternetAddress(to));
        message.setSubject("Notification of confirmation of Participant");

        StringBuilder emailMessage = new StringBuilder();
        emailMessage.append("Congratulations ").append(participantName).append("\n\n");
        emailMessage.append("You have been accepted to participate in this challenge under: \n").append(school).append("\n\n");
        emailMessage.append("Please enjoy this International Mathematics Challenge.");
        emailMessage.append("If you have any questions, please contact the school administration.\n\n");

        message.setText(emailMessage.toString());
        Transport.send(message);
    }

    public void sendParticipantRejectedEmail(String to, String participantName, String school) throws MessagingException {
        MimeMessage message = new MimeMessage(this.session);
        message.setFrom(new InternetAddress(this.from));
        message.addRecipient(Message.RecipientType.TO, new InternetAddress(to));
        message.setSubject("Notification of Registration Rejection");

        StringBuilder emailMessage = new StringBuilder();
        emailMessage.append("Dear ").append(participantName).append(",\n\n");
        emailMessage.append("We regret to inform you that your registration request for ").append(school).append(" has been rejected.\n");
        emailMessage.append("If you have any questions, please contact the school administration.\n\n");
        emailMessage.append("Thank you for your interest.");

        message.setText(emailMessage.toString());
        Transport.send(message);
    }

    public void sendEmailWithAttachment(String to, String subject, String body, File file) throws MessagingException {
        if (to == null || to.isEmpty()) {
            throw new IllegalArgumentException("Recipient email address cannot be null or empty");
        }
        if (file == null) {
            throw new IllegalArgumentException("Attachment file cannot be null");
        }
        if (!file.exists()) {
            throw new IllegalArgumentException("Attachment file does not exist: " + file.getAbsolutePath());
        }

        MimeMessage message = new MimeMessage(this.session);
        try {
            message.setFrom(new InternetAddress(this.from));
            message.addRecipient(Message.RecipientType.TO, new InternetAddress(to));
            message.setSubject(subject);

            // Create the message part with the body text
            BodyPart messageBodyPart = new MimeBodyPart();
            messageBodyPart.setText(body);

            // Create a multipart message for attachment
            Multipart multipart = new MimeMultipart();
            multipart.addBodyPart(messageBodyPart);

            // Add the file attachment
            messageBodyPart = new MimeBodyPart();
            DataSource source = new FileDataSource(file);
            messageBodyPart.setDataHandler(new DataHandler(source));
            messageBodyPart.setFileName(file.getName());
            multipart.addBodyPart(messageBodyPart);

            // Set the complete message parts
            message.setContent(multipart);

            // Send the message
            Transport.send(message);
        } catch (MessagingException e) {
            logger.error("Error sending email with attachment: " + e.getMessage(), e);
            throw new MessagingException("Failed to send email with attachment", e);
        }
    }
}
