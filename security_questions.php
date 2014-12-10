<?php 
	session_start();
	
	$page_title = "Security Questions";
	
	require 'header.php';
	
	if (isset($_COOKIE['user'])){
		$_SESSION['loggedin'] = true;
		$_SESSION['userID'] = $_COOKIE['user'];
	}
	
	if (isset($_SESSION['loggedin'])){
		if ($_SESSION['loggedin'] == true){
			header('Location: control_panel.php?alreadyLoggedIn=true');
		} else {
			echo "Error";
			session_destroy();
		}
	} else {
		if(isset($_GET['email'])){
		
			$uid = "i7709331"; 
			$pwd = "phppass"; 
			$host = "127.0.0.1";
			$db = $uid;
			$conn = mysqli_connect($host, $uid, $pwd, $db);
			if (!$conn){
				die(mysqli_connect_error());
			}
			
			$user_answer = $_POST['answer'];
			
			//to get users email parsed and ready from the $_GET variable
			$users_email = strtolower($_GET['email']);
			$users_email = str_replace("%20"," ",$users_email);
			
			$q_email_check = "SELECT u_email FROM user_details WHERE u_email LIKE '$users_email'"; //Query to find duplicate emails
			$result_email = mysqli_query($conn, $q_email_check);
			$num_rows = mysqli_fetch_array($result_email);
			
			if (empty($num_rows)){
				header('Location: forgot_password.php?invalidemail=true');
			} else {
			
				$_SESSION['user_resetting_pass'] = $users_email;
				
				$qry_get_q_a = "SELECT u_sec_q,u_sec_a FROM user_details WHERE u_email LIKE '$users_email'";
				$result = mysqli_query($conn, $qry_get_q_a);
				
				$sec = mysqli_fetch_row($result);
				
				$question = $sec[0];
	?>
	<div class="form-box forgot-password">
		
		<h1> Security Question</h1>
		
		<p class="warning">This is the security question for <?php echo $users_email;?></p>
		
		<?php if($_GET['no_input']){ echo "<p class='error'>Please insert an answer</p><style>.a{border: 1px solid #CC0000;}</style>"; }?>
		<?php if($_GET['incorrect_answer']){ echo "<p class='error'>That answer is incorrect</p><style>.a{border: 1px solid #CC0000;}</style>";}?>
		
		<form action="password_reset.php" method="GET">	
			<label>Please answer your question to reset your password</label><BR>
			<label for="answer"> <?php echo $question ?></label>
			<input type="text" name="answer" class="a">
			<input type="submit" value="Reset password" class="submit">
		</form>
	</div>
	<?php	
			}
			
		} else {
			header('Location: /forgot_password.php');
		}
	}
	?>

</body>

</html>