<?php

namespace App\Controller\admin;

use App\Core\BaseController;
use App\Entity\User;
use App\Repository\Manager\UserManager;
use App\Services\AdminProtectionPart;
use App\Services\PHPSession;
use App\Services\TranslateUsersAdminStatus;
use Ramsey\Uuid\Nonstandard\Uuid;

class UserController extends BaseController
{
    public function listUsers()
    {
        $adminProtectionPart = new AdminProtectionPart();
        $adminProtectionPart->redirectNotAdmin();
        $userManager = new UserManager('user');
        $users = $userManager->getAll();
        $translateUsersAdminStatus = new TranslateUsersAdminStatus;
        $users = $translateUsersAdminStatus->translate($users);
        $uuid = Uuid::uuid4();
        $uuid = $uuid->toString();
        $session = new PHPSession;
        $session->set('token', $uuid);
        $this->render('admin/usersList.html.twig', [
            'users' => $users,
            'token' => $uuid
        ]);
    }
    
    /**
     * Change the statut of the user with id = iduser in admin
     *
     * @param  mixed $idUser
     * @return void
     */
    public function createAdmin(int $idUser, string $token)
    {
        $adminProtectionPart = new AdminProtectionPart();
        $adminProtectionPart->redirectNotAdmin();
        $session = new PHPSession;
        if($token == $session->get('token'))
        {
            $userManager = new UserManager('user');
            $userAttributes = $userManager->getById($idUser);
            $user = new User($userAttributes['pseudo'], $userAttributes['password'], $userAttributes['email'], 1, $userAttributes['email_validated'], $userAttributes['uuid']);
            $userManager->update($user, $idUser);
            $session->set('success', "L'utilisateur est bien passé en statut administrateur.");
        } else
        {
            $session->set('fail', "La transmission d'information a échoué.");
        }
        return $this->redirect('/liste-utilisateurs');
    }
    
    /**
     * Change the statut of the user with id = iduser in simple user
     *
     * @param  mixed $idUser
     * @return void
     */
    public function deleteAdmin(int $idUser, string $token)
    {
        $adminProtectionPart = new AdminProtectionPart();
        $adminProtectionPart->redirectNotAdmin();
        $session = new PHPSession;
        if($token == $session->get('token'))
        {
            $userManager = new UserManager('user');
            $userAttributes = $userManager->getById($idUser);
            $user = new User($userAttributes['pseudo'], $userAttributes['password'], $userAttributes['email'], 0, $userAttributes['email_validated'], $userAttributes['uuid']);
            $userManager->update($user, $idUser);
            $session->set('success', "L'utilisateur est bien passé en statut simple utilisateur.");
        } else
        {
            $session->set('fail', "La transmission d'information a échoué.");
        }
        return $this->redirect('/liste-utilisateurs');
    }
}