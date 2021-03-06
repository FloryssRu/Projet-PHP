<?php

namespace App\Controller\admin;

use App\Core\BaseController;
use App\Entity\Comment;
use App\Repository\Manager\CommentManager;
use App\Services\DateFormat;
use App\Services\PHPSession;

class CommentController extends BaseController
{
    private const ADMIN_COMMENTS_TEMPLATE = 'admin/adminComments.html.twig';
        
    /**
     * Render a list with all comments and link to validate or unvalidate for each
     */
    public function adminComment()
    {
        $session = new PHPSession();
		if ($session->get('admin') == NULL || !$session->get('admin')) {
            return $this->redirect(parent::ERROR_403_PATH);
        }
        $commentManager = new CommentManager('Comment');
        $commentsNotValidated = $commentManager->getCommentNotValidated();
        $commentsValidated = $commentManager->getCommentValidated();

        $commentsNotValidated = DateFormat::formatListComments($commentsNotValidated);
        $commentsValidated = DateFormat::formatListComments($commentsValidated);

        return $this->render(self::ADMIN_COMMENTS_TEMPLATE, [
            "commentsNotValidated" => $commentsNotValidated,
            "commentsValidated" => $commentsValidated
        ]);
    }
    
    /**
     * Change the isValidated attribute to 1 (valide this comment)
     */
    public function validComment(int $id = NULL)
    {
        $session = new PHPSession();
        if ($session->get('admin') == NULL || !$session->get('admin') || !is_int($id)) {
            return $this->redirect(parent::ERROR_403_PATH);
        }
        $commentManager = new CommentManager('comment');
        $comment = new Comment();
        $comment->setIsValidated(1);
        $commentManager->update($comment, $id);
        $session->set('success', 'Le commentaire a été validé.');

        return $this->redirect('/admin-commentaires');
    }
    
    /**
     * Change the isValidated attribute to 0 (invalide this comment)
     */
    public function invalidComment(int $id = NULL)
    {
        $session = new PHPSession();
		if ($session->get('admin') == NULL || !$session->get('admin') || !is_int($id)) {
            return $this->redirect(parent::ERROR_403_PATH);
        }
        $commentManager = new CommentManager('comment');
        $comment = new Comment();
        $comment->setIsValidated(0);
        $commentManager->update($comment, $id);
        $session->set('success', 'Le commentaire a été invalidé.');

        return $this->redirect('/admin-commentaires');
    }
}