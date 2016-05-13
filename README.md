# RBXLX-File-Splitter
Converts .rbxlx files into a directory structure.

#Problem
Roblox Studio place files typically save the entire structure of a project in one binary file.  This is hard to track in source control. There is a setting that forces Studio to save the place file as an xml.  While this is better than a binary file, there is still much to be desired.  This project exists to convert .rbxlx xml files into a directory structure that resembles the internal structure of the project while in Roblox Studio so that project changes can be more easily tracked.  

#Usage
The tool is run once to convert an existing .rbxlx file to a directory structure.  This directory can now be committed to git and changes are more easily visible.  The tool can also be used to convert a project directory back into a rbxlx file.

##TODO
1.  Figure out how to do bullets in markdown.
2.  Write a recursive function to turn a simpleXML object into a specific directory structure.
3.  Write a recursive function that goes the other way.
4.  ???


