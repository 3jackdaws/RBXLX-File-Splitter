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
    public function __construct(RBXSubstrate $rbxs)
    {
        $this->RBXSubstrateInstance = $rbxs;
        
    }
    
    public function createPropertiesFile(SimpleXMLElement $xml, string $path = NULL)
    {
        if($path == NULL) $path = $this->RBXSubstrateInstance->currentDirectory();

        /*
         * Don't uncomment unless you want to ruin your run directory.
         * !-------- Needs testing ----------!
         */
        //RBXSubstrate::XML2File($xml, $path);
        print " - Creating Properties file at " . $path . PHP_EOL;

    }

    public function getChildren()
    {
        //TODO implement
    }
}