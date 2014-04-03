<?php
if(!empty($_POST['type'])){
	require_once 'message.lib.php';	
	$user_file = 'user/user.lib.php';
	//require_once  (is_file('etc/modules/'.$user_file))? 'etc/modules/'.$user_file: '../'.$user_file;
	require_once '../user/user.lib.php';
	$mail = new Message();
	$user = new User();
	$user->start_session();
	$user_id = $user->getUserBy('username');
	switch($_POST['type']){
		case 'add':
			addMessage();
			break;
		case 'get_ten':
			getMessages();
			break;
		case 'get_recent':
			$cond = ' and TIMESTAMPDIFF(second, message.date, now()) < 5';
			getMessages(false, $cond);
			break;
		case 'getMessage':
			$cond = (!empty($_POST['mid']))? ' and message.id = '.$_POST['mid']:'';
			getMessages(true, $cond);
			break;
		case 'getSent':
			getSentMessages(true);
			break;
		case 'sent':
			getSentMessages();
			break;
		default:
			echo 0;
			break;
	}
}
function addMessage(){
	global $mail, $user, $user_id;
	if(!empty($_POST['message']) && !empty($_POST['recipients'])){	
		$subject = (!empty($_POST['message']))? $_POST['message']:'';
		$recips = $_POST['recipients'];
		$recis = explode(';',$recips);
		foreach($recis as $recip){
			if($user->userExists($recip) != 1){
				$mail->addMessage('admin', $user_id, $recip.', this user does not exists' , 'Unable to deliever to '.$recip);	
			}
		}
		echo $mail->addMessage($user_id, $recips, $_POST['message'], $_POST['subject']);	
	}
}

function getSentMessages($details = false){
	global $mail, $user, $user_id;	
	$messages = $mail->getSentMessages($user_id);
	if($messages){
		$push_message = array();
		foreach($messages as $message){ 
			if(!$details){
			?>        
				<tr <?php echo ((empty($message['id']) )? 'class="new"':'').' id="'.$message['id'].'"'; ?> onClick="readMessage(this, 'getSent')">
					<td><?php echo $message['recipient_ids']; ?></td>
					<td><?php echo $message['subject'].'<span>  -   '.$message['body'].'</span>'; ?></td>
				</tr>
			<?php }else{
			?>
            <article id="mes_<?php echo $message['id']; ?>">
            	<h3 class="dfrom"><span>From:</span><label><?php echo $message['user_id']; ?></label></h3>
            	<h3 class="dto"><span>To:</span><label><?php echo $message['recipient_ids']; ?></label></h3>
            	<h3 class="dsubject"><span>Subject:</span><label><?php echo $message['subject']; ?></label></h3>
                <p class="dmessage"><?php echo $message['body']; ?></p>
            </article>
            <?php	}
		}
	}else{
		echo 0;
	}		
}

function getMessages($details = false, $condition = ''){
	global $mail, $user, $user_id;	
	$messages = $mail->getMessages($user_id, 10, $condition);
	if($messages){
		$push_message = array();
		foreach($messages as $message){ 
			if(!$details){
				array_push($push_message, $message['message_id']);
			?>        
				<tr <?php echo ((empty($message['message_id']) )? 'class="new"':'').' id="'.$message['mid'].'"'; ?> onClick="readMessage(this, 'getMessage')">
					<td><?php echo $message['user_id']; ?></td>
					<td><?php echo $message['subject'].'<span>  -   '.$message['body'].'</span>'; ?></td>
				</tr>
			<?php }else{
				$mail->messageRead($message['mid'], $user_id);
			?>
            <article id="mes_<?php echo $message['mid']; ?>">
            	<input type="button" value="close" class="close" onClick="closeNessage()" />
            	<h2 class="dsubject"><span>Subject:</span><label><?php echo $message['subject']; ?></label></h2>
            	<input type="button" value="reply" class="reply" onClick="reply('mes_<?php echo $message['mid']; ?>')" />
            	<h3 class="dfrom"><span>From:</span><label><?php echo $message['user_id']; ?></label></h3>
            	<h3 class="dto"><span>To:</span><label><?php echo $message['recipient_ids']; ?></label></h3>
                <h3 class="ddate"><span>Date</span><label><?php echo $message['arrive_date']; ?></label></h3>
                <p class="dmessage"><?php echo $message['body']; ?></p>
            </article>
            <?php	
            break;
            }
		}
	}else{
		echo 0;
	}		
}
?>