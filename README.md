# RBXLX-File-Splitter
Converts .rbxlx files into a directory structure.

#Problem
Roblox Studio place files typically save the entire structure of a project in one binary file.  This is hard to track in source control. There is a setting that forces Studio to save the place file as an xml.  While this is better than a binary file, there is still much to be desired.  This project exists to convert .rbxlx xml files into a directory structure that resembles the internal structure of the project while in Roblox Studio so that project changes can be more easily tracked.  

##Usage
The tool is run once to convert an existing .rbxlx file to a directory structure.  This directory can now be committed to git and changes are more easily visible.  The tool can also be used to convert a project directory back into a rbxlx file.

##Why PHP?
PHP is great for websites because it can be embedded into the page markup.  Why, then, is it being used like a general purpose scripting language? Actually, this started out as a web thing.  When the idea changed to this xml converter, the language did not.  Normally, a project like this would be done in Python or Java or basically anything that isn't a web scripting language, but we wanted to use something new and our choices were either PHP or a functional programming language. 

##TODO
* Fix .xml spacing from split
* Actually remove deleted directories in /datamodel when updating the split



