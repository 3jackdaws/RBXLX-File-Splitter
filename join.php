<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 5/13/16
 * Time: 4:05 PM
 */

include_once("splitter_src/RBXDirectory.php");
$filename = $argv[1];
if (strlen($filename) == 0)
{
    $filename = "Output";
}
$filename = $filename . ".rbxlx";

echo "Joining: " . $filename . "\n";
//$xml = RBXSubstrate::File2XML($filename);

$constructXML = simplexml_load_string(ROBLOX_TAG . "</roblox>");
$constructXML->addChild("External", "null");
$constructXML->addChild("External", "nil");

$parser = new RBXDirectory(dirname(__FILE__));
$parser->recursiveAddChildren($constructXML, getcwd() . "/datamodel", 0);

$xmlString = $constructXML->asXML();
$xmlString = RBXSubstrate::FilterPHPXMLStringRetardations($xmlString);

$path = getcwd() . "/" . $filename;

try
{
    $fileHandler = fopen($path, "w");
    fwrite($fileHandler, $xmlString, strlen($xmlString));
}
catch (Exception $e)
{
    echo $e->getMessage();
}