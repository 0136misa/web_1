<?php

function base_url(){
	return 'http://localhost:8080/library_management_system/library_management_system/';
}

function is_admin_login(){
	if(isset($_SESSION['admin_id']))
	{
		return true;
	}
	return false;
}
function is_user_login(){
	if(isset($_SESSION['user_id']))
	{
		return true;
	}
	return false;
}

function convert_data($string, $action = 'encrypt')
{
	$encrypt_method = "AES-256-CBC";
	$secret_key = 'AA74CDCC2BBRT935136HH7B63C27'; 
	$secret_iv = '5fgf5HJ5g27'; 
	$key = hash('sha256', $secret_key);
	$iv = substr(hash('sha256', $secret_iv), 0, 16); 
	if ($action == 'encrypt'){
		$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
	    $output = base64_encode($output);
	} 
	else if ($action == 'decrypt') {
		$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	}
	return $output;
}

function fill_author($connect){
	$query = "SELECT author_name FROM lms_author ORDER BY author_name ASC";
	$result = $connect->query($query);
	$output = '<option value="">Select Author</option>';
	foreach($result as $row){
		$output .= '<option value="'.$row["author_name"].'">'.$row["author_name"].'</option>';
	}
	return $output;
}
function fill_category($connect){
	$query = "SELECT category_name FROM lms_category ORDER BY category_name ASC";
	$result = $connect->query($query);
	$output = '<option value="">Select Category</option>';
	foreach($result as $row){
		$output .= '<option value="'.$row["category_name"].'">'.$row["category_name"].'</option>';
	}
	return $output;
}
?>