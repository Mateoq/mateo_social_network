<?php

$db_conx = mysqli_connect("localhost", "rakingsa_mateo", "Mdjqj1993", "rakingsa_social_network");

// Evaluate the connection
if (mysqli_connect_errno()) {
	echo mysqli_connect_error();
	exit();
}/*else{
	echo "Succesful database connection!!";
}*/

 ?>