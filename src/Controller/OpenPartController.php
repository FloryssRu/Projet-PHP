<?php

namespace App\Controller;

use App\Core\BaseController;
use App\Entity\Comment;
use App\Services\PHPSession;
use App\Repository\Manager\PostManager;
use App\Repository\Manager\CommentManager;
use App\Services\DateFormat;

class OpenPartController extends BaseController
{
	public function showPost($idPost)
    {
        $postManager = new PostManager('post');
        $post = $postManager->getById($idPost);
        $dateFormat = new DateFormat;
        if($post['date_last_update'] == NULL)
        {
            $post['date_publication'] = 'Publié ' . $dateFormat->formatToDisplay($post['date_publication']);
        } else
        {
            $post['date_last_update'] = 'Mis à jour ' . $dateFormat->formatToDisplay($post['date_last_update']);
        }
        
        $commentManager = new CommentManager('comment');
        $arrayComments = $commentManager->getCommentsByIdPost($idPost);

        $session = new PHPSession;
        if($session->get('idUser') !== NULL)
        {
            $userConnected = true;
        } else
        {
            $userConnected = false;
        }

        return $this->render('post.html.twig', [
            "post" => $post,
            "comments" => $arrayComments,
            "userConnected" => $userConnected
        ]);

    }

    public function showList()
    {
        $postManager = new PostManager('post');
        $listPosts = $postManager->getAll();
        $dateFormat = new DateFormat;
        $listPosts = $dateFormat->formatListPosts($listPosts);

        return $this->render('listPosts.html.twig', [
            'listPosts' => $listPosts
        ]);
    }

    public function newComment()
    {
        $fields = [$_POST['content']];

        if($this->isSubmit('newComment') && $this->isValid($fields)) {

            $session = new PHPSession;
            $pseudo = $session->get('pseudo');
            $date = date("Y-m-d H:i:s");
            $isValidated = 0;
            $commentManager = new CommentManager('comment');

            $comment = new Comment($pseudo, $_POST['content'], $date, $isValidated, $_POST['idPost']);
            $commentManager->insert($comment);
            
            $session->set('success', 'Votre commentaire a été envoyé pour validation.');

            $this->showPost($_POST['idPost']);
        } else {
            $session = new PHPSession;
            $session->set('fail', 'Votre commentaire a rencontré un problème.');

            $this->showPost($_POST['idPost']);
        }
    }

    public function error403()
    {
        return $this->render('403.html.twig');
    }
}