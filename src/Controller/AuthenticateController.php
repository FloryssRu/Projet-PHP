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
    protected const PATH_TO_SIGNUP_PAGE = "/connexion";
    protected const PATH_TO_FORGOTTEN_PSWD = "/mot-de-passe-oublie";
    protected const PATH_TO_PSWD_RESET = "/reinitialisation-mot-de-passe?uuid=";

    public function signUpForm()
    {
        return $this->render('signUp.html.twig');
    }
    
    /**
     * Connects the user if the pair of username and password is valid, else sends an error message
     */
    public function signUp()
    {
        $userManager = new UserManager('user');
        $user = $userManager->findOneUserBy($_POST['pseudo'], $_POST['password']);

        if(is_object($user) && $this->isSubmit('signUp') && $this->isValid($user))
        {
            $session = new PHPSession;
            $session->set('pseudo', $_POST['pseudo']);
            $session->set('idUser', $user->getId());

            $isAdmin = $userManager->isAdminById($user->getId());
            $session->set('admin', $isAdmin);

            return $this->redirect('/');

        } else
        {
            $session = new PHPSession;
            $session->set('fail', 'Vous avez entré un mauvais pseudo ou mot de passe.');
            return $this->redirect(self::PATH_TO_SIGNUP_PAGE);
        }

    }

    public function signInForm()
    {
        return $this->render('signIn.html.twig');
    }
    
    /**
     * Calls a sub-function to test if the fields are filled in correctly to register the visitor
     */
    public function signIn()
    {
        $handlerSignIn = new HandlerSignIn;
        return $handlerSignIn->tryToSignIn($_POST);
    }

    public function signOut()
    {
        unset($_SESSION['pseudo']);
        unset($_SESSION['idUser']);
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
        if(preg_match('#[0-9]+#', $idUser))
        {
            $user = new User();
            $user->hydrate($user, ['emailValidated' => 1, 'uuid' => NULL]);
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
		return $this->render('emailToResetPassword.html.twig');
	}
    
    /**
     * Calls functions to check if the email is in the database, generates a uuid, assigns it to the user and generates a password reset email.
     */
    public function sendEmailResetPassword()
    {
        $handlerResetPassword = new HandlerResetPassword;
        $handlerResetPassword->handlerEmailResetPassword($_POST['email']);
    }
	
	/**
	 * Shows the form to change your password
	 *
	 * @param  string $uuid
	 */
	public function changePasswordForm(string $uuid)
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
     */
    public function resetPassword()
    {
        $handlerResetPassword = new HandlerResetPassword;
        $handlerResetPassword->handlerResetPassword($_POST);
    }

}