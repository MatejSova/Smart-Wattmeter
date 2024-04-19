<?php
require_once('header.php');
?>
		<div class="register">
			<h1>Registrácia</h1>
			<form action="registration.php" method="post">
				<input type="text" name="username" placeholder="Prihlasovacie meno" id="username" required>
				<input type="email" name="email" placeholder="E-mail" id="email" required>
				<input type="password" name="password" placeholder="Heslo" id="password" onChange="onChange()" required>
				<input type="password" name="confirm_password" placeholder="Znovu zadaj heslo" id="confirm_password" onChange="onChange()" required>
				<span id='message'></span>
				<input type="submit" value="Registrácia">
			</form>
		</div>
<?php
require_once('footer.php');
?>

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