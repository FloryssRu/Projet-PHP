<?php

namespace App\Controller\admin;

use App\Core\BaseController;
use App\Entity\Post;
use App\Repository\Manager\PostManager;
use App\Services\DateFormat;
use App\Services\PHPSession;
use Ramsey\Uuid\Uuid;

class PostController extends BaseController
{

    private $PATH_TO_ADMIN_POSTS = '/admin-posts';
    private $ADMIN_POSTS_TEMPLATE = 'admin/adminPosts.html.twig';
    
    /**
     * Form to create a new Post
     * 
     * @return void
     */
    public function newPost(): void
    {
        $session = new PHPSession;
		if($session->get('admin') == NULL || !$session->get('admin'))
        {
            return $this->redirect('/erreur-403');
        }
        $uuid = Uuid::uuid4();
        $uuid = $uuid->toString();
        $session->set('token', $uuid);
        return $this->render('admin/newPost.html.twig');
    }
    
    /**
     * Add a new post to the database
     * 
     * @param $title
     * @param $heading
     * @param $content
     * @param $author
     * @param $token
     * @return void
     */
    public function addPost(string $title, string $heading, string $content, string $author, string $token): void
    {
        $session = new PHPSession;
		if($session->get('admin') == NULL || !$session->get('admin'))
        {
            return $this->redirect('/erreur-403');
        }
        $fields = [$title, $heading, $content, $author];

        if($this->isSubmit('newPost') && $this->isValid($fields) && $token == $session->get('token')) {

            $session->delete('token');

            $datePublication = date("Y-m-d H:i:s");
            $postManager = new PostManager('post');

            $post = new Post($title, $datePublication, NULL, $heading, $content, $author);
            $postManager->insert($post);
            
            $session->set('success', 'Votre nouveau post a bien été enregistré.');

            return $this->redirect($this->PATH_TO_ADMIN_POSTS);

        } else {

            $session->set('fail', 'Votre nouveau post n\'a pas été enregistré, une erreur dans le formulaire a été détectée.');

            return $this->redirect($this->PATH_TO_ADMIN_POSTS);

        }
    }
    
    /**
     * retrieves all published posts and forwards them to the page
     * 
     * @return void
     */
    public function adminPosts(): void
    {
        $session = new PHPSession;
		if($session->get('admin') == NULL || !$session->get('admin'))
        {
            return $this->redirect('/erreur-403');
        }
        $adminPostManager = new PostManager('Post');
        $getAllPosts = $adminPostManager->getAll();

        $dateFormat = new DateFormat;
        $getAllPosts = $dateFormat->formatListPostsAdmin($getAllPosts);

        $uuid = Uuid::uuid4();
        $uuid = $uuid->toString();
        $session->set('token', $uuid);

        return $this->render($this->ADMIN_POSTS_TEMPLATE, [
            "allPosts" => $getAllPosts
        ]);
        
        
    }
    
    /**
     * retrieves the posts to modify and complete the field of the edit page
     * 
     * @return void
     */
    public function editPost(): void
    {
        $session = new PHPSession;
		if($session->get('admin') == NULL || !$session->get('admin'))
        {
            return $this->redirect('/erreur-403');
        }
        $adminPostManager = new PostManager('Post');
        $getThisPost = $adminPostManager->getById($_GET['idPost']);
        $uuid = Uuid::uuid4();
        $uuid = $uuid->toString();
        $session->set('token', $uuid);

        return $this->render('admin/editPost.html.twig', [
            'getThisPost' => $getThisPost
        ]);
    }

    /**
     * Update a post which just was send with the editpost form
     *
     * @param  mixed $id
     * @param  string $title
     * @param  string $heading
     * @param  string $content
     * @param  string $author
     * @param  string $token
     * @return void
     */
    public function updatePost($id, string $title, string $heading, string $content, string $author, string $token): void
    {
        $session = new PHPSession;
		if($session->get('admin') == NULL || !$session->get('admin'))
        {
            return $this->redirect('/erreur-403');
        }
        $fields = [$title, $heading, $content, $author];

        if($this->isSubmit('editPost') && $this->isValid($fields) && $token == $session->get('token')) {

            $session->delete('token');
            $dateLastUpdate = date("Y-m-d H:i:s");
            $postManager = new PostManager('post');
            $postData = $postManager->getById($id);
            $post = new Post($title, $postData['date_publication'], $dateLastUpdate, $heading, $content, $author);
            
            $postManager->update($post, $id);
            $session->set('success', 'Votre post a bien été modifié.');
            return $this->redirect($this->PATH_TO_ADMIN_POSTS);

        } else {

            $session->set('fail', 'Votre post n\'a pas été modifié, une erreur dans le formulaire a été détectée.');
            return $this->redirect($this->PATH_TO_ADMIN_POSTS);

        }
    }

    /**
     * Delete the post of the line
     *
     * @param  mixed $id
     * @param  string $token
     * @return void
     */
    public function deletePost($id = NULL, string $token = NULL): void
    {
        $session = new PHPSession;
		if($session->get('admin') == NULL || !$session->get('admin'))
        {
            return $this->redirect('/erreur-403');
        }
        if($token == $session->get('token'))
        {
            $session->delete('token');
            $deletePostManager = new PostManager('post');
            $deletePostManager->delete($id);

            $session->set('success', 'Votre post a bien été supprimé.');

            return $this->redirect('/admin-posts');
        } else
        {
            return $this->redirect('/');
        }
        
    }
}