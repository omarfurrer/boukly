<?php

namespace App\Services;

use App\Services\NodeScriptRunners\UrlAdultDetectorNodeScriptRunner;

class UrlAdultDetector
{

    /**
     * True if URL is adult content.
     * 
     * @var boolean 
     */
    protected $isAdult;

    /**
     * @var UrlAdultDetectorNodeScriptRunner 
     */
    protected $urlAdultDetectorNodeScriptRunner;

    /**
     * Constructor.
     * 
     * @param UrlAdultDetectorNodeScriptRunner $urlAdultDetectorNodeScriptRunner
     */
    public function __construct(UrlAdultDetectorNodeScriptRunner $urlAdultDetectorNodeScriptRunner)
    {
        $this->urlAdultDetectorNodeScriptRunner = $urlAdultDetectorNodeScriptRunner;
    }

    /**
     * Detects whether a URL is of adult content or not.
     * 
     * @param string $url
     * @return boolean
     */
    public function detect($url)
    {
        $this->isAdult = $this->urlAdultDetectorNodeScriptRunner->run([
            'url' => $url
        ]);
        return $this->isAdult;
    }

    /**
     * isAdult getter.
     * 
     * @return boolean
     */
    public function getIsAdult()
    {
        return $this->isAdult;
    }
}
