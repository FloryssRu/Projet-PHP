<?php

namespace APP\Services;

use App\Controller\AuthenticateController;
use App\Entity\User;
use App\Repository\Manager\UserManager;
use App\Services\PHPSession;
use PHPMailer\PHPMailer\PHPMailer;
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

        if($data['password'] === $data['passwordValid']
        && $this->isValid($data)
        && $this->isSubmit('signIn')
        && $data['mentions'] == 'on'
        && strlen($data['pseudo']) <= 100
        && strlen($data['password']) <= 50
        && strlen($data['email']) <= 100
        && preg_match('##', $data['email']) != false
        && $isEmailOccupied == false)
        {
            $uuid = Uuid::uuid4();
            $uuid = $uuid->toString();
            $password = password_hash($data['password'], PASSWORD_DEFAULT);
            $userManager = new UserManager('user');
            $user = new User($data['pseudo'], $password, $data['email'], false, 0, $uuid);


            $mail = new PHPMailer();
            $emailConfig = json_decode(file_get_contents('../config/emailConfig.json'));
            $mail->isSendmail();
            $mail->Host = $emailConfig->smtp;
            $mail->Port = 465;
            $mail->SMTPSecure = 'tls';   
            $mail->SMTPAuth = true;
            $mail->Username = $emailConfig->emailOrigin;
            $mail->Password = $emailConfig->password;
            $mail->setFrom($emailConfig->emailOrigin);
            $mail->addAddress($data['email']);
            $mail->CharSet="utf-8"; 
            $mail->Subject = 'Valider votre email - Blog de Floryss Rubechi';
            $mail->msgHTML('<p>Vous vous êtes inscrit sur le Blog de Floryss Rubechi.</p>
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
            $data['emailFree'] = $isEmailOccupied;
            $problem = $this->findSignInProblem($data);
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
    private function findSignInProblem(array $data): string
    {
        if($data['password'] != $data['passwordValid'])
        {
            return 'Vous n\'avez pas écrit deux fois le même mot de passe.';
        } elseif($this->isValid($data) == false)
        {
            return 'Un ou plusieurs champs sont vides.';
        } elseif($this->isSubmit('signIn') == false)
        {
            return 'Le formulaire n\'a pas été soumis.';
        } elseif($data['mentions'] != 'on')
        {
            return 'Vous n\'avez pas accepté les mentions.';
        } elseif(strlen($data['pseudo']) > 100)
        {
            return 'Votre pseudo est trop long. 100 caractères max';
        } elseif(strlen($data['password']) > 50)
        {
            return 'Votre mot de passe est trop long. 50 caractères max';
        } elseif(strlen($data['email']) > 100)
        {
            return 'Votre email est trop long. 100 caractères max';
        } elseif(preg_match('##', $data['email']) == false)
        {
            return 'Le format de l\'email n\'est pas valide.';
        } elseif($data['emailFree'] == false)
        {
            return 'Cet email est déjà associé à un compte.';
        }
    }
}