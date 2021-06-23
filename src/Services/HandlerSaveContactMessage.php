<?php

namespace App\Services;

use App\Controller\HomeController;
use App\Entity\Contact;
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

        $message = new Contact('contact');
        $message->hydrate($message, $_POST);

        if($this->isSubmit('contactForm')
        && $this->isValid($message)
        && strlen($_POST['firstName']) <= 100
        && strlen($_POST['lastName']) <= 100
        && strlen($_POST['email'] <= 100)
        && $_POST['mentions'] == 'on'
        && preg_match('#^[a-zA-Z\.0-9\+]+@[a-zA-Z\.0-9]+\.[a-z]{0,5}$#', $_POST['email'])
        && isset($_POST['firstName'])
        && isset($_POST['lastName'])
        && isset($_POST['email'])
        && isset($_POST['title'])
        && isset($_POST['content']))
        {
            $title = $_POST['title'];
            $content = $_POST['content'];

            $arrayData = [
                'first_name' => $_POST['firstName'],
                'last_name' => $_POST['lastName'],
                'email' => $_POST['email'],
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
                $mail->send();
                if (!$mail->send()) {
                    $success = false;
                }
            }

            if($success)
            {
                $contactManager = new ContactManager('contact');
                $contactManager->insert($arrayData);
                $session->set('success', 'Votre message a bien été envoyé.');
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