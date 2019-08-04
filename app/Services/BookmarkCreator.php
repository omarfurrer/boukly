<?php

namespace App\Services;

use App\Traits\DomainExtractorTrait;
use App\models\Bookmark;

class BookmarkCreator
{

    use DomainExtractorTrait;

    /**
     * BookmarkCreator constructor.
     */
    public function __construct()
    { }

    /**
     * Create a bookmark from URL.
     * 
     * @param String $url
     * @return Bookmark
     */
    public function create($url, $forceAdult = true)
    {
        $domain = $this->extractDomain($url);
        return Bookmark::create(["url" => $url, "domain" => $domain, 'is_adult' => $forceAdult]);
    }
}
