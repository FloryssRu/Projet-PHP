<?php

namespace App\Controller;

use App\Core\BaseController;
use App\Entity\User;
use App\Repository\Manager\UserManager;
use App\Services\HandlerResetPassword;
use App\Services\HandlerSignIn;
use App\Services\PHPSession;
use Ramsey\Uuid\Uuid;


class AuthenticateController extends BaseController
{
    protected $PATH_TO_SIGNUP_PAGE = "/connexion";
    protected $PATH_TO_FORGOTTEN_PSWD = "/mot-de-passe-oublie";
    protected $PATH_TO_PSWD_RESET = "/reinitialisation-mot-de-passe?uuid=";

    public function signUpForm(): void
    {
        return $this->render('signUp.html.twig');
    }
    
    /**
     * Connects the user if the pair of username and password is valid, else sends an error message
     *
     * @param  string $pseudo
     * @param  string $password
     * @return void
     */
    public function signUp(string $pseudo, string $password): void
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
            return $this->redirect($this->PATH_TO_SIGNUP_PAGE);
        }

    }

    public function signInForm(): void
    {
        return $this->render('signIn.html.twig');
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

    public function signOut(): void
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
     * @return void
     */
    public function validEmail(string $uuid): void
    {
        $userManager = new UserManager('user');
        $idUser = $userManager->getIdByUuid($uuid);
        if(preg_match('#[0-9]+#', $idUser))
        {
            $userData = $userManager->getById($idUser);
            $user = new User($userData['pseudo'], $userData['password'], $userData['email'], $userData['admin'], 1, NULL);
            $userManager->update($user, $idUser);
            $userManager->setUuidNull($idUser);
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
     * 
     * @return void
     */
    public function emailToResetPassword(): void
	{
		return $this->render('emailToResetPassword.html.twig');
	}
    
    /**
     * Calls functions to check if the email is in the database, generates a uuid, assigns it to the user and generates a password reset email.
     *
     * @param  string $email
     * @return void
     */
    public function sendEmailResetPassword(string $email): void
    {
        $handlerResetPassword = new HandlerResetPassword;
        $handlerResetPassword->handlerEmailResetPassword($email);
    }
	
	/**
	 * Shows the form to change your password
	 *
	 * @param  string $uuid
	 * @return void
	 */
	public function changePasswordForm(string $uuid): void
	{
        $session = new PHPSession;
        $session->set('uuid', $uuid);
        $token = Uuid::uuid4();
        $token = $token->toString();
        $session->set('token', $token);
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
     * @return void
     */
    public function resetPassword(string $password, string $validPassword, string $uuid, string $token): void
    {
        $handlerResetPassword = new HandlerResetPassword;
        $handlerResetPassword->handlerResetPassword($password, $validPassword, $uuid, $token);
    }

}