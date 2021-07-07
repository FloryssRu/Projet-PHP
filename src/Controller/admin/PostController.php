<?php

namespace App\Controller\admin;

use App\Core\BaseController;
use App\Repository\Manager\PostManager;
use App\Services\DateFormat;
use App\Services\HandlerPicture;
use App\Services\HandlerPost;
use App\Services\PHPSession;
use Ramsey\Uuid\Uuid;

class PostController extends BaseController
{
    protected const PATH_TO_ADMIN_POSTS = '/admin-posts';
    private const ADMIN_POSTS_TEMPLATE = 'admin/adminPosts.html.twig';
    protected const DATE = "Y-m-d H:i:s";

    /**
     * Form to create a new Post
     */
    public function newPost()
    {
        $session = new PHPSession();
		if ($session->get('admin') == NULL || !$session->get('admin')) {
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
        $handlerAddPost = new HandlerPost();
        return $handlerAddPost->handlerAddPost();
    }

    /**
     * retrieves all published posts and forwards them to the page
     */
    public function adminPosts()
    {
        $session = new PHPSession();
		if ($session->get('admin') == NULL || !$session->get('admin')) {
            return $this->redirect(parent::ERROR_403_PATH);
        }
        $adminPostManager = new PostManager('Post');
        $getAllPosts = $adminPostManager->getAll();

        $getAllPosts = DateFormat::formatListPostsAdmin($getAllPosts);

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
        $session = new PHPSession();
		if ($session->get('admin') == NULL || !$session->get('admin')) {
            return $this->redirect(parent::ERROR_403_PATH);
        }
        $postManager = new PostManager('Post');
        $post = $postManager->getBySlug($_GET['slug']);

        $handlerPicture = new HandlerPicture();
        $picture = $handlerPicture->searchPicture($post->getDatePublication());

        $uuid = Uuid::uuid4();
        $uuid = $uuid->toString();
        $session->set('token', $uuid);

        return $this->render('admin/editPost.html.twig', [
            'post' => $post,
            'picture' => $picture
        ]);
    }

    /**
     * Update a post which just was send with the editpost form
     */
    public function updatePost()
    {
        $handlerPost = new HandlerPost();
        return $handlerPost->handlerUpdatePost();
    }

    /**
     * Delete the post of the line
     *
     * @param  mixed $id
     * @param  string $token
     */
    public function deletePost($slug = NULL, string $token = NULL)
    {
        $session = new PHPSession();
		if ($session->get('admin') == NULL || !$session->get('admin')) {
            return $this->redirect(parent::ERROR_403_PATH);
        }
        if ($token == $session->get('token')) {
            $session->delete('token');
            $postManager = new PostManager('post');
            $id = $postManager->getIdBySlug($slug);
            $postManager->delete($id);

            $session->set('success', 'Votre post a bien été supprimé.');

            return $this->redirect(self::PATH_TO_ADMIN_POSTS);
        } else {
            return $this->redirect('/');
        }
    }
}