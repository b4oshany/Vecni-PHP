<?php
/* Developer:			Oshane Bailey
 * Organization:		Osoobe Inc.
 * Description:			Variable definition based on host location
 */
require_once "__autoload.php";
use libs\database\Database;

# Section 2.1.1 Server Settings

# Section 2.1.1.1 Host Location
# By default the host is set to localhost or the IP captured by the server environment
# In order to point this website to a specific location, then change the host location below
$server_location = $_SERVER['HTTP_HOST'];

# Section 2.1.2 Database Settings


# Section 2.1.2.1 Mysql Library
# The mysql library consist of database error handling, manipulation and consifiguration
# It is advise to not to mess with this file unless you posses advance experience in PHP object orientated programming and database handling
# The file below is the library of all mysql functions that can be performed on the database


# Section 2.1.2.2 Mysql Settings
# The following lines below defines the database connection settings that will be used across the website

# By default the database location is set to localhost
# To change the default location, change the value of the db_location below
$db_location = 'localhost';

# By default the user name and password for the database is set to root and null
# To change this, change the value of the db_user or/and db_name to the appropriate credentials for the databse
$db_user = 'root';
$db_pass = 'oshany1991';

# Please set the default databse name in db_name variable below
$db_name = 'mybook';

# Section 2.1.2.3 Databse Connection
# In order for the website to successfully connect to a database all the required fields in Section 2.2.2 Mysql Settings must be valid
# The following lines below uses the values that were set in Section 2.1.2.2 to connect to the database by setting the static variable for the database connection class
Database::set_Connection($db_user, $db_pass, $db_name, $db_location, $db_name);

# Section 2.1.3 Sessions
# Start sessions at the begining of the script
session_start();

?>
