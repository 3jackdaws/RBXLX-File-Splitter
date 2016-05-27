<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 5/13/16
 * Time: 4:05 PM
 */

include_once("splitter_src/RBXDirectory.php");
$filename = $argv[1];
echo "Splitting: " . $filename . "\n";
$xml = RBXSubstrate::File2XML($filename);
$parser = new RBXDirectory(dirname(__FILE__));
$parser->generateDirFromXml($xml);

$parser->deleteUnusedDirectories(getcwd() . "/datamodel");