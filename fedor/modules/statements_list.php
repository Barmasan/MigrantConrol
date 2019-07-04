<?
if($manager_id != 0){
  $sql = "SELECT Statements.ID, Statements.FIO, Managers.username, Statuses.name FROM Statuses, Statements, Managers WHERE Statements.manager_id = '$manager_id' AND Statements.active = 1 AND Statuses.statement_id = Statements.ID AND Managers.ID = '$manager_id'";
}
else {
  $sql = "SELECT Statements.ID, Statements.FIO, Managers.username, Statuses.name FROM Statuses, Statements, Managers WHERE Statements.active = 1 AND Statuses.statement_id = Statements.ID AND Managers.ID = Statements.manager_id";
}
if(isset($_POST['filter'])){
$fil = htmlspecialchars($_POST['fil']);
  switch ($_POST['how']) {
    case 1:
      $sql = $sql. ' AND Statements.ID = "'.$fil.'" ';
      break;
    case 2:
      $sql = $sql. ' AND Statements.FIO = "'.$fil.'" ';
      break;
    case 3:
      $sql = $sql. ' AND Statuses.name = "'.$fil.'" ';
      break;
  }
}
if(isset($_POST['filter_clear'])){
  if($manager_id != 0){
    $sql = "SELECT Statements.ID, Statements.FIO, Managers.username, Statuses.name FROM Statuses, Statements, Managers WHERE Statements.manager_id = '$manager_id' AND Statements.active = 1 AND Statuses.statement_id = Statements.ID AND Managers.ID = '$manager_id'";
  }
  else {
    $sql = "SELECT Statements.ID, Statements.FIO, Managers.username, Statuses.name FROM Statuses, Statements, Managers WHERE Statements.active = 1 AND Statuses.statement_id = Statements.ID AND Managers.ID = Statements.manager_id";
  }
}
  $query = $mysqli->query($sql);
?>
<form action="#statements" method="post">
  <div class="col-xs-4">
    <select id="filter_st" name="how" class="form-control">
      <option value="1" selected>По номеру</option>
      <option value="2">По ФИО</option>
      <option value="3">По статусу</option>
    </select>
  </div>
  <div class="col-xs-6">
    <select name="fil" class="form-control fil_in fil_in1">
      <?
      while($row = $query->fetch_assoc())
      {
        echo '<option value="'.$row['ID'].'">'.$row['ID'].'</option>';
      }
      ?>
    </select>
    <select name="fil" class="form-control fil_in fil_in2" style="display: none;" disabled>
      <?
      $query = $mysqli->query($sql);
      while($row = $query->fetch_assoc())
      {
        echo '<option value="'.$row['FIO'].'">'.$row['FIO'].'</option>';
      }
      ?>
    </select>
    <select name="fil" class="form-control fil_in fil_in3" style="display: none;" disabled>
      <option value="st-1" selected>Подал заявление об участии в Государственной программе</option>
      <option value="st-2">Направлен запрос в МВК района</option>
      <option value="st-3">Принято решение о согласовании</option>
      <option value="st-4">Принято решение об отказе</option>
    </select>
  </div>
  <button type="submit" name="filter_clear" class="btn btn-default btn-default right">Без фильтра</button>
  <button type="submit" name="filter" class="btn btn-default btn-default right">Искать</button>
</form>

<table class="table table-striped">
  <thead>
    <tr>
      <th>Номер</th>
      <th>ФИО</th>
      <th>Менеджер</th>
      <th>Статус</th>
      <th class="actions">Действия</th>
    </tr>
  </thead>
  <tbody>
    <?
      $st_name = '';
      $green = '';
      $query = $mysqli->query($sql);
      while($row= $query->fetch_assoc())
      {
        switch ($row['name']) {
          case 'st-1':
            $st_name = 'Подал заявление об участии в Государственной программе';
            $green = '';
            break;
          case 'st-2':
            $st_name = 'Направлен запрос в МВК района';
            $green = '';
            break;
          case 'st-3':
            $st_name = 'Принято решение о согласовании';
            $green = 'style="background-color: lightgreen;"';
            break;
          case 'st-4':
            $st_name = 'Принято решение об отказе';
            $green = '';
            break;
        }
        echo '<tr '.$green.'>';
          echo '<td>'.$row['ID'].'</td>';
          echo '<td>'.$row['FIO'].'</td>';
          echo '<td>'.$row['username'].'</td>';
          echo '<td>'.$st_name.'</td>';
          echo '<td>';
            echo '<form method="GET" action="statement-info.php">';
              echo '<input type="hidden" name="st_id" value="'.$row['ID'].'">';
              echo '<button type="submit" class="btn btn-sm btn-info" name="st_info"><span>Подробнее<span></button>';
            echo '</form>';
            echo '<form method="GET" action="statement-edit.php">';
              echo '<input type="hidden" name="st_id" value="'.$row['ID'].'">';
              echo '<button type="submit" class="btn btn-sm btn-warning" name="st_edit"><span>Редактировать<span></button>';
            echo '</form>';
            echo '<form method="POST">';
              echo '<input type="hidden" name="st_id" value="'.$row['ID'].'">';
              echo '<button type="submit" class="btn btn-sm btn-danger" name="st_del"><span>Удалить<span></button>';
            echo '</form>';
          echo '</td>';
        echo '</tr>';
      }
    ?>
  </tbody>
</table>
