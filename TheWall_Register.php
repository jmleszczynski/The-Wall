<?php
	
	session_start();

	require_once('TheWall_Connection.php');

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
				<p>Welcome Guest</p>

			</div>

			<div class="register">

				<?php

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
				
				<!-- <h3>Please complete all the requested information and hit the register button!</h3>
 -->
				<form action="TheWall_Process.php" method="post" class="form_register">
		
					<input type="hidden" name="origin" value="register" />
					First Name: <input type="text" name="first_name" placeholder="First Name" /><br>
					Last Name: <input type="text" name="last_name" placeholder="Last Name" /><br>
					Email: <input type="email" name="email" placeholder="Email" /><br>
					Password: <input type="password" name="password" placeholder="password" /><br>
					Confirm Password: <input type="password" name="confirm_password" placeholder="Confirm Password" /><br>
					<input type="submit" value="register" />

				</form>

			</div>


		</div>

	</body>

</html>