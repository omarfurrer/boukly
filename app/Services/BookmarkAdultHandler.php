<?php

namespace App\Services;

use App\models\Bookmark;

class BookmarkAdultHandler
{

    /**
     * @var UrlAdultDetector 
     */
    protected $urlAdultDetector;

    /**
     * BookmarkAdultHandler Constructor.
     * 
     * @param UrlAdultDetector $urlAdultDetector
     */
    public function __construct(UrlAdultDetector $urlAdultDetector)
    {
        $this->urlAdultDetector = $urlAdultDetector;
    }

    /**
     * Handle adult flag for a bookmark.
     * 
     * @param Bookmark $bookmark
     * @return Bookmark
     */
    public function handle(Bookmark $bookmark)
    {
        $isAdult = $this->checkAdult($bookmark);
        $this->updateBookmarkAdult($bookmark, $isAdult);
        return $bookmark->fresh();
    }

    /**
     * Check if a bookmark is of adult content.
     * 
     * @param Bookmark $bookmark
     * @return Boolean
     */
    public function checkAdult(Bookmark $bookmark)
    {
        return $this->urlAdultDetector->detect($bookmark->url);
    }

    /**
     * Update specific adult related attributes for a bookmark.
     * 
     * @param Bookmark $bookmark
     * @param Boolean $isAdult
     * @return Bookmark
     */
    public function updateBookmarkAdult(Bookmark $bookmark, $isAdult)
    {
        return $bookmark->update([
            'is_adult' => $isAdult
        ]);
    }
}
