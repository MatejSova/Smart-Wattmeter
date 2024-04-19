<h3>Zariadenia</h3>
<div class="devices">
<?php
require_once('db.php');

$sql = "SELECT id, type FROM devices WHERE id_user ='" . htmlspecialchars($_SESSION['id'], ENT_QUOTES) . "'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
  // output data of each row
  echo '<table class="tb">';
  echo '<tr class="row">';
  while($row = mysqli_fetch_assoc($result)) {
    echo '<td><a href="device.php?id='. $row["type"],$row["id"]  .'"><img src="images/smartmeter.jpg" width="135" height="135"></br>'. $row["type"] .' '. $row["id"] .'</a></td>';
  }
  echo '</tr>';
  echo '<tr class="row">';
  echo '<td></br><a href="main.php?page=newdevice"><img src="images/adddevice.png" width="135" height="135"></br>Pridaj zariadenie</a></td>';
  echo '</tr>';
} else {
  echo "Nemáš žiadne Zariadenia";
  echo '<a href="main.php?page=newdevice">Pridaj zariadenie</a>';
}
echo '</table>';

mysqli_close($conn);
?> 
</div>






