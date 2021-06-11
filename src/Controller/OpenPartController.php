<?php

namespace App\Controller;

use App\Core\BaseController;
use App\Entity\Comment;
use App\Services\PHPSession;
use App\Repository\Manager\PostManager;
use App\Repository\Manager\CommentManager;
use App\Services\DateFormat;
use App\Services\HandlerPicture;

class OpenPartController extends BaseController
{
	public function showPost(int $idPost)
    {
        $postManager = new PostManager('post');
        $post = $postManager->getById($idPost);

        $handlerPicture = new HandlerPicture;
        $picture = $handlerPicture->searchPicture($post->getDatePublication());

        $dateFormat = new DateFormat;
        if($post->getDateLastUpdate() == NULL)
        {
            $post->setDatePublication('Publié ' . $dateFormat->formatToDisplay($post->getDatePublication()));
        } else
        {
            $post->setDateLastUpdate('Mis à jour ' . $dateFormat->formatToDisplay($post->getDateLastUpdate()));
        }
        
        $commentManager = new CommentManager('comment');
        $arrayComments = $commentManager->getCommentsByIdPost($idPost);

        return $this->render('post.html.twig', [
            "post" => $post,
            "picture" => $picture,
            "comments" => $arrayComments
        ]);

    }

    public function showList()
    {
        $postManager = new PostManager('post');
        $listPosts = $postManager->getAll();
        $dateFormat = new DateFormat;
        $listPosts = $dateFormat->formatListPosts($listPosts); //affiche une erreur

        return $this->render('listPosts.html.twig', [
            'listPosts' => $listPosts
        ]);
    }

    public function newComment()
    {
        $session = new PHPSession;

        $_POST['pseudo'] = $session->get('pseudo');
        $_POST['date'] = date("Y-m-d H:i:s");
        $_POST['is_validated'] = 0;

        $comment = new Comment();
        $comment->hydrate($comment, $_POST);
        
        if($this->isSubmit('newComment') && $this->isValid($comment))
        {
            $commentManager = new CommentManager('comment');
            $commentManager->insert($_POST);
            
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