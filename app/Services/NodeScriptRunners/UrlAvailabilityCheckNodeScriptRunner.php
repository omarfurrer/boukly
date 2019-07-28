<?php

namespace App\Services\NodeScriptRunners;

class UrlAvailabilityCheckNodeScriptRunner extends AbstractNodeScriptRunner {

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get the name of the script to be run.
     * 
     * @returns string
     */
    public function getScriptName()
    {
        return 'UrlAvailabilityCheck.js';
    }

    /**
     * Get the specific mapping for the command arguments.
     * 
     * @return array
     */
    public function getParamsMapping()
    {
        return [
            'url' => 0
        ];
    }

}
