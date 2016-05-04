<?php
	
	session_start();

	require_once('TheWall_Connection.php');


	date_default_timezone_set('America/Los_Angeles');

	if (isset($_SESSION['error'])) {
		foreach($_SESSION['error'] as $error) {
			echo "<div class='message'>" . $error . "</div>";
		}
		unset($_SESSION['error']);
	}
	if (isset($_SESSION['success_message'])) {
		echo "<div class='message'>" . $_SESSION['success_message'] . "</div>";
		unset($_SESSION['success_message']);
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<html lang="en">
		<meta charset="utf-8">
		<meta name="description" content="JonMichael's The Wall Assignment for Coding Dojo">
		<meta name="keywords" content="JonMichael, Coding Dojo, The Wall, PHP, MySQL, database, login, messageboard">
		<title></title>
		<link rel="stylesheet" type="text/css" href="TheWall.css">
		<!-- <link rel="stylesheet" type="text/css" href="normalize.css"> -->
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
		<script type="text/javascript">
		</script>
	</head>

	<body>

		<div id="container">
			
			<div class="header">
				
				<h2><a href="TheWall_Login.php"> CodingDojo Wall</a></h2>
				<p>Welcome <?php echo "{$_SESSION['first_name']}" ?> </p>
				<SPAN class="logout"><a href="TheWall_Logout.php">Log off</a></SPAN>

			</div>

			<div class="index_message">
				
				<form action="TheWall_Process.php" method="post">
					
					<input type="hidden" name="action" value="post_message" />
					<textarea type="text" name="message" cols="200" rows="10"></textarea>
					<button type="submit">Post a message</button>

				</form>

			</div>

			<div class="message_log">
				
			<?php 
				$query2 = "SELECT messages.id, messages.message, messages.user_id, users.first_name, users.last_name, messages.created_at
						   FROM messages LEFT JOIN users ON users.id=messages.user_id
						   ORDER BY messages.created_at DESC";

				$messages = fetch_all($query2);

				// echo count($messages);
				// exit("error");

				foreach($messages as $message) {

					$posted_time = strtotime($message['created_at'], $now=time());
					$message_date = date('m/d/y', $posted_time);
					$message_time = date('g:i a', strtotime($message['created_at'], $now=time()));

					echo "<div class='messagePost'><h3>  {$message['first_name']} {$message['last_name']} -</h3> <p>     Posted on: </p> <h6>    {$message_date} </h6> <p>  at: </p> <h6> {$message_time} </h6></div> <br>
					<p class='message_format'> {$message['message']}</p> <br>";
					$query_comment = "SELECT comments.comment, comments.message_id, users.first_name, users.last_name, comments.created_at
									  FROM comments LEFT JOIN users ON users.id=comments.user_id
									  -- LEFT JOIN messages ON messages.id=comments.message_id
									  WHERE comments.message_id = '{$message['id']}'";

					// echo $query_comment;
					// exit("error");
					
					$comments = fetch_all($query_comment);

					foreach ($comments as $comment) {

						if ($comment['message_id'] == $message['id']) {

						$comment_date = date('m/d/y', strtotime($comment['created_at'], $now=time()));
						
						echo "<div class='comment'> <br> <h5>  {$comment['first_name']} {$comment['last_name']} - </h5> <p>on: </p><h6> {$comment_date} </h6></div> <p class='comment_format'> {$comment['comment']}</p><br>";

						}
					}

					echo "<div><form class='comment_form' action='TheWall_Process.php' method='post'>
							<input type='hidden' name='action_comment' value='comment'>
							<input type='hidden' name='message_id' value='{$message['id']}'>
							<textarea class='comment_box' name='comment' placeholder='Post your comment here'></textarea>
							<input class='post_comment' type='submit' value='Comment'>
					  	 </form></div>";

				}
			?>

			</div>			

		</div>

	</body>

</html>