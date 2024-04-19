<?php
session_start();

require_once('db.php');
$sql="";
if (!empty($_POST['username'])){
	$sql = $sql . " username='". $_POST['username']."'";
	$_SESSION['name'] = $_POST['username'];
}
if (!empty($_POST['email'])){
	if(empty($sql))
	{
	$sql = $sql . " email='". $_POST['email']."'";
	}
	$sql = $sql . " ,email='". $_POST['email']."'";
}
if (!empty($_POST['password'])){
    if(empty($sql))
	{
	$sql = $sql . " password='". password_hash($_POST['password'], PASSWORD_DEFAULT)."'";
	}
	$sql = $sql . " ,password='". password_hash($_POST['password'], PASSWORD_DEFAULT)."'";
}
$sql ="UPDATE users SET" . $sql . " WHERE id=" . $_POST['id'];

if (mysqli_query($conn, $sql)) {
  $message = 'Úspešne si si zmenil údaje !';
} else {
  echo "Error updating record: " . mysqli_error($conn);
}

mysqli_close($conn);

$message = 'Úspešne si si zmenil údaje !';
echo "<SCRIPT>
        alert('$message')
        window.location.replace('main.php?page=account');
    </SCRIPT>";
?>

