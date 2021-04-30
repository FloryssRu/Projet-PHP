<?php

namespace App\Controller\admin;

use App\Core\BaseController;
use App\Repository\Manager\CommentManager;
use App\Services\PHPSession;

class CommentController extends BaseController
{
        
    /**
     * Render a list with all comments which have isValidated on 0
     *
     * @return void
     */
    public function adminComment()
    {
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
    public function validComment($id)
    {
        $commentManager = new CommentManager('Comment');
        $params = ['is_validated' => 1];
        $commentManager->update($params, $id);
        $session = new PHPSession;
        $session->set('success', 'Le commentaire a été validé.');
        $commentsNotValidated = $commentManager->getCommentNotValidated();

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
            return $this->render('admin/adminComments.html.twig', [
                "commentsNotValidated" => $commentsNotValidated
            ]);
        }
        
    }
    
}