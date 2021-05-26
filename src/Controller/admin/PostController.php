<?php

namespace App\Controller\admin;

use App\Core\BaseController;
use App\Entity\Post;
use App\Repository\Manager\PostManager;
use App\Services\AdminProtectionPart;
use App\Services\PHPSession;
use Ramsey\Uuid\Uuid;

class PostController extends BaseController
{
    
    /**
     * Form to create a new Post
     */
    public function newPost()
    {
        $adminProtectionPart = new AdminProtectionPart();
        $adminProtectionPart->redirectNotAdmin();
        $session = new PHPSession;
        $uuid = Uuid::uuid4();
        $uuid = $uuid->toString();
        $session->set('token', $uuid);
        return $this->render('admin/newPost.html.twig', []);
    }
    
    /**
     * Add a new post to the database
     */
    public function addPost(string $title, string $heading, string $content, string $author, string $token)
    {
        $adminProtectionPart = new AdminProtectionPart();
        $adminProtectionPart->redirectNotAdmin();
        $fields = [$title, $heading, $content, $author];
        $session = new PHPSession;

        if($this->isSubmit('newPost') && $this->isValid($fields) && $token == $session->get('token')) {

            $session->delete('token');

            $datePublication = $dateLastUpdate = date("Y-m-d H:i:s");
            $postManager = new PostManager('post');

            $post = new Post($title, $datePublication, $dateLastUpdate, $heading, $content, $author);
            $postManager->insert($post);
            
            $session->set('success', 'Votre nouveau post a bien été enregistré.');

            return $this->redirect('/admin-posts');

        } else {

            $session->set('fail', 'Votre nouveau post n\'a pas été enregistré, une erreur dans le formulaire a été détectée.');

            return $this->redirect('/admin-posts');

        }
    }
    
    /**
     * retrieves all published posts and forwards them to the page
     */
    public function adminPosts()
    {
        $adminProtectionPart = new AdminProtectionPart();
        $adminProtectionPart->redirectNotAdmin();
        $adminPostManager = new PostManager('Post');
        $getAllPosts = $adminPostManager->getAll();
        $session = new PHPSession;
        $uuid = Uuid::uuid4();
        $uuid = $uuid->toString();
        $session->set('token', $uuid);

        return $this->render('admin/adminPosts.html.twig', [
            "allPosts" => $getAllPosts
        ]);
        
    }
    
    /**
     * retrieves the posts to modify and complete the field of the edit page
     */
    public function editPost()
    {
        $adminProtectionPart = new AdminProtectionPart();
        $adminProtectionPart->redirectNotAdmin();
        $adminPostManager = new PostManager('Post');
        $getThisPost = $adminPostManager->getById($_GET['idPost']);
        $session = new PHPSession;
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
     */
    public function updatePost($id, string $title, string $heading, string $content, string $author, string $token)
    {
        $adminProtectionPart = new AdminProtectionPart();
        $adminProtectionPart->redirectNotAdmin();
        $fields = [$title, $heading, $content, $author];
        $session = new PHPSession;

        if($this->isSubmit('editPost') && $this->isValid($fields) && $token == $session->get('token')) {

            $session->delete('token');
            $dateLastUpdate = date("Y-m-d H:i:s");
            $postManager = new PostManager('post');
            $postData = $postManager->getById($id);
            $post = new Post($title, $postData['date_publication'], $dateLastUpdate, $heading, $content, $author);
            
            $postManager->update($post, $id);
            $session = new PHPSession;
            $session->set('success', 'Votre post a bien été modifié.');

            return $this->redirect('/admin-posts');

        } else {

            $session = new PHPSession;
            $session->set('fail', 'Votre post n\'a pas été modifié, une erreur dans le formulaire a été détectée.');

            return $this->redirect('/admin-posts');

        }
    }

    /**
     * Delete the post of the line
     *
     * @param  mixed $id
     * @param  string $token
     */
    public function deletePost($id, string $token)
    {
        $adminProtectionPart = new AdminProtectionPart();
        $adminProtectionPart->redirectNotAdmin();
        $session = new PHPSession;
        if($token == $session->get('token'))
        {
            $session->delete('token');
            $adminProtectionPart = new AdminProtectionPart();
            $adminProtectionPart->redirectNotAdmin();
            $deletePostManager = new PostManager('post');
            echo $deletePostManager->delete($id);

            $session = new PHPSession;
            $session->set('success', 'Votre post a bien été supprimé.');

            return $this->redirect('/admin-posts');
        } else
        {
            return $this->redirect('/');
        }
        
    }
}