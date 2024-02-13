<?php

namespace App\email;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    private PHPMailer $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->setLanguage('br');
        $this->mail->isHTML(true);
        $this->mail->SMTPAuth = true;
        $this->mail->SMTPSecure = 'tls';
        $this->mail->CharSet = 'utf-8';

        $this->mail->Host = $_ENV['MAIL_HOST'];
        $this->mail->Port = $_ENV['MAIL_PORT'];
        $this->mail->Username = $_ENV['MAIL_USERNAME'];
        $this->mail->Password = $_ENV['MAIL_PASSWORD'];
    }
    public function send(array $from, array $address, string $subject, string $body)
    {
        $this->mail->setFrom(...$from);
        $this->mail->addAddress(...$address);
        $this->mail->Subject = $subject;
        $this->mail->Body = $body;

        $this->mail->send();
    }
}