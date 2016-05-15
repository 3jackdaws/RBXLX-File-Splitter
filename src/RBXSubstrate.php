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
    public static function XML2File(SimpleXMLElement $xmlObject, $path)
    {
        $fileHandle = fopen($path, "w") or die("Could not open file " . $path . " for writing.");
        $xmlString = $xmlObject->asXML();
        $pattern = "/<\?xml.+\\n/";
        $replacement = "";
        $new = preg_replace($pattern, $replacement, $xmlString);
        fwrite($fileHandle, $new, strlen($new));
    }

    public static function File2XML($path)
    {
        $fileHandle = fopen($path, "r") or die("Could not open file " . $path . " for reading.");
        $xmlString = fread($fileHandle, filesize($path));
        return simplexml_load_string($xmlString);
    }

    public function __construct($rootDirectory)
    {
        $this->_rootDirectory = $rootDirectory . "/datamodel";
        $this->_currentDirectory = $this->_rootDirectory;
    }

    public function createDirectory($path, bool $changeDir = NULL)
    {
        if(mkdir($path, 0777, true))
        {
            if($changeDir == NULL || $changeDir) $this->_currentDirectory = $path;
            return true;
        }
        else
        {
            print "error at: " . $path. "\n";
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
            if($this->_currentDirectory != $this->_rootDirectory)
                $this->_currentDirectory = dirname($this->_currentDirectory);
            return $this->_currentDirectory;
        }
        return false;
    }

    public function setCurrentDirectory($path)
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

    public function createPropertiesFile(SimpleXMLElement $xml, $path = NULL)
    {
        if($xml != NULL)
        {
            if($path == NULL) $path = $this->currentDirectory();
            $properties = simplexml_load_string("<roblox xmlns:xmime=\"http://www.w3.org/2005/05/xmlmime\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"http://www.roblox.com/roblox.xsd\" version=\"4\">" . $xml->asXML() . "</roblox>");
            RBXSubstrate::XML2File($properties, $path . "/Properties.xml");
            //print " - Creating Properties file at " . $path . PHP_EOL;
        }


    }

    public function createItemDirectory(SimpleXMLElement $xml)
    {
        $directory = $this->currentDirectory() . "/" . $this->getItemName($xml);
        //mkdir($this->RBXSubstrateInstance->currentDirectory() . "/" . $this->getItemName($xml));
        $this->createDirectory($directory);
        //print "Creating Directory " . $this->RBXSubstrateInstance->currentDirectory() . "/" . $this->getItemName($xml) . "\n";
    }

    public function getItemName(SimpleXMLElement $xml)
    {
        return $xml->Properties->string;
    }

    public function createItemAttributesXML(SimpleXMLElement $xml, $path = NULL)
    {
        if($xml != NULL)
        {
            if($path == NULL) $path = $this->currentDirectory();
            $newXML = "";
            $attributes = simplexml_load_string("<roblox xmlns:xmime=\"http://www.w3.org/2005/05/xmlmime\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"http://www.roblox.com/roblox.xsd\" version=\"4\"></roblox>");
            foreach ($xml->attributes() as $k => $v)
            {
                $attributes->addChild($k, $v);
            }
            
            RBXSubstrate::XML2File($attributes, $path . "/Attributes.xml");

        }
    }
}