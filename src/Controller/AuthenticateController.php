<?php

namespace App\Controller;

use App\Core\BaseController;
use App\Repository\Manager\UserManager;
use App\Services\PHPSession;

class AuthenticateController extends BaseController
{
    public function signUpForm()
    {
        return $this->render('signUp.html.twig', []);
    }

    public function signUp(string $pseudo, string $password)
    {

        $userManager = new UserManager('user');
        $idUser = $userManager->testSignUp($pseudo, $password);
        //ici idUser est NULL car il y a un problème avec la reconnaissance du hachage du mdp

        if($idUser != NULL && $idUser != '')
        {
            $session = new PHPSession;
            $session->set('idUser', $idUser);
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

        //il faut que l'email ne soit pas déjà utilisée (utiliser isValid et isSubmit)
        if($password === $passwordValid
        && $mentionsAccepted == 'on'
        && $pseudo != ''
        && strlen($pseudo) <= 100
        && $password != ''
        && strlen($password) <= 50
        && $email != ''
        && strlen($email) <= 100
        && preg_match('##', $email) != false)
        {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $userManager = new UserManager('user');
            $params = [
                "pseudo" => $pseudo,
                "password" => $password,
                "email" => $email
            ];
            $userManager->insert($params);

            //je voudrais enregistrer l'id plutôt mais je ne sais pas comment le sélectionner
            $session = new PHPSession;
            $session->set('pseudo', $pseudo);
        } else
        {
            //je voudrais envoyer des messages personnalisés en fonction des problèmes.
            //je fais une série de if ?
            return $this->render('signin.html.twig', [
                "fail" => 'Les champs sont mal remplis.'
            ]);
        }
    }

    public function signOut()
    {
        session_destroy();
    }

}