<?php

namespace App\Controller\admin;

use App\Core\BaseController;
use App\Repository\Manager\PostManager;
use App\Services\PHPSession;

class PostController extends BaseController
{
    
    /**
     * Form to create a new Post
     *
     * @return void
     */
    public function newPost()
    {
        return $this->render('admin/newPost.html.twig', []);
    }
    
    /**
     * Add a new post to the database
     *
     * @return void
     */
    public function addPost(string $title, string $heading, string $content, string $author)
    {
        $fields = [$title, $heading, $content, $author];

        if($this->isSubmit('newPost') && $this->isValid($fields)) {

            $datePublication = $dateLastUpdate = date("Y-m-d H:i:s");
            $addPostManager = new PostManager('post');

            $params = [
                'title' => $title,
                'heading' => $heading,
                'content' => $content,
                'author' => $author,
                'date_publication' =>$datePublication,
                'date_last_update' => $dateLastUpdate
            ];  

            $addPostManager->insert($params);
            $session = new PHPSession;
            $session->set('success', 'Votre nouveau post a bien été enregistré.');

            return $this->adminPosts();

        } else {

            $session = new PHPSession;
            $session->set('fail', 'Votre nouveau post n\'a pas été enregistré, une erreur dans le formulaire a été détectée.');

            return $this->adminPosts();

        }
    }
    
    /**
     * retrieves all published posts and forwards them to the page
     *
     * @return void
     */
    public function adminPosts()
    {
        $adminPostManager = new PostManager('Post');
        $getAllPosts = $adminPostManager->getAll();

        $session = new PHPSession;

        if($session->get('success') != NULL)
        {
            $success = $session->get('success');
            $session->delete('success');
            return $this->render('admin/adminPosts.html.twig', [
                "allPosts" => $getAllPosts,
                'success' => $success
            ]);

        } elseif ($session->get('fail') != NULL)
        {
            $fail = $session->get('fail');
            $session->delete('fail');
            return $this->render('admin/adminPosts.html.twig', [
                "allPosts" => $getAllPosts,
                'fail' => $fail
            ]);
        } else
        {
            return $this->render('admin/adminPosts.html.twig', [
                "allPosts" => $getAllPosts
            ]);
        }
        
    }
    
    /**
     * retrieves the posts to modify and complete the field of the edit page
     *
     * @return void
     */
    public function editPost()
    {
        $adminPostManager = new PostManager('Post');
        $getThisPost = $adminPostManager->getById($_GET['idPost']);

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
     * @return void
     */
    public function updatePost($id, string $title, string $heading, string $content, string $author)
    {

        $fields = [$title, $heading, $content, $author];

        if($this->isSubmit('editPost') && $this->isValid($fields)) {

            $dateLastUpdate = date("Y-m-d H:i:s");
            $updatePostManager = new PostManager('post');
            $params = [
                'title' => $title,
                'heading' => $heading,
                'content' => $content,
                'author' => $author,
                'date_last_update' => $dateLastUpdate
            ];
            
            $updatePostManager->update($params, $id);
            $session = new PHPSession;
            $session->set('success', 'Votre post a bien été modifié.');

            return $this->adminPosts();

        } else {

            $session = new PHPSession;
            $session->set('fail', 'Votre post n\'a pas été modifié, une erreur dans le formulaire a été détectée.');

            return $this->adminPosts();

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
    public function deletePost($id)
    {
        $deletePostManager = new PostManager('post');
        echo $deletePostManager->delete($id);

        $session = new PHPSession;
        $session->set('success', 'Votre post a bien été supprimé.');

        return $this->adminPosts();
    }
}