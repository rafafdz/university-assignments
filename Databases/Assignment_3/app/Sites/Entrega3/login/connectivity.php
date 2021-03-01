<?php

/*
$ID = $_POST['user'];
$Password = $_POST['pass'];
*/
function SignIn()
{
session_start();   //starting the session for user profile page
if(!empty($_POST['user']))   //checking the 'user' name which is from Sign-In.html,
# is it empty or have some text
{
	require("../config/conexion.php");
	$userName = $_POST["user"];
	$pass = $_POST["pass"];
	$query = "SELECT *  FROM UserName where userName = '$userName' AND pass = '$pass';";
	#$query = "SELECT * FROM userName;";
	$result = $db -> prepare($query);
	$result -> execute();
	$rows = $result -> fetchAll();
	print_r ($rows);
	# echo '$rows['userName'], $rows['pass']';
	if( !$rows['userName'] AND !$rows["pass"])
	{
		$_SESSION['userName'] = $rows["pass"];
		echo "SUCCESSFULLY LOGIN TO USER PROFILE PAGE...";

	}
	else
	{
		echo "SORRY... YOU ENTERD WRONG ID AND PASSWORD... PLEASE RETRY...";
	}
}
}
if(isset($_POST['submit']))
{
	SignIn();
}

?>

CREATE TABLE users (
    id INT NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
	PRIMARY KEY (id)
);
