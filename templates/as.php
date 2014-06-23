<?php
  require_once 'etc/config/mysql_functions.lib.php';
	require_once 'etc/modules/prototype/String.php';
	require_once 'etc/modules/Image/controller/UH_Image.php';	
	//test data
	/*
	$_POST['id'] = 'b4_oshany@yahoo.com';
	$_POST['request'] = 'usr_upd';
	$_POST['udata'] = array('status' => 'incomplete');
	*/
	
	/*
	* Define the request codes to be used 
	* xap01 - for add property
	*/
	
	//Initiate the database connection and collect the data list for the search area
	
	$db = new Db_Functions();	
	//check if all post data is not null
if(!empty($_POST['udata']) && !empty($_POST['request']) && !empty($_POST['id'])){
		$udata = $_POST['udata'];  //get the data to update
		$id = $_POST['id']; //get the id which used to filter out and ensure that the data is been updated in the correct record
		$id_col = NULL; //initialize the type of id to be used, that is the column
		//print_r($udata);
		//echo $id;
		//define the property columns
		$prop_col = array(
			'district' => 'district_code',
			'propid'=>	'property_id',
			'proptitle' => 'title',
			'proptype' => 'type',
			'nbedr' => 'bedrooms',
			'nbathr' => 'bathrooms',
			'norooms' => 'no_rooms', 
			'oemail' => 'owner_email',
			'exhibitype' => 'exhibition',
			'nobeds' => 'no_beds',
			'propra' => 'ratings',
			'propstat' => 'state',
			'propsta' => 'status',
			'propadd' => 'address',
			'propco' => 'cost',
			'propsize' => 'property_size',
			'propacc' => 'accessories',
			'ad-rooms' => 'rooms',
			'propapp' => 'appliances',
			'paymethod' => 'mop',
		);
		
		$usr_col = array(
			'unum' => 'number',
			'utype'=>	'type',
			'urate' => 'rating',
			'ustat' => 'status',
			'ugen' => 'gender',
			'upic' => 'cphoto',
			'udob' => 'dob'
		);
		
		//define the sql statement base on the request type
		//if the request type is usr_upd [user update] then set the db table to users
		$first = true; //check if the udata array data is index 0
		$success = 1; //echo 1 for success by default
		switch($_POST['request']){
			case 'usr_dupd': 
				$id_col = 'email'; //set the id column to email				
				$sql = "update users set"; 
				$sql = Db_Functions::build_sql_update($usr_col, $id_col, $id, $sql, $udata);
				break;
			case 'prop_dupd':
				$area_code = substr($udata['propid'], 0, 5);
				$id_col = 'property_id'; //set the id column to email				
				$sql = "update property_".$area_code." set"; 
				$sql = Db_Functions::build_sql_update($prop_col, $id_col, $id, $sql, $udata);
				break;
			case 'prop_pupd':
				$success = 1;
				$sql = true;
				break;
			case 'get_parishes':
				if($id != 'all'){
					$id = '';	
				}else{
					$id = '*';	
				}
				$sql = 'select distinct '.$id.' from parishes';
				$success = 2;
				break;	
			case 'get_areas':
				if($id != 'all'){
					$id = '';
				}else{
					$id = '*';	
				}
				$sql = 'select distinct '.$id.' from areas where parish_code in ('.$udata.')';
				$success = 2;
				break;			
			case 'get_districts':
				if($id != 'all'){					
					if(stripos($id, ',') != false){
						$id = explode(',', $id);
						foreach($id as $val){
							$val = str_delimiter_join($val, 'districts', '.');	
						}
						$id = implode(',', $id);
					}else{
						$id = 'districts.'.$id;	
					}
				}else{
					$id = '*';	
				}
				$sql = 'select distinct '.$id.' from districts join areas on areas.area_code = districts.area_code where areas.name = \''.$udata.'\'';
				$success = 2;
				break;	
			case 'add_property':
				$sql = 'insert into property_temp '; //define the temporary db table to set the info in
				if($result = $db->ReturnArrayData($db->Query('select distinct district_code from districts where name = \''.$udata['district'].'\''))){
				$udata['propadd'] = $udata['lotnum'].' '.$udata['district'].' '.$udata['area'];
				$udata['district'] = $result[0]['district_code']; //create a temporary property id
				$udata['propsta'] = 'unplublished';
				$udata['propid'] = $result[0]['district_code'].'d'.$udata['lotnum'];

				//create an insert or update on duplicate sql string
				$sql = Db_Functions::build_sql_insert_or_update($prop_col, $sql, $udata);
					if($db->Query($sql)){
						$success = $udata['propid'];
						$sql = true;
					}else{
						$sql = false;
					}
				}
				break;
			case 'up_property':
				require_once 'etc/modules/property/add.php';
				$sql = true;
				$success = 3;
				if($area = $db->ReturnArrayData($db->Query("select distinct property_id from property_temp where address = '".$udata['addr']."'"))){
					$addr = $udata['addr'];
					$pid = $area[0]['property_id'];
					$code = substr($pid,0, 5); //get area code
					//echo $code;
					//echo '<br>'.$pid;
					//echo '<br>'.$addr;
					if($db->Query("insert into property_".$code." (property_id, owner_email, pub_date, title, address, cost, type, exhibition, bathrooms, bedrooms, status, mop, district_code, state) select property_id, owner_email, pub_date, title, address, cost, type, exhibition, bathrooms, bedrooms, status, mop, district_code, state from property_temp where property_id = '".$pid."'")){						
						if($db->Query('insert into property_owner (property_id, owner_email) select property_id, owner_email from property_temp where property_id = \''.$pid.'\'')){
							$db->Query('insert into property_ratings (property_id) select property_id from property_temp where property_id = \''.$pid.'\'');
							$db->Query('delete from property_temp where property_id = \''.$pid.'\'');
							$success = $pid;
							if(info_dir($pid)){
								$sql = true;
							}else{
								$sql = false;
							}
						}else{
							$sql = false;	
						}
					}else{
						$sql = false;	
					}
				}
				break;
						
		}
		if($sql != 'err' && $sql != false){
			if($result = $db->Query($sql)){
				if($success == 2){
					if($geta = $db->ReturnArrayData($result)){
						echo json_encode($geta);
					}else{
						echo 0; //fail
					}
				}else{
					echo $success; //success
				}
			}else{
				echo 0;//fail
			}		
		}else if($sql == true){
			echo $success;	
		}
	}else{
		echo 0;//fail
	}
?>