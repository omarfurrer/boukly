<?php

namespace App\Services;

use App\User;

class Tags
{
    /**
     * Tags Constructor.
     * 
     */
    public function __construct()
    { }

    /**
     * Check is user has bookmark.
     *
     * @param User $user
     * @return boolean
     */
    public function getUserTags(User $user)
    {
        return $user->tags;
    }
}
