<?php
session_start();

require_once('db.php');

if ( !isset($_POST['username'], $_POST['email'], $_POST['password']) ) {
	exit('Please fill both the username and password fields!');
}

if ($stmt = $conn->prepare('SELECT username FROM users WHERE username = ?')) {
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();
if ($stmt->num_rows > 0) {	
	$message = 'Použivateľ už existuje, zmeň meno !';
    echo "<SCRIPT> //not showing me this
        alert('$message')
        window.location.replace('register.php');
    </SCRIPT>";
	$stmt->close();
    mysqli_close($conn);
}
else{
	$stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
	$stmt->bind_param('sss', $_POST['username'], password_hash($_POST['password'], PASSWORD_DEFAULT), $_POST['email']);
    $stmt->execute();
	$message = 'Úspešne si sa zaregistroval, pokračuj prihlásením !';
    echo "<SCRIPT> //not showing me this
        alert('$message')
        window.location.replace('index.php');
    </SCRIPT>";
}
	$stmt->close();
}
?>

