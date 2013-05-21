<?php
  session_start();
  // If user is already logged in, header that weenis away
  if (isset($_SESSION["username"])) {
    header("location: user.php?u=" . $_SESSION["username"]);
    exit();
  }
?>

<?php
  // AJAX CALLS THIS LOGIN CODE TO EXECUTE
  if (isset($_POST["e"])) {
    // CONNECT TO THE DATABASE
    include_once 'php_includes/db_conex.php';
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES AND SANITIZE
    $e = mysqli_real_escape_string($db_conx, $_POST['e']);
    $p = md5($_POST['p']);
    // GET USER IP ADDRESS
    $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
    // FORM DATA ERROR HANDLING
    if ($e == "" || $p == "") {
      echo("login_failed");
      exit();
    // END FORM DATA ERROR HANDLING
    } else {
      $sql = "SELECT id, username, password FROM users WHERE email='$e' AND activated='1' LIMIT 1";
      $query = mysqli_query($db_conx, $sql);
      $row = mysqli_fetch_row($query);
      $db_id = $row[0];
      $db_username = $rom[1];
      $db_pass_str = $row[2];

      if ($p != $db_pass_str) {
        echo "login_failed";
        exit();
      } else {
        # CREATE THEIR SESSIONS AND COOKIES
        $_SESSION['userid'] = $db_id;
        $_SESSION['username'] = $db_username;
        $_SESSION['password'] = $db_pass_str;
        setcookie("id", $db_id, strtotime( '+30 days' ), "/", "", "", TRUE);
        setcookie("user", $db_username, strtotime( '+30 days' ), "/", "", "", TRUE);
        setcookie("pass", $db_pass_str, strtotime( '+30 days' ), "/", "", "", TRUE);
        # UPDATE THEIR "IP" AND "LASTLOGIN" FIELDS
        $sql = "UPDATE users SET ip='$ip', lastlogin=now() WHERE username='$db_username' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        echo $db_username;
        exit();
      }
    }
    exit();
  }
?>

<!DOCTYPE  HTML>
<html>
  <head>
    <meta charset="UTF-8"  />
    <title>Log In</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/login.css">

    <script type="text/javascript" src="js/main.js"></script>
    <script type="text/javascript" src="js/ajax.js"></script>
    <script type="text/javascript" src="js/fadeEffects.js"></script>
    <script type="text/javascript" src="js/validations.js"></script>
    <script type="text/javascript" src="js/login.js"></script>
  </head>
  <body>
    <?php include_once 'template_pageTop.php'; ?>
    <div id="pageMiddle">
      <h3>Log In Here</h3>
      <!-- LOGIN FORM -->
      <form id="loginform" onsubmit="return false;">
        <div>Email Address:</div>
        <input typye="text" id="email" onfocus="emptyElement('status')" maxlength="88">
        <div>Password:</div>
        <input type="password" id="password" onfocus="emptyElement('status')" maxlength="100">
        <br /><br />
        <button id="loginbtn" onclick="login()">Log In</button>
        <p id="status"></p>
        <a href="#">Forgot Your Password?</a>
      </form>
      <!-- LOGIN FORM -->
    </div>
    <?php include_once 'template_pageBottom.php'; ?>
  </body>
</html>