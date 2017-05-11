<?php 


function searchByFieldArray($array, $field, $value) {
	for($i = 0; $i < count($array); $i++){
		if($array[$i][$field] == $value) {
			return $array[$i];
		}
	}
	return false;
}

function searchByFieldMArray($array, $field, $value) {
	$output = array();
	for($i = 0; $i < count($array); $i++){
		if($array[$i][$field] == $value) {
			array_push($output, $array[$i]);
		}
	}
	return $output;
}

function sendMail($robot_player, $robot_email, $to_player, $to_email, $subject, $text, $is_html = false, $cc = '', $bcc = '', $attachs = array()) {
	$mailer = new PHPmailer();
	$mailer->From = $robot_email;
	$mailer->FromName = $robot_player;
	$mailer->AddAddress($to_email, $to_player);
	//$mailer->AddReplyTo($robot_email); 
	$mailer->Subject = $subject;
	if($is_html) {
		$mailer->IsHTML(true);
	}
	if(strlen($cc) > 0) {
		$ccs = explode(',', $cc);
		for($i = 0; $i < count($ccs); $i++) {
			$mailer->AddCC(trim($ccs[$i]));
		}
	}
	if(strlen($bcc) > 0) {
		$bccs = explode(',', $bcc);
		for($i = 0; $i < count($bccs); $i++) {
			$mailer->AddBCC(trim($bccs[$i]));
		}
	}
	$mailer->Body = $text;
	for($i = 0; $i < count($attachs); $i++) {
		if(file_exists($attachs[$i])) {
			$mailer->AddAttachment($attachs[$i]);
		}
	}
	if(!$mailer->Send()) {
		$resp['success'] = false;
		$resp['msg'] = $mailer->ErrorInfo; 
	}
	else {
		$resp['success'] = true;
	}
	unset($mailer);
	return $resp;
}

?>