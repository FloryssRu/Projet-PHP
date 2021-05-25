<?php

namespace App\Services;

use App\Controller\AuthenticateController;
use App\Core\Exceptions\SignInFailedException;
use App\Entity\User;
use App\Repository\Manager\UserManager;
use App\Services\PHPSession;
use Ramsey\Uuid\Nonstandard\Uuid;

class HandlerSignIn extends AuthenticateController
{

    public function __construct()
    {
        
    }

    /**
     * Tests if the values in $data are filled in to register the visitor.
     * If so, an email validation is sent and the user is added to the database,
     * else a sub-function is called to return an appropriate error message
     *
     * @param  array $data
     * @return mixed
     */
    public function tryToSignIn(array $data)
	{

		$userManager = new UserManager('user');
        $isEmailOccupied = $userManager->getEmail($data['email']);
        $isPseudoOccupied = $userManager->getPseudo($data['pseudo']);

        if($data['password'] === $data['passwordValid']
        && $this->isValid($data)
        && $this->isSubmit('signIn')
        && $data['mentions'] == 'on'
        && strlen($data['pseudo']) <= 100
        && strlen($data['password']) <= 50
        && strlen($data['email']) <= 100
        && preg_match('#^[a-zA-Z\.1-9\+]+@[a-zA-Z\.1-9]+\.[a-z]{0,5}$#', $data['email']) != false
        && $isEmailOccupied == false
        && $isPseudoOccupied == false)
        {
            
            $uuid = Uuid::uuid4();
            $uuid = $uuid->toString();
            $password = password_hash($data['password'], PASSWORD_DEFAULT);
            $userManager = new UserManager('user');
            $user = new User($data['pseudo'], $password, $data['email'], false, 0, $uuid);

            $baseEmails = new BaseEmails;
            $mail = $baseEmails->sendEmail($data['email'], 'Valider votre email - Blog de Floryss Rubechi', '<p>Vous vous êtes inscrit sur le Blog de Floryss Rubechi.</p>
            <p>Pour valider votre adresse email et terminer votre inscritpion, veuillez cliquer sur le lien ci-dessous</p>
            <p><a href="http://localhost/blogphp/validation-email?uuid=' . $uuid . '">Valider mon email !</a></p>');

            $session = new PHPSession;
            $session->set('pseudo', $data['pseudo']);
            
            if (!$mail->send()) {
                $session->set('fail', "L'email de confirmation d'adresse email n'a pas pu être envoyé. Réécrivez votre adresse email.");
                return $this->redirect('/inscription');
            } else {
                $userManager->insert($user);
                $session->set('success', "Bienvenue sur le Blog de Floryss Rubechi. Un mail de confirmation d'email vous a été envoyé.");
                return $this->redirect('/');
            }

        } else
        {
            $problem = $this->findSignInProblem($data, $isEmailOccupied);
            $session = new PHPSession;
            $session->set('fail', $problem);
            return $this->redirect('/inscription');
        }
	}
    
    /**
     * Finds the problem that caused the registration fail and returns the appropriate message
     *
     * @param  mixed $data
     * @return string
     */
    private function findSignInProblem(array $data, bool $isEmailOccupied): string
    {
        if($data['password'] != $data['passwordValid'])
        {
            $error = 'Vous n\'avez pas écrit deux fois le même mot de passe.';
        } elseif($this->isValid($data) == false)
        {
            $error = 'Un ou plusieurs champs sont vides.';
        } elseif($this->isSubmit('signIn') == false)
        {
            $error = 'Le formulaire n\'a pas été soumis.';
        } elseif($data['mentions'] != 'on')
        {
            $error = 'Vous n\'avez pas accepté les mentions.';
        } elseif(strlen($data['pseudo']) > 100)
        {
            $error = 'Votre pseudo est trop long. 100 caractères max';
        } elseif(strlen($data['password']) > 50)
        {
            $error = 'Votre mot de passe est trop long. 50 caractères max';
        } elseif(strlen($data['email']) > 100)
        {
            $error = 'Votre email est trop long. 100 caractères max';
        } elseif(preg_match('#^[a-zA-Z\.1-9\+]+@[a-zA-Z\.1-9]+\.[a-z]{0,5}$#', $data['email']) == false)
        {
            $error = 'Le format de l\'email n\'est pas valide.';
        } elseif($isEmailOccupied != false)
        {
            $error = 'Votre inscription a échoué.';
        } elseif($data['pseudo'] != false)
        {
            $error = 'Choisissez un autre pseudo.';
        } else
        {
            $error = 'Une erreur est survenue.';
        }
        $exception = new SignInFailedException($error);
        return $exception->getMoreDetail();
    }
}