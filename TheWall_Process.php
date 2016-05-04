<?php
	
	session_start();

	require_once('TheWall_Connection.php');

	$_SESSION['error'] = array();

	if(empty($_POST['origin']) && $_POST['origin'] == 'register') {
		//checks to make sure registration is not blank
		error("Please take this seriously!");
		
	}

	if(empty($_POST['origin']) && $_POST['origin'] == 'login') {
		//checks to make sure login is not blank
		error("We need to know your an existing user!");
		
	}

	else if (isset($_POST['origin']) && $_POST['origin'] == 'register') {
		//do registration stuff
		register($_POST);

	}

	else if (isset($_POST['origin']) && $_POST['origin'] == 'login') {
		//do login stuff
		login($_POST);

	}

	else if(isset($_POST['action']) && $_POST['action'] == 'post_message') {
	
		post_message($_POST);
	
	}

	else if (isset($_POST['action_comment']) && $_POST['action_comment'] == 'comment') {

		comment($_POST);

	}

	else {
		
		session_destroy();
		header('Location: TheWall_Login.php');
		die();
	
	}

	function register($post_info) {
		//checks to see if first name is empty
		if (empty($post_info['first_name'])) {
			error("First name can't be empty.");
		}
		//checks to see if last name is empty
		if (empty($post_info['last_name'])) {
			error("Lasst name can't be empty.");
		}
		//checks to see if email is empty and it is in the correct email format
		if (empty($post_info['email']) || !filter_var($post_info['email'], FILTER_VALIDATE_EMAIL)) {
			error("Email can't be empty or in an invalid email format.");
		}
		//checks to see if the password is empty and has more than 5 characters
		if (empty($post_info['password']) || strlen($post_info['password']) < 6) {
			error("Password can't be empty and be at least 6 characters.");
		}
		//checks to see if confirm password and password match
		if (empty($post_info['confirm_password']) || $post_info['password'] != $post_info['confirm_password']) {
			error("Confirm password cannot be empty plus it must match password.");
		}
		if(count($_SESSION['error']) > 0) {
			header('Location: TheWall_Register.php');
			die();
		}
		else {
			$first_name = escape_this_string($post_info['first_name']);
			$last_name = escape_this_string($post_info['last_name']);
			$email = escape_this_string($post_info['email']);
			$password = md5($_POST['password']);
			$salt = bin2hex(openssl_random_pseudo_bytes(22));
 			$encrypted_password = md5($password . '' . $salt);
			$query = "INSERT INTO users (first_name, last_name, password, email, created_at, modified_at) VALUES ('{$first_name}', '{$last_name}', '{$encrypted_password}', '{$email}', NOW(), NOW())";
			run_mysql_query($query);
			$_SESSION['success_message'] = 'User was succesfully created!';
			header('Location: TheWall_Login.php');
			die();
			
		}
	
	}

	function login($post_info) {
		//checks to see if email is empty and it is in the correct email format
		if (empty($post_info['email']) || !filter_var($post_info['email'], FILTER_VALIDATE_EMAIL)) {
			error("Email can't be empty or in an invalid email format.");
		}
		//checks to see if password was left empty
		if (empty($post_info['password'])) {
			error("Password cannt be left empty.");
		}
		$email = escape_this_string($post_info['email']);
		$password = escape_this_string($post_info['password']);
		$user_query = "SELECT * FROM users WHERE users.email = '{$email}'";
		 $user = fetch_record($user_query);
		 if(!empty($user))
		 {
		  $encrypted_password = md5($password . '' . $user['salt']);
		  if($user['password'] == $encrypted_password)
		  {
		   //this means we have a successful login!
		  }
		  else
		  {
		   //invalid password!
		  } 
		 }
		 else
		 {
		  //invalid email!
		 }
		if(count($user) > 0) {
			$_SESSION['user_id'] = $user[0]['id'];
			$_SESSION['first_name'] = $user[0]['first_name'];
			$_SESSION['logged_in'] = TRUE;
			header('Location: TheWall_Index.php');
			die();
		}
		else {
			$_SESSION['error'][] = "Cannot find user in system.";
			header('Location: TheWall_Login.php');
			die();

		}
	}

	function post_message($post) {

		if(empty($post['message'])) {

			error("Sorry something went wrong. Your message was not posted!");
			header('Location: TheWall_Index.php');
			die();

		}

		else {

			$message = $post['message'];
			$session_id = $_SESSION['user_id'];
			$query  = "INSERT INTO messages (message, created_at, modified_at, user_id) VALUES ('{$message}', NOW(), NOW(), '{$session_id}')";
			// echo $query;
			run_mysql_query($query);
			$_SESSION['success_message'] = 'Your message was succesfully added.';
			header('Location: TheWall_Index.php');
			die();

		}
	}

	function comment($post) {

		if(empty($post['comment'])) {

			error("Sorry something went wrong. Your comment was not posted!");
			header('Location: TheWall_Index.php');
			die();

		}

		else {

			$comment = $post['comment'];
			$message_id = $post['message_id'];
			$session_id = $_SESSION['user_id'];
			$query = "INSERT INTO comments (comment, created_at, modified_at, message_id, user_id) VALUES ('{$comment}', NOW(), NOW(), '{$message_id}', '{$session_id}') ";
			// echo $query;
			run_mysql_query($query);
			$_SESSION['success_message'] = 'Your comment was successfully added..';
			header('Location: TheWall_Index.php');
			die();

		}

	}
	
	function error($string) {
		$_SESSION['error'][] = $string;
	}

?>