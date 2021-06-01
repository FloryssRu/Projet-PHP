<?php

namespace App\Controller\admin;

use App\Core\BaseController;
use App\Entity\Comment;
use App\Repository\Manager\CommentManager;
use App\Services\DateFormat;
use App\Services\PHPSession;

class CommentController extends BaseController
{

    private $ADMIN_COMMENTS_TEMPLATE = 'admin/adminComments.html.twig';
        
    /**
     * Render a list with all comments and link to validate or unvalidate for each
     *
     * @return void
     */
    public function adminComment(): void
    {
        $session = new PHPSession;
		if($session->get('admin') == NULL || !$session->get('admin'))
        {
            return $this->redirect('/erreur-403');
        }
        $commentManager = new CommentManager('Comment');
        $commentsNotValidated = $commentManager->getCommentNotValidated();
        $commentsValidated = $commentManager->getCommentValidated();

        $dateFormat = new DateFormat;
        $commentsNotValidated = $dateFormat->formatListComments($commentsNotValidated);
        $commentsValidated = $dateFormat->formatListComments($commentsValidated);

        return $this->render($this->ADMIN_COMMENTS_TEMPLATE, [
            "commentsNotValidated" => $commentsNotValidated,
            "commentsValidated" => $commentsValidated
        ]);

    }
    
    /**
     * Change the isValidated attribute to 1 (valide this comment)
     * 
     * @return void
     */
    public function validComment($id = NULL): void
    {
        $session = new PHPSession;
        if($id == NULL || $session->get('admin') == NULL || !$session->get('admin'))
        {
            return $this->redirect('/erreur-403');
        }
        $commentManager = new CommentManager('Comment');
        $commentData = $commentManager->getById($id);
        $comment = new Comment($commentData['pseudo'], $commentData['content'], $commentData['date'], 1, $commentData['id_post']);
        $commentManager->update($comment, $id);
        $session->set('success', 'Le commentaire a été validé.');

        return $this->redirect('/admin-commentaires');
        
    }
    
    /**
     * Change the isValidated attribute to 0 (invalide this comment)
     */
    public function invalidComment($id)
    {
        $session = new PHPSession;
		if($session->get('admin') == NULL || !$session->get('admin'))
        {
            return $this->redirect('/erreur-403');
        }
        $commentManager = new CommentManager('Comment');
        $commentData = $commentManager->getById($id);
        $comment = new Comment($commentData['pseudo'], $commentData['content'], $commentData['date'], 0, $commentData['id_post']);
        $commentManager->update($comment, $id);
        $session->set('success', 'Le commentaire a été invalidé.');

        return $this->redirect('/admin-commentaires');
    }
    
}