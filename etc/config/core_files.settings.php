<?php

# Section 2.3.1 Core JavaScript
# The array below defines the core javascript files that are used throughout the system and the order it should be called
$core_js = array(
	'jquery' => "static/js/jquery.js", 
	'default' => "static/js/default.js", 
	'facebook'=> "etc/plugins/fb/fb.js"
	);

# Section 2.3.2 Core CSS 
# The array below defines the core css files that are used throughout the system and the order it should be called
$core_css = array(
	'normalize' => "static/css/normalize.css",
	'default' => "static/css/default.css"
	); 

# Section 2.3.3 Core Modules 
# The array below defines the core css files that are used throughout the system and the order it should be called
$core_modules = array(
	'date' => 'etc/modules/prototype/Date.php',
	'string' => 'etc/modules/prototype/String.php',
	'generator' => 'etc/modules/enc.gen.php'
);

?>