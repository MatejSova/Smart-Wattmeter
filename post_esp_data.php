<?php
$dbname = "merania";
$username = "esp";
$password = "98AvorevuaN88";
$api_key= $zariadenie = $napatie = $value1 = $prud_senzor_1 = $prud_senzor_2 = $prud_senzor_3 = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli($servername, $username, $password, $dbname);
    $sql = "SELECT id, type FROM devices WHERE api_key = '".$_POST["api_key"]."'";
    $result = $conn->query($sql);
    if (mysqli_num_rows($result) > 0) {
      while($row = mysqli_fetch_assoc($result)) {
        $table = $row["type"]. $row["id"];
        $zariadenie = test_input($_POST["zariadenie"]);
        $napatie = test_input($_POST["napatie"]);
        $prud_senzor_1 = test_input($_POST["prud_senzor_1"]);
        $prud_senzor_2 = test_input($_POST["prud_senzor_2"]);
        $prud_senzor_3 = test_input($_POST["prud_senzor_3"]);
        
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        
        $sql = "INSERT INTO ".$table." (zariadenie, napatie, prud_senzor_1, prud_senzor_2, prud_senzor_3)
        VALUES ('" . $zariadenie . "', '" . $napatie . "', '" . $prud_senzor_1 . "', '" . $prud_senzor_2 . "', '" . $prud_senzor_3 . "')";
        
        if ($conn->query($sql) === TRUE) {
            echo "Záznam bol úspešne zapísany !";
        } 
        else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        $conn->close();
}
    } else {
        echo "Neexistujúci api-key!";
    }

}
else {
    echo "Niesu poslané žiadné dáta cez HTTP POST.";
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}