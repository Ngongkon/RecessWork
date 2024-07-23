

import javax.mail.*;
import javax.mail.internet.InternetAddress;
import javax.mail.internet.MimeMessage;
import java.util.Properties;

public class Email {
    String host = "smtp.gmail.com";
    String username = "group2confirmation@gmail.com";
    String from = "group2confirmation@gmail.com";
    String password = "alty tjrh sqex xlpt";
    Session session;

    public Email() {
        Properties properties = System.getProperties();
        properties.put("mail.smtp.host", host);
        properties.put("mail.smtp.port", "465");
        properties.put("mail.smtp.ssl.enable", "true");
        properties.put("mail.smtp.auth", "true");
        String username = this.username;
        String password = this.password;
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
        emailMessage.append("\tconfirm with:-> confirm yes/no " + username + "\n");

        message.setText(emailMessage.toString());

        Transport.send(message);

    }
    public void sendParticipantConfirmedEmail(String to, String participantName, String school) throws MessagingException {
        MimeMessage message = new MimeMessage(this.session);

        message.setFrom(new InternetAddress(this.from));

        message.addRecipient(Message.RecipientType.TO, new InternetAddress(to));

        message.setSubject("Notification of confirmation of Participant");

        StringBuilder emailMessage = new StringBuilder();
        emailMessage.append("Congratulations " + participantName + "\n\n");
        emailMessage.append("You have been accepted to participate in this challenge under: \n" + school +"\n\n" + "" +
                "Please enjoy this International Mathematics Challenge.");
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
        emailMessage.append("Dear " + participantName + ",\n\n");
        emailMessage.append("We regret to inform you that your registration request for " + school + " has been rejected.\n");
        emailMessage.append("If you have any questions, please contact the school administration.\n\n");
        emailMessage.append("Thank you for your interest.");

        message.setText(emailMessage.toString());

        Transport.send(message);
    }



}