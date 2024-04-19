<!DOCTYPE html>
<html><body>
<?php

$servername = "localhost";
$dbname = "merania";
$username = "esp";
$password = "98AvorevuaN88";

$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT id, casova_znamka, zariadenie, napatie, prud_senzor_1, prud_senzor_2, prud_senzor_3 FROM spotreba ORDER BY id DESC";
echo '<table cellspacing="5" cellpadding="5">
      <tr> 
        <td>ID</td> 
        <td>Časová známka</td> 
        <td>Zariadenie</td> 
        <td>Napätie</td> 
        <td>Prúd Senzor 1</td>
        <td>Prúd senzor 2</td> 
        <td>Prúd senzor 3</td>
      </tr>';

if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $row_id = $row["id"];
        $row_casova_znamka = $row["casova_znamka"];
        $row_zariadenie = $row["zariadenie"];
        $row_napatie = $row["napatie"];
        $row_prud_senzor_1 = $row["prud_senzor_1"]; 
        $row_prud_senzor_2 = $row["prud_senzor_2"];
        $row_prud_senzor_3 = $row["prud_senzor_3"];
        echo '<tr> 
                <td>' . $row_id . '</td> 
                <td>' . $row_casova_znamka . '</td>
                <td>' . $row_zariadenie . '</td> 
                <td>' . $row_napatie . '</td> 
                <td>' . $row_prud_senzor_1 . '</td> 
                <td>' . $row_prud_senzor_2 . '</td>
                <td>' . $row_prud_senzor_3 . '</td>
              </tr>';
    }
    $result->free();
}

$conn->close();
?> 
</table>
</body>
</html>