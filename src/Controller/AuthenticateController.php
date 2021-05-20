<?php

namespace App\Controller;

use App\Core\BaseController;
use App\Entity\User;
use App\Repository\Manager\UserManager;
use App\Services\HandlerEmails;
use App\Services\HandlerSignIn;
use App\Services\PHPSession;
use Ramsey\Uuid\Uuid;


class AuthenticateController extends BaseController
{
    public function signUpForm()
    {
        return $this->render('signUp.html.twig', []);
    }
    
    /**
     * Connects the user if the pair of username and password is valid, else sends an error message
     *
     * @param  string $pseudo
     * @param  string $password
     * @return mixed
     */
    public function signUp(string $pseudo, string $password)
    {

        $fields = [$pseudo, $password];

        $userManager = new UserManager('user');
        $idUser = $userManager->findOneUserBy($pseudo, $password);

        if($idUser != NULL && $this->isSubmit('signUp') && $this->isValid($fields))
        {
            $session = new PHPSession;
            $session->set('pseudo', $pseudo);

            $isAdmin = $userManager->isAdminById($idUser);
            $session->set('admin', $isAdmin);

            return $this->redirect('/');

        } else
        {
            $session = new PHPSession;
            $session->set('fail', 'Vous avez entré un mauvais pseudo ou mot de passe.');
            return $this->redirect('/connexion');
        }

    }

    public function signInForm()
    {
        return $this->render('signIn.html.twig', []);
    }
    
    /**
     * Calls a sub-function to test if the fields are filled in correctly to register the visitor
     *
     * @param  string $pseudo
     * @param  string $password
     * @param  string $passwordValid
     * @param  string $email
     * @param  mixed $mentionsAccepted
     * @return void
     */
    public function signIn(string $pseudo, string $password, string $passwordValid, string $email, $mentionsAccepted): void
    {

        $data = [
            'pseudo' => $pseudo,
            'password' => $password,
            'passwordValid' => $passwordValid,
            'email' => $email,
            'mentions' => $mentionsAccepted
        ];

        $handlerSignIn = new HandlerSignIn;
        $handlerSignIn->tryToSignIn($data);
    }

    public function signOut()
    {
        unset($_SESSION['pseudo']);
        unset($_SESSION['admin']);
        $session = new PHPSession();
        $session->set('success', 'Vous avez bien été déconnecté.');
        return $this->redirect('/');
    }
    
    /**
     * Validates the user's email, identifying the user by the uuid
     *
     * @param  string $uuid
     */
    public function validEmail(string $uuid)
    {
        $userManager = new UserManager('user');
        $idUser = $userManager->getIdByUuid($uuid);
        if($idUser != false)
        {
            $userData = $userManager->getById($idUser);
            $user = new User($userData['pseudo'], $userData['password'], $userData['email'], $userData['admin'], 1, NULL);
            $userManager->update($user, $idUser);
            $session = new PHPSession;
            $session->set('success', 'Votre email a bien été confirmé.');
            return $this->redirect('/');
        } else
        {
            return $this->redirect('/erreur404');
        }
        
    }
    
    /**
     * Leads to the form to enter your email address and change your password
     */
    public function emailToResetPassword()
	{
		return $this->render('emailToResetPassword.html.twig', []);
	}
    
    /**
     * Calls functions to check if the email is in the database, generates a uuid, assigns it to the user and generates a password reset email.
     *
     * @param  string $email
     */
    public function sendEmailResetPassword(string $email)
    {
        $fields = [$email];
        if($this->isValid($fields) && $this->isSubmit('emailResetPassword'))
        {
            $uuid = Uuid::uuid4();
            $uuid = $uuid->toString();

            $userManager = new UserManager('user');
            $emailOccupied = $userManager->getEmail($email);
            $idUser = $userManager->getIdByEmail($email)['id'];            

            if($emailOccupied != false && $idUser != NULL)
            {
                $handlerEmails = new HandlerEmails;
                $mail = $handlerEmails->sendEmail($email, 'Réinitialisation de votre mot de passe - Blog de Floryss Rubechi', '<p>Réinitialisation de votre mot de passe</p>
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
                    return $this->redirect('/connexion');
                    
                } else
                {
                    $session = new PHPSession;
                    $session->set('fail', "L'email de réinitialisation de mot de passe n'a pas pu être envoyé. Veuillez réécrire votre adresse email.");
                    return $this->redirect('/mot-de-passe-oublie');
                }

            } else
            {
                $session = new PHPSession;
                $session->set('fail', "L'email de réinitialisation de mot de passe n'a pas pu être envoyé. Veuillez réécrire votre adresse email.");
                return $this->redirect('/mot-de-passe-oublie');
            }
            
        } else
        {
            $session = new PHPSession;
            $session->set('fail', "Veuillez renseigner un email valide.");
            return $this->redirect('/mot-de-passe-oublie');
        }

    }
	
	/**
	 * Shows the form to change your password
	 *
	 * @param  string $uuid
	 * @return void
	 */
	public function changePasswordForm(string $uuid)
	{
        $session = new PHPSession;
        $session->set('uuid', $uuid);
		return $this->render('changePasswordForm.html.twig', [
            'uuid' => $uuid
        ]);
	}
    
    /**
     * Processes the password change form
     *
     * @param  string $password
     * @param  string $validPassword
     * @param  string $uuid
     */
    public function resetPassword(string $password, string $validPassword, string $uuid)
    {
        $fields = [$uuid, $password, $validPassword];
        if($this->isValid($fields) && $this->isSubmit('resetPassword') && $uuid != NULL && $password == $validPassword)
        {
            $userManager = new UserManager('user');
            $idUser = $userManager->getIdByUuid($uuid);
            if($idUser == NULL) {
                return $this->redirect("/reinitialisation-mot-de-passe?uuid=" . $uuid);
            } else
            {
                $password = password_hash($password, PASSWORD_DEFAULT);
                $userData = $userManager->getById($idUser);
                $user = new User($userData['pseudo'], $password, $userData['email'], $userData['admin'], $userData['email_validated'], NULL);
                $userManager->update($user, $idUser);
                $session = new PHPSession;
                $session->set('success', "Votre mot de passe a bien été changé.");
                return $this->redirect('/connexion');
            }
        
        } elseif($password != $validPassword)
        {
            $session = new PHPSession;
            $session->set('fail', "Les mots de passe entrés ne sont pas identiques.");
            return $this->redirect("/reinitialisation-mot-de-passe?uuid=" . $session->get('uuid'));
        } else
        {
            $session = new PHPSession;
            $session->set('fail', "Votre réinitialisation de mot de passe a rencontré un problème. Veuillez recommencer.");
            return $this->redirect("/reinitialisation-mot-de-passe?uuid=" . $session->get('uuid'));
        }
    }

}