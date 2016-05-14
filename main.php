<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 5/13/16
 * Time: 4:05 PM
 */

include_once("RBXDirectory.php");
//var_dump($argv);
$filename = $argv[1];
echo $filename . "\n";
$xml = RBXSubstrate::File2XML($filename);
//var_dump($xml);
$parser = new RBXDirectory(dirname(__FILE__));
$parser->generateDirFromXml($xml);
//var_dump($parser);
