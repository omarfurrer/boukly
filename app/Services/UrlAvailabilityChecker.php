<?php

namespace App\Services;

use App\Services\NodeScriptRunners\UrlAvailabilityCheckNodeScriptRunner;
use Log;
use Exception;

class UrlAvailabilityChecker
{

    /**
     * HTTP code if any for the requested URL.
     * 
     * @var Integer 
     */
    protected $code;

    /**
     * HTTP code if any for the requested URL.
     * 
     * @var string 
     */
    protected $message;

    /**
     * Indicates whether the URL is available.
     * 
     * @var boolean 
     */
    protected $isAvailable;

    /**
     * @var UrlAvailabilityCheckNodeScriptRunner 
     */
    protected $urlAvailabilityNodeScriptRunner;

    /**
     * Constructor.
     * 
     * @param UrlAvailabilityCheckNodeScriptRunner $urlAvailabilityNodeScriptRunner
     */
    public function __construct(UrlAvailabilityCheckNodeScriptRunner $urlAvailabilityNodeScriptRunner)
    {
        $this->urlAvailabilityNodeScriptRunner = $urlAvailabilityNodeScriptRunner;
    }

    /**
     * Reset class attributes. 
     */
    public function reset()
    {
        $this->code = null;
        $this->message = '';
        $this->isAvailable = false;
    }

    /**
     * Check whether a URL is available or not.
     * 
     * @param string $url
     * @return boolean
     */
    public function check($url)
    {
        Log::debug("[UrlAvailabilityChecker][check] Attempting to check availability.", [
            'url' => $url
        ]);

        $this->reset();

        $scriptOutput = $this->urlAvailabilityNodeScriptRunner->run([
            'url' => $url
        ]);

        Log::debug("[UrlAvailabilityChecker][check] Availability check output.", [
            'url' => $url,
            'response' => $scriptOutput
        ]);

        if (empty($scriptOutput) || $scriptOutput->error) {
            // log error
            Log::error("[UrlAvailabilityChecker][check] Error checking availability.", [
                'url' => $url,
                'details' => empty($scriptOutput) ? $scriptOutput : $scriptOutput->errorDetails
            ]);
            throw new Exception('Error checking availability.');
        }

        $this->isAvailable = $scriptOutput->isAvailable;

        if (!empty($scriptOutput->code)) {
            $this->code = $scriptOutput->code;
        }

        if (!empty($scriptOutput->message)) {
            $this->message = $scriptOutput->message;
        }

        return $this->isAvailable;
    }

    /**
     * Code getter.
     * 
     * @return Integer
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Message getter.
     * 
     * @return String
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * isAvailable getter.
     * 
     * @return Boolean
     */
    public function getIsAvailable()
    {
        return $this->isAvailable;
    }
}
