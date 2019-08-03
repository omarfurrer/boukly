<?php

namespace App\Services;

use App\models\Bookmark;
use App\User;

class Bookmarks
{
    /**
     * Bookmarks Constructor.
     * 
     */
    public function __construct()
    { }

    /**
     * Check is user has bookmark.
     *
     * @param string $url
     * @param User $user
     * @return boolean
     */
    public function checkUserHasBookmark($url, User $user)
    {
        $bookmark = Bookmark::where('url', $url)->first();
        if (!$bookmark) {
            return false;
        }

        if ($user->bookmarks()->where('bookmarks.id', $bookmark->id)->first()) {
            return true;
        }

        return false;
    }
}
