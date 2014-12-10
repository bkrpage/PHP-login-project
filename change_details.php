<?php 
	session_start();
	
	$page_title = "Change your details"; // Used in header.php
	
	require 'header.php';
	
	if (isset($_COOKIE['user'])){
		$_SESSION['loggedin'] = true;
		$_SESSION['userID'] = $_COOKIE['user'];
	}
	
	if (isset($_SESSION['loggedin'])){
		if ($_SESSION['loggedin'] == true){
	
			$session_email = $_SESSION['userID'];
			
			//Information from forms
			$firstname = $_POST['first_name'];
			$surname = $_POST['surname'];
			$phone = $_POST['number'];
			$addr1 = $_POST['address1'];
			$addr2 = $_POST['address2'];
			$addr3 = $_POST['address3'];
			$postcode = $_POST['post_code'];
			$country = $_POST['country'];
			$sec_q = $_POST['sec_q'];
			$sec_a = $_POST['sec_a'];
			
			$email = $_POST['email'];
			
			$email_confirmation = $_POST['confirm_email']; 
			
			$password = $_POST['password'];
			$hashed_pw = SHA1("$password"); //Hashed password for entry into DB
			
			$email = strtolower($email);
			$email_confirmation = strtolower($email_confirmation);
			
			$sec_a = strtolower($sec_a);
			
			$uid = "i7709331"; 
			$pwd = "phppass"; 
			$host = "127.0.0.1";
			$db = $uid;
			$conn = mysqli_connect($host, $uid, $pwd, $db);
			if (!$conn){
				die(mysqli_connect_error());
			}
			
			$auth_errors = array();
			
			$qry_user_details = "SELECT * FROM user_details WHERE u_email LIKE '$session_email';";
			$result_user_details = mysqli_query($conn,$qry_user_details);
			
			$user_details = mysqli_fetch_row($result_user_details);

			
		
?>
	<div class="form-box change-details ">
	
		<h1>Change details</h1>
		<p>Changing details for <?php echo" $user_details[1]";?>. Not you? <a href="logout.php">Logout</a><p>
		<p>Leave a field empty if you do not wish to change it</p>
<?php
		if (!empty($_POST)){
			//check for existing emails
			if (isset($email)){
				$q_email_check = "SELECT u_email FROM users WHERE u_email LIKE '$email'"; //Query to find duplicate emails
				$result_email = mysqli_query($conn, $q_email_check);
				
				if (mysqli_num_rows($result_email) >= 1){
					$auth_errors[] = "<p class='error'>Email is taken</p><style>.e{border: 1px solid #CC0000;}</style>";
				} else {
					//check if email and email confirm are the same
					if ($email != $email_confirmation){
						$auth_errors[] = "<p class='error'>Emails do not match</p><style>.e,.ec{border: 1px solid #CC0000;}</style>";
					}
				}
			}
				
			//check if Phone number is valid length.
			if (!empty($phone)){
				if (!preg_match("/^[0-9]+$/",$phone)){
					$auth_errors[] = "<p class='error'>Phone number can only be digits</p><style>.pn{border: 1px solid #CC0000;}</style>";
				} else {
					if (strlen($phone) != 11){
						$auth_errors[] = "<p class='error'>Phone number is an incorrect length, please enter 11 digits.</p><style>.pn{border: 1px solid #CC0000;}</style>";
					}
				}
			}
			
			//check if a security answer is given when updating question
			if(!empty($sec_q) && empty($sec_a)){
				$auth_errors[] = "<p class='error'>Please enter an answer for your security question.</p><style>.sa{border: 1px solid #CC0000;}</style>";
			} else if (empty($sec_q) && !empty($sec_a)){
				$auth_errors[] = "<p class='error'>Please enter a Security Question</p><style>.sq{border: 1px solid #CC0000;}</style>";
			}
			
				
			//password check
			if (empty($password)){
				$auth_errors[] = "<p class='error'>Please enter your current password</p><style>.pw{border: 1px solid #CC0000;}</style>";
			} else {
				$qry_pwd_check = "SELECT * FROM users WHERE u_email LIKE '$session_email' AND u_password LIKE '$hashed_pw'";
				$result_pwd = mysqli_query($conn, $qry_pwd_check);
				
				if (mysqli_num_rows($result_pwd) == 0){
					$auth_errors[] = "<p class='error'>Password is incorrect</p><style>.pw{border: 1px solid #CC0000;}</style>";
				}
			}
			
			// Used PHP and MySQL in easy steps' error method here - modified to my usage.
			if (empty($auth_errors)){
			
				$update_errors = array();
				
				//SQL queries, adds to $update_errors if the connection fails
				if (!empty($firstname)){
					$q_edit_fn = "UPDATE user_details SET u_name = '$firstname' WHERE u_email LIKE '$session_email'";
					
					if(!mysqli_query($conn, $q_edit_fn)){
						$update_errors[] ="<p class='error'>There was an error while changing the First Name. Please try again.</p><style>.fn{border: 1px solid #CC0000;}</style>";
					}
				}
				
				if (!empty($surname)){
					$q_edit_sn = "UPDATE user_details SET u_surname = '$surname' WHERE u_email LIKE '$session_email'";
					
					if(!mysqli_query($conn, $q_edit_sn)){
						$update_errors[] ="<p class='error'>There was an error while changing the Surname. Please try again.</p><style>.sn{border: 1px solid #CC0000;}</style>";
					}
				}
				
				if (!empty($phone)){
					$q_edit_no = "UPDATE user_details SET u_number = '$phone' WHERE u_email LIKE '$session_email'";
					
					if(!mysqli_query($conn, $q_edit_no)){
						$update_errors[] ="<p class='error'>There was an error while changing the Phone number. Please try again.</p><style>.pn{border: 1px solid #CC0000;}</style>";
					}
				}
				
				if (!empty($addr1)){
					$q_edit_a1 = "UPDATE user_details SET u_address1 = '$addr1' WHERE u_email LIKE '$session_email'";
					
					if(!mysqli_query($conn, $q_edit_a1)){
						$update_errors[] ="<p class='error'>There was an error while changing Address 1. Please try again.</p><style>.a1{border: 1px solid #CC0000;}</style>";
					}
				}
				
				if (!empty($addr2)){
					$q_edit_a2 = "UPDATE user_details SET u_address2 = '$addr2' WHERE u_email LIKE '$session_email'";
					
					if(!mysqli_query($conn, $q_edit_a2)){
						$update_errors[] ="<p class='error'>There was an error while changing Address 2. Please try again.</p><style>.a2{border: 1px solid #CC0000;}</style>";
					}
				}
				
				if (!empty($addr3)){
					$q_edit_a3 = "UPDATE user_details SET u_address3 = '$addr3' WHERE u_email LIKE '$session_email'";
					
					if(!mysqli_query($conn, $q_edit_a3)){
						$update_errors[] ="<p class='error'>There was an error while changing Address 3. Please try again.</p><style>.a3{border: 1px solid #CC0000;}</style>";
					}
				}
				
				if (!empty($postcode)){
					$q_edit_pc = "UPDATE user_details SET u_postcode = '$postcode' WHERE u_email LIKE '$session_email'";
					
					if(!mysqli_query($conn, $q_edit_pc)){
						$update_errors[] ="<p class='error'>There was an error while changing the Post Code. Please try again.</p><style>.pc{border: 1px solid #CC0000;}</style>";
					}
				}
				
				if (!empty($country)){
					$q_edit_co = "UPDATE user_details SET u_country = '$country' WHERE u_email LIKE '$session_email'";
					
					if(!mysqli_query($conn, $q_edit_co)){
						$update_errors[] ="<p class='error'>There was an error while changing the Country. Please try again.</p><style>.co{border: 1px solid #CC0000;}</style>";
					}
				}
				
				if (!empty($sec_q)){
					$q_edit_sq = "UPDATE user_details SET u_sec_q = '$sec_q' WHERE u_email LIKE '$session_email'";
					
					if(!mysqli_query($conn, $q_edit_sq)){
						$update_errors[] ="<p class='error'>There was an error while changing the Security Question. Please try again.</p><style>.sq{border: 1px solid #CC0000;}</style>";
					}
				}
				
				if (!empty($sec_a)){
					$q_edit_sa = "UPDATE user_details SET u_sec_a = '$sec_a' WHERE u_email LIKE '$session_email'";
					
					if(!mysqli_query($conn, $q_edit_sa)){
						$update_errors[] ="<p class='error'>There was an error while changing the Security Answer. Please try again.</p><style>.sa{border: 1px solid #CC0000;}</style>" ;
					}
				}
				
				if (!empty($email)){
					$q_edit_users = "UPDATE users SET u_email = '$email' WHERE u_email LIKE '$session_email'";

					
					if(!mysqli_query($conn, $q_edit_users)){
						$update_errors[] = "<p class='error'>There was an error while changing the Email. Please try again.</p><style>.e{border: 1px solid #CC0000;}</style>";
					} else {
						$_SESSION['userID'] = $email;
						//needs to change the session emails so it doesnt stay logged in as a false email after changing.
						$cookie_name = "user";
						$cookie_value = $email;
						$cookie_time = time() + 3600 * 24 * 7; //setting cookie expiry time for a week
						setcookie($cookie_name, $cookie_value, $cookie_time);
					}
				}
				
				if (empty($update_errors)){
					header('Location: control_panel.php?successfulChange=true');
					
					
				} else {
					foreach($update_errors as $error){
						echo "$error";
					}
				}
				
			} else {
				foreach($auth_errors as $error){
					echo "$error";
				}
			}
		}
?>
		<form action="change_details.php?" method="POST">
			<div class="change-details left">
				<label for="first_name">Name</label>
				<input type="text" name="first_name" maxlength="256" class="groupdown fn" value="<?php if (isset($firstname)) echo $firstname;?>">
				
				<label for="surname">Surname</label>
				<input type="text" name="surname" maxlength="256" class="sn" value="<?php if (isset($surname)) echo $surname;?>">
				
				<label for="email">Email </label>
				<input type="email" name="email" maxlength="256"  class="groupdown e" value="<?php if (isset($email)) echo $email;?>">
				
				<label for="confirm_email">Email Confirm</label>
				<input type="email" name="confirm_email" maxlength="256"  class="ec" value="<?php if (isset($email_confirmation)) echo $email_confirmation;?>">
				
				<label for="sec_q">Security Question </label>
				<input type="text" name="sec_q" maxlength="256" class="groupdown sq" value="<?php if (isset($sec_q)) echo $sec_q;?>">
				
				<label for="sec_a">Security Answer </label>
				<input type="text" name="sec_a" maxlength="256" class="sa" value="<?php if (isset($sec_a)) echo $sec_a;?>">
			</div>
			
			<div class="change-details right">
				<label for="number">Phone number</label>
				<input type="text" name="number" maxlength="11" class="pn"value="<?php if (isset($phone)) echo $phone;?>">
				
				<label for="address1">Address</label>
				<input type="text" name="address1" maxlength="256" class="groupdown a1"  value="<?php if (isset($addr1)) echo $addr1;?>">
				
				<label for="address2">Address 2</label>
				<input type="text" name="address2" maxlength="256" class="groupdown a2"  value="<?php if (isset($addr3)) echo $addr2;?>">
				
				<label for="address3">Address 3 </label>
				<input type="text" name="address3" maxlength="256" class="groupdown a3" value="<?php if (isset($addr3)) echo $addr3;?>">
				
				<label for="post_code">Post/Zip Code </label>
				<input type="text" name="post_code"  maxlength="16" class="groupdown pc"value="<?php if (isset($postcode)) echo $postcode;?>">
				
				<label for="countries">Country </label>
				<select id="countries" name="country" class="co">
				<option value="">Select a Country</option>
				<option value="Afghanistan">Afghanistan</option>
				<option value="Åland Islands">Åland Islands</option>
				<option value="Albania">Albania</option>
				<option value="Algeria">Algeria</option>
				<option value="American Samoa">American Samoa</option>
				<option value="Andorra">Andorra</option>
				<option value="Angola">Angola</option>
				<option value="Anguilla">Anguilla</option>
				<option value="Antarctica">Antarctica</option>
				<option value="Antigua and Barbuda">Antigua and Barbuda</option>
				<option value="Argentina">Argentina</option>
				<option value="Armenia">Armenia</option>
				<option value="Aruba">Aruba</option>
				<option value="Australia">Australia</option>
				<option value="Austria">Austria</option>
				<option value="Azerbaijan">Azerbaijan</option>
				<option value="Bahamas">Bahamas</option>
				<option value="Bahrain">Bahrain</option>
				<option value="Bangladesh">Bangladesh</option>
				<option value="Barbados">Barbados</option>
				<option value="Belarus">Belarus</option>
				<option value="Belgium">Belgium</option>
				<option value="Belize">Belize</option>
				<option value="Benin">Benin</option>
				<option value="Bermuda">Bermuda</option>
				<option value="Bhutan">Bhutan</option>
				<option value="Bolivia">Bolivia</option>
				<option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
				<option value="Botswana">Botswana</option>
				<option value="Bouvet Island">Bouvet Island</option>
				<option value="Brazil">Brazil</option>
				<option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
				<option value="Brunei Darussalam">Brunei Darussalam</option>
				<option value="Bulgaria">Bulgaria</option>
				<option value="Burkina Faso">Burkina Faso</option>
				<option value="Burundi">Burundi</option>
				<option value="Cambodia">Cambodia</option>
				<option value="Cameroon">Cameroon</option>
				<option value="Canada">Canada</option>
				<option value="Cape Verde">Cape Verde</option>
				<option value="Cayman Islands">Cayman Islands</option>
				<option value="Central African Republic">Central African Republic</option>
				<option value="Chad">Chad</option>
				<option value="Chile">Chile</option>
				<option value="China">China</option>
				<option value="Christmas Island">Christmas Island</option>
				<option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
				<option value="Colombia">Colombia</option>
				<option value="Comoros">Comoros</option>
				<option value="Congo">Congo</option>
				<option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
				<option value="Cook Islands">Cook Islands</option>
				<option value="Costa Rica">Costa Rica</option>
				<option value="Cote D'ivoire">Cote D'ivoire</option>
				<option value="Croatia">Croatia</option>
				<option value="Cuba">Cuba</option>
				<option value="Cyprus">Cyprus</option>
				<option value="Czech Republic">Czech Republic</option>
				<option value="Denmark">Denmark</option>
				<option value="Djibouti">Djibouti</option>
				<option value="Dominica">Dominica</option>
				<option value="Dominican Republic">Dominican Republic</option>
				<option value="Ecuador">Ecuador</option>
				<option value="Egypt">Egypt</option>
				<option value="El Salvador">El Salvador</option>
				<option value="Equatorial Guinea">Equatorial Guinea</option>
				<option value="Eritrea">Eritrea</option>
				<option value="Estonia">Estonia</option>
				<option value="Ethiopia">Ethiopia</option>
				<option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
				<option value="Faroe Islands">Faroe Islands</option>
				<option value="Fiji">Fiji</option>
				<option value="Finland">Finland</option>
				<option value="France">France</option>
				<option value="French Guiana">French Guiana</option>
				<option value="French Polynesia">French Polynesia</option>
				<option value="French Southern Territories">French Southern Territories</option>
				<option value="Gabon">Gabon</option>
				<option value="Gambia">Gambia</option>
				<option value="Georgia">Georgia</option>
				<option value="Germany">Germany</option>
				<option value="Ghana">Ghana</option>
				<option value="Gibraltar">Gibraltar</option>
				<option value="Greece">Greece</option>
				<option value="Greenland">Greenland</option>
				<option value="Grenada">Grenada</option>
				<option value="Guadeloupe">Guadeloupe</option>
				<option value="Guam">Guam</option>
				<option value="Guatemala">Guatemala</option>
				<option value="Guernsey">Guernsey</option>
				<option value="Guinea">Guinea</option>
				<option value="Guinea-bissau">Guinea-bissau</option>
				<option value="Guyana">Guyana</option>
				<option value="Haiti">Haiti</option>
				<option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
				<option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
				<option value="Honduras">Honduras</option>
				<option value="Hong Kong">Hong Kong</option>
				<option value="Hungary">Hungary</option>
				<option value="Iceland">Iceland</option>
				<option value="India">India</option>
				<option value="Indonesia">Indonesia</option>
				<option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
				<option value="Iraq">Iraq</option>
				<option value="Ireland">Ireland</option>
				<option value="Isle of Man">Isle of Man</option>
				<option value="Israel">Israel</option>
				<option value="Italy">Italy</option>
				<option value="Jamaica">Jamaica</option>
				<option value="Japan">Japan</option>
				<option value="Jersey">Jersey</option>
				<option value="Jordan">Jordan</option>
				<option value="Kazakhstan">Kazakhstan</option>
				<option value="Kenya">Kenya</option>
				<option value="Kiribati">Kiribati</option>
				<option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
				<option value="Korea, Republic of">Korea, Republic of</option>
				<option value="Kuwait">Kuwait</option>
				<option value="Kyrgyzstan">Kyrgyzstan</option>
				<option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
				<option value="Latvia">Latvia</option>
				<option value="Lebanon">Lebanon</option>
				<option value="Lesotho">Lesotho</option>
				<option value="Liberia">Liberia</option>
				<option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
				<option value="Liechtenstein">Liechtenstein</option>
				<option value="Lithuania">Lithuania</option>
				<option value="Luxembourg">Luxembourg</option>
				<option value="Macao">Macao</option>
				<option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
				<option value="Madagascar">Madagascar</option>
				<option value="Malawi">Malawi</option>
				<option value="Malaysia">Malaysia</option>
				<option value="Maldives">Maldives</option>
				<option value="Mali">Mali</option>
				<option value="Malta">Malta</option>
				<option value="Marshall Islands">Marshall Islands</option>
				<option value="Martinique">Martinique</option>
				<option value="Mauritania">Mauritania</option>
				<option value="Mauritius">Mauritius</option>
				<option value="Mayotte">Mayotte</option>
				<option value="Mexico">Mexico</option>
				<option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
				<option value="Moldova, Republic of">Moldova, Republic of</option>
				<option value="Monaco">Monaco</option>
				<option value="Mongolia">Mongolia</option>
				<option value="Montenegro">Montenegro</option>
				<option value="Montserrat">Montserrat</option>
				<option value="Morocco">Morocco</option>
				<option value="Mozambique">Mozambique</option>
				<option value="Myanmar">Myanmar</option>
				<option value="Namibia">Namibia</option>
				<option value="Nauru">Nauru</option>
				<option value="Nepal">Nepal</option>
				<option value="Netherlands">Netherlands</option>
				<option value="Netherlands Antilles">Netherlands Antilles</option>
				<option value="New Caledonia">New Caledonia</option>
				<option value="New Zealand">New Zealand</option>
				<option value="Nicaragua">Nicaragua</option>
				<option value="Niger">Niger</option>
				<option value="Nigeria">Nigeria</option>
				<option value="Niue">Niue</option>
				<option value="Norfolk Island">Norfolk Island</option>
				<option value="Northern Mariana Islands">Northern Mariana Islands</option>
				<option value="Norway">Norway</option>
				<option value="Oman">Oman</option>
				<option value="Pakistan">Pakistan</option>
				<option value="Palau">Palau</option>
				<option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
				<option value="Panama">Panama</option>
				<option value="Papua New Guinea">Papua New Guinea</option>
				<option value="Paraguay">Paraguay</option>
				<option value="Peru">Peru</option>
				<option value="Philippines">Philippines</option>
				<option value="Pitcairn">Pitcairn</option>
				<option value="Poland">Poland</option>
				<option value="Portugal">Portugal</option>
				<option value="Puerto Rico">Puerto Rico</option>
				<option value="Qatar">Qatar</option>
				<option value="Reunion">Reunion</option>
				<option value="Romania">Romania</option>
				<option value="Russian Federation">Russian Federation</option>
				<option value="Rwanda">Rwanda</option>
				<option value="Saint Helena">Saint Helena</option>
				<option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
				<option value="Saint Lucia">Saint Lucia</option>
				<option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
				<option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
				<option value="Samoa">Samoa</option>
				<option value="San Marino">San Marino</option>
				<option value="Sao Tome and Principe">Sao Tome and Principe</option>
				<option value="Saudi Arabia">Saudi Arabia</option>
				<option value="Senegal">Senegal</option>
				<option value="Serbia">Serbia</option>
				<option value="Seychelles">Seychelles</option>
				<option value="Sierra Leone">Sierra Leone</option>
				<option value="Singapore">Singapore</option>
				<option value="Slovakia">Slovakia</option>
				<option value="Slovenia">Slovenia</option>
				<option value="Solomon Islands">Solomon Islands</option>
				<option value="Somalia">Somalia</option>
				<option value="South Africa">South Africa</option>
				<option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
				<option value="Spain">Spain</option>
				<option value="Sri Lanka">Sri Lanka</option>
				<option value="Sudan">Sudan</option>
				<option value="Suriname">Suriname</option>
				<option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
				<option value="Swaziland">Swaziland</option>
				<option value="Sweden">Sweden</option>
				<option value="Switzerland">Switzerland</option>
				<option value="Syrian Arab Republic">Syrian Arab Republic</option>
				<option value="Taiwan, Province of China">Taiwan, Province of China</option>
				<option value="Tajikistan">Tajikistan</option>
				<option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
				<option value="Thailand">Thailand</option>
				<option value="Timor-leste">Timor-leste</option>
				<option value="Togo">Togo</option>
				<option value="Tokelau">Tokelau</option>
				<option value="Tonga">Tonga</option>
				<option value="Trinidad and Tobago">Trinidad and Tobago</option>
				<option value="Tunisia">Tunisia</option>
				<option value="Turkey">Turkey</option>
				<option value="Turkmenistan">Turkmenistan</option>
				<option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
				<option value="Tuvalu">Tuvalu</option>
				<option value="Uganda">Uganda</option>
				<option value="Ukraine">Ukraine</option>
				<option value="United Arab Emirates">United Arab Emirates</option>
				<option value="United Kingdom">United Kingdom</option>
				<option value="United States">United States</option>
				<option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
				<option value="Uruguay">Uruguay</option>
				<option value="Uzbekistan">Uzbekistan</option>
				<option value="Vanuatu">Vanuatu</option>
				<option value="Venezuela">Venezuela</option>
				<option value="Viet Nam">Viet Nam</option>
				<option value="Virgin Islands, British">Virgin Islands, British</option>
				<option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
				<option value="Wallis and Futuna">Wallis and Futuna</option>
				<option value="Western Sahara">Western Sahara</option>
				<option value="Yemen">Yemen</option>
				<option value="Zambia">Zambia</option>
				<option value="Zimbabwe">Zimbabwe</option>
			</select>
			</div>	

			<div class="clearfix"><!-- Standard clearfix to correct floated divs --></div>
			
			<div id="password-confirm" >
				<label for="password">So we know it's you, please confirm your password</label>
				<input type="password" name="password" class="pw">
				<input type="submit" value="Edit details" class="submit">
			</div>
		</form>
		
		<div id="cd-change-pass">
			<form action="change_password.php">
				<input type="submit" value="Change your password here" class="submit">
			</form>
		</div>
	</div>
	<?php
	
		} else {
			echo "Error";
			session_destroy();
		}
	} else {
		header('Location: index.php?notLoggedIn=true'); //this refers to an error message in index.php
		
	}
	?>

</body>

</html>