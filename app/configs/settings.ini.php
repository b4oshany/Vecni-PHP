<?php
use libs\vecni\Vecni;
use libs\mysql\PDOConnector;

# name of the website
Vecni::$BRAND_NAME = 'Vecni';
# company location
Vecni::$company_address = 'Kingston, Jamaica';
# company contact number
Vecni::$company_number = '(876) 8295969';
# company email address
Vecni::$company_email = 'b4.oshany@gmail.com';
Vecni::$company_name = 'Osoobe';

/***************** Database Connection ***********************/
# If you intend to use a SQL Server, please uncomment the two lines
# below to use the PDO Extension.

# PDOConnector::set_connection($DB_USER, $DB_PASS, $DB_NAME);
# PDOConnector::connect();

# Enable error reporting
Vecni::enable_error_reporting();
?>
