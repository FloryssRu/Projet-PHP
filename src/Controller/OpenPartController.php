<?php

namespace App\Controller;

use App\Core\BaseController;
use App\Entity\Comment;
use App\Entity\User;
use App\Services\PHPSession;
use App\Repository\Manager\PostManager;
use App\Repository\Manager\CommentManager;
use App\Repository\Manager\UserManager;
use App\Services\DateFormat;
use App\Services\HandlerPicture;

class OpenPartController extends BaseController
{
    private const URL_ERROR_404 = '/erreur-404';

	public function showPost(string $slug = NULL)
    {
        if(!is_string($slug) || $slug == NULL)
        {
            return $this->redirect('/erreur-404');
        }
        $postManager = new PostManager('post');
        if($postManager->thisSlugExists($slug))
        {
            $id = $postManager->getIdBySlug($slug);
            $post = $postManager->getById($id);

            $handlerPicture = new HandlerPicture;
            $picture = $handlerPicture->searchPicture($post->getDatePublication());

            DateFormat::changeFormatDatePost($post);
            
            $commentManager = new CommentManager('comment');
            $comments = $commentManager->getAllCommentsWithAvatars($id);
            $comments = DateFormat::formatListComments($comments);

            return $this->render('post.html.twig', [
                "post" => $post,
                "picture" => $picture,
                "comments" => $comments
            ]);
        }

        return $this->redirect(self::URL_ERROR_404);
    }

    public function showList()
    {
        $postManager = new PostManager('post');
        $listPosts = $postManager->getAll();
        $listPosts = DateFormat::formatListPosts($listPosts);

        return $this->render('listPosts.html.twig', [
            'listPosts' => $listPosts
        ]);
    }

    public function newComment()
    {
        $session = new PHPSession;
        if($session->get('pseudo') == NULL)
        {
            $session->set('fail', 'Connectez-vous pour poster un commentaire.');
            return $this->redirect('/connexion');
        }

        $_POST['pseudo'] = $session->get('pseudo');
        $_POST['date'] = date("Y-m-d H:i:s");
        $_POST['is_validated'] = 0;

        $comment = new Comment();
        $comment->hydrate($comment, $_POST);

        $postManager = new PostManager('post');
        $post = $postManager->getById($_POST['idPost']);
        
        if($this->isSubmit('newComment') && $this->isValid($comment))
        {
            unset($_POST['newComment']);
            $commentManager = new CommentManager('comment');
            $commentManager->insert($_POST);
            
            $session->set('success', 'Votre commentaire a été envoyé pour validation.');

        } else {
            $session->set('fail', 'Votre commentaire a rencontré un problème.');
        }

        return $this->redirect('/post/' . $post->getSlug());
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
            return $this->redirect(self::URL_ERROR_404);
        }

        $userManager = new UserManager('user');
        $user = $userManager->getById($session->get('idUser'));

        $commentManager = new CommentManager('comment');
        $comments = $commentManager->getUserCommentsWithPostTitle($session->get('pseudo'));
        $comments = DateFormat::formatListComments($comments);

        return $this->render('dashboard.html.twig', ["user" => $user, "comments" => $comments]);
    }

    public function changeAvatar()
    {
        $session = new PHPSession();
        if($session->get('pseudo') == null)
        {
            return $this->redirect(self::URL_ERROR_404);
        }

        $user = new User();
        $user->hydrate($user, $_POST);

        if($this->isSubmit('avatarChange')
        && $this->isValid($user)
        && 0 < $_POST['avatarNumber']
        && $_POST['avatarNumber'] < 6)
        {
            unset($user);
            $user = new User();
            $user->hydrate($user, ['avatarNumber' => $_POST['avatarNumber']]);

            $userManager = new UserManager('user');
            $userManager->update($user, $session->get('idUser'));

            $session->set('success', 'Votre avatar a bien été changé.');
        } else 
        {
            $session->set('fail', 'Une erreur dans le formulaire a été détectée.');
        }
        return $this->redirect('/dashboard');
    }

    public function mentions()
    {
        return $this->render('mentions.html.twig');
    }
}