<?php
require_once('header.php');
?>
		<div class="login">
			<h1>Prihlásenie</h1>
			<form action="authenticate.php" method="post">
				<input type="text" name="username" placeholder="Prihlasovacie meno" id="username" required>
				<input type="password" name="password" placeholder="Heslo" id="password" required>
				<input type="submit" value="Prihlásenie">
				<a href="register.php" type="registration">Registrácia</a>
			</form>
		</div>
