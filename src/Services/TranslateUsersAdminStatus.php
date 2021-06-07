<?php

namespace App\Services;

class TranslateUsersAdminStatus //extends BaseController
{    
    /**
     * Translate the value of admin attribute in a comprehensible value like "yes" or "no"
     *
     * @param  array $users Containing all the users in the database
     */
    public function translate(array $users)
    {
        foreach($users as $user)
        {
            if($user->getAdmin() == 1)
            {
                $user->adminToShow = 'Admin';
            } else
            {
                $user->adminToShow = 'Utilisateur simple';
            }
            $usersModified[] = $user;
        }
        return $usersModified;
    }
}