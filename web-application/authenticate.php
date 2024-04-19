<?php
session_start();

require_once('db.php');

if ( !isset($_POST['username'], $_POST['password']) ) {
	// Could not get the data that should have been sent.
	exit('Please fill both the username and password fields!');
}

if ($stmt = $conn->prepare('SELECT id, password FROM users WHERE username = ?')) {
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();

if ($stmt->num_rows > 0) {
	$stmt->bind_result($id, $password);
	$stmt->fetch();
	if (password_verify($_POST['password'], $password)) {
		session_regenerate_id();
		$_SESSION['loggedin'] = TRUE;
		$_SESSION['name'] = $_POST['username'];
		$_SESSION['id'] = $id;
		header('Location: main.php');
	} else {
		$message = 'Nesprávne heslo !';
		echo "<SCRIPT> 
        alert('$message')
        window.location.replace('index.php');
    </SCRIPT>";
	}
} else {
	$message = 'Uživateľ neexistuje, zaregistruj sa !';
		echo "<SCRIPT> //not showing me this
        alert('$message')
        window.location.replace('register.php');
    </SCRIPT>";
}
$stmt->free();
}
?>
