<?php 
	session_start();
	
	$page_title = "Register!";
	
	require 'header.php';
	
	if (isset($_COOKIE['user'])){
		$_SESSION['loggedin'] = true;
		$_SESSION['userID'] = $_COOKIE['user'];
	}
	
	if (isset($_SESSION['loggedin'])){
		if ($_SESSION['loggedin'] == true){
		
			header('Location: control_panel.php?alreadyRegistered=true');
			
		} else {
			echo "Error";
			unset($_SESSION['loggedin']);
		}
	} else { 
		
		// This info is inserted into databases
		$email = $_POST['email'];
		$password = $_POST['password'];
		$hashed_pw = SHA1("$password");
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
?>


	<div class="form-box change-details">
	
		<h1>Register</h1>
<?php
		if (!empty($_POST)){
			$uid = "i7709331";
			$pwd = "phppass";
			$host = "127.0.0.1";
			$db = $uid;
			$conn = mysqli_connect($host, $uid, $pwd, $db);
			
			
			$db_errors = array();
			
			//This is only for email confirmation
			$email_confirmation = $_POST['confirm_email']; 
			$password_confirmation = $_POST['confirm_password']; 
			
			//set lower case
			$email = strtolower($email);
			$email_confirmation = strtolower($email_confirmation);
			
			$sec_a = strtolower($sec_a);
			
			
			
			//email check
			if (empty($email)){ 
				$db_errors[] = "<p class='error'>An email address is required</p><style>.e{border: 1px solid #CC0000;}</style>"; //add to the errors
			} else {
				//Check if user exists
				$q_email_check = "SELECT u_email FROM users WHERE u_email LIKE '$email'"; //Query to find duplicate emails
				$result_email = mysqli_query($conn, $q_email_check);
				
				if (mysqli_num_rows($result_email) >= 1){
					$db_errors[] = "<p class='error'>Email is taken</p><style>.e{border: 1px solid #CC0000;}</style>";
				} else {
					//check if emails are the same
					if ($email != $email_confirmation){
						$db_errors[] = "<p class='error'>you need to confirm the email address</p><style>.ec{border: 1px solid #CC0000;}</style>";
					}
				}
			}
			//password check
			if (empty($password)){
				$db_errors[] = "<p class='error'>A password is required</p><style>.pw{border: 1px solid #CC0000;}</style>";
			} else {
				if (strlen($password) < 8){
					$db_errors[] = "<p class='error'>Password needs to be more than 8 characters</p><style>.pw{border: 1px solid #CC0000;}</style>";
				} else {
					//check if passwords are the same
					if ($password != $password_confirmation){
						$db_errors[] = "<p class='error'>Password do not match</p><style>.pw,.cpw{border: 1px solid #CC0000;}</style>";
					}
				}
			}
			
			//check everything else
			if(empty($firstname)){ //tested and worked
				$db_errors[] = "<p class='error'>First name is required</p><style>.fn{border: 1px solid #CC0000;}</style>";
			} 
			if(empty($surname)){ // tested and worked
				$db_errors[] = "<p class='error'>Surname is required</p><style>.sn{border: 1px solid #CC0000;}</style>";
			}
			if(empty($phone)){ //tested and worked
				$db_errors[] = "<p class='error'>Phone number is required</p><style>.pn{border: 1px solid #CC0000;}</style>";
			} else {
				//check if Phone number is valid length.
				if (!preg_match("/^[0-9]+$/",$phone)){
					$db_errors[] = "<p class='error'>Phone number can only be digits</p><style>.pn{border: 1px solid #CC0000;}</style>";
				} else {
					if (strlen($phone) != 11){
						$db_errors[] = "<p class='error'>Phone number needs to be 11 digits long</p><style>.pn{border: 1px solid #CC0000;}</style>";
					}
				}
			}
			if(empty($addr1)){ //test3ed and worked
				$db_errors[] = "<p class='error'>First address line is requires</p><style>.a1{border: 1px solid #CC0000;}</style>";
			}
			if(empty($postcode)){ //tested and worked
				$db_errors[] = "<p class='error'>A post code is required</p><style>.pc{border: 1px solid #CC0000;}</style>";
			} 
			if(empty($country)){ //tested and worked (not with drop down)
				$db_errors[] = "<p class='error'>Select a country</p><style>.co{border: 1px solid #CC0000;}</style>";
			} 
			if(empty($sec_q)){ //tested and worked
				$db_errors[] = "<p class='error'>Enter a security question</p><style>.sq{border: 1px solid #CC0000;}</style>";
			} else {
				if(empty($sec_a)){
					$db_errors[] = "<p class='error'>Answer your security question</p><style>.sa{border: 1px solid #CC0000;}</style>";
				}	 
			}
			
			$email = mysqli_real_escape_string($conn, $email);
			$firstname = mysqli_real_escape_string($conn, $firstname);
			$surname = mysqli_real_escape_string($conn, $surname);
			$phone = mysqli_real_escape_string($conn, $phone);
			$addr1 = mysqli_real_escape_string($conn, $addr1);
			$addr2 = mysqli_real_escape_string($conn, $addr2);
			$addr3 = mysqli_real_escape_string($conn, $addr3);
			$postcode = mysqli_real_escape_string($conn, $postcode);
			$sec_q = mysqli_real_escape_string($conn, $sec_q);
			$sec_a = mysqli_real_escape_string($conn, $sec_a);
			
			
			
			//checks if there are any errors.
			if (empty($db_errors)){
				//sql queries
				$q_users = "INSERT INTO users VALUES ('$email','$hashed_pw');";
				$q_user_details = "INSERT INTO user_details VALUES ('$email','$firstname','$surname','$phone','$addr1','$addr2','$addr3',
							'$postcode','$country','$sec_q','$sec_a');";
				
				if (mysqli_query($conn, $q_users)){
					
					if (mysqli_query($conn, $q_user_details)){
						header('Location: index.php?registered=true');
					} else {
						echo "<p class='error'>There was an unexpected Error. Please try again.</p>";
					}
				} else {
					echo "<p class='error'>There was an unexpected Error. Please try again.</p>";
				}
			} else {
				foreach($db_errors as $error){
					echo "$error";
				}
			}
			mysqli_close($conn);
		}
	}
?>
		<form action="register.php" method="POST">
			<div class="change-details left">
				<label for="first_name">Name *</label>
				<input type="text" name="first_name" maxlength="256" class="groupdown fn" value="<?php if (isset($firstname)) echo $firstname;?>">
				
				<label for="surname">Surname *</label>
				<input type="text" name="surname" maxlength="256" class="sn" value="<?php if (isset($surname)) echo $surname;?>">
				
				<label for="email">Email *</label>
				<input type="email" name="email" maxlength="256"  class="groupdown e" value="<?php if (isset($email)) echo $email;?>">
				
				<label for="confirm_email">Email Confirm *</label>
				<input type="email" name="confirm_email" maxlength="256"  class="ec" value="<?php if (isset($email_confirmation)) echo $email_confirmation;?>">
				
				<label for="sec_q">Security Question *</label>
				<input type="text" name="sec_q" maxlength="256" class="groupdown sq" value="<?php if (isset($sec_q)) echo $sec_q;?>">
				
				<label for="sec_a">Security Answer *</label>
				<input type="text" name="sec_a" maxlength="256" class="sa" value="<?php if (isset($sec_a)) echo $sec_a;?>">
			</div>
			
			<div class="change-details right">
				<label for="number">Phone number *</label>
				<input type="text" name="number" maxlength="11" class="pn"value="<?php if (isset($phone)) echo $phone;?>">
				
				<label for="address1">Address *</label>
				<input type="text" name="address1" maxlength="256" class="groupdown a1"  value="<?php if (isset($addr1)) echo $addr1;?>">
				
				<label for="address2">Address 2 </label>
				<input type="text" name="address2" maxlength="256" class="groupdown a2"  value="<?php if (isset($addr3)) echo $addr2;?>">
				
				<label for="address3">Address 3 </label>
				<input type="text" name="address3" maxlength="256" class="groupdown a3" value="<?php if (isset($addr3)) echo $addr3;?>">
				
				<label for="post_code">Post/Zip Code *</label>
				<input type="text" name="post_code"  maxlength="16" class="groupdown pc"value="<?php if (isset($postcode)) echo $postcode;?>">
				
				<label for="countries">Country *</label>
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
			
			<div id="password" >
				<label for="password">Password *</label>
				<input type="password" name="password" class="groupdown pw">
				
				<label for="confirm_password">Password confirm *</label>
				<input type="password" name="confirm_password" class="cpw">
				
				<input type="submit" value="Register" class="submit">
			</div>

		</form>
	</div>

</body>
</html>
</html>