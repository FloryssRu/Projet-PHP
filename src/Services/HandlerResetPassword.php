<?php

namespace App\Services;

use App\Controller\AuthenticateController;
use App\Entity\User;
use App\Repository\Manager\UserManager;
use Ramsey\Uuid\Uuid;

class HandlerResetPassword extends AuthenticateController
{

    /**
     * Is empty because the mother class has a construct function and we don't want to use it for this class.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    public function handlerEmailResetPassword(string $email)
    {
        $fields = [$email];
        if($this->isValid($fields) && $this->isSubmit('emailResetPassword'))
        {
            $uuid = Uuid::uuid4();
            $uuid = $uuid->toString();

            $userManager = new UserManager('user');
            $emailOccupied = $userManager->getEmail($email);
            $idUser = $userManager->getIdByEmail($email)['id']; //returne faux si l'email n'a pas de propriétaire et je ne sais pas quoi sinon   

            if($emailOccupied && $idUser != NULL)
            {
                $baseEmails = new BaseEmails;
                $mail = $baseEmails->sendEmail($email, 'Réinitialisation de votre mot de passe - Blog de Floryss Rubechi', '<p>Réinitialisation de votre mot de passe</p>
                <p>Pour changer votre mot de passe, veuillez cliquer sur le lien ci-dessous</p>
                <p><a href="http://localhost/blogphp/reinitialisation-mot-de-passe?uuid=' . $uuid . '">Changer mon mot de passe !</a></p>
                <p>Si vous n\'êtes pas à l\'origine de cette demande, veuillez ignorer cet email.</p>');

                if ($mail->send())
                {
                    $userData = $userManager->getById($idUser);
                    $user = new User($userData['pseudo'], $userData['password'], $userData['email'], $userData['admin'], $userData['email_validated'], $uuid);
                    $userManager->update($user, $idUser);
                    $session = new PHPSession;
                    $session->set('success', "Un mail de réinitialisation de mot de passe vous a été envoyé.");
                    $this->redirect($this->PATH_TO_SIGNUP_PAGE);
                    
                } else
                {
                    $session = new PHPSession;
                    $session->set('fail', "L'email de réinitialisation de mot de passe n'a pas pu être envoyé. Veuillez réécrire votre adresse email.");
                    $this->redirect($this->PATH_TO_FORGOTTEN_PSWD);
                }

            } else
            {
                $session = new PHPSession;
                $session->set('fail', "L'email de réinitialisation de mot de passe n'a pas pu être envoyé. Veuillez réécrire votre adresse email.");
                $this->redirect($this->PATH_TO_FORGOTTEN_PSWD);
            }
            
        } else
        {
            $session = new PHPSession;
            $session->set('fail', "Veuillez renseigner un email valide.");
            $this->redirect($this->PATH_TO_FORGOTTEN_PSWD);
        }
    }

}