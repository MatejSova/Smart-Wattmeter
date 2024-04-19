<h1>Aktuálne namerané hodnoty:</h1>
<div class="actual_graphs">
<div class="values">
<?php
require_once('db.php');
if (isset($_GET['id']))
		$id = $_GET['id'];
echo "<h3>Zariadenie: " . $id . "</h3>" ;
$sql = "SELECT casova_znamka, napatie, prud_senzor_1, prud_senzor_2, prud_senzor_3 FROM " . $id . " ORDER BY id DESC LIMIT 15";
$result = mysqli_query($conn, $sql);

$index = 0;
$array_casova_znamka = []; 
$array_napatie = []; 
$array_prud_senzor_1 = []; 
$array_prud_senzor_2 = []; 
$array_prud_senzor_3 = []; 
$array_spotreba_senzor_1 = [];
$array_spotreba_senzor_2 = [];
$array_spotreba_senzor_3 = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $row_casova_znamka = $row["casova_znamka"];
		$array_casova_znamka[$index] = date('H:i:s', strtotime($row["casova_znamka"]));
        $row_napatie = $row["napatie"];
		$array_napatie[$index] = $row["napatie"];
        $row_prud_senzor_1 = $row["prud_senzor_1"];
		$array_prud_senzor_1[$index] = $row["prud_senzor_1"];		
        $row_prud_senzor_2 = $row["prud_senzor_2"];
		$array_prud_senzor_2[$index] = $row["prud_senzor_2"];
        $row_prud_senzor_3 = $row["prud_senzor_3"];
		$array_prud_senzor_3[$index] = $row["prud_senzor_3"];
		$array_spotreba_senzor_1[$index] = $row["napatie"] * $row["prud_senzor_1"];
		$array_spotreba_senzor_2[$index] = $row["napatie"] * $row["prud_senzor_2"];
		$array_spotreba_senzor_3[$index] = $row["napatie"] * $row["prud_senzor_3"];
		$array_spotreba[$index] = $array_spotreba_senzor_1[$index] + $array_spotreba_senzor_2[$index] + $array_spotreba_senzor_3[$index];
		$index++;	  
    }
    $result->free();
	mysqli_close($conn);
	
	$prud = reset($array_prud_senzor_1) + reset($array_prud_senzor_2) + reset($array_prud_senzor_3);
	$spotreba = reset($array_spotreba_senzor_1) + reset($array_spotreba_senzor_2) + reset($array_spotreba_senzor_3);

	echo '<h5>Posledné meranie: ' . reset($array_casova_znamka) . '</h5>';
	echo '<h5>Napätie [V]: ' . reset($array_napatie). 
	 '</br>Celkový prúd [A]: ' . $prud .
	 '</br>Celkový výkon [W]: ' . $spotreba  .
     '</h5>';

	echo '<h5>Prúd č.1 [A]: ' . reset($array_prud_senzor_1) . '</br>  Výkon [W]: ' . reset($array_spotreba_senzor_1) . '</h5>';
	echo '<h5>Prúd č.2 [A]: ' . reset($array_prud_senzor_2) . '</br>  Výkon [W]: ' . reset($array_spotreba_senzor_2) . '</h5>';
	echo '<h5>Prúd č.3 [A]: ' . reset($array_prud_senzor_3). '</br>  Výkon [W]: ' . reset($array_spotreba_senzor_3) . '</h5>';
	
}else
{
	echo "Nie su zaznamenané žiadne údaje.";
}
?> 

</div>
<div class="two"><canvas id="consumptionChart" style="width:100%;max-width:1600px"></canvas></div>
<div class="three"><canvas id="voltageChart" style="width:100%;max-width:1600px"></canvas></div>
<div class="four"><canvas id="currentChart" style="width:100%;max-width:1600px"></canvas></div>
</div>

<script>
Chart.defaults.global.defaultFontColor = "#A7AFB2";
setTimeout(function(){
   window.location.reload(1);
}, 30000);

var xTimestamp = <?php echo json_encode(array_reverse($array_casova_znamka)); ?>;
var yVoltage = <?php echo json_encode(array_reverse($array_napatie)); ?>;
var yCurrent1 = <?php echo json_encode(array_reverse($array_prud_senzor_1)); ?>;
var yCurrent2 = <?php echo json_encode(array_reverse($array_prud_senzor_2)); ?>;
var yCurrent3 = <?php echo json_encode(array_reverse($array_prud_senzor_3)); ?>;
var yConsumption = <?php echo json_encode(array_reverse($array_spotreba)); ?>;

new Chart("consumptionChart", {
  type: "line",
  data: {
    labels: xTimestamp,
    datasets: [{ 
      data: yConsumption,
      borderColor: "red",
      fill: true
    }]
  },
  options: {
	  scales: {
		yAxes: [{
			ticks: {
				beginAtZero: true,
				min: 0
				   }
               }]
             },
    legend: {display: false},
	title: {
      display: true,
      text: "Výkon [W]"
    }
  }
});

new Chart("voltageChart", {
  type: "line",
  data: {
    labels: xTimestamp,
    datasets: [{ 
      data: yVoltage,
      borderColor: "red",
      fill: true
    }]
  },
  options: {
	  scales: {
		yAxes: [{
			ticks: {
				beginAtZero: true,
				min: 217
				   }
               }]
             },
    legend: {display: false},
	title: {
      display: true,
      text: "Napätie [V]"
    }
  }
});

new Chart("currentChart", {
  type: "line",
  data: {
    labels: xTimestamp,
    datasets: [{ 
	  label: 'Prúd č.1',
      data: yCurrent1,
      borderColor: "red",
      fill: false
    },{
	  label: 'Prúd č.2',	
      data: yCurrent2,
      borderColor: "green",
      fill: false
    },{
	  label: 'Prúd č.3',	
      data: yCurrent3,
      borderColor: "blue",
      fill: false
    }]
  },
  options: {
	 scales: {
		yAxes: [{
			ticks: {
				beginAtZero: true,
				min: 0
				   }
               }]
             }, 
    legend: {display: true},
	title: {
      display: true,
      text: "Prúd [A]"
    }
  }
});
</script>
