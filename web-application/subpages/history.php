<?php
require_once('db.php');
if (isset($_GET['id']))
		$id = $_GET['id'];
echo "<h1>Zariadenie: " . $id . "</h1>" ;
?>

<h3>Vyber si mesiac:</h3>
<select name="months" id="months">
  <option value="01">Január</option>
  <option value="02">Február</option>
  <option value="03">Marec</option>
  <option value="04">Apríl</option>
  <option value="05">Máj</option>
  <option value="06">Jún</option>
  <option value="07">Júl</option>
  <option value="08">August</option>
  <option value="09">September</option>
  <option value="10">Október</option>
  <option value="11">November</option>
  <option value="12">December</option>
</select>

<div id="result"></div>

<script type="text/javascript">
$('#months').change(function() {
    var val1 = $('#months').val();
    var val2 = <?php echo json_encode($id); ?>;
    $.ajax({
        type: 'POST',
        url: 'subpages/months.php',
        data: { month: val1, device: val2 },
        success: function(response) {
            $('#result').html(response);
        }
    });
});
</script>
