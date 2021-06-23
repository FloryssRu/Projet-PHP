<?php

namespace App\Services;

use App\Controller\admin\PostController;

class HandlerPicture extends PostController
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
     * Save a picture in the directory public/img and create its name with the post's date of publication
     *
     * @param  mixed $picture   The $_FILE['picture'] content
     * @param  string $name     The date of publication
     */
    public function savePicture($picture, string $name)
    {
        if($picture !== NULL && $picture['error'] == 0 && $picture['size'] <= 3000000)
        {
            $infosfichier = pathinfo($picture['name']);
            $extension_upload = $infosfichier['extension'];
            $extensions_autorisees = array('jpg', 'png', 'gif');
            if(in_array($extension_upload, $extensions_autorisees))
            {
                $picture['name'] = str_replace([':','-',' '], '_', $name) . '.' . $extension_upload;
                move_uploaded_file($picture['tmp_name'], '../public/img/' . $picture['name']);
                return true;
            } elseif (!in_array($extension_upload, $extensions_autorisees))
            {
                $session = new PHPSession();
                $session->set('fail', "Le fichier n'a pas pu être ajouté car son extension n'est pas acceptée.");
            }
        }
    }

    public function searchPicture(string $date_publication)
    {
        $extensions = ['.jpg', '.png', '.gif'];
        foreach($extensions as $extension)
        {
            $pathToPicture = '../public/img/' . str_replace([':', '-', ' '], '_', $date_publication) . $extension;
            if(file_exists($pathToPicture))
            {
                $picture = 'public/img/' . str_replace([':', '-', ' '], '_', $date_publication) . $extension;
            }
        }

        if(!isset($picture))
        {
            $picture = 'public/img/' . 'default.jpg';
        }

        return $picture;
    }
    
}