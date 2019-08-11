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

    /**
     * Get a user's bookmarks.
     *
     * @param User $user
     * @return boolean
     */
    public function getUserBookmarks(User $user, $perPage = 100, $page = 1, $tags = [])
    {
        $bookmarks = $user->bookmarks();

        if (!empty($tags)) {
            $bookmarks = $bookmarks
                ->join('bookmark_tag', 'bookmarks.id', '=', 'bookmark_tag.bookmark_id')
                ->join('tags', 'tags.id', '=', 'bookmark_tag.tag_id')
                ->where('bookmark_tag.user_id', $user->id)
                ->whereIn('tags.name', $tags);
        }

        $bookmarks = $bookmarks
            // ->where('is_adult', false)
            ->paginate($perPage, ['bookmarks.*'], 'page', $page);

        return $bookmarks;
    }
}
