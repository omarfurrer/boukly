<?php

namespace App\Services;

use App\models\Bookmark;
use App\Services\UrlMetatagsExtractor;

class BookmarkMetatagsHandler
{
    /**
     * @var UrlMetatagsExtractor 
     */
    protected $urlMetatagsExtractor;

    /**
     * BookmarkMetatagsHandler Constructor.
     * 
     * @param UrlMetatagsExtractor $urlMetatagsExtractor
     */
    public function __construct(UrlMetatagsExtractor $urlMetatagsExtractor)
    {
        $this->urlMetatagsExtractor = $urlMetatagsExtractor;
    }

    /**
     * Handle metatags for a bookmark.
     * 
     * @param Bookmark $bookmark
     * @return Bookmark
     */
    public function handle(Bookmark $bookmark)
    {
        if ($bookmark->is_dead) {
            return $bookmark;
        }
        $metatags = $this->extractMetatags($bookmark);
        $this->updateBookmarkMetatags($bookmark, $metatags, $this->urlMetatagsExtractor->getTitle(), $this->urlMetatagsExtractor->getDescription(), $this->urlMetatagsExtractor->getImage());
        return $bookmark->fresh();
    }

    /**
     * Extract meta tags for a bookmark.
     * 
     * @param Bookmark $bookmark
     * @return Object
     */
    public function extractMetatags(Bookmark $bookmark)
    {
        return $this->urlMetatagsExtractor->extract($bookmark->url);
    }

    /**
     * Update specific metatags related attributes for a bookmark.
     * Updates title, description and image as well.
     * 
     * @param Bookmark $bookmark
     * @param Object $metatags
     * @param String $title
     * @param String $description
     * @param String $image
     * @return Bookmark
     */
    public function updateBookmarkMetatags(Bookmark $bookmark, $metatags, $title, $description, $image)
    {
        return $bookmark->update(
            [
                'metatags' => $metatags,
                'title' => $title,
                'description' => $description,
                'image' => $image
            ]
        );
    }
}
