<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.php');
	exit;
}
if (isset($_GET['id']))
		$id = $_GET['id'];

require_once('header.php');
?>

<div class="header">
  <h1>Smart Wattmeter v. 1.0</h1>
  <hr/>
</div>

<div class="content">
  <div class="menu">
	<h2>Vitaj, <?=htmlspecialchars($_SESSION['name'], ENT_QUOTES)?>!</h2>
    <a href="device.php?page=actual&id=<?php echo $id?>">Aktuálna spotreba</a>
    <a href="device.php?page=history&id=<?php echo $id?>">História spotreby</a>
	<a href="main.php?page=info">Späť</a>
	<a href="logout.php">Odhlásiť sa</a>
  </div>
  <div class="main">
  <?php
	if (isset($_GET['page']))
		$page = $_GET['page'];
	else
    $page = 'actual';

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
