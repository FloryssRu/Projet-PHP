<?php

namespace App\Controller\admin;

use App\Core\BaseController;
use App\Repository\Manager\ContactManager;
use App\Services\PHPSession;
use Ramsey\Uuid\Uuid;

class ContactController extends BaseController
{
    public function listMessages()
    {
        $session = new PHPSession;
		if($session->get('admin') == NULL || !$session->get('admin'))
        {
            return $this->redirect(parent::ERROR_403_PATH);
        }
        $contactManager = new ContactManager('contact');
        $listMessages = $contactManager->getAll();

        $token = Uuid::uuid4();
        $token = $token->toString();
        $session->set('token', $token);

        return $this->render('admin/listMessages.html.twig', [
            'listMessages' => $listMessages,
            "token" => $token
        ]);
    }

    public function messageStatut(int $idMessage, int $isProcessed, string $token)
    {
        $session = new PHPSession;
		if($session->get('admin') == NULL || !$session->get('admin'))
        {
            return $this->redirect('/erreur-403');
        }

        $contactManager = new ContactManager('contact');
        $message = $contactManager->getById($idMessage);

        if($token == $session->get('token') && is_object($message))
        {
            $contactManager->changeStatutMessage($idMessage, $isProcessed);
            if($isProcessed == 1)
            {
                $session->set('success', 'Le message a bien été noté comme "traité".');
            } else
            {
                $session->set('success', 'Le message a bien été noté comme "non traité".');
            }
            return $this->redirect('/liste-messages');
        }
        return $this->redirect('/error-404');
    }

    public function deleteMessage(int $idMessage, string $token)
    {
        $session = new PHPSession;
		if($session->get('admin') == NULL || !$session->get('admin'))
        {
            return $this->redirect('/erreur-403');
        }

        $contactManager = new ContactManager('contact');
        $message = $contactManager->getById($idMessage);

        if($token == $session->get('token') && is_object($message))
        {
            $contactManager->delete($idMessage);
            $session->set('success', 'Le message a bien été supprimé.');
            return $this->redirect('/liste-messages');
        }
        return $this->redirect('/error-404');
    }
}