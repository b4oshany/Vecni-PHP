<?php

require_once '../../config/server.settings.php';
require_once '../../config/database.lib.php';


$settings_file = 'config/server.settings.php';
$database_file = 'config/database.lib.php';
require_once  (is_file('etc/'.$settings_file))? 'etc/'.$settings_file: '../../'.$settings_file;
require_once (is_file('etc/'.$database_file))? 'etc/'.$database_file:'../../'.$database_file;

class Message{
	private $body, $sender, $message_id, $user_id, $subject, $recipient_ids, $reader_id, $date, $db;
	
	public function __construct(){	
		$this->db = new Db_Functions();	
	} 	
	
	public function addMessage($sender, $recipients, $msg_body, $msg_subject = ''){
		if($this->db->query("insert into message(user_id, recipient_ids, body, subject) values('".$sender."', '".$recipients."', '".$msg_body."', '".$msg_subject."')")){
			return 1;
		}else{
			return 0;
		}
	}
	
	public function getMessages($user, $limit = 'all', $condition=''){
		$limit = ($limit != 'all')? 'limit '.$limit : '';
		if($result = $this->db->fetch_query_results('select distinct message.user_id, date_format(message.date, "%b %d %Y %h:%i %p") as arrive_date, date_format(message_read.date, "%b %d %Y %h:%i %p") as read_date, message.subject, message.body, message.recipient_ids, message.id as mid, message_read.message_id from message left join message_read on message.id = message_read.message_id where message.recipient_ids like "%'.$user.'%" '.$condition.' ORDER BY message.date DESC '.$limit.'')){
			return $result;
		}else{
			return 0;
		}		
	}		
	
	public function getSentMessages($user){
		if($result = $this->db->fetch_query_results('SELECT * FROM message WHERE user_id = "'.$user.'" order by date desc limit 10')){
			return $result;
		}else{
			return 0;
		}		
	}
	
	public function messageRead($mesage_id, $user){	
		if(!$this->db->recordExist('select * from message_read where message_id = '.$mesage_id.' and read_id = "'.$user.'"')){
			if($this->db->query('insert into message_read(message_id, read_id) select message.id, user.username from message, user where username = "'.$user.'" and message.id = '.$mesage_id)){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	
}


?>