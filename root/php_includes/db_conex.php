<?php 

$db_conx = mysqli_connect("localhost", "root", "", "mateo_social");

// Evaluate the connection
if (mysqli_connect_errno()) {
	echo mysqli_connect_error();
	exit();
}else{
	echo "Succesful database connection!!";
}

 ?>