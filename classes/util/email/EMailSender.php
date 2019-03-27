<?php
namespace classes\util\email {

    // Import PHPMailer classes into the global namespace
    // These must be at the top of your script, not inside a function
    require './vendor/autoload.php';

    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\PHPMailer;

    final class EmailSender
    {

        private $config = "";
        public function __construct()
        {
            $this->config = parse_ini_file("./app.ini");
        }

        public function sendSystemEmail($to, $subject, $html_message)
        {

            // Instantiation and passing `true` enables exceptions
            $mail = new PHPMailer(true);

            try {
                //Server settings
                //$mail->SMTPDebug = 0; // Enable verbose debug output

                // Set mailer to use SMTP
                $mail->isSMTP();
                $mail->Host = $this->config['SMTP_HOST'];
                $mail->SMTPAuth = true;
                $mail->Username = $this->config['SMTP_USERNAME'];
                $mail->Password = $this->config['SMTP_PASSWORD'];
                $mail->SMTPSecure = 'tls';
                $mail->Port = $this->config['SMTP_TLS_PORT'];

                //Recipients
                $mail->setFrom($this->config['SMTP_DEFAULT_SENDER'], $this->config['SMTP_DEFAULT_SENDER_NAME']);
                $mail->addAddress($to);

                // Attachments
                //$mail->addAttachment('/var/tmp/file.tar.gz'); // Add attachments
                //$mail->addAttachment('/tmp/image.jpg', 'new.jpg'); // Optional name

                // Content
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = $subject;
                //HTML BODY
                $mail->Body = $html_message;
                //NON HTML BODY
                //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                $mail->send();

            } catch (\Exception $e) {
                throw $e;
                //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }

        }

    }

}
