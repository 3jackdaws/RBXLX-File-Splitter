i<?php

/**
 * Created by PhpStorm.
 * User: ian
 * Date: 5/12/16
 * Time: 7:35 PM
 */

const ROBLOX_TAG = "<roblox xmlns:xmime=\"http://www.w3.org/2005/05/xmlmime\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"http://www.roblox.com/roblox.xsd\" version=\"4\">\n";//</roblox>";

class RBXSubstrate
{
    protected $_currentDirectory;
    protected $_rootDirectory;
    /*
     * Parameters: (simpleXML object, path to save location
     */
    public static function XML2File(SimpleXMLElement $xmlObject, $path)
    {
        $xmlString = $xmlObject->asXML();

        $pattern = "/<\?xml.+\\n/";
        $replacement = "";
        $xmlString = preg_replace($pattern, $replacement, $xmlString);

        // Fix stupid PHP XML stuff
        $ltPattern = "&lt;";
        $ltReplacement = "<";
        $gtPattern = "&gt;";
        $gtReplacement = ">";
        $nullPattern = "<" . "null/" . ">";
        $nullReplacement = "<" . "null" . "><" . "/null" . ">";
        $descPattern = "<" . "string name=\"Description\"/>";
        $descReplacement = "<" . "string name=\"Description\"></string>";

        $xmlString = str_replace($ltPattern, $ltReplacement, $xmlString);
        $xmlString = str_replace($gtPattern, $gtReplacement, $xmlString);
        $xmlString = str_replace($nullPattern, $nullReplacement, $xmlString);
        $xmlString = str_replace($descPattern, $descReplacement, $xmlString);

        $xmlString = ROBLOX_TAG . "\t<External>null</External>\n\t<External>nil</External>\n\t" . $xmlString;
        $xmlString = $xmlString . "\n</roblox>";

        if(file_exists($path))
        {
            $fileHandle = fopen($path, "r") or die("Could not open file " . $path . " for writing.");
            $checkString = fread($fileHandle, filesize($path));

            if($checkString == $xmlString)
            {
                return;
            }
        }

        $fileHandle = fopen($path, "w") or die("Could not open file " . $path . " for writing.");
        fwrite($fileHandle, $xmlString, strlen($xmlString));
    }

    public static function WriteOrderingFile($path, $ordering)
    {
        $saveString = "";
        foreach($ordering as $value)
        {
            $saveString = $saveString . $value . "\r\n";
        }

        if(file_exists($path))
        {
            $fileHandle = fopen($path, "r") or die("Could not open file " . $path . " for writing.");
            $checkString = fread($fileHandle, filesize($path));

            if($checkString == $saveString)
            {
                return;
            }
        }

        $fileHandle = fopen($path, "w") or die("Could not open file " . $path . " for writing.");
        fwrite($fileHandle, $saveString, strlen($saveString));
    }

    public static function File2XML($path)
    {
        $fileHandle = fopen($path, "r") or die("Could not open file " . $path . " for reading.");
        $xmlString = fread($fileHandle, filesize($path));
        return simplexml_load_string($xmlString);
    }

    public function __construct($rootDirectory)
    {
        $this->_rootDirectory = $rootDirectory . "/datamodel";
        $this->_currentDirectory = $this->_rootDirectory;
    }

    public function createDirectory($path, bool $changeDir = NULL)
    {
        if(!is_dir($path))
        {
            if(!mkdir($path, 0777, true))
            {
                print "Could not create directory: " . $path . "\n";
                return false;
            }
        }

        if($changeDir == NULL || $changeDir) $this->_currentDirectory = $path;
        return true;
    }

    public function currentDirectory()
    {
        return $this->_currentDirectory;
    }

    public function moveUpLevel()
    {
        if($this->_currentDirectory != null)
        {
            if($this->_currentDirectory != $this->_rootDirectory)
                $this->_currentDirectory = dirname($this->_currentDirectory);
            return $this->_currentDirectory;
        }
        return false;
    }

    public function setCurrentDirectory($path)
    {
        if(file_exists($path))
        {
            $this->_currentDirectory = $path;
            return true;
        }
        return false;
    }

    public function getRootDirectory()
    {
        return $this->_rootDirectory;
    }

    public function createPropertiesFile(SimpleXMLElement $xml, $path = NULL)
    {
        if($xml != NULL)
        {
            if($path == NULL) $path = $this->currentDirectory();
            $properties = simplexml_load_string("<roblox xmlns:xmime=\"http://www.w3.org/2005/05/xmlmime\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"http://www.roblox.com/roblox.xsd\" version=\"4\">" . $xml->asXML() . "</roblox>");
            RBXSubstrate::XML2File($properties, $path . "/Properties.xml");
            //print " - Creating Properties file at " . $path . PHP_EOL;
        }


    }

    public function XMLItemToDirectoryName(SimpleXMLElement $xml)
    {
        $refID = substr($xml["referent"], -6);
        $itemName = $this->getItemName($xml);
        $directoryName = "";
        if (strlen($itemName) > 16)
        {
            $directoryName = substr($itemName, 0, 7) . ".." . substr($itemName, -7);
        }
        else
        {
            $directoryName = $itemName;
        }

        $directoryName = $directoryName . " " . $refID;

        return $directoryName;
    }

    public function createItemDirectory(SimpleXMLElement $xml, $childNum)
    {
        /*
        while (strlen($childNum) < 5) {
            $childNum = "0" . $childNum;
        }
        */

        $directoryName = $this->XMLItemToDirectoryName($xml);

        $directory = $this->currentDirectory() . "/" . $directoryName;
        $this->createDirectory($directory);
    }

    public function getItemName(SimpleXMLElement $xml)
    {
        $strAry = $xml->Properties->string;
        $arr = $xml->Properties;

        foreach ($arr->children() as $child)
        {
            if($child->getName() == "string")
                if($child["name"] == "Name"){
                    return $child;
                }

        }
        return $strAry;
    }

    public function createItemAttributesXML(SimpleXMLElement $xml, $path = NULL)
    {
        if($xml != NULL)
        {
            if($path == NULL) $path = $this->currentDirectory();
            $newXML = "";
            $attributes = simplexml_load_string("<roblox xmlns:xmime=\"http://www.w3.org/2005/05/xmlmime\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"http://www.roblox.com/roblox.xsd\" version=\"4\"></roblox>");

            foreach ($xml->attributes() as $k => $v)
            {
                $attributes->addChild($k, $v);
            }
            
            RBXSubstrate::XML2File($attributes, $path . "/Attributes.xml");

        }
    }

    public function saveXMLWithoutChildren(SimpleXMLElement $xml, $path = NULL)
    {
        if($xml != NULL)
        {
            if($path == NULL) $path = $this->currentDirectory();

            /*
            $saveXML = simplexml_load_string(ROBLOX_TAG);
            $saveXML->addChild("External", "null");
            $saveXML->addChild("External", "nil");
            $itemXML = $saveXML->addChild("Item", $xml->Properties->asXML());

            foreach($xml->attributes() as $k => $v)
            {
                $itemXML->addAttribute($k, $v);
            }
            */

            $saveXML = simplexml_load_string($xml->asXML());
            if(isset($saveXML->Item))
            {
                unset($saveXML->Item);
            }

            RBXSubstrate::XML2File($saveXML, $path . "/" . "Properties" . ".rbxmx");
        }
    }
}