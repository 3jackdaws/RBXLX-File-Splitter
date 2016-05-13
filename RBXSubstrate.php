<?php

/**
 * Created by PhpStorm.
 * User: ian
 * Date: 5/12/16
 * Time: 7:35 PM
 */
class RBXSubstrate
{
    protected $_currentDirectory;
    protected $_rootDirectory;
    /*
     * Parameters: (simpleXML object, path to save location
     */
    public static function XML2File(SimpleXMLElement $xmlObject, string $path)
    {
        $fileHandle = fopen($path, "w") or die("Could not open file " . $path . " for writing.");
        $xmlString = $xmlObject->asXML();
        fwrite($fileHandle, $xmlString, strlen($xmlString));
    }

    public static function File2XML(string $path)
    {
        $fileHandle = fopen($path, "r") or die("Could not open file " . $path . " for reading.");
        $xmlString = fread($fileHandle, filesize($path));
        return simplexml_load_string($xmlString);
    }

    public function __construct(string $rootDirectory)
    {
        $this->_rootDirectory = $rootDirectory;
    }

    public function createDirectory(string $path, bool $changeDir = true)
    {
        if(mkdir($path, 0777, true))
        {
            if($changeDir) $this->_currentDirectory = $path;
            return true;
        }
        return false;
    }

    public function currentDirectory()
    {
        return $this->_currentDirectory;
    }

    public function moveUpLevel()
    {
        if($this->_currentDirectory != null)
        {
            $this->_currentDirectory = dirname($this->_currentDirectory);
            return $this->_currentDirectory;
        }
        return false;
    }

    public function setCurrentDirectory(string $path)
    {
        if(file_exists($path))
        {
            $this->_currentDirectory = $path;
            return true;
        }
        return false;
    }

    public function getRootDirectory()
    {
        return $this->_rootDirectory;
    }
}