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
    /**
     * Is empty because the mother class has a construct function and we don't want to use it for this class.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Tests if the values in $data are filled in to register the visitor.
     * If so, an email validation is sent and the user is added to the database,
     * else a sub-function is called to return an appropriate error message
     *
     * @param  array $data
     * @return void
     */
    public function tryToSignIn(array $data)
	{
		$userManager = new UserManager('user');
        $isEmailOccupied = $userManager->getEmail($data['email']);
        $isPseudoOccupied = $userManager->getPseudo($data['pseudo']);

        $user = new User();
        $user->hydrate($user, $data);

        $session = new PHPSession();

        if (
            $data['password'] === $data['passwordValid']
            && $this->isValid($user)
            && $this->isSubmit('signIn')
            && isset($data['mentionsAccepted'])
            && strlen($data['pseudo']) <= 100
            && strlen($data['password']) <= 50
            && strlen($data['email']) <= 100
            && preg_match('#^[a-zA-Z\.0-9\+]+@[a-zA-Z\.0-9]+\.[a-z]{0,5}$#', $data['email'])
            && !$isEmailOccupied
            && !$isPseudoOccupied
            && isset($data['pseudo'])
            && isset($data['password'])
            && isset($data['passwordValid'])
            && !preg_match("#[<>':=\/$();&]+#", $_POST['pseudo'])
        ) {
            $uuid = Uuid::uuid4();
            $uuid = $uuid->toString();
            $password = password_hash(htmlspecialchars($data['password']), PASSWORD_DEFAULT);
            $userManager = new UserManager('user');

            $data['password'] = $password;
            $data['admin'] = 0;
            $data['email_validated'] = 0;
            $data['uuid'] = $uuid;

            $baseEmails = new BaseEmails();
            $mail = $baseEmails->sendEmail($data['email'], 'Valider votre email - Blog de Floryss Rubechi', '<p>Vous vous ??tes inscrit sur le Blog de Floryss Rubechi.</p>
            <p>Pour valider votre adresse email et terminer votre inscritpion, veuillez cliquer sur le lien ci-dessous</p>
            <p><a href="http://localhost/blogphp/validation-email?uuid=' . $uuid . '">Valider mon email !</a></p>');

            $session->set('pseudo', $data['pseudo']);
            
            if (!$mail->send()) {
                $session->set('fail', "L'email de confirmation d'adresse email n'a pas pu ??tre envoy??. R????crivez votre adresse email.");
                return $this->redirect('/inscription');
            } else {
                unset($data['mentionsAccepted']);
                unset($data['passwordValid']);
                unset($data['signIn']);
                $userManager->insert($data);
                $user = $userManager->getIdByEmail($data['email']);
                $session->set('idUser', $user->getId());
                $session->set('success', "Bienvenue sur le Blog de Floryss Rubechi. Un mail de confirmation d'email vous a ??t?? envoy??.");
                return $this->redirect('/');
            }

        } else {
            $problem = $this->findSignInProblem($data, $user, $isEmailOccupied);
            $session->set('fail', $problem);
            return $this->redirect('/inscription');
        }
	}

    /**
     * Finds the problem that caused the registration fail and returns the appropriate message
     *
     * @param  array $data
     * @param  Object $user
     * @param  $isEmailOccupied (bool or array of results)
     * @return string
     */
    private function findSignInProblem(array $data, Object $user, $isEmailOccupied): string
    {
        if ($data['password'] !== $data['passwordValid']) {
            $error = 'Vous n\'avez pas ??crit deux fois le m??me mot de passe.';
        } elseif (!$this->isValid($user)) {
            $error = 'Un ou plusieurs champs sont vides.';
        } elseif (!$this->isSubmit('signIn')) {
            $error = 'Le formulaire n\'a pas ??t?? soumis.';
        } elseif (!isset($data['mentionsAccepted'])) {
            $error = 'Vous n\'avez pas accept?? les mentions.';
        } elseif (strlen($data['pseudo']) > 100) {
            $error = 'Votre pseudo est trop long. 100 caract??res max';
        } elseif (strlen($data['password']) > 50) {
            $error = 'Votre mot de passe est trop long. 50 caract??res max';
        } elseif (strlen($data['email']) > 100) {
            $error = 'Votre email est trop long. 100 caract??res max';
        } elseif (!preg_match('#^[a-zA-Z\.1-9\+]+@[a-zA-Z\.1-9]+\.[a-z]{0,5}$#', $data['email'])) {
            $error = 'Le format de l\'email n\'est pas valide.';
        } elseif (!is_bool($isEmailOccupied)) {
            $error = 'Votre inscription a ??chou??.';
        } elseif (is_bool($data['pseudo'])) {
            $error = 'Choisissez un autre pseudo.';
        } elseif (preg_match("#[<>':=\/$();&]*#", $_POST['pseudo'])) {
            $error = 'Votre pseudo contient des caract??res interdits.';
        } else {
            $error = 'Une erreur est survenue.';
        }
        $exception = new SignInFailedException($error);
        return $exception->getMoreDetail();
    }
}