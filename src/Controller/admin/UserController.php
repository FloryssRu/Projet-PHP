<?php

namespace App\Controller\admin;

use App\Core\BaseController;
use App\Entity\User;
use App\Repository\Manager\UserManager;
use App\Services\PHPSession;
use App\Services\TranslateUsersAdminStatus;
use Ramsey\Uuid\Nonstandard\Uuid;

class UserController extends BaseController
{
    public function listUsers()
    {
        $session = new PHPSession();
		if ($session->get('admin') == NULL || !$session->get('admin')) {
            return $this->redirect('/erreur-403');
        }
        $userManager = new UserManager('user');
        $users = $userManager->getAll();
        $users = TranslateUsersAdminStatus::translate($users);
        $uuid = Uuid::uuid4();
        $uuid = $uuid->toString();
        $session->set('token', $uuid);
        return $this->render('admin/usersList.html.twig', [
            'users' => $users,
            'token' => $uuid
        ]);
    }

    /**
     * Change the statut of the user with id = iduser in admin or simple user
     *
     * @param  int $idUser
     * @param  string $token
     * @param  int $becomeAdmin Is 0 if the user will become an admin, and 1 if the user become a simple user
     */
    public function changeUserStatut(
        int $idUser = NULL,
        string $token = NULL,
        int $becomeAdmin = NULL
    ) {
        $session = new PHPSession();
		if (
            $session->get('admin') == NULL
            || !$session->get('admin')
            || !is_int($idUser)
            || !is_string($token)
            || !is_int($becomeAdmin)
        ) {
            return $this->redirect('/erreur-403');
        }
        if ($token == $session->get('token')) {
            if ($becomeAdmin == 0) {
                $admin = true;
                $session->set('success', "L'utilisateur est passé en statut administrateur.");
            } else {
                $admin = false;
                $session->set('success', "L'utilisateur est passé en statut simple utilisateur.");
            }

            $user = new User();
            $user->setAdmin($admin);
            $userManager = new UserManager('user');
            $userManager->update($user, $idUser);
        } else {
            $session->set('fail', "La transmission d'information a échoué.");
        }
        return $this->redirect('/liste-utilisateurs');
    }
}