<?php

namespace App\Controller\admin;

use App\Core\BaseController;
use App\Entity\Comment;
use App\Repository\Manager\CommentManager;
use App\Services\AdminProtectionPart;
use App\Services\PHPSession;

class CommentController extends BaseController
{
        
    /**
     * Render a list with all comments and link to validate or unvalidate for each
     *
     * @return void
     */
    public function adminComment()
    {
        $adminProtectionPart = new AdminProtectionPart();
        $adminProtectionPart->redirectNotAdmin();
        $commentManager = new CommentManager('Comment');
        $commentsNotValidated = $commentManager->getCommentNotValidated();

        return $this->render('admin/adminComments.html.twig', [
                "commentsNotValidated" => $commentsNotValidated
            ]);

    }
    
    /**
     * Change the isValidated attribute to 1 (valide this comment)
     *
     * @return void
     */
    public function validComment($id = NULL)
    {
        if($id == NULL)
        {
            return $this->redirect('/erreur-403');
        }
        $adminProtectionPart = new AdminProtectionPart();
        $adminProtectionPart->redirectNotAdmin();
        $commentManager = new CommentManager('Comment');
        $commentData = $commentManager->getById($id);
        $comment = new Comment($commentData['pseudo'], $commentData['content'], $commentData['date'], 1, $commentData['id_post']);
        $commentManager->update($comment, $id);
        $session = new PHPSession;
        $session->set('success', 'Le commentaire a été validé.');

        return $this->redirect('/admin-commentaires');

        /*$commentsNotValidated = $commentManager->getCommentNotValidated();
        if($session->get('success') != NULL)
        {
            $success = $session->get('success');
            $session->delete('success');
            return $this->render('admin/adminComments.html.twig', [
                "commentsNotValidated" => $commentsNotValidated,
                'success' => $success
            ]);

        } elseif ($session->get('fail') != NULL)
        {
            $fail = $session->get('fail');
            $session->delete('fail');
            return $this->render('admin/adminComments.html.twig', [
                "commentsNotValidated" => $commentsNotValidated,
                'fail' => $fail
            ]);
        } else
        {
            return $this->redirect('/admin-commentaires');
        }*/
        
    }
    
}