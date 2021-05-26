<?php

namespace App\Controller\admin;

use App\Core\BaseController;
use App\Entity\Comment;
use App\Repository\Manager\CommentManager;
use App\Services\AdminProtectionPart;
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
        $adminProtectionPart = new AdminProtectionPart();
        $adminProtectionPart->redirectNotAdmin();
        $commentManager = new CommentManager('Comment');
        $commentsNotValidated = $commentManager->getCommentNotValidated();

        $this->render($this->ADMIN_COMMENTS_TEMPLATE, [
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
            $this->redirect('/erreur-403');
        }
        $adminProtectionPart = new AdminProtectionPart();
        $adminProtectionPart->redirectNotAdmin();
        $commentManager = new CommentManager('Comment');
        $commentData = $commentManager->getById($id);
        $comment = new Comment($commentData['pseudo'], $commentData['content'], $commentData['date'], 1, $commentData['id_post']);
        $commentManager->update($comment, $id);
        $session = new PHPSession;
        $session->set('success', 'Le commentaire a été validé.');

        $this->redirect('/admin-commentaires');
        
    }
    
}