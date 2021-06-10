<?php

namespace App\Controller\admin;

use App\Core\BaseController;
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
        $session = new PHPSession;
		if($session->get('admin') == NULL || !$session->get('admin'))
        {
            return $this->redirect(parent::ERROR_403_PATH);
        }
        $commentManager = new CommentManager('Comment');
        $commentsNotValidated = $commentManager->getCommentNotValidated();
        $commentsValidated = $commentManager->getCommentValidated();

        $dateFormat = new DateFormat;
        $commentsNotValidated = $dateFormat->formatListComments($commentsNotValidated);
        $commentsValidated = $dateFormat->formatListComments($commentsValidated);

        return $this->render(self::ADMIN_COMMENTS_TEMPLATE, [
            "commentsNotValidated" => $commentsNotValidated,
            "commentsValidated" => $commentsValidated
        ]);

    }
    
    /**
     * Change the isValidated attribute to 1 (valide this comment)
     */
    public function validComment($id = NULL)
    {
        $session = new PHPSession;
        if($id == NULL || $session->get('admin') == NULL || !$session->get('admin'))
        {
            return $this->redirect(parent::ERROR_403_PATH);
        }
        $commentManager = new CommentManager('Comment');
        $arrayData = [
            'is_validated' => 1
        ];
        $commentManager->update($arrayData, $id);
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
            return $this->redirect(parent::ERROR_403_PATH);
        }
        $commentManager = new CommentManager('Comment');
        $arrayData = [
            'is_validated' => 0
        ];
        $commentManager->update($arrayData, $id);
        $session->set('success', 'Le commentaire a été invalidé.');

        return $this->redirect('/admin-commentaires');
    }
    
}