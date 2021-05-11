<?php

namespace App\Controller;

use App\Core\BaseController;
use App\Repository\Manager\UserManager;
use App\Services\PHPSession;
use Ramsey\Uuid\Uuid;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class AuthenticateController extends BaseController
{
    public function signUpForm()
    {
        return $this->render('signUp.html.twig', []);
    }

    public function signUp(string $pseudo, string $password)
    {

        $userManager = new UserManager('user');
        $idUser = $userManager->findOneUserBy($pseudo, $password);

        if($idUser != NULL && $idUser != '')
        {
            $session = new PHPSession;
            $session->set('pseudo', $pseudo);
            return $this->redirect('blogphp');

        } else
        {
            return $this->render('signup.html.twig', [
                "fail" => 'Vous avez entré un mauvais pseudo ou mot de passe.'
            ]);
        }

    }

    public function signInForm()
    {
        return $this->render('signIn.html.twig', []);
    }

    public function signIn(string $pseudo, string $password, string $passwordValid, string $email, $mentionsAccepted)
    {

        $data = [
            'pseudo' => $pseudo,
            'password' => $password,
            'passwordValid' => $passwordValid,
            'email' => $email,
            'mentions' => $mentionsAccepted
        ];

        $this->tryToSignIn($data);            
    }

    private function tryToSignIn(array $data)
	{
		$userManager = new UserManager('user');
        $emailFree = $userManager->getEmail($data['email']);

        if($data['password'] === $data['passwordValid']
        && $this->isValid($data)
        && $this->isSubmit('signIn')
        && $data['mentions'] == 'on'
        && strlen($data['pseudo']) <= 100
        && strlen($data['password']) <= 50
        && strlen($data['email']) <= 100
        && preg_match('##', $data['email']) != false
        && $emailFree != false)
        {
            $uuid = Uuid::uuid4();
            $uuid = $uuid->toString();
            $password = password_hash($data['password'], PASSWORD_DEFAULT);
            $userManager = new UserManager('user');
            $params = [
                "pseudo" => $data['pseudo'],
                "password" => $password,
                "email" => $data['email'],
                "email_validated" => 0,
                "uuid" => $uuid
            ];
            $userManager->insert($params);


            $mail = new PHPMailer();
            $emailConfig = json_decode(file_get_contents('../config/emailConfig.json'));
            $mail->isSendmail();
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
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
            
            if (!$mail->send()) {
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            } else {
                echo 'Message sent!';
            }     

            $session = new PHPSession;
            $session->set('pseudo', $data['pseudo']);
            return $this->render('home.html.twig', []);
        } else
        {
            $data[] = ['emailFree' => $emailFree];
            $problem = $this->findSignInProblem($data);
            return $this->render('signin.html.twig', [
                "fail" => $problem,
                "data" => $data
            ]);
        }
	}

    private function findSignInProblem(array $data)
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
        } elseif($data['emailFree'] != false)
        {
            return 'Cet email est déjà associé à un compte.';
        }
    }

    public function signOut()
    {
        session_destroy();
    }

    public function validEmail(string $uuid)
    {
        $userManager = new UserManager('user');
        $idUser = $userManager->getUser($uuid);
        var_dump($idUser);
        if($idUser != false)
        {
            $userManager->updateUuid($idUser);
            $params = [
                "email_validated" => 1
            ];
            $userManager->update($params, $idUser);
            return $this->render('home.html.twig', [
                "success" => "Votre email a bien été confirmé."
            ]);
        } else
        {
            return $this->render('404.html.twig', []);
        }
        
    }

}