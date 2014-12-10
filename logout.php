<?php 
	session_start();
	
	if (isset($_COOKIE['user'])){
		$_SESSION['loggedin'] = true;
		$_SESSION['userID'] = $_COOKIE['user'];
	}
	
	if ($_SESSION['loggedin'] == true){
		$_SESSION['loggedin'] = false;
		unset($_SESSION['loggedin']);
		
		$cookie_name = "user";
		$cookie_value = $_SESSION['userID'];
		$cookie_time = time() -1; //setting cookie expiry time for a week
		setcookie($cookie_name, $cookie_value, $cookie_time);
		
		session_destroy();
		
		header('Location: /index.php?loggedout=true');
		
	} else {
		echo "You are already logged out";
		
		header('Location: /index.php?alreadyloggedout=true');
	}
?>