<?php

namespace App\Services\NodeScriptRunners;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

abstract class AbstractNodeScriptRunner
{

    /**
     * Base path for scripts directory.
     */
    const BASE_SCRIPT_PATH = 'bin/node/';

    /**
     * Node path to run script.
     */
    const NODE_PATH = '/usr/bin/node';

    /**
     * Params in an associative array form.
     * 
     * @var array 
     */
    protected $associativeParams;

    /**
     * Params in an indexed array form.
     * 
     * @var array 
     */
    protected $indexedParams;

    /**
     * The returned raw string from running the script.
     * 
     * @var string 
     */
    protected $rawResult;

    /**
     * The returned result from the script encoded into an object.
     * 
     * @var object 
     */
    protected $result;

    /**
     * Symphony Process object To run script. 
     * 
     * @var Process
     */
    protected $process;

    /**
     * Script name to be running.
     * 
     * @var string 
     */
    protected $scriptName;

    /**
     * BASE_SCRIPT_PATH + script name.
     * 
     * @var string 
     */
    protected $fullPath;

    /**
     * Command to be run in the Process class.
     * 
     * @var string 
     */
    protected $command;

    /**
     * Constructor.
     */
    public function __construct()
    { }

    /**
     * Init class variables.
     */
    public function init()
    {
        $this->scriptName = $this->getScriptName();
        $this->fullPath = self::BASE_SCRIPT_PATH . $this->scriptName;
        $this->command = "cd " . base_path() . " && " . self::NODE_PATH . " " . $this->fullPath;
    }

    /**
     * Reset class to be used again.
     */
    public function reset()
    {
        $this->associativeParams = [];
        $this->indexedParams = [];
        $this->rawResult = '';
        $this->result = null;
        $this->process = null;
        $this->scriptName = '';
        $this->fullPath = '';
        $this->command = '';
        $this->init();
    }

    /**
     * Get the name of the script to be run.
     * 
     * @returns string
     */
    public abstract function getScriptName();

    /**
     * Get the specific mapping for the command arguments.
     * 
     * @return array
     */
    public abstract function getParamsMapping();

    /**
     * Run the process with specific params.
     * 
     * @param array $associativeParams
     * @return Object
     * @throws ProcessFailedException
     */
    public function run($associativeParams)
    {
        $this->reset();

        $this->associativeParams = $associativeParams;

        // Handle associative to index array using cutom mapping.
        $this->setIndexedParamsFromAssociativeParams();

        // Add arguments to command 
        $this->setCommandWithIndexedParams();

        $this->process = new Process($this->command);

        $this->process->run();

        // executes after the command finishes
        if (!$this->isSuccessful()) {
            throw new ProcessFailedException($this->process);
        }

        $this->rawResult = $this->process->getOutput();

        $this->result = json_decode($this->rawResult);

        return $this->result;
    }

    /**
     * Transform array of associative params to an indexed one to be used in the command according to the mapping array.
     */
    protected function setIndexedParamsFromAssociativeParams()
    {
        $this->indexedParams = $this->convertAssociativeToIndexedArray($this->associativeParams, $this->getParamsMapping());
    }

    /**
     * Using an array of params, parse them in the command by array index order.
     * 
     * @param array $params
     * @return string
     */
    public function setCommandWithIndexedParams()
    {
        $this->command = $this->addArrayValuesToString($this->command, $this->indexedParams);
    }

    /**
     * Convert an associative array into an indexed one using a mapping array.
     * 
     * @param array $associativeArray
     * @param array $mapping
     * @return array
     */
    public function convertAssociativeToIndexedArray($associativeArray, $mapping)
    {
        $indexedArray = [];
        foreach ($mapping as $key => $index) {
            $indexedArray[$index] = $associativeArray[$key];
        }
        return $indexedArray;
    }

    /**
     * Concatenate values of an array to a string.
     * 
     * @param type $string
     * @param type $array
     * @return string
     */
    public function addArrayValuesToString($string, $array)
    {
        foreach ($array as $value) {
            $string .= ' ' . (is_bool($value) ? ($value ? "true" : "false") : '"' . $value . '"');
        }
        return $string;
    }

    /**
     * Indicates whether command was successful or not.
     * 
     * @return boolean
     */
    public function isSuccessful()
    {
        return $this->process->isSuccessful();
    }

    /**
     * result Getter.
     * 
     * @return Object
     */
    public function getResult()
    {
        return $this->result;
    }
}
