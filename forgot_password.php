<?php 
	session_start();
	
	$page_title = "Forgot Password";
	
	require 'header.php';
	
	if (isset($_COOKIE['user'])){
		$_SESSION['loggedin'] = true;
		$_SESSION['userID'] = $_COOKIE['user'];
	}
	
	if (!empty($_SESSION['loggedin'])){
		if ($_SESSION['loggedin'] == true){
		
			header('Location: control_panel.php?alreadyLoggedIn=true');
			
		} else {
			echo "Error";
			session_destroy();
		}
	} else {
?>
	<div class="form-box forgot-password">
		<h1> Reset Password</h1>
	
		<form action="security_questions.php<?php echo "?email=".$_GET['email']?>" method="GET">
			
			<?php 
			if ($_GET['invalidemail']){
				echo "<p class='warning'>That email does not exist on our system.</p>";
			} ?>
			<label for="email">So we know who you are please enter your account email</label>
			<input type="email" name="email" >
			<input type="submit" value="Next >" class="submit">
		</form>
	</div>
<?php
	}
	?>

</body>

</html>