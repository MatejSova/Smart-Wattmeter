<?php
require_once('db.php');

$sql = "SELECT id, username, email, password FROM users WHERE username='" . htmlspecialchars($_SESSION['name'], ENT_QUOTES) . "'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
  while($row = mysqli_fetch_assoc($result)) {
	$id = $row["id"];
	$name = $row["username"];
	$email = $row["email"];
  }
} else {
  echo "0 výsledkov";
}

mysqli_close($conn);
?>

<div class="profile">
			<h1>Správa účtu</h1>
			<form action="update.php" method="post">
				<input type="hidden" name="id" value="<?php echo $id; ?>" />
				<input type="text" name="username" placeholder="<?php echo $name; ?>" id="username" ><br>
				<input type="email" name="email" placeholder="<?php echo $email; ?>" id="email" ><br>
				<input type="password" name="password" placeholder="Nové heslo" id="password" > </br>
				<input type="password" name="confirm_password" placeholder="Znovu zadaj heslo" id="confirm_password" onChange="onChange()"> </br>
				<span id='message'></span>
				<input type="submit" value="Zmeň údaje">
			</form>
		</div>
		
<script>
function onChange() {
  const password = document.querySelector('input[name=password]');
  const confirm_password = document.querySelector('input[name=confirm_password]');
  if (confirm_password.value === password.value) {
    confirm_password.setCustomValidity('');
  } else {
    confirm_password.setCustomValidity('Heslá sa nezhodujú');
  }
}
</script>