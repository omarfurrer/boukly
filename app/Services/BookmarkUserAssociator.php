<?php

namespace App\Services;

use App\models\Bookmark;
use App\User;

class BookmarkUserAssociator
{

    /**
     * BookmarkUserAssociator Constructor.
     */
    public function __construct()
    { }

    /**
     * Associate bookmark with a user.
     * 
     * @param Bookmark $bookmark
     * @param User $user
     * @return Bookmark
     */
    public function associate(Bookmark $bookmark, User $user)
    {
        // originally associate bookmark privately until shown otherwise
        $user->bookmarks()->attach($bookmark->id, ['is_private' => true]);
        return $bookmark->fresh();
    }
}
