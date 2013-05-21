<?php
  require_once 'php_includes/check_login_status.php';
  # Initialize any variables that the page might echo
  $u = "";
  $sex = "Male";
  $userlevel = "";
  $country = "";
  $joindate = "";
  $lastsession = "";
  # Make sure the _GET username is set, and sanitize it
  if (isset($_GET["u"])) {
    $u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
  } else {
    header("location: http://www.rakingsas.com/mtsocial/");
    exit();
  }
  # Select the member from the users table
  $sql = "SELECT * FROM users WHERE username='$u' AND activated='1' LIMIT 1";
  $user_query = mysqli_master_query($db_conx, $sql);
  # Now make sure that user exists in the table
  $numrows = mysqli_num_rows($user_query);
  if ($numrows < 1) {
    echo "That user does not exist or is not yet activated, press back";
    exit();
  }
  # Check to see if the viewer is the account owner
  $isOwner = "no";
  if ($u == $log_username && $user_ok == true) {
      $isOwner = "yes";
  } 
  # Fetch the user row from the query above
  while ($row = mysqli_fetch_array($user_query, MYSQL_ASSOC)) {
   	$profile_id = $row["id"];
	$country = $row["country"];
	$userlevel = $row["userlevel"];
	$joindate = strftime("%b %d, %Y", strtotime($row["signup"]));
	$lastsession = strftime("%b %d, %Y", strtotime($row["lastlogin"]));
 	if ($row["gender"] == "f") {
		$sex = "Female";
	 }   
  }
?>

<!DOCTYPE  HTML>
<html>
  <head>
    <meta charset="UTF-8"  />
    <title><?php echo $u; ?></title>
    <link rel="stylesheet" href="css/index.css" />
    <script type="text/javascript" src="js/main.js"></script>
    <script type="text/javascript" src="js/ajax.js"></script>
    <script type="text/javascript" src="js/fadeEffects.js"></script>
    <script type="text/javascript" src="js/validations.js"></script>
  </head>
  <body>
    <?php include_once 'template_pageTop.php'; ?>
    <div id="pageMiddle">
      <h3><?php echo $u; ?></h3>
      <p>Is the viewer the page owner, logged in and verified? <b><?php echo($isOwner); ?></b></p>
      <p>Gender: <?php echo $sex; ?></p>
      <p>Country: <?php echo $country; ?></p>
      <p>User Level: <?php echo $userlevel; ?></p>
      <p>Join Date: <?php echo $joindate; ?></p>
      <p>Last Session: <?php echo $lastsession; ?></p>
    </div>
    <?php include_once 'template_pageBottom.php'; ?>
  </body>
</html>