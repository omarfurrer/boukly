<?php

namespace App\Traits;

use Purl\Url;

trait DomainExtractorTrait {

    /**
     * Extract domain name from URL.
     * 
     * @param string $url
     * @return string
     */
    public function extractDomain($url)
    {
        $url = new Url($url);
        return $url->registerableDomain;
    }

}
