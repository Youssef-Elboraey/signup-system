<?php

require "Connector.php";

function Signup() {

	$Connection = $GLOBALS['Connection'];

	// Collect User Data
	$UID = rand(000000 , 999999);
	$fname = $_POST["Fname"];
	$lname = $_POST["Lname"];
	$email = $_POST["E-Mail"];
	$passwd = md5($_POST["passwd"]);
	$birthday = $_POST["birthDay"];

	$sql = $Connection->query("INSERT INTO `users`(
		`id` , `First Name` , `Last Name` , `BirthDay`)
		VALUES
		($UID , '$fname' , '$lname' , '$birthday');");

	$Connection->query("INSERT INTO `credentials` VALUES ($UID, '$email' , '$passwd');");

	header("location:/User/Login/");

}

function Login() {

	$Connection = $GLOBALS['Connection'];

	$email = $_POST["email"];
	$passwd= md5($_POST["pass"]);

	$query = $Connection->prepare("SELECT * FROM credentials WHERE `email` = ?");
	$query->execute([$email]);

	$data = $query->fetchAll();

	if (count($data) == 0) {

		echo "User Not Found, Please " . "<a href='/'>Signup</a>";

	} else {
		if ($passwd == $data[0]["password"]) {

			$UID = $data[0]['user_id'];

			$user = $Connection->prepare("SELECT `First Name` FROM users WHERE id = ?");
			$user->execute([$UID]);

			$userInfo = $user->fetchAll();

			echo "Welcome Back, " . $userInfo[0][0];
		} else {
			echo "E-Mail/Password Is Incorrect";
		}
	}

	// print_r($data);

}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	if (isset($_POST["signup"])) {
		Signup();
	} elseif (isset($_POST["login"])) {
		Login();
	} else {
		header("location:/");
	}

} else {
	header("location:/");
}

?>