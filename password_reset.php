<?php
	session_start();
	if (isset($_SESSION['loggedin'])){
		if ($_SESSION['loggedin'] == true){
			header('Location: control_panel.php?alreadyLoggedIn=true');
		} else {
			echo "Error";
			session_destroy();
		}
	} else {
		if(isset($_SESSION['user_resetting_pass'])){
			$uid = "i7709331"; 
			$pwd = "phppass"; 
			$host = "127.0.0.1";
			$db = $uid;
			$conn = mysqli_connect($host, $uid, $pwd, $db);
			if (!$conn){
				die(mysqli_connect_error());
			}
			
			$user_answer = $_GET['answer'];
			
			$user_answer = strtolower($user_answer);
			
			//to get users email parsed and ready from the $_GET variable
			$users_email = $_SESSION['user_resetting_pass'];
			
			$qry_get_q_a = "SELECT u_sec_q,u_sec_a FROM user_details WHERE u_email LIKE '$users_email' AND u_sec_a LIKE '$user_answer'";
			$result = mysqli_query($conn, $qry_get_q_a);
			
			if (empty($user_answer)){
				$location = "/security_questions.php?email=$users_email&no_input=true";
				header('Location: '.$location);
			} else {
				if (!mysqli_fetch_row($result)){
				$location = "/security_questions.php?email=$users_email&incorrect_answer=true";
				header('Location: '.$location);
				} else {
					header('Location: reset.php');
				}
			}
				
			
		} else {
			header('Location: forgot_password.php');
		}
	}
	?>