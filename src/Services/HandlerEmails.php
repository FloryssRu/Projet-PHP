<?php

namespace App\Services;

use App\Controller\AuthenticateController;
use App\Entity\User;
use App\Repository\Manager\UserManager;
use App\Services\PHPSession;
use PHPMailer\PHPMailer\PHPMailer;
use Ramsey\Uuid\Nonstandard\Uuid;

class HandlerEmails extends AuthenticateController
{
    public function __construct()
    {

    }
    
    /**
     * Send an email with PHPMailer
     *
     * @param  string $receiverEmail
     * @param  string $title
     * @param  string $content The content in html
     * @return object
     */
    public function sendEmail(string $receiverEmail, string $title, string $content)
    {
        $mail = new PHPMailer();
        $emailConfig = json_decode(file_get_contents('../config/emailConfig.json'));
        $mail->isSendmail();
        $mail->Host = $emailConfig->smtp;
        $mail->Port = 465;
        $mail->SMTPSecure = 'tls';   
        $mail->SMTPAuth = true;
        $mail->Username = $emailConfig->emailOrigin;
        $mail->Password = $emailConfig->password;
        $mail->setFrom($emailConfig->emailOrigin);
        $mail->addAddress($receiverEmail);
        $mail->CharSet="utf-8"; 
        $mail->Subject = $title;
        $mail->msgHTML($content);
        return $mail;
    }
}