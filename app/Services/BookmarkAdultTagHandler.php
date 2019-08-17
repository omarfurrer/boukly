<?php

namespace App\Services;

use App\models\Bookmark;
use App\User;
use App\models\Tag;

class BookmarkAdultTagHandler
{

    /**
     * BookmarkAdultTagHandler Constructor.
     */
    public function __construct()
    { }

    /**
     * Handle adding adult tag for bookmark.
     * 
     * @param Bookmark $bookmark
     * @param User $user
     * @return Bookmark
     */
    public function handle(Bookmark $bookmark, User $user)
    {
        if (!$bookmark->is_adult) {
            return $bookmark;
        }

        // make sure adult tag exists
        $tag = Tag::firstOrCreate(['name' => 'adult', 'is_adult' => true]);

        $bookmark->tags()->attach($tag->id, ['user_id' => $user->id, 'is_private' => true]);

        return $bookmark->fresh();
    }
}
