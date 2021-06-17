<?php

namespace App\Controller;

use App\Core\BaseController;
use App\Entity\User;
use App\Services\PHPSession;
use App\Repository\Manager\PostManager;
use App\Repository\Manager\CommentManager;
use App\Repository\Manager\UserManager;
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
            $comments = $commentManager->getCommentsByIdPost($id);

            $userManager = new UserManager('user');
            foreach($comments as $comment)
            {
                $user = new User;
                $user->setAvatarNumber($userManager->getAvatarByPseudo($comment->getPseudo())->avatar_number);
                $comment->avatar = $user->getAvatarNumber();
                $comment->postTitle = $postManager->getById($comment->getIdPost())->getTitle();
            }
            return $this->render('post.html.twig', [
                "post" => $post,
                "picture" => $picture,
                "comments" => $comments
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
    
    /**
     * Displays the page of user's dashboard
     *
     * @param  mixed $idUser
     * @return void
     */
    public function showDashboard()
    {
        $session = new PHPSession();
        if($session->get('pseudo') == null)
        {
            return $this->redirect('/erreur-404');
        }

        $userManager = new UserManager('user');
        $user = $userManager->getById($session->get('idUser'));

        $commentManager = new CommentManager('comment');
        $comments = $commentManager->getAllCommentsByPseudo($session->get('pseudo'));
        
        $postManager = new PostManager('post');
        $dateFormat = new DateFormat;
        foreach($comments as $comment)
        {
            $comment->postTitle = $postManager->getById($comment->getIdPost())->getTitle();
            $comment->setDate($dateFormat->formatToDisplay($comment->getDate()));
        }

        return $this->render('dashboard.html.twig', ["user" => $user, "comments" => $comments]);
    }

    public function changeAvatar()
    {
        $session = new PHPSession();
        if($session->get('pseudo') == null)
        {
            return $this->redirect('/erreur-404');
        }
        if($this->isSubmit('avatarChange')
        && $this->isValid($_POST)
        && 0 < $_POST['avatar']
        && $_POST['avatar'] < 6)
        {
            $userManager = new UserManager('user');
            $userManager->update(['avatar_number' => $_POST['avatar']], $session->get('idUser'));
            $session->set('success', 'Votre avatar a bien été changé.');
        } else 
        {
            $session->set('fail', 'Une erreur dans le formulaire a été détectée.');
        }
        $this->redirect('/dashboard');
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