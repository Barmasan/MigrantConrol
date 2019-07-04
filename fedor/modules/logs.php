<?
$sql = "SELECT Managers.username, Logs.manager_id, Logs.type, Logs.date FROM Managers, Logs WHERE Managers.ID = Logs.manager_id";
if(isset($_POST['filter_log'])){
$fil = htmlspecialchars($_POST['fil_log']);
  $sql = $sql. ' AND Managers.ID = '.$fil;
}
if(isset($_POST['filter_clear'])){
  $sql = "SELECT Managers.username, Logs.manager_id, Logs.type, Logs.date FROM Managers, Logs WHERE Managers.ID = Logs.manager_id";
}
?>
<form action="#logs" method="post">
  <div class="col-xs-6">
    <select id="fil_log" name="fil_log" class="form-control">
      <?
      $sel_query = $mysqli->query("SELECT Managers.ID, Managers.username FROM Managers");
      while($row = $sel_query->fetch_assoc())
      {
        echo '<option value="'.$row['ID'].'">'.$row['username'].'</option>';
      }
      ?>
    </select>
  </div>
  <button type="submit" name="filter_clear" class="btn btn-default btn-default right">Без фильтра</button>
  <button type="submit" name="filter_log" class="btn btn-default btn-default right">Искать</button>
</form>

<table class="table table-striped col-xs-12">
  <thead>
    <tr>
      <th>Менеджер</th>
      <th>Действие</th>
      <th>Дата</th>
    </tr>
  </thead>
  <tbody>
    <?
      $query = $mysqli->query($sql);
      while($row= $query->fetch_assoc())
      {
        echo '<tr>';
          echo '<td>'.$row['username'].'</td>';
          echo '<td>'.$row['type'].'</td>';
          echo '<td>'.$row['date'].'</td>';
        echo '</tr>';
      }
    ?>
  </tbody>
</table>
