<?php

namespace App\Services;

use App\Controller\HomeController;
use App\Repository\Manager\ContactManager;
use App\Repository\Manager\UserManager;

class HandlerSaveContactMessage extends HomeController
{
    /**
     * Is empty because the mother class has a construct function and we don't want to use it for this class.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    public function saveMessageHandler()
    {
        $session = new PHPSession;

        if($this->isSubmit('contactForm')
        && $this->isValid($_POST)
        && strlen($_POST['firstName']) <= 100
        && strlen($_POST['lastName']) <= 100
        && strlen($_POST['email'] <= 100)
        && htmlspecialchars($_POST['mentions']) == 'on'
        && preg_match('#^[a-zA-Z\.0-9\+]+@[a-zA-Z\.0-9]+\.[a-z]{0,5}$#', htmlspecialchars($_POST['email'])))
        {
            $title = htmlspecialchars($_POST['title']);
            $content = htmlspecialchars($_POST['content']);

            $arrayData = [
                'first_name' => htmlspecialchars($_POST['firstName']),
                'last_name' => htmlspecialchars($_POST['lastName']),
                'email' => htmlspecialchars($_POST['email']),
                'title' => $title,
                'content' => $content,
                'is_processed' => 0
            ];

            $cutContent = substr($content, 0, 200);
            if($cutContent != $content)
            {
                $cutContent .= '[...]';
            }

            $userManager = new UserManager('user');
            $adminsEmails = $userManager->getAdminsEmails();

            $baseEmails = new BaseEmails;

            $titleEmail = 'Nouveau message - Blog de Floryss Rubechi';
            $contentEmail = '<p>Vous venez de recevoir un nouveau message sur le site "Blog de Floryss Rubechi".</p>
            <p>Titre : <i>' . $title . '</i></p>
            <p>Contenu :</p>
            <p><i>' . $cutContent . '</i></p>
            <p>Pour voir la liste des messages reçus, veuillez cliquer sur le lien ci-dessous.</p>
            <p><a href="http://localhost/blogphp/liste-messages">Voir les messages</a></p>';

            $success = true;

            foreach($adminsEmails as $email)
            {
                $mail = $baseEmails->sendEmail($email, $titleEmail, $contentEmail);
                if (!$mail->send()) {
                    $success = false;
                }
            }

            if($success)
            {
                $contactManager = new ContactManager('contact');
                $contactManager->insert($arrayData);
                $session->set('success', 'Votre message a bien été envoyé. Nous vous répondrons par email dans les plus brefs délais.');
            } else
            {
                $session->set('fail', 'Votre message a rencontré un problème.');
            }

        } else
        {
            $session->set('fail', 'Votre message a rencontré un problème.');
            
        }

        return $this->redirect('/');
    }
}