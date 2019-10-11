<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . "third_party/swiftmailer/vendor/autoload.php";
require APPPATH . "third_party/phpmailer/vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $config_email = array();
        require APPPATH . "third_party/config_email.php";
        $this->email_library = $config_email['mail_library'];
    }

    //send email
    public function send_email($to, $subject, $message, $show_unsubscribe, $link)
    {
        $data = array(
            'title' => $subject,
            'content' => $message,
            'email' => $to,
            'show_unsubscribe' => $show_unsubscribe,
            'link' => $link
        );

        //swift mailer
        if ($this->email_library == 'swift') {
            try {
                // Create the Transport
                $transport = (new Swift_SmtpTransport($this->general_settings->mail_host, $this->general_settings->mail_port, 'tls'))
                    ->setUsername($this->general_settings->mail_username)
                    ->setPassword($this->general_settings->mail_password);

                // Create the Mailer using your created Transport
                $mailer = new Swift_Mailer($transport);

                // Create a message
                $message = (new Swift_Message($this->settings->application_name))
                    ->setFrom(array($this->general_settings->mail_username => $this->settings->application_name))
                    ->setTo([$to => ''])
                    ->setSubject($subject)
                    ->setBody($this->load->view("email/email_template", $data, TRUE), 'text/html');

                //Send the message
                $result = $mailer->send($message);
                if ($result) {
                    return true;
                }
            } catch (\Swift_TransportException $Ste) {
                $this->session->set_flashdata('error', $Ste->getMessage());
                return false;
            } catch (\Swift_RfcComplianceException $Ste) {
                $this->session->set_flashdata('error', $Ste->getMessage());
                return false;
            }
        } //php mailer
        elseif ($this->email_library == 'php') {
            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host = $this->general_settings->mail_host;
                $mail->SMTPAuth = true;
                $mail->Username = $this->general_settings->mail_username;
                $mail->Password = $this->general_settings->mail_password;
                $mail->SMTPSecure = 'tls';
                $mail->CharSet = 'UTF-8';
                $mail->Port = $this->general_settings->mail_port;
                //Recipients
                $mail->setFrom($this->general_settings->mail_username, $this->settings->application_name);
                $mail->addAddress($data['email']);
                //Content
                $mail->isHTML(true);
                $mail->Subject = $data['title'];
                $mail->Body = $this->load->view("email/email_template", $data, TRUE);
                $mail->send();
                return true;
            } catch (Exception $e) {
                $this->session->set_flashdata('error', $mail->ErrorInfo);
                return false;
            }
        } else {
            $this->load->library('email');

            if ($this->general_settings->mail_protocol == "mail") {
                $config = Array(
                    'protocol' => 'mail',
                    'smtp_host' => $this->general_settings->mail_host,
                    'smtp_port' => $this->general_settings->mail_port,
                    'smtp_user' => $this->general_settings->mail_username,
                    'smtp_pass' => $this->general_settings->mail_password,
                    'smtp_timeout' => 30,
                    'mailtype' => 'html',
                    'charset' => 'utf-8',
                    'wordwrap' => TRUE
                );
            } else {
                $config = Array(
                    'protocol' => 'smtp',
                    'smtp_host' => $this->general_settings->mail_host,
                    'smtp_port' => $this->general_settings->mail_port,
                    'smtp_user' => $this->general_settings->mail_username,
                    'smtp_pass' => $this->general_settings->mail_password,
                    'smtp_timeout' => 30,
                    'mailtype' => 'html',
                    'charset' => 'utf-8',
                    'wordwrap' => TRUE
                );
            }

            //initialize
            $this->email->initialize($config);
            //send email
            $message = $this->load->view("email/email_template", $data, TRUE);
            $this->email->from($this->general_settings->mail_username, $this->settings->application_name);
            $this->email->to($data['email']);
            $this->email->subject($data['title']);
            $this->email->message($message);

            $this->email->set_newline("\r\n");

            if ($this->email->send()) {
                return true;
            } else {
                $this->session->set_flashdata('error', $this->email->print_debugger(array('headers')));
                return false;
            }
        }


    }
}