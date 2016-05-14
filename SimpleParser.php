<?php
	include_once("RBXSubstrate.php");

	function createPropertiesFile(string $path, SimpleXMLElement $xml)
	{
		echo " - Creating Properties file at " . $path . "<br>";
	}

	function createItemDirectory(string $path, SimpleXMLElement $xml)
	{
		echo "<br>Creating Directory [" . $path . "/" . getItemName($xml) . "]<br>";
	}

	function getChildren(SimpleXMLElement $xml, int $level)
	{

		for ($i = 0; $i < $level*2; $i++)
		{
			echo "&nbsp;&nbsp;&nbsp;";
		}
		switch ($xml->getName())
		{
			case "Properties":
			{
				createPropertiesFile("Temp Path", $xml);
				break;
			}
			case "Item":
			{
				createItemDirectory("Temp Path", $xml);
			}
			default:
			{
				for ($i = 0; $i < $level*2; $i++)
				{
					echo "&nbsp;&nbsp;&nbsp;";
				}
				echo "Name: [" . $xml->getName() . "]";
				if(strlen($xml->attributes()) > 1) echo "<br>";

				for ($i = 0; $i < $level; $i++)
				{
					echo "&nbsp;&nbsp;&nbsp;";
				}

				echo "Attributes: [" . $xml->attributes() . "]";
				foreach ($xml->children() as $toplevel)
				{
					getChildren($toplevel, $level + 1);
				}
			}
		}




	}

	function getItemName(SimpleXMLElement $xml)
	{
		return $xml->Properties->string;
	}

	include_once("RBXSubstrate.php");
	$xml = RBXSubstrate::File2XML("Main.rbxlx");
	//var_dump($xml->Item);
	getChildren($xml, 0);
?>