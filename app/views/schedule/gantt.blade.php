<?php
function dateToJsStr($date)
{
	return sprintf('new Date(%d)', ($date->getTimestamp() * 1000) - 3600000);
}
$defaultCategory = 'AAULAN';
$categories = array("CS:GO", "LoL", "Hearthstone", "PUBG", "Pizza", "Midnight Fun", "Trackmania");
$curDay = null;
$now = \Carbon\Carbon::now('Europe/Amsterdam');
?>

<!doctype html>

<html lang="en">
<head>
	<meta charset="utf-8">
	<title>AAULAN - Schedule</title>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script type="text/javascript">
	  google.charts.load('current', {'packages':['timeline']});
	  google.charts.setOnLoadCallback(drawChart);
	  function drawChart() {
		var container = document.getElementById('gantt');
		var chart = new google.visualization.Timeline(container);
		var dataTable = new google.visualization.DataTable();

		dataTable.addColumn({ type: 'string', id: 'Room' });
		dataTable.addColumn({ type: 'string', id: 'Event' });
		dataTable.addColumn({ type: 'date', id: 'Start' });
		dataTable.addColumn({ type: 'date', id: 'End' });
		dataTable.addRows([
		  @foreach ($items as $item)
			<?php
				$ends = $item->ends;
				if (!$ends)
					$ends = $lan->closes;
				$name = $item->name;
                $category = $defaultCategory;
				foreach ($categories as $value)
				{
					if (strpos($name, $value) !== false)
						$category = $value;
				}
			?>
			['{{ $category }}', '{{ $name }}', {{ dateToJsStr($item->starts) }}, {{ dateToJsStr($ends) }} ],
		  @endforeach]);

		// year, month, dayh, 

		chart.draw(dataTable);
	  }
	</script>
</head>

<body>
  <div id="gantt" style="height: 1000px;"></div>
</body>
</html>