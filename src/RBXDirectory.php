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
    public function __construct($rootDirectory, RBXSubstrate $rbx_instance = NULL)
    {
        if($rbx_instance instanceof RBXSubstrate)
            $this->RBXSubstrateInstance = $rbx_instance;
        else
            $this->RBXSubstrateInstance = new RBXSubstrate($rootDirectory);
    }
    
    

    public function generateDirFromXml($xml)
    {
        if($xml instanceof SimpleXMLElement)
        {
            $this->parseXMLVisual($xml, 1, -1);
        }
        else
        {
            throw new Exception("Not a SimpleXMLElement Object");
        }
    }

    protected function parseXMLVisual(SimpleXMLElement $xml, $child, $childStart)
    {
        //TODO implement
        switch ($xml->getName())
        {
            case "Properties":
            {
                //$this->RBXSubstrateInstance->createPropertiesFile($xml);
                break;
            }
            case "Item":
            {
                $this->RBXSubstrateInstance->createItemDirectory($xml, $child);
                //$this->RBXSubstrateInstance->createItemAttributesXML($xml);
                $this->RBXSubstrateInstance->saveXMLWithoutChildren($xml);
            }
            default:
            {
                $orderAry = [];
                $child = $childStart;
                foreach ($xml->children() as $toplevel)
                {
                    if($toplevel->getName() == "Item")
                    {
                        $orderAry[] = $this->RBXSubstrateInstance->XMLItemToDirectoryName($toplevel);
                    }

                    $this->parseXMLVisual($toplevel, $child++, 0);
                }
                if(count($orderAry) > 0)
                {
                    $this->RBXSubstrateInstance->WriteOrderingFile(
                        $this->RBXSubstrateInstance->currentDirectory() . "/Ordering.txt", $orderAry);
                }

                if(count($xml->children()) > 0)
                    $this->RBXSubstrateInstance->moveUpLevel();


            }
        }
    }

    public function recursiveAddChildren(SimpleXMLElement $xml, $directory, $level)
    {
        $orderFile = "Ordering.txt";
        if(file_exists($directory . "/" . $orderFile))
        {
            $lines = file($directory . "/" . $orderFile) or die("Could not open order file in directory: " . $directory);
            foreach($lines as $line)
            {
                $childDir = substr($line, 0, (strlen($line) - 2));

                /*
                for ($i = 0; $i < $level; $i++)
                {
                    echo "    ";
                }
                echo "[" . $childDir . "]\n";
                */

                $childPath = $directory . "/" . $childDir;

                if (is_dir($childPath))
                {
                    if (!file_exists($childPath . "/obj.rbxmx"))
                        die($directory . "/" . $childDir . " is missing it's obj.rbxmx file!!!");

                    $fileHandle = fopen($childPath . "/obj.rbxmx", "r");
                    $xmlString = fread($fileHandle, filesize($childPath . "/obj.rbxmx"));

                    $childXML = simplexml_load_string($xmlString);
                    $childXML = $childXML->Item;

                    $this->recursiveAddChildren($childXML, $childPath, $level + 1);

                    $childString = $childXML->asXML();

                    $frontPos = strpos($childString, ">");
                    $addString = substr($childString, $frontPos + 1, strlen($childString) - ($frontPos + 1) - 7);

                    $added = $xml->addChild("Item", $addString);
                    foreach($childXML->attributes() as $k => $v)
                    {
                        $added->addAttribute($k, $v);
                    }
                }
            }
        }
    }
    
}