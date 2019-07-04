<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<?
$sql = "SELECT name FROM Districts";
$query = $mysqli->query($sql);
while ($row = $query->fetch_assoc()) {
  foreach ($row as $key => $value) {
    $distr[$value] = 0;
  }
}
$stat = array(
  'Положительно' => 0,
  'Отрицательно' => 0,
);
$workers = array(
  'Работоспособные' => 0,
  'Не Работоспособные' => 0,
  'Работоспособные Родственники' => 0,
  'Не Работоспособные Родственники' => 0,
);
$sql = "SELECT statusinfo FROM Statuses";
$query = $mysqli->query($sql);
while ($row = $query->fetch_assoc()) {
  foreach ($row as $key => $value) {
    $reg = json_decode($value)->Район;
    $st = json_decode($value)->Статус;
    switch ($st) {
      case 'Принято решение о согласовании':
        $stat['Положительно']++;
        break;
      case 'Принято решение об отказе':
        $stat['Отрицательно']++;
        break;
    }
    foreach ($distr as $key1 => $value1) {
      if ($key1 == $reg) {
        $distr[$key1]++;
      }
    }
  }
}
$sql = "SELECT birthday, children FROM Statements";
$query = $mysqli->query($sql);
while ($row = $query->fetch_assoc()) {
  $age = (time() - strtotime($row['birthday'])) / 60 / 60 / 24 / 30 / 12;
  if($age >= 16 && $age <= 64){
    $workers['Работоспособные']++;
  }
  else {
    $workers['Не Работоспособные']++;
  }
  if(!empty($row['children'])){
    $ch = json_decode($row['children']);
    foreach ($ch as $key => $value) {
      $age = (time() - strtotime($value->{'День рождения'})) / 60 / 60 / 24 / 30 / 12;
      if ($age >= 16 && $age <= 64) {
        $workers['Работоспособные Родственники']++;
      }
      else {
        $workers['Не Работоспособные Родственники']++;
      }
    }
  }
}
$sql = "SELECT COUNT(ID) FROM Statements";
$query = $mysqli->query($sql);
$st_num = $query->fetch_assoc()['COUNT(ID)'];
?>
<script type="text/javascript">
  google.charts.load('current', {packages:['corechart']});
  google.charts.setOnLoadCallback(drawChart);
  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      ['Группа', 'Заявления'],
      <?
      foreach ($distr as $key => $value) {
        echo '["'.$key.'",'.$value.'],';
      }
      ?>
    ]);
    var options = {
      title: "Заявления: "+ <?php echo $st_num; ?>,
      bar: {groupWidth: '95%'},
      legend: { position: 'none' },
    };
    var chart = new google.visualization.BarChart(document.getElementById('distr'));
    chart.draw(data, options);
  }
  google.charts.setOnLoadCallback(drawChart1);
  function drawChart1() {
    var data = google.visualization.arrayToDataTable([
      ['Группа', 'Заявления'],
      <?
      foreach ($stat as $key => $value) {
        echo '["'.$key.'",'.$value.'],';
      }
      ?>
    ]);
    var options = {
      title: "Заявления: "+ <?php echo $st_num; ?>,
      bar: {groupWidth: '95%'},
      legend: { position: 'none' },
    };
    var chart = new google.visualization.BarChart(document.getElementById('acdec'));
    chart.draw(data, options);
  }
  google.charts.setOnLoadCallback(drawChart2);
  function drawChart2() {
    var data = google.visualization.arrayToDataTable([
      ['Группа', 'Заявления'],
      <?
      foreach ($workers as $key => $value) {
        echo '["'.$key.'",'.$value.'],';
      }
      ?>
    ]);
    var options = {
      title: "Заявления: "+ <?php echo $st_num; ?>,
      bar: {groupWidth: '95%'},
      legend: { position: 'none' },
    };
    var chart = new google.visualization.BarChart(document.getElementById('workers'));
    chart.draw(data, options);
  }
</script>
<div class="st-st" id="distr"></div>
<div class="st-st" id="acdec"></div>
<div class="st-st" id="workers"></div>
