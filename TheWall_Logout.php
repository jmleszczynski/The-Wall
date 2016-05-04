<?php

	session_start();
	session_destroy();
	header("Location: TheWall_Login.php");

?>