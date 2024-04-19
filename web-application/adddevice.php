<?php
session_start();
require_once('db.php');

if ( !isset($_POST['device'], $_POST['apikey'])) {
	exit('Vyplň obidva polia ! Meno a heslo');
}
	$stmt = $conn->prepare("INSERT INTO devices (id_user, type, api_key) VALUES (?, ?, ?)");
	$stmt->bind_param('sss', $_SESSION['id'], $_POST['device'], $_POST['apikey']);
    $stmt->execute();
	$last_id = $conn->insert_id;
	echo $last_id;
	
	$sql = "CREATE TABLE ". $_POST['device'].$last_id . " (
	id INT(7) AUTO_INCREMENT PRIMARY KEY,
	casova_znamka TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	napatie FLOAT,
	prud_senzor_1 FLOAT,
	prud_senzor_2 FLOAT,
	prud_senzor_3 FLOAT
	)";
	
	mysqli_query($conn, $sql);
	$message = 'Zariadenie bolo pridané !';
	
    echo "<SCRIPT> //not showing me this
        alert('$message')
        window.location.replace('main.php?=info');
    </SCRIPT>";
	
	$stmt->close();
?>
