<?php

/**
 * Created by PhpStorm.
 * User: ian
 * Date: 5/14/16
 * Time: 12:46 PM
 */
class RBXSubstrateDummy extends RBXSubstrate
{
    public function __construct($rootDirectory)
    {
        parent::__construct($rootDirectory);
    }

    public function createPropertiesFile(SimpleXMLElement $xml, $path = NULL)
    {
        if($path == NULL) $path = $this->currentDirectory();

        print "Properties.xml\n";
    }

    public function createDirectory($path, bool $changeDir = NULL)
    {
        if(print "[Directory] - " . $path)
        {
            if($changeDir == NULL || $changeDir) $this->_currentDirectory = $path;
            return true;
        }
        return false;
    }

    public function createItemDirectory(SimpleXMLElement $xml, $child)
    {
        $attributesObject = new SimpleXMLElement("<attributes></attributes>");
        foreach ($xml->attributes() as $k => $v)
        {
            $attributesObject = $attributesObject->addChild($k, $v);
        }
        print $attributesObject->asXML();
        //print "Attributes: " . var_dump($xml->attributes()). "\n";
        $directory = $this->currentDirectory() . "/" . $this->getItemName($xml);
        //mkdir($this->RBXSubstrateInstance->currentDirectory() . "/" . $this->getItemName($xml));
        $this->createDirectory($directory);
        //print "Creating Directory " . $this->RBXSubstrateInstance->currentDirectory() . "/" . $this->getItemName($xml) . "\n";
    }
}