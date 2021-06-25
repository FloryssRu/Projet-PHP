<?php

namespace App\Services;

use App\Controller\admin\PostController;
use App\Entity\Post;
use App\Repository\Manager\PostManager;
use Cocur\Slugify\Slugify;

class HandlerPost extends PostController
{
    /**
     * Is empty because the mother class has a construct function and we don't want to use it for this class.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function handlerAddPost()
    {
        $session = new PHPSession();
		if($session->get('admin') == NULL || !$session->get('admin'))
        {
            return parent::redirect(parent::ERROR_403_PATH);
        }
        $post = new Post();
        $_POST['date_publication'] = date(parent::DATE);
        $_POST['date_last_update'] = NULL;
        $post->hydrate($post, $_POST);

        $postManager = new PostManager('post');
        $titleNotFree = $postManager->titleIsFree($_POST['title']);

        if(parent::isSubmit('newPost')
        && parent::isValid($post)
        && $_POST['token'] == $session->get('token')
        && !$titleNotFree)
        {
            $session->delete('token');

            $handlerPicture = new HandlerPicture();
            $savePictureSuccess = $handlerPicture->savePicture($_FILES['picture'], date(self::DATE));

            $slugify = new Slugify();
            $_POST['slug'] = $slugify->slugify($_POST['title']);

            unset($_POST['newPost']);
            unset($_POST['token']);
            
            $postManager->insert($_POST);

            if($savePictureSuccess)
            {
                $session->set('success', 'Votre nouveau post et son image ont bien été enregistrés.');
            } else
            {
                $session->set('success', 'Votre nouveau post a bien été enregistré.');
            }

        } else {

            $session->set('fail', 'Votre nouveau post n\'a pas été enregistré, une erreur dans le formulaire a été détectée.');
        }

        return $this->redirect(parent::PATH_TO_ADMIN_POSTS);
    }

    public function handlerUpdatePost()
    {
        $session = new PHPSession;
		if($session->get('admin') == NULL || !$session->get('admin'))
        {
            return $this->redirect(parent::ERROR_403_PATH);
        }

        $post = new Post();
        $post->hydrate($post, $_POST);

        if($this->isSubmit('editPost')
        && $this->isValid($post)
        && $_POST['token'] == $session->get('token')
        && preg_match('#^[0-9]+$#', $_POST['id'])) {

            $session->delete('token');
            
            $post->setDateLastUpdate(date(self::DATE));

            $postManager = new PostManager('post');
            $post->setDatePublication($postManager->getById($_POST['id'])->getDatePublication());

            $handlerPicture = new HandlerPicture;
            $savePictureSuccess = $handlerPicture->savePicture($_FILES['picture'], $post->getDatePublication());

            $slugify = new Slugify();

            $_POST['slug'] = $slugify->slugify($_POST['title']);

            $postManager->update($post, $_POST['id']);
 
            if($savePictureSuccess)
            {
                $session->set('success', 'Votre post a bien été modifié et votre image a bien été enregistrée.');
            } else
            {
                $session->set('success', 'Votre post a bien été modifié.');
            }
            
        } else {

            $session->set('fail', 'Votre post n\'a pas été modifié, une erreur dans le formulaire a été détectée.');
        }

        return $this->redirect(self::PATH_TO_ADMIN_POSTS);
    }
}