<?php

namespace App\Services;

use App\models\Bookmark;
use App\User;
use DB;

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
        $bookmarks = Bookmark::join('bookmark_user', 'bookmarks.id', '=', 'bookmark_user.bookmark_id')
            ->where('bookmark_user.user_id', $user->id);

        if (!empty($tags)) {
            $bookmarks = $bookmarks
                ->join('bookmark_tag', 'bookmarks.id', '=', 'bookmark_tag.bookmark_id')
                ->join('tags', 'tags.id', '=', 'bookmark_tag.tag_id')
                ->whereIn('tags.name', $tags)
                ->groupBy('bookmarks.id')
                ->having(DB::raw('count(*)'), '=', count($tags));
        }

        $bookmarks = $bookmarks
            // ->where('is_adult', false)
            ->orderBy('bookmark_user.id','DESC')
            ->paginate($perPage, ['bookmarks.*'], 'page', $page);

        return $bookmarks;
    }
}
