<?php

namespace App\Controller;

use App\Core\BaseController;
use App\Services\PHPSession;
use App\Repository\Manager\PostManager;
use App\Repository\Manager\CommentManager;
use App\Repository\Manager\UserManager;

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

        if($session->get('success') != NULL)
        {
            $success = $session->get('success');
            $session->delete('success');
            return $this->render('post.html.twig', [
                "post" => $post,
                "comments" => $arrayComments,
                "userConnected" => $userConnected,
                'success' => $success
            ]);

        } elseif ($session->get('fail') != NULL)
        {
            $fail = $session->get('fail');
            $session->delete('fail');
            return $this->render('post.html.twig', [
                "post" => $post,
                "comments" => $arrayComments,
                "userConnected" => $userConnected,
                'fail' => $fail
            ]);
        } else
        {
            return $this->render('post.html.twig', [
                "post" => $post,
                "comments" => $arrayComments,
                "userConnected" => $userConnected
            ]);
        }

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
            
            $userManager = new UserManager('user');
            $pseudo = $userManager->getPseudoByIdUser($session->get('idUser'));
            //problème ici pour récupérer le pseudo
            $date = date("Y-m-d H:i:s");
            $isValidated = 0;
            $commentManager = new CommentManager('comment');

            $params = [
                'pseudo' => $pseudo,
                'content' => $content,
                'date' => $date,
                'is_validated' => $isValidated,
                'id_post' => $idPost
            ];  

            $commentManager->insert($params);
            
            $session->set('success', 'Votre commentaire a été envoyé pour validation.');

            return $this->showPost($idPost);
        } else {
            $session = new PHPSession;
            $session->set('fail', 'Votre commentaire a rencontré un problème.');

            return $this->showPost($idPost);
        }
    }
}