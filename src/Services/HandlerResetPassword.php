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
        $user = new User();
        $user->hydrate($user, ['email' => $email]);

        if($this->isValid($user)
        && $this->isSubmit('emailResetPassword')
        && filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $uuid = Uuid::uuid4();
            $uuid = $uuid->toString();

            $userManager = new UserManager('user');
            $emailOccupied = $userManager->getEmail($email);
            $user = $userManager->getIdByEmail($email);

            if($emailOccupied && $user->getId() != NULL)
            {
                $baseEmails = new BaseEmails;
                $mail = $baseEmails->sendEmail($email, 'Réinitialisation de votre mot de passe - Blog de Floryss Rubechi', '<p>Réinitialisation de votre mot de passe</p>
                <p>Pour changer votre mot de passe, veuillez cliquer sur le lien ci-dessous</p>
                <p><a href="http://localhost/blogphp/reinitialisation-mot-de-passe?uuid=' . $uuid . '">Changer mon mot de passe !</a></p>
                <p>Si vous n\'êtes pas à l\'origine de cette demande, veuillez ignorer cet email.</p>');

                if ($mail->send())
                {
                    $user = $userManager->getById($user->getId());
                    $user->hydrate($user, ['uuid' => $uuid]);
                    $userManager->update($user, $user->getId());
                    $session = new PHPSession;
                    $session->set('success', "Un mail de réinitialisation de mot de passe vous a été envoyé.");
                    return $this->redirect(parent::PATH_TO_SIGNUP_PAGE);
                    
                } else
                {
                    $session = new PHPSession;
                    $session->set('fail', "L'email de réinitialisation de mot de passe n'a pas pu être envoyé. Veuillez réécrire votre adresse email.");
                }

            } else
            {
                $session = new PHPSession;
                $session->set('fail', "L'email de réinitialisation de mot de passe n'a pas pu être envoyé. Veuillez réécrire votre adresse email.");
            }
            
        } else
        {
            $session = new PHPSession;
            $session->set('fail', "Veuillez renseigner un email valide.");
        }

        return $this->redirect(parent::PATH_TO_FORGOTTEN_PSWD);
    }

    public function handlerResetPassword(array $data)
    {
        foreach($data as $key => $value)
        {
            $data[$key] = htmlspecialchars($value);
        }
        $session = new PHPSession;

        $user = new User();
        $user->hydrate($user, $data);

        $userManager = new UserManager('user');
        $idUser = $userManager->getIdByUuid(htmlspecialchars($data['uuid']));

        if($this->isValid($user)
        && $this->isSubmit('resetPassword')
        && $data['uuid'] != NULL
        && $data['password'] == $data['validPassword']
        && $data['token'] == $session->get('token')
        && is_int($idUser))
        {
            unset($user);
            $user = $userManager->getById($idUser);

            if($idUser == NULL) {
                $this->redirect(parent::PATH_TO_PSWD_RESET . $data['uuid']);
            } else
            {
                $password = password_hash($data['password'], PASSWORD_DEFAULT);

                $user->setPassword($password);
                $user->setUuid(NULL);
                $userManager->update($user, $idUser);

                $session = new PHPSession;
                $session->set('success', "Votre mot de passe a bien été changé.");
                return $this->redirect(parent::PATH_TO_SIGNUP_PAGE);
            }
        
        } elseif($data['password'] != $data['validPassword'])
        {
            $session = new PHPSession;
            $session->set('fail', "Les mots de passe entrés ne sont pas identiques.");
        } else
        {
            $session = new PHPSession;
            $session->set('fail', "Votre réinitialisation de mot de passe a rencontré un problème. Veuillez recommencer.");
        }
        //return $this->redirect(parent::PATH_TO_PSWD_RESET . $session->get('uuid'));
    }

}