<?php
  $message = "No message";
  $msg = preg_replace('#[^a-z 0-9.:_()]#i', '', $_GET['msg']);

  if ($msg == "activation_failure") {
    $message = '<h2>Activation Error</h2> Sorry there seems to have been an issue activating your account at this time. We have already notified ourselves of this issue and we will contact you via email when we have identified the issue.';
  } else if ($msg == "activation_success") {
    $message = '<h2>Activation Success</h2> your account is now activated. <a href="login.php">Click here to log in</a>';
  }else {
    $message = $msg;
  }
?>

<div style="height: 200px; width: 600px;">
  <?php echo $message; ?>
</div>