<?php
session_start();
// If user is logged in, header them away
if (isset($_SESSION['username'])) {
	header("location: message.php?msg=NO to that weenis");
	exit();
}
?>

<?php
// Ajax calls this NAME CHECK code to execute
if (isset($_POST['usernamecheck'])) {
	include_once "php_includes/db_conex.php";
	$username = preg_replace('#[^a-z0-9]#i', '', $_POST['usernamecheck']);
	$sql = "SELECT id FROM users WHERE username='$username' LIMIT 1";
	$query = mysqli_query($db_conx, $sql);
	$uname_check = mysqli_num_rows($query);

	if (strlen($username) < 3 || strlen($username) > 16) {
		echo '<strong style="color: #F00;">3 - 16 characters please</strong>';
		exit();
	}

	if (is_numeric($username[0])) {
		echo '<strong style="color: #F00;">Usernames must begin with a letter</strong>';
		exit();
	}

	if ($uname_check < 1) {
		echo '<strong style="color: #009900;">' . $username . ' is OK</strong>';
		exit();
	}else {
		echo '<strong style="color: F00;">' . $username . ' is taken</strong>';
		exit();
	}
}
?>

<?php
	// Ajax calls this REGISTRATION code to execute
	if (isset($_POST["u"])) {
		// CONNECT TO THE DATABASE
		include_once "php_includes/db_conex.php";
		// GATHER THE POSTED DATA INTO LOCAL VARIABLES
		$u = preg_replace('#[^a-z0-9]#i', '', $_POST['u']);
		$e = mysqli_real_escape_string($db_conx, $_POST['e']);
		$p = $_POST['p'];
		$g = preg_replace('#[^a-z]#', '', $_POST['g']);
		$c = preg_replace('#[^a-z ]#i', '', $_POST['c']);
		// GET USER IP ADDRESS
		$ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
		// DUPLICATE DATA CHECKS FOR USERNAME AND EMAIL
		$sql = "SELECT id FROM users WHERE username='$u' LIMIT 1";
		$query = mysqli_query($db_conx, $sql);
		$u_check = mysqli_num_rows($query);
		// -----------------------------------------------
		$sql = "SELECT id FROM users WHERE email='$e' LIMIT 1";
		$query = mysqli_query($db_conx, $sql);
		$e_check = mysqli_num_rows($query);
		// FORM DATA ERROR HANDLING
		if ($u == "" || $e == "" || $p == "" || $g == "" || $c == "") {
			echo "The username you entered is already taken";
			exit();
		} elseif ($u_check > 0) {
			echo "The username you entered is already taken";
			exit();
		} elseif ($e_check > 0) {
			echo "That email address is already in use in the system";
			exit();
		} elseif (strlen($u) < 3 || strlen($u) > 16) {
			echo "Username must be between 3 and 16 characters";
			exit();
		} elseif (is_numeric($u[0])) {
			echo "Username cannot begin with a number";
			exit();
		} else{
		// END FORM DATA ERROR HANDLING
			// Begin Insertion of data into the database
			// Hash the password and apply your own mysterious unique salt
			$cryptpass = crypt($p);
			include_once ("php_includes/randStrGen.php");
			$p_hash = randStrGen(20) . "$cryptpass" . randStrGen(20);
			// Add user info into the database table for the main site table
			$sql = "INSERT INTO users (username, email, password, gender, country, ip, signup, lastlogin, notescheck)
						 VALUES('$u', '$e', '$p_hash', '$g', '$c', now(), now(), now())";
			$query = mysqli_query($db_conx, $sql);
			$uid = mysql_insert_id($db_conx);
			// Establish their row in the useroptions table
			$sql = "INSERT INTO useroptions (id, username, background) VALUES ('$uid', '$u', 'original')";
			$query = mysqli_query($db_conx, $sql);
			// Create directory(folder) to hold each user's files(pics, MP3s, etc.)
			if (!file_exists("user/$e")) {
			 	mkdir("user/$e", 0755);
			 }
			// Email the user their activation link
			$to = "$e";
			$from = "quinterom1592@gmail.com";
			$subject = "Mateo Social Network Account Activation";
			$message = '<!DOCTYPE html>
									<html>
										<head>
											<meta charset="UTF-8">
											<title>Mateo Social Network Message</title>
										</head>
										<body style="font-family:Tahoma, Geneva, sans-serif;">
											<div style="padding:10px; background: #333; font-size:24px; color: #CCC;">
												<a href="http://localhost:78/Matep_Social_Network/root/">
													<img src="http://localhost:78/Matep_Social_Network/root/img/logo.png" alt="Mateo Social Network" style="border:none; float:left;">
												</a>
												Mateo Social Network Account Activation
											</div>
											<div style="padding:24px; font-size:17px;">
												Hello ' . $u . ',<br /><br />
												Click the link below to activate your account when ready:<br /><br />
												<a href="http://localhost:78/Matep_Social_Network/root/activation.php?id=' . $uid . '&u=' . $u . '&e=' . $e . '&p=' . $p_hash . '">
													Click here to activate your account now
												</a><br /><br />
												Login after successful activation using your:<br />
												* E-mail Address: <b>' . $e . '</b><br />
											</div>
										</body>
									</html>';
			$headers = "From: $from\n";
			$headers .= "MIME-Version: 1.0\n";
			$headers .= "Content-type: text/html; charset: iso-8859-1\n";

			if (mail($to, $subject, $message, $headers)) {
				echo "signup_success";
			} else {
				echo "Sorry, there was an issue :(";
			}
			exit();
		}
		exit();
	}
?>

<!DOCTYPE  HTML>
<html>
	<head>
		<meta charset="UTF-8"  />
		<title>Sign Up</title>
		<link rel="stylesheet" href="css/index.css" />
		<link rel="stylesheet" href="css/signup.css">

		<script type="text/javascript" src="js/main.js"></script>
		<script type="text/javascript" src="js/ajax.js"></script>
		<script type="text/javascript" src="js/fadeEffects.js"></script>
		<script type="text/javascript" src="js/validations.js"></script>
		<script type="text/javascript" src="js/signup.js"></script>
	</head>
	<body>
		<?php include_once 'template_pageTop.php'; ?>
		<div id="pageMiddle">
			<h3>Sign Up Here</h3>
			<form name="signupform" id="signupform" onsubmit="return false;">
				<div>Username: </div>
				<input type="text" id="username" onblur="checkusername()" onkeyup="restrict('username')" maxlength="16">
				<div>Email: </div>
				<input id="email" type="text" onfocus="emptyElement('status')" onkeyup="restrict('email')" maxlength="88">
				<span id="unamestatus"></span>
				<div>Password: </div>
				<input id="pass1" type="password" onfocus="emptyElement('status')" maxlength="16">
				<div>Confirmation: </div>
				<input type="password" id="pass2" onfocus="emptyElement('status')" maxlength="16">
				<div>Gender: </div>
				<select name="gender" id="gender" onfocus="emptyElement('status')">
					<option value=""></option>
					<option value="m">Male</option>
					<option value="f">Female</option>
				</select>
				<div>Country: </div>
				<select name="country" id="country" onfocus="emptyElement('status')">
					<?php include_once("template_country_list.php"); ?>
				</select>
				<div>
					<a href="#" onclick="return false" onmousedown="openTerms()">
						View the Terms of Use
					</a>
				</div>
				<div id="terms" style="display: none;">
					<h3>Mateo Social Network Terms of User</h3>
					<p>1. Play nice here.</p>
					<p>2. Take a bath before you visit.</p>
					<p>3. Brush your teeth before bed.</p>
				</div>
				<br /><br />
				<button id="signupbtn" onclick="signup()">Create Account</button>
				<span id="status"></span>
			</form
		</div>
		<?php include_once 'template_pageBottom.php'; ?>
	</body>
</html>