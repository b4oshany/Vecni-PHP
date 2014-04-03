<?php
/*
*	Section 1. Comapny Information
*	The following lines below consisit of the company detailed information which is used across this website
*/

# name of the website
$website_name = 'Fero Inc.';
# company location
$company_address = 'Kingston, Jamaica';
# company contact number
$company_number = '(876) 8295969';
#company email address
$company_email = 'info@feroinc.com';

/*
* Section 2.0 Host Configuration
* The following lines below defines the host specification and configuration that should be adjust based on the server which this site is being hosted on	
*/

# To setup a server settings, you can make a copy of the default.server.settings.php file located in the same directory as this (etc/config/). Afterwards, you need to uncomment the server settings file in Section 2.1 and place the location of the file in the required filed

# Section 2.1 Server Setting
# change the file location for server setting file which you like to use for the current hosting configuration
require_once 'server.settings.php';

# Section 2.3 File System
# This defines the overall structure of the website, that is, it defines the location of the modules, themes, pictures, data, css and javascript files and folders
# The file below defines the location core folders and files withing the system
require_once 'core_files.settings.php';


# Section X Extraction
# This section defines functions that will extract the core files needed throughout the website
function output_files($cores){
	foreach($cores as $file){
		$type = substr($file, strrpos($file, '.')+1);
		switch($type){
		case 'js':
			echo '<script src="'.$file.'"></script>';
			break;
		case 'php':
			require_once $file;
			break;
		case 'css':
			echo '<link type="text/css" rel="stylesheet" href="'.$file.'" />';
			break;			
		}
	}
}
?>