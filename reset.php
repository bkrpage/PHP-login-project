<?php 
	session_start();
	
	$page_title = "Reset Password";
	
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
		if(isset($_SESSION['user_resetting_pass'])){
			$uid = "i7709331"; 
			$pwd = "phppass"; 
			$host = "127.0.0.1";
			$db = $uid;
			$conn = mysqli_connect($host, $uid, $pwd, $db);
			if (!$conn){
				die(mysqli_connect_error());
			}
			
			//to get users email ready from the $_GET variable
			$users_email = $_SESSION['user_resetting_pass'];
			
			$new_password = $_POST['new_password'];
			$new_hashed_pw = SHA1("$new_password");
			
			$new_password_confirm = $_POST['new_password_confirm'];
			
			$auth_errors = array();

?>

	<div class="form-box ">
		<h1> Reset Password </h1>
		
<?php
			if (!empty($_POST)){
				
				//password check
				if (!isset($_POST['new_password'])){
					$auth_error[] = "<p class='error'>Please enter a new password</p><style>.npw{border: 1px solid #CC0000;}</style>";
				} else {
					if(strlen($new_password) < 8){
						$auth_errors[] = "<p class='error'>Password is not long enough</p><style>.npw{border: 1px solid #CC0000;}</style>";
					} else {
						if (empty($new_password_confirm)){
						$auth_errors[] = "<p class='error'>Please confirm the new password</p><style>.npwc{border: 1px solid #CC0000;}</style>";
						} else {
							if ($new_password != $new_password_confirm){
								$auth_errors[] = "<p class='error'>The password do not match</p><style>.npw,.npwc{border: 1px solid #CC0000;}</style>";
							}	
						}
					}
				}
				
				if (empty($auth_errors)){
				
					$update_errors = array();
					
					$q_update_pw = "UPDATE users SET u_password = '$new_hashed_pw' WHERE u_email LIKE '$users_email'";
							
						if(!mysqli_query($conn, $q_update_pw)){
							$update_errors[] ="There was an error while changing the Password. for  Please try again.";
						}
					
					if (empty($update_errors)){
												
						header("Location: index.php?successfulReset=true");
						
					} else {
						foreach($update_errors as $error){
							echo "$error ";
						}
					}
					
				} else {
					foreach($auth_errors as $error){
						echo "$error ";
					}
				}
			}
		
?>		
	
		<form action="reset.php" method="POST">	
			<label for="new_password">New Password</label>
			<input type="password" name="new_password" class="npw">
			<label for="new_password_confirm">Confirm New Password</label>
			<input type="password" name="new_password_confirm" class="npwc">
			
			<input type="submit" value="Change Password" class="submit">
		</form>
<?php
		} else {
			header('Location: forgot_password.php');
		}
	}
	?>

</body>

</html>