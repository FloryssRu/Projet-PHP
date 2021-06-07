<?php

namespace App\Controller\admin;

use App\Core\BaseController;
use App\Entity\Post;
use App\Repository\Manager\PostManager;
use App\Services\DateFormat;
use App\Services\HandlerPicture;
use App\Services\PHPSession;
use Ramsey\Uuid\Uuid;

class PostController extends BaseController
{

    private const PATH_TO_ADMIN_POSTS = '/admin-posts';
    private const ADMIN_POSTS_TEMPLATE = 'admin/adminPosts.html.twig';
    
    /**
     * Form to create a new Post
     */
    public function newPost()
    {
        $session = new PHPSession;
		if($session->get('admin') == NULL || !$session->get('admin'))
        {
            return $this->redirect(parent::ERROR_403_PATH);
        }
        $uuid = Uuid::uuid4();
        $uuid = $uuid->toString();
        $session->set('token', $uuid);
        return $this->render('admin/newPost.html.twig');
    }
    
    /**
     * Add a new post to the database
     */
    public function addPost()
    {
        $session = new PHPSession;
		if($session->get('admin') == NULL || !$session->get('admin'))
        {
            return $this->redirect(parent::ERROR_403_PATH);
        }

        if($this->isSubmit('newPost') && $this->isValid($_POST) && $_POST['token'] == $session->get('token')) {

            $session->delete('token');

            $datePublication = date("Y-m-d H:i:s");
            $postManager = new PostManager('post');

            $handlerPicture = new HandlerPicture;
            $savePictureSuccess = $handlerPicture->savePicture($_FILES['picture'], $datePublication);

            $arrayData = [
                'title' => $_POST['title'],
                'date_publication' => $datePublication,
                'date_last_update' => NULL,
                'heading' => $_POST['heading'],
                'content' => $_POST['content'],
                'author' => $_POST['author']
            ];
            $postManager->insert($arrayData);
            
            if($savePictureSuccess)
            {
                $session->set('success', 'Votre nouveau post et son image ont bien été enregistrés.');
            } else
            {
                $session->set('success', 'Votre nouveau post a bien été enregistré.');
            }

            return $this->redirect(self::PATH_TO_ADMIN_POSTS);

        } else {

            $session->set('fail', 'Votre nouveau post n\'a pas été enregistré, une erreur dans le formulaire a été détectée.');

            return $this->redirect(self::PATH_TO_ADMIN_POSTS);

        }
    }
    
    /**
     * retrieves all published posts and forwards them to the page
     */
    public function adminPosts()
    {
        $session = new PHPSession;
		if($session->get('admin') == NULL || !$session->get('admin'))
        {
            return $this->redirect(parent::ERROR_403_PATH);
        }
        $adminPostManager = new PostManager('Post');
        $getAllPosts = $adminPostManager->getAll();

        $dateFormat = new DateFormat;
        $getAllPosts = $dateFormat->formatListPostsAdmin($getAllPosts);

        $uuid = Uuid::uuid4();
        $uuid = $uuid->toString();
        $session->set('token', $uuid);

        return $this->render(self::ADMIN_POSTS_TEMPLATE, [
            "allPosts" => $getAllPosts
        ]);
        
        
    }
    
    /**
     * retrieves the posts to modify and complete the field of the edit page
     */
    public function editPost()
    {
        $session = new PHPSession;
		if($session->get('admin') == NULL || !$session->get('admin'))
        {
            return $this->redirect(parent::ERROR_403_PATH);
        }
        $adminPostManager = new PostManager('Post');
        $getThisPost = $adminPostManager->getById($_GET['idPost']);

        $handlerPicture = new HandlerPicture;
        $picture = $handlerPicture->searchPicture($getThisPost->getDatePublication());

        $uuid = Uuid::uuid4();
        $uuid = $uuid->toString();
        $session->set('token', $uuid);

        return $this->render('admin/editPost.html.twig', [
            'getThisPost' => $getThisPost,
            'picture' => $picture
        ]);
    }

    /**
     * Update a post which just was send with the editpost form
     */
    public function updatePost()
    {
        $session = new PHPSession;
		if($session->get('admin') == NULL || !$session->get('admin'))
        {
            return $this->redirect(parent::ERROR_403_PATH);
        }
        $fields = [$_POST['title'], $_POST['heading'], $_POST['content'], $_POST['author']];

        if($this->isSubmit('editPost') && $this->isValid($fields) && $_POST['token'] == $session->get('token')) {

            $session->delete('token');
            $dateLastUpdate = date("Y-m-d H:i:s");
            $postManager = new PostManager('post');
            $postData = $postManager->getById($_POST['id']);

            $handlerPicture = new HandlerPicture;
            $savePictureSuccess = $handlerPicture->savePicture($_FILES['picture'], $postData->getDatePublication());

            $arrayData = [
                'title' => $_POST['title'],
                'date_publication' => $postData->getDatePublication(),
                'date_last_update' => $dateLastUpdate,
                'heading' => $_POST['heading'],
                'content' => $_POST['content'],
                'author' => $_POST['author']
            ];   
            $postManager->update($arrayData, $_POST['id']);
            
            if($savePictureSuccess)
            {
                $session->set('success', 'Votre post a bien été modifié et votre image a bien été enregistrée.');
            } else
            {
                $session->set('success', 'Votre post a bien été modifié.');
            }
            return $this->redirect(self::PATH_TO_ADMIN_POSTS);

        } else {

            $session->set('fail', 'Votre post n\'a pas été modifié, une erreur dans le formulaire a été détectée.');
            return $this->redirect(self::PATH_TO_ADMIN_POSTS);

        }
    }

    /**
     * Delete the post of the line
     *
     * @param  mixed $id
     * @param  string $token
     */
    public function deletePost($id = NULL, string $token = NULL)
    {
        $session = new PHPSession;
		if($session->get('admin') == NULL || !$session->get('admin'))
        {
            return $this->redirect(parent::ERROR_403_PATH);
        }
        if($token == $session->get('token'))
        {
            $session->delete('token');
            $deletePostManager = new PostManager('post');
            $deletePostManager->delete($id);

            $session->set('success', 'Votre post a bien été supprimé.');

            return $this->redirect(self::PATH_TO_ADMIN_POSTS);
        } else
        {
            return $this->redirect('/');
        }
        
    }
}