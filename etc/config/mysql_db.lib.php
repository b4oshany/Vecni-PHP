<?php
require_once 'database.lib.php';
$da = new Db_Functions();
$da->Query('CREATE TABLE IF NOT EXISTS areas(
				area_code int(12) NOT "NULL" COMMENT "area code of each district, city, town, parish",
				name varchar(100)  NOT "NULL" COMMENT "City, town or nearby university of real estate",
				abbr_name varchar(100)  NOT "NULL" COMMENT "abbreviated name for area_name",
				parish_code varchar(2)  NOT "NULL" COMMENT "parish code"
			)');

$da->Query('CREATE TABLE IF NOT EXISTS area_lookup (
  		id int(11) NOT "NULL" AUTO_INCREMENT,
  		area_name varchar(32)  NOT "NULL",
  		date date NOT "NULL",
  		PRIMARY KEY (id)
		)');
		
$da->Query('CREATE TABLE IF NOT EXISTS districts (
			district_code varchar(100)  NOT "NULL" COMMENT "This is used to identify the district within a town or city",
			name varchar(255)  NOT "NULL" COMMENT "Name of the district",
			abbr_name varchar(100)  NOT "NULL",
			area_code varchar(100)  NOT "NULL"
		)');
		
$da->Query('CREATE TABLE IF NOT EXISTS parishes (
			name varchar(100)  NOT "NULL" COMMENT "City, town or nearby university of real estate",
			abbr_name varchar(100)  NOT "NULL" COMMENT "abbreviated name for area_name",
			parish_code varchar(2)  NOT "NULL" COMMENT "parish code"	
		)');

$da->Query('CREATE TABLE IF NOT EXISTS property_ratings (
  no_shares int(11) NOT "NULL" COMMENT "number of shares",
  no_likes int(11) NOT "NULL" COMMENT "number of likes",
  no_views int(11) NOT "NULL" COMMENT "number of views",
  property_id varchar(11)  NOT "NULL" COMMENT "property_id"
)');

$da->Query('CREATE TABLE IF NOT EXISTS pro_users (
  company_name varchar(100)  NOT "NULL",
  company_email varchar(100)  NOT "NULL",
  user_email varchar(100)  NOT "NULL",
  company_address varchar(255)  NOT "NULL",
  company_number int(11) NOT "NULL",
  website varchar(100)  NOT "NULL"
)'); 

$da->Query('CREATE TABLE IF NOT EXISTS schools (
  area_code int(12) NOT "NULL" COMMENT "area code of each district, city, town, parish",
  name varchar(100)  NOT "NULL" COMMENT "name of the school",
  abbr_name varchar(100)  NOT "NULL" COMMENT "abbreviated name for area_name",
  parish_code varchar(2)  NOT "NULL" COMMENT "parish code",
  school_id varchar(100)  NOT "NULL"
)');

$da->Query('CREATE TABLE IF NOT EXISTS users (
  fname varchar(25)  NOT "NULL" COMMENT "first name",
  lname varchar(25)  NOT "NULL" COMMENT "last name",
  email varchar(100) primary key  NOT "NULL" COMMENT "email addresses",
  pass varchar(16)  NOT "NULL" COMMENT "password",
  number int(10) NOT "NULL" COMMENT "contact numbers",
  type varchar(10)  NOT "NULL" COMMENT "user types",
  ratings int(1) NOT "NULL" COMMENT "user ratings",
  reg_date timestamp NOT "NULL" DEFAULT CURRENT_TIMESTAMP COMMENT "registration date"
)');

$da->Query('CREATE TABLE IF NOT EXISTS users_history (
  email varchar(100) NOT "NULL" COMMENT "email addresses",
	shared varchar(255) NOT "NULL" COMMENT "property shared",
	liked varchar(255) NOT "NULL" COMMENT "property liked",
	watching varchar(255) NOT "NULL" COMMENT "property that the user is watching",
	favourite varchar(255) NOT "NULL" COMMENT "favourite properties",
	recommended varchar(255) NOT "NULL" COMMENT "properties you recommended",
	recommends varchar(255) NOT "NULL" COMMENT "properties that others recommended for you	"
)');

?>