<?php

namespace App\Services;

use App\models\Bookmark;
use Carbon\Carbon;

class BookmarkAvailabilityHandler
{

    /**
     * @var UrlAvailabilityChecker 
     */
    protected $urlAvailabilityCheker;

    /**
     * BookmarkAvailabilityHandler Constructor.
     * 
     * @param UrlAvailabilityChecker $urlAvailabilityCheker
     */
    public function __construct(UrlAvailabilityChecker $urlAvailabilityCheker)
    {
        $this->urlAvailabilityCheker = $urlAvailabilityCheker;
    }

    /**
     * Handle availability for a bookmark.
     * 
     * @param Bookmark $bookmark
     * @return Bookmark
     */
    public function handle(Bookmark $bookmark)
    {
        $isAvailable = $this->checkAvailability($bookmark);
        $this->updateBookmarkAvailability($bookmark, !$isAvailable, $this->urlAvailabilityCheker->getCode(), $this->urlAvailabilityCheker->getMessage(), Carbon::now());
        return $bookmark->fresh();
    }

    /**
     * Check the availability of a bookmark.
     * 
     * @param Bookmark $bookmark
     * @return Boolean
     */
    public function checkAvailability(Bookmark $bookmark)
    {
        return $this->urlAvailabilityCheker->check($bookmark->url);
    }

    /**
     * Update specific availability related attributes for a bookmark.
     * 
     * @param Bookmark $bookmark
     * @param Boolean $isDead
     * @param Integer $httpCode
     * @param String $httpMessage
     * @param Carbon $lastAvailabilityCheckAt
     * @return Bookmark
     */
    public function updateBookmarkAvailability(Bookmark $bookmark, $isDead, $httpCode, $httpMessage, $lastAvailabilityCheckAt)
    {
        return $bookmark->update(
            [
                'is_dead' => $isDead,
                'http_code' => $httpCode,
                'http_message' => $httpMessage,
                'last_availability_check_at' => $lastAvailabilityCheckAt
            ]
        );
    }
}
