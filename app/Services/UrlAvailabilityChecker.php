<?php

namespace App\Services;

use App\Services\NodeScriptRunners\UrlAvailabilityCheckNodeScriptRunner;

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
        $this->reset();

        $scriptOutput = $this->urlAvailabilityNodeScriptRunner->run([
            'url' => $url
        ]);

        $this->isAvailable = !$scriptOutput->error;

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
