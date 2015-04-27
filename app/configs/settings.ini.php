<?php
use libs\vecni\Vecni as app;
use libs\scifile\Image;
use libs\scifile\File;

# name of the website
app::$BRAND_NAME = 'Vecni';
# company location
app::$company_address = 'Kingston, Jamaica';
# company contact number
app::$company_number = '(876) 8295969';
# company email address
app::$company_email = 'b4.oshany@gmail.com';
app::$company_name = 'Osoobe Inc.';

/***************** Database Connection ***********************/
# If you intend to use a SQL Server, please uncomment the two lines
# below to use the PDO Extension.
# NOTE: Create a file name settings.override.php in the same directory as this file.
# The settings.override.php file must consist of the following:
/* <?php
    use libs\vecni\Vecni as app;
    use libs\mysql\PDOConnector;

    PDOConnector::set_connection(db_user, db_pass, db_name);
    PDOConnector::connect();
    app::$mode = "debug";
 ?>
*/
# The db_name is 'ohomes'.
# By default the db_user and db_pass is 'root', '' respectively, else replace those
# variables with the corrent db_user and db_pass in the settings.override.php file.

# Enable error reporting.
# This is only applicable on development or local server.
# If there is a need to enable it on live or production server, the put the following
# code in the settings.override.php file.
# use libs/vecni/Vecni as app; // Place this after the openning php tags at the top.
# app:enable_error_reporting(true, true);
# This will override the default behaviour;
app::enable_error_reporting();

/***************** File Storage Location *********************/
# Get static folder.
$relative_static = app::getStaticFolder();
$absolute_static = app::getStaticFolder(false);

# Set default image folder for storage.
$default_image_folder = "dist/img";
File::$base = app::getRootFolder();
Image::register_location(Image::build_path($absolute_static, $default_image_folder),
                         Image::build_path($relative_static, $default_image_folder));

# Require the override file if exists
# TODO: Create a settings.override.php in app/configs/ folder.
# NOTE: This file is ignore by git. It will contain your personal settings for the project.
$settings_override = File::build_path(dirname(__FILE__), "settings.override.php");
if(is_file($settings_override)){
    require_once $settings_override;
}

?>
