<?php

namespace App\Controller;

use App\Core\BaseController;
use App\Repository\Manager\UserManager;
use App\Services\PHPSession;
use Ramsey\Uuid\Uuid;
use PHPMailer\PHPMailer\PHPMailer;


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

        $userManager = new UserManager('user');
        $idUser = $userManager->findOneUserBy($pseudo, $password);

        if($idUser != NULL && $idUser != '')
        {
            $session = new PHPSession;
            $session->set('pseudo', $pseudo);
            return $this->render('home.html.twig', [
                "success" => 'Bienvenue, ' . $pseudo . ' !'
            ]);

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

        $this->tryToSignIn($data);            
    }
    
    /**
     * Tests if the values in $data are filled in to register the visitor.
     * If so, an email validation is sent and the user is added to the database,
     * else a sub-function is called to return an appropriate error message
     *
     * @param  array $data
     * @return mixed
     */
    private function tryToSignIn(array $data)
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
            $params = [
                "pseudo" => $data['pseudo'],
                "password" => $password,
                "email" => $data['email'],
                "email_validated" => 0,
                "uuid" => $uuid
            ];


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
                return $this->render('signIn.html.twig', [
                    "fail" => "L'email de confirmation d'adresse email n'a pas pu être envoyé. Réécrivez votre adresse email."
                ]);
            } else {
                $userManager->insert($params);
                return $this->render('home.html.twig', [
                    "success" => "Bienvenue sur le Blog de Floryss Rubechi. Un mail de confirmation d'email vous a été envoyé."
                ]);
            }

        } else
        {
            $data['emailFree'] = $isEmailOccupied;
            $problem = $this->findSignInProblem($data);
            return $this->render('signin.html.twig', [
                "fail" => $problem,
                "data" => $data
            ]);
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

    public function signOut()
    {
        session_destroy();
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
            $emailUnknown = $userManager->getEmail($email);
            $idUser = $userManager->getIdByEmail($email)['id'];            

            if($emailUnknown == false && $idUser != NULL)
            {
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
                $mail->addAddress($email);
                $mail->CharSet="utf-8"; 
                $mail->Subject = 'Réinitialisation de votre mot de passe - Blog de Floryss Rubechi';
                $mail->msgHTML('<p>Réinitialisation de votre mot de passe</p>
                    <p>Pour changer votre mot de passe, veuillez cliquer sur le lien ci-dessous</p>
                    <p><a href="http://localhost/blogphp/reinitialisation-mot-de-passe?uuid=' . $uuid . '">Changer mon mot de passe !</a></p>
                    <p>Si vous n\'êtes pas à l\'origine de cette demande, veuillez ignorer cet email.</p>');

                if ($mail->send())
                {
                    $params = [
                        'uuid' => $uuid
                    ];
                    $userManager->update($params, $idUser);
                    return $this->render('signup.html.twig', [
                        "success" => "Un mail de réinitialisation de mot de passe vous a été envoyé."
                    ]);
                    
                } else
                {
                    return $this->render('emailToResetPassword.html.twig', [
                        "fail" => "L'email de réinitialisation de mot de passe n'a pas pu être envoyé. Veuillez réécrire votre adresse email."
                    ]);                
                }

            } else
            {
                return $this->render('emailToResetPassword.html.twig', [
                    "fail" => "L'email que vous avez renseigné ne correspond pas à un compte. Veuillez écrire une adresse email associée à un compte."
                ]);
            }
            
        } else
        {
            return $this->render('emailToResetPassword.html.twig', [
                "fail" => "Veuillez renseigner un email valide."
            ]);
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
        $userManager = new UserManager('user');
        $idUser = $userManager->getIdByUuid($uuid);
		return $this->render('changePasswordForm.html.twig', [
            "uuid" => $uuid
        ]);
	}
    
    /**
     * Processes the password change form
     *
     * @param  string $password
     * @param  string $validPassword
     * @param  string $uuid
     */
    public function resetPassword(string $password, string $validPassword, string $uuid = NULL)
    {
        $fields = [$uuid, $password, $validPassword];
        if($this->isValid($fields) && $this->isSubmit('resetPassword') && $uuid != NULL && $password == $validPassword)
        {
            $userManager = new UserManager('user');
            $idUser = $userManager->getIdByUuid($uuid);
            if($idUser == NULL) {
                //ici on a changé l'uuid mais comment revenir sur la page d'avant ? je dois faire arriver sur une nouvelle page qui explique le problème avec un lien
                //js pour revenir à la page précédente ?
                //header ("Location: $_SERVER[HTTP_REFERER]" );
                //return $this->redirect("/reinitialisation-mot-de-passe?uuid=" . $uuid);
            } else
            {
                $password = password_hash($password, PASSWORD_DEFAULT);
                $params = [
                    "password" => $password
                ];
                $userManager->update($params, $idUser);
                $userManager->updateUuid($idUser);
                return $this->render('signup.html.twig', [
                   "success" => "Votre mot de passe a bien été changé." 
                ]);
            }
        
        } elseif($password != $validPassword)
        {
            //je voudrais mettre un message avec pour dire que les 2 passwords ne sont pas identiques
            return $this->redirect("/reinitialisation-mot-de-passe?uuid=" . $uuid);
        } else
        {
            //même problème de l'uuid qu'on ne connait plus et du message qu'on voudrait faire passer
            return $this->render('signin.html.twig', [
                "fail" => 'Votre réinitialisation de mot de passe a rencontré un problème. Veuillez recommencer.'
            ]);
        }
    }

}