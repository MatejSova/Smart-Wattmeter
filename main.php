<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.php');
	exit;
}
require_once('header.php');
?>

<div class="header">
  <h1>Smart Wattmeter v. 1.0</h1>
  <hr/>
</div>
<div class="content">
  <div class="menu">
	<h2>Vitaj, <?=htmlspecialchars($_SESSION['name'], ENT_QUOTES)?>!</h2>
    <a href="main.php?page=info">Zariadenia</a>
    <a href="main.php?page=account">Správa účtu</a>
	<a href="logout.php">Odhlásiť sa</a>
  </div>
  <div class="main">
  <?php
	if (isset($_GET['page']))
		$page = $_GET['page'];
	else
    $page = 'info';

	if (preg_match('/^[a-z]+$/', $page))
	{
		$insert = include('subpages/' . $page . '.php');
		if (!$insert)
			echo('Podstránka nenájdená');
	}
	else
		echo('Neplatný parameter.');
	?>    
  </div>
</div>
<?php
require_once('footer.php');
?>
