<?php

namespace App\Controller;

use App\Core\BaseController;
use App\Services\PHPSession;
use App\Repository\Manager\PostManager;
use App\Repository\Manager\CommentManager;
use App\Services\DateFormat;
use App\Services\HandlerPicture;

class OpenPartController extends BaseController
{
	public function showPost(string $slug)
    {
        $postManager = new PostManager('post');
        if($postManager->thisSlugExists($slug))
        {
            $id = $postManager->getIdBySlug($slug);
            $post = $postManager->getById($id);

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
            $arrayComments = $commentManager->getCommentsByIdPost($id);

            return $this->render('post.html.twig', [
                "post" => $post,
                "picture" => $picture,
                "comments" => $arrayComments
            ]);
        }

        return $this->redirect('/erreur-404');
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
            $commentManager = new CommentManager('comment');
            $arrayData = [
                'pseudo' => $session->get('pseudo'),
                'content' => $_POST['content'],
                'date' => date("Y-m-d H:i:s"),
                'is_validated' => 0,
                'id_post' => $_POST['idPost']
            ];
            $commentManager->insert($arrayData);

            $postManager = new PostManager('post');
            $post = $postManager->getById($_POST['idPost']);
            
            $session->set('success', 'Votre commentaire a été envoyé pour validation.');

            $this->redirect('/post/' . $post->getSlug());
        } else {
            $postManager = new PostManager('post');
            $post = $postManager->getById($_POST['idPost']);

            $session = new PHPSession;
            $session->set('fail', 'Votre commentaire a rencontré un problème.');

            $this->redirect('/post/' . $post->getSlug());
        }
    }

    public function error403()
    {
        return $this->render('403.html.twig');
    }

    public function error404()
    {
        return $this->render('404.html.twig');
    }

    public function mentions()
    {
        return $this->render('mentions.html.twig');
    }
}