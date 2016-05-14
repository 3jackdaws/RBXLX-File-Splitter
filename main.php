<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 5/13/16
 * Time: 4:05 PM
 */

include_once("src/RBXDirectory.php");
include_once("src/RBXSubstrateDummy.php");
//var_dump($argv);
$filename = $argv[1];
echo $filename . "\n";
$xml = RBXSubstrate::File2XML($filename);
//var_dump($xml);
$rbxDummy = new RBXSubstrateDummy(dirname(__FILE__));
$parser = new RBXDirectory(dirname(__FILE__));
$parser->generateDirFromXml($xml);
//var_dump($parser);
