<div class="values">
<?php
require_once('../db.php');

$index = 0;
$monthly_consumption = 0;
$month = 0;
$month = $_POST['month'];
$device = 0;
$device = $_POST['device'];

$start_date = '2024-'. $month . '-01';
$end_date = '2024-'. $month . '-31';

$sql = "SELECT casova_znamka,
		COUNT(id) AS total_measurement,
		SUM(napatie) AS total_voltage,
		SUM(prud_senzor_1) AS total_current_1,
		SUM(prud_senzor_2) AS total_current_2,
		SUM(prud_senzor_3) AS total_current_3
		FROM ". $device ." WHERE casova_znamka BETWEEN'". $start_date."' AND'". $end_date."' GROUP BY day(casova_znamka) ORDER BY casova_znamka ASC";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $row_id = $row["total_measurement"];
        $row_casova_znamka = date('Y-m-d', strtotime($row["casova_znamka"]));
		$array_date[$index] = date('Y-m-d', strtotime($row["casova_znamka"]));
		$check_month = date('m', strtotime($row["casova_znamka"]));
        $row_napatie = $row["total_voltage"];
		$row_prud_senzor_1 = $row['total_current_1'];
		$row_prud_senzor_2 = $row['total_current_2'];
		$row_prud_senzor_3 = $row['total_current_3'];
		$array_consumption[$index] = ((($row_prud_senzor_1/$row_id + $row_prud_senzor_2/$row_id + $row_prud_senzor_3/$row_id) * $row_napatie/$row_id)/1000) *24;
        $monthly_consumption += ((($row_prud_senzor_1/$row_id + $row_prud_senzor_2/$row_id + $row_prud_senzor_3/$row_id) * $row_napatie/$row_id)/1000) *24;
		$index++;
    }
    $result->free();
	echo '<h5>Celková spotreba: ' . number_format($monthly_consumption,2,',','') . ' kWh</h5>';

}
mysqli_close($conn);

?> 

<div class="history_graphs">
<canvas id="consumptionChart" style="width:100%;max-width:1300px"></canvas>
</div>
</div>


<script>
Chart.defaults.global.defaultFontColor = "#A7AFB2";
var xDate = <?php echo json_encode(array_values($array_date)); ?>;
var yConsumption = <?php echo json_encode(array_values($array_consumption)); ?>;

new Chart("consumptionChart", {
  type: "bar",
  data: {
    labels: xDate,
    datasets: [{
      backgroundColor: "white",
      data: yConsumption
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
      text: "Spotreba [kWh]"
    }
  }
});
</script>