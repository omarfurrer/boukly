<?php

namespace App\Services;

use App\models\Bookmark;
use App\User;

class BookmarkUserPrivacyHandler
{

    /**
     * BookmarkUserPrivacyHandler constructor.
     * 
     */
    public function __construct()
    { }

    /**
     * Handle user bookmark privacy.
     * 
     * @param Bookmark $bookmark
     * @param User $user
     * @return Bookmark
     */
    public function handle(Bookmark $bookmark, User $user)
    {
        $isPrivate = false;

        // get default privacy of bookmark
        // If is_adult is true, mark as private
        $isPrivate = $bookmark->is_adult;

        // TODO : check global black list
        // TODO : check user black list

        $user->bookmarks()->updateExistingPivot($bookmark->id, ["is_private" => $isPrivate]);

        return $bookmark->fresh();
    }
}
