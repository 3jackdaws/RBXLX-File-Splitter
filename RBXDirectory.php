<?php

/**
 * Created by PhpStorm.
 * User: ian
 * Date: 5/13/16
 * Time: 11:34 AM
 */

include_once("RBXSubstrate.php");
/*
 * This class handles parsing a .rbxlx file and generating a directory structure from it.  
 * 
 * Instantiation: Takes a RBXSubstrate instance in order to track the root path and current path during directory creation.
 * 
 */
class RBXDirectory
{
    protected $RBXSubstrateInstance;
    public function __construct($rootDirectory)
    {
        $this->RBXSubstrateInstance = new RBXSubstrate($rootDirectory);
    }
    
    public function createPropertiesFile(SimpleXMLElement $xml, $path = NULL)
    {
        if($path == NULL) $path = $this->RBXSubstrateInstance->currentDirectory();

        /*
         * Don't uncomment unless you want to ruin your run directory.
         * !-------- Needs testing ----------!
         */
        //RBXSubstrate::XML2File($xml, $path);
        print " - Creating Properties file at " . $path . PHP_EOL;

    }

    public function generateDirFromXml($xml)
    {
        if($xml instanceof SimpleXMLElement)
        {
            $this->parseXMLVisual($xml, 0);
        }
        else
        {
            throw new Exception("Not a SimpleXMLElement Object");
        }
    }

    protected function parseXMLVisual(SimpleXMLElement $xml, $level)
    {
        //TODO implement
        $spacing = "";
        for ($i = 0; $i < $level; $i++)
        {
            $spacing .= "   ";
        }
        switch ($xml->getName())
        {
            case "Properties":
            {
                $this->createPropertiesFile($xml);
                break;
            }
            case "Item":
            {
                print $spacing;
                $this->createItemDirectory($xml);
            }
            default:
            {
                echo $spacing . "Name: [" . $xml->getName() . "]";
                if(strlen($xml->attributes()) > 1) print "\n";

                echo $spacing . "Attributes: [" . $xml->attributes() . "]";
                foreach ($xml->children() as $toplevel)
                {
                    $this->parseXMLVisual($toplevel, $level + 1);
                }
                $this->RBXSubstrateInstance->moveUpLevel();
            }
        }
    }

    protected function createItemDirectory(SimpleXMLElement $xml)
    {
        $directory = $this->RBXSubstrateInstance->currentDirectory() . "/" . $this->getItemName($xml);
        //mkdir($this->RBXSubstrateInstance->currentDirectory() . "/" . $this->getItemName($xml));
        $this->RBXSubstrateInstance->testCreateDirectory($directory);
        //print "Creating Directory " . $this->RBXSubstrateInstance->currentDirectory() . "/" . $this->getItemName($xml) . "\n";
    }

    protected function getItemName(SimpleXMLElement $xml)
    {
        return $xml->Properties->string;
    }
}