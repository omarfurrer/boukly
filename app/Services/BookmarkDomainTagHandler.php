<?php

namespace App\Services;

use App\models\Bookmark;
use App\User;
use App\models\Tag;

class BookmarkDomainTagHandler
{

    /**
     * BookmarkDomainTagHandler Constructor.
     */
    public function __construct()
    { }

    /**
     * Handle adding domain tag for bookmark.
     * 
     * @param Bookmark $bookmark
     * @param User $user
     * @return Bookmark
     */
    public function handle(Bookmark $bookmark, User $user)
    {
        // get domain
        $domain = $bookmark->domain;

        $isDomainAdult = $bookmark->is_adult;

        // make sure adult tag exists
        $tag = Tag::firstOrCreate(['name' => $domain, 'is_adult' => $isDomainAdult]);

        $bookmark->tags()->syncWithoutDetaching([$tag->id => ['user_id' => $user->id, 'is_private' => $isDomainAdult]]);

        return $bookmark->fresh();
    }
}
