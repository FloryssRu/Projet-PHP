<?php

namespace App\Services;

use App\Core\BaseController;

class DateFormat extends BaseController
{

    /**
     * Is empty because the mother class has a construct function and we don't want to use it for this class.
     *
     * @return void
     */
    public function __construct()
    {

    }
    
    /**
     * Format the date to make it readable
     *
     * @param  string $date The date in format "YYYY-MM-DD HH-II-SS"
     * @return string       The date in readable version : "le DD MONTH YYYY, à HHhIIminSS"
     */
    public function formatToDisplay(string $date)
    {
        $dayAndHour = explode(' ', $date);
        $day = explode('-', $dayAndHour[0]);
        return "le " . $day[2] . "/" . $day[1] . "/" . $day[0] . ", à " . $dayAndHour[1];
    }
    
    /**
     * Format all the dates in the array $listPosts for the adminpart
     *
     * @param  array $listPosts
     * @return array
     */
    public function formatListPostsAdmin(array $listPosts): array
    {
        foreach($listPosts as $key => $post)
        {
            if($post->getDateLastUpdate() == NULL)
            {
                $listPosts[$key]->setDateLastUpdate('Ce post n\'a pas été modifié.');
            } else
            {
                $listPosts[$key]->setDateLastUpdate($this->formatToDisplay($post->getDateLastUpdate()));
            }
        }
        return $listPosts;
    }

    /**
     * Format all the dates in the array $listPosts for the post list page
     *
     * @param  array $listPosts An array of posts objects
     * @return array
     */
    public function formatListPosts(array $listPosts): array
    {
        foreach($listPosts as $key => $post)
        {
            
            if($post->getDateLastUpdate() == NULL)
            {
                $listPosts[$key]->setDatePublication('Publié ' . $this->formatToDisplay($post->getDatePublication()));
            } else
            {
                $listPosts[$key]->setDateLastUpdate('Mis à jour ' . $this->formatToDisplay($post->getDateLastUpdate()));
            }
        }
        return $listPosts;
    }

    /**
     * Format all the dates in the array $listComments
     *
     * @param  array $listComments
     * @return array
     */
    public function formatListComments(array $listComments): array
    {
        foreach($listComments as $key => $comment)
        {
            $listComments[$key]->setDate($this->formatToDisplay($comment->getDate()));
        }
        return $listComments;
    }

}