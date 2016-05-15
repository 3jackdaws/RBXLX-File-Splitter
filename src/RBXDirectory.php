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
                print $spacing;
                $this->RBXSubstrateInstance->createPropertiesFile($xml);
                break;
            }
            case "Item":
            {
                print $spacing;
                $this->RBXSubstrateInstance->createItemDirectory($xml);
                $this->RBXSubstrateInstance->createItemAttributesXML($xml);
            }
            default:
            {
                //echo $spacing . "Name: [" . $xml->getName() . "]";
                if(strlen($xml->attributes()) > 1) print "\n";

                //echo $spacing . "Attributes: [" . $xml->attributes() . "]\n";
                //print $spacing . "Children: " . count($xml->children()) . "\n";
                foreach ($xml->children() as $toplevel)
                {
                    $this->parseXMLVisual($toplevel, $level + 1);
                }
                if(count($xml->children()) > 0)
                    $this->RBXSubstrateInstance->moveUpLevel();
            }
        }
    }

    
}