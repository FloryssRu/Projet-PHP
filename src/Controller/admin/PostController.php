<?php

namespace App\Controller\admin;

use App\Core\BaseController;
use App\Entity\Post;
use App\Repository\Manager\PostManager;
use App\Services\PHPSession;

class PostController extends BaseController
{

    private $PATH_TO_ADMIN_POSTS = '/admin-posts';
    
    /**
     * Form to create a new Post
     *
     * @return void
     */
    public function newPost(): void
    {
        $this->render('admin/newPost.html.twig', []);
    }
    
    /**
     * Add a new post to the database
     *
     * @return void
     */
    public function addPost(string $title, string $heading, string $content, string $author): void
    {
        $fields = [$title, $heading, $content, $author];

        if($this->isSubmit('newPost') && $this->isValid($fields)) {

            $datePublication = $dateLastUpdate = date("Y-m-d H:i:s");
            $postManager = new PostManager('post');

            $post = new Post($title, $datePublication, $dateLastUpdate, $heading, $content, $author);
            $postManager->insert($post);
            $session = new PHPSession;
            $session->set('success', 'Votre nouveau post a bien été enregistré.');

            $this->redirect($this->PATH_TO_ADMIN_POSTS);

        } else {

            $session = new PHPSession;
            $session->set('fail', 'Votre nouveau post n\'a pas été enregistré, une erreur dans le formulaire a été détectée.');

            $this->redirect($this->PATH_TO_ADMIN_POSTS);

        }
    }
    
    /**
     * retrieves all published posts and forwards them to the page
     *
     * @return void
     */
    public function adminPosts(): void
    {
        $adminPostManager = new PostManager('Post');
        $getAllPosts = $adminPostManager->getAll();

        $session = new PHPSession;

        if($session->get('success') != NULL)
        {
            $success = $session->get('success');
            $session->delete('success');
            $this->render('admin/adminPosts.html.twig', [
                "allPosts" => $getAllPosts,
                'success' => $success
            ]);

        } elseif ($session->get('fail') != NULL)
        {
            $fail = $session->get('fail');
            $session->delete('fail');
            $this->render('admin/adminPosts.html.twig', [
                "allPosts" => $getAllPosts,
                'fail' => $fail
            ]);
        } else
        {
            $this->render('admin/adminPosts.html.twig', [
                "allPosts" => $getAllPosts
            ]);
        }
        
    }
    
    /**
     * retrieves the posts to modify and complete the field of the edit page
     *
     * @return void
     */
    public function editPost(): void
    {
        $adminPostManager = new PostManager('Post');
        $getThisPost = $adminPostManager->getById($_GET['idPost']);

        $this->render('admin/editPost.html.twig', [
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
     * @return void
     */
    public function updatePost($id, string $title, string $heading, string $content, string $author): void
    {

        $fields = [$title, $heading, $content, $author];

        if($this->isSubmit('editPost') && $this->isValid($fields)) {

            $dateLastUpdate = date("Y-m-d H:i:s");
            $postManager = new PostManager('post');
            $postData = $postManager->getById($id);
            $post = new Post($title, $postData['date_publication'], $dateLastUpdate, $heading, $content, $author);
            
            $postManager->update($post, $id);
            $session = new PHPSession;
            $session->set('success', 'Votre post a bien été modifié.');

            $this->redirect($this->PATH_TO_ADMIN_POSTS);

        } else {

            $session = new PHPSession;
            $session->set('fail', 'Votre post n\'a pas été modifié, une erreur dans le formulaire a été détectée.');

            $this->redirect($this->PATH_TO_ADMIN_POSTS);

        }
    }

    /**
     * Delete the post of the line
     *
     * @param  mixed $id
     * @param  string $title
     * @param  string $heading
     * @param  string $content
     * @param  string $author
     * @return void
     */
    public function deletePost($id): void
    {
        $deletePostManager = new PostManager('post');
        echo $deletePostManager->delete($id);

        $session = new PHPSession;
        $session->set('success', 'Votre post a bien été supprimé.');

        $this->adminPosts();
    }
}