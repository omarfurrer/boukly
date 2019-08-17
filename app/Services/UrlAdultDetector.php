<?php

namespace App\Services;

use App\Services\NodeScriptRunners\UrlAdultDetectorNodeScriptRunner;
use Log;

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
        Log::debug("[UrlAdultDetector][detect] Attempting to detect adult.", [
            'url' => $url
        ]);

        $scriptOutput = $this->urlAdultDetectorNodeScriptRunner->run([
            'url' => $url
        ]);

        Log::debug("[UrlAdultDetector][detect] Adult detection output.", [
            'url' => $url,
            'response' => $scriptOutput
        ]);

        // log error
        if ($scriptOutput->error) {
            Log::error("[UrlAvailabilityChecker][check] Error detecting adult.", [
                'url' => $url,
                'details' => $scriptOutput->errorDetails
            ]);
            throw new Exception('Error detecting adult.');
        }

        $this->isAdult = $scriptOutput->isAdult;

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
