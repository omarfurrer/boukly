<?php

namespace App\Services;

use App\models\Bookmark;
use App\Services\UrlAvailabilityChecker;
use App\Services\UrlMetatagsExtractor;
use App\Services\UrlAdultDetector;
use App\Traits\DomainExtractorTrait;
use Carbon\Carbon;
use Storage;

class BookmarkImporter
{
    use DomainExtractorTrait;

    /**
     * @var UrlMetatagsExtractor 
     */
    protected $urlMetatagsExtractor;

    /**
     * @var UrlAdultDetector 
     */
    protected $urlAdultDetector;

    /**
     * @var UrlAvailabilityChecker 
     */
    protected $urlAvailabilityCheker;

    /**
     * BookmarkImporter Constructor.
     * 
     * @param UrlAvailabilityChecker $urlAvailabilityCheker
     * @param UrlMetatagsExtractor $urlMetatagsExtractor
     * @param UrlAdultDetector $urlAdultDetector
     */
    public function __construct(UrlAvailabilityChecker $urlAvailabilityCheker, UrlMetatagsExtractor $urlMetatagsExtractor, UrlAdultDetector $urlAdultDetector)
    {
        $this->urlAvailabilityCheker = $urlAvailabilityCheker;
        $this->urlMetatagsExtractor = $urlMetatagsExtractor;
        $this->urlAdultDetector = $urlAdultDetector;
    }

    public function importFromTextFile($name)
    {
        $urls = array_filter(file(storage_path("/app/{$name}"), FILE_IGNORE_NEW_LINES));

        foreach ($urls as $url) {
            $bookmarks[] =  $this->import($url);
        }

        return $bookmarks;
    }

    public function multiImport($urls)
    {
        foreach ($urls as $url) {
            $bookmarks[] =  $this->import($url);
        }

        return $bookmarks;
    }

    public function import($url)
    {
        //   "url",
        // "domain",

        // "title",
        // "description",
        // "image",
        // "metatags"

        // "is_dead",
        // "http_code",
        // "http_message",
        // "last_availability_check_at",

        // "is_adult",
        try {
            // check if it exists
            $bookmark = Bookmark::where("url", $url)->first();
            if ($bookmark) {
                return false;
            }
            print $url;

            $data = [
                "url" => $url,
                "domain" => $this->extractDomain($url),
                "is_dead" => false
            ];

            // check availability
            $isAvailable = $this->urlAvailabilityCheker->check($url);
            if (!$isAvailable) {
                $data['is_dead'] = true;
            }

            $data['http_code'] = $this->urlAvailabilityCheker->getCode();
            $data['http_message'] = $this->urlAvailabilityCheker->getMessage();
            $data['last_availability_check_at'] = Carbon::now();

            if ($isAvailable) {
                // get meta tags
                $data['metatags'] = $this->urlMetatagsExtractor->extract($url);
                $data['title'] = $this->urlMetatagsExtractor->getTitle();
                $data['description'] = $this->urlMetatagsExtractor->getDescription();
                $data['image'] = $this->urlMetatagsExtractor->getImage();
            }

            // get adult
            $data['is_adult'] = $this->urlAdultDetector->detect($url);

            return Bookmark::create($data);
        } catch (Exception $e) {
            return $e;
        }
    }
}
