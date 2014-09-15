<?php
use libs\vecni\Vecni;
use libs\mongodb\Model;

# name of the website
Vecni::$BRAND_NAME = 'Vecni';
# company location
Vecni::$company_address = 'Kingston, Jamaica';
# company contact number
Vecni::$company_number = '(876) 8295969';
# company email address
Vecni::$company_email = 'b4.oshany@gmail.com';
Vecni::$company_name = 'Osoobe';

Model::$db = "vecni";
?>
