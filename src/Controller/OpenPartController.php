<?php

namespace App\Controller;

use App\Core\BaseController;
use App\Entity\Comment;
use App\Services\PHPSession;
use App\Repository\Manager\PostManager;
use App\Repository\Manager\CommentManager;

class OpenPartController extends BaseController
{
	public function showPost($idPost)
    {
        $postManager = new PostManager('post');
        $post = $postManager->getById($idPost);
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
        
        //ici modifier le format des dates (problèmes de ciblage)

        return $this->render('listPosts.html.twig', [
                'listPosts' => $listPosts
            ]);
    }

    public function newComment(string $content, int $idPost)
    {
        $fields = [$content];

        if($this->isSubmit('newComment') && $this->isValid($fields)) {

            $session = new PHPSession;
            $pseudo = $session->get('pseudo');
            $date = date("Y-m-d H:i:s");
            $isValidated = 0;
            $commentManager = new CommentManager('comment');

            $comment = new Comment($pseudo, $content, $date, $isValidated, $idPost);
            $commentManager->insert($comment);
            
            $session->set('success', 'Votre commentaire a été envoyé pour validation.');

            return $this->showPost($idPost);
        } else {
            $session = new PHPSession;
            $session->set('fail', 'Votre commentaire a rencontré un problème.');

            return $this->showPost($idPost);
        }
    }

    public function error403()
    {
        return $this->render('403.html.twig', []);
    }
}