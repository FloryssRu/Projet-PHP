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
                    $this->redirect(parent::PATH_TO_SIGNUP_PAGE);
                    
                } else
                {
                    $session = new PHPSession;
                    $session->set('fail', "L'email de réinitialisation de mot de passe n'a pas pu être envoyé. Veuillez réécrire votre adresse email.");
                    $this->redirect(parent::PATH_TO_FORGOTTEN_PSWD);
                }

            } else
            {
                $session = new PHPSession;
                $session->set('fail', "L'email de réinitialisation de mot de passe n'a pas pu être envoyé. Veuillez réécrire votre adresse email.");
                $this->redirect(parent::PATH_TO_FORGOTTEN_PSWD);
            }
            
        } else
        {
            $session = new PHPSession;
            $session->set('fail', "Veuillez renseigner un email valide.");
            $this->redirect(parent::PATH_TO_FORGOTTEN_PSWD);
        }
    }

    public function handlerResetPassword(string $password, string $validPassword, string $uuid, string $token)
    {
        $session = new PHPSession;
        $fields = [$uuid, $password, $validPassword];
        if($this->isValid($fields) && $this->isSubmit('resetPassword') && $uuid != NULL && $password == $validPassword && $token == $session->get('token'))
        {
            $userManager = new UserManager('user');
            $idUser = $userManager->getIdByUuid($uuid);
            if($idUser == NULL) {
                $this->redirect(parent::PATH_TO_PSWD_RESET . $uuid);
            } else
            {
                $password = password_hash($password, PASSWORD_DEFAULT);
                $userData = $userManager->getById($idUser);
                $user = new User($userData['pseudo'], $password, $userData['email'], $userData['admin'], $userData['email_validated'], NULL);
                $userManager->update($user, $idUser);
                $userManager->setUuidNull($idUser);
                $session = new PHPSession;
                $session->set('success', "Votre mot de passe a bien été changé.");
                $this->redirect(parent::PATH_TO_SIGNUP_PAGE);
            }
        
        } elseif($password != $validPassword)
        {
            $session = new PHPSession;
            $session->set('fail', "Les mots de passe entrés ne sont pas identiques.");
            $this->redirect(parent::PATH_TO_PSWD_RESET . $session->get('uuid'));
        } else
        {
            $session = new PHPSession;
            $session->set('fail', "Votre réinitialisation de mot de passe a rencontré un problème. Veuillez recommencer.");
            $this->redirect(parent::PATH_TO_PSWD_RESET . $session->get('uuid'));
        }
    }

}