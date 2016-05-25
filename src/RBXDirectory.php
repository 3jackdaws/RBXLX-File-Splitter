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
                $child = $childStart;
                foreach ($xml->children() as $toplevel)
                {
                    $this->parseXMLVisual($toplevel, $child++, 0);
                }
                if(count($xml->children()) > 0)
                    $this->RBXSubstrateInstance->moveUpLevel();
            }
        }
    }

    
}