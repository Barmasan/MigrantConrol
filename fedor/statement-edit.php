<?php session_start();?>
<?php require_once("functions/connection.php");?>
<?php include("functions/logs.php")?>
<?
if(!isset($_GET["st_edit"])){
  header("Location: admin.php");
  exit;
}
$previous = "javascript:history.go(-1)";
$st_id = $_GET["st_id"];
$manager_id = $_SESSION['manager_id'];
if(isset($_POST["edit_statement"])){
  $FIO = htmlspecialchars($_POST['FIO']);
  $gender = htmlspecialchars($_POST['gender']);
  $birthday = htmlspecialchars($_POST['birthday']);
  $workpoint = htmlspecialchars($_POST['workpoint']);
  $citizenship = htmlspecialchars($_POST['citizenship']);

  if(!isset($_POST['ФИО'])){
    $sql="UPDATE Statements SET manager_id = '$manager_id', FIO = '$FIO', gender = '$gender', birthday = '$birthday', workpoint = '$workpoint', citizenship = '$citizenship', children='' WHERE ID = '$st_id'";
    $result= $mysqli->query($sql);
  }
  else{
    foreach ($_POST['ФИО'] as $key => $item) {
      $child[] = array(
        'ФИО' => htmlspecialchars($_POST['ФИО'][$key]),
        'Пол' => htmlspecialchars($_POST['Пол'][$key]),
        'День рождения' => htmlspecialchars($_POST['День_рождения'][$key]),
        'Место работы' => htmlspecialchars($_POST['Место_работы'][$key]),
      );
    }
    $children = json_encode($child, JSON_UNESCAPED_UNICODE);
    $sql="UPDATE Statements SET manager_id = '$manager_id', FIO = '$FIO', gender = '$gender', birthday = '$birthday', workpoint = '$workpoint', citizenship = '$citizenship', children = '$children' WHERE ID = '$st_id'";
    $result= $mysqli->query($sql);
  }
  if($result){
    $m_type = 'success';
    $message = "Заявка успешно обновлена";
    add_log($manager_id, "Отредактировал заявку №". $st_id);
  } else {
    $m_type = 'warning';
    $message = "Ошибка в системе!";
  }
}
if(isset($_POST["edit_status"])){
  $st_name = $_POST['status_number'];
  switch ($_POST['status_number']) {
    case 'st-1':
      $status = array(
        'Статус' => 'Подал заявление об участии в Государственной программе',
        'Номер' =>  htmlspecialchars($_POST['state_number']),
        'Дата' => htmlspecialchars($_POST['state_date'])
      );
      $statusinfo = json_encode($status, JSON_UNESCAPED_UNICODE);
      break;
    case 'st-2':
      $status = array(
        'Статус' => 'Направлен запрос в МВК района',
        'Район' =>  htmlspecialchars($_POST['status_region'])
      );
      $statusinfo = json_encode($status, JSON_UNESCAPED_UNICODE);
      break;
    case 'st-3':
      $status = array(
        'Статус' => 'Принято решение о согласовании',
        'Номер' => htmlspecialchars($_POST['state_number']),
        'Дата' => htmlspecialchars($_POST['state_date']),
        'Район' =>  htmlspecialchars($_POST['status_region']),
        'Причина_согласия' => htmlspecialchars($_POST['state_reason'])
      );
      $statusinfo = json_encode($status, JSON_UNESCAPED_UNICODE);
      break;
    case 'st-4':
      $status = array(
        'Статус' => 'Принято решение об отказе',
        'Номер' => htmlspecialchars($_POST['state_number']),
        'Дата' => htmlspecialchars($_POST['state_date']),
        'Район' =>  htmlspecialchars($_POST['status_region']),
        'Причина_отказа' => htmlspecialchars($_POST['state_reason'])
      );
      $statusinfo = json_encode($status, JSON_UNESCAPED_UNICODE);
      break;
  }
  $sql="UPDATE Statuses SET name = '$st_name', statusinfo = '$statusinfo' WHERE statement_id = '$st_id'";
  $result= $mysqli->query($sql);
  if($result){
    $m_type = 'success';
    $message = "Статус заявки успешно изменен";
    add_log($manager_id, "Изменил статус заявки №". $st_id);
  } else {
    $m_type = 'warning';
    $message = "Ошибка в системе!";
  }

}
$query = $mysqli->query("SELECT * FROM Statements WHERE ID = '$st_id'");
$result= $query->fetch_assoc();
$children = json_decode($result['children']);
?>
<?php include("modules/header.php"); ?>
<?php include("modules/massages.php"); ?>
<div class="container st_info">
  <h2 class="col-xs-11 col-xs-offset-1">Заявление № <?php echo $result['ID']; ?>. От <?php echo $result['date'] ?></h2>
  <div class="state-info col-xs-11 col-xs-offset-1">
    <form method="post" role="form" class="form-horizontal">
      <div class="man">
        <h3>Заявитель</h3>
        <div class="form-group">
          <label class="col-xs-3 control-label">ФИО</label>
          <div class="col-xs-9">
            <input type="text" class="form-control" name="FIO" value="<? echo $result['FIO'];?>">
          </div>
        </div>
        <div class="form-group">
          <label class="col-xs-3 control-label">Пол</label>
          <div class="col-xs-9">
            <div class="radio">
              <label>
                <input type="radio" name="gender" value="male" <? echo ($result['gender'] == 'male') ? 'checked' : ''; ?>>
                Мужской
              </label>
            </div>
            <div class="radio">
              <label>
                <input type="radio" name="gender" value="female" <? echo ($result['gender'] == 'female') ? 'checked' : ''; ?>>
                Женский
              </label>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="col-xs-3 control-label">День рождения</label>
          <div class="col-xs-9">
            <input type="text" class="form-control" name="birthday" value="<? echo $result['birthday'];?>">
          </div>
        </div>
        <div class="form-group">
          <label class="col-xs-3 control-label">Место работы</label>
          <div class="col-xs-9">
            <input type="text" class="form-control" name="workpoint" value="<? echo $result['workpoint'];?>">
          </div>
        </div>
        <div class="form-group">
          <label class="col-xs-3 control-label">Гражданство</label>
          <div class="col-xs-9">
            <input type="text" class="form-control" name="citizenship" value="<? echo $result['citizenship'];?>">
          </div>
        </div>
      </div>
      <div class="child">
        <h3>Родственники</h3>
        <?
        if(!empty($children)){
          $res = json_decode($result['children']);
          foreach ($res as $key => $num) {
            echo "<h4>Родственник</h4>";
            foreach ($num as $child => $value) {
              echo '<div class="form-group">';
                echo '<label class="col-xs-3 control-label">'.$child.'</label>';
                echo '<div class="col-xs-9">';
                  switch ($value) {
                    case 'Мужской':
                      echo '<div class="radio">';
                        echo '<label>';
                          echo '<input type="radio" name="Пол['.$key.']" value="Мужской" checked>';
                          echo "Мужской";
                        echo "</label>";
                      echo "</div>";
                      echo '<div class="radio">';
                        echo '<label>';
                          echo '<input type="radio" name="Пол['.$key.']" value="Женский">';
                          echo "Женский";
                        echo "</label>";
                      echo "</div>";
                      break;
                    case 'Женский':
                      echo '<div class="radio">';
                        echo '<label>';
                          echo '<input type="radio" name="Пол['.$key.']" value="Мужской">';
                          echo "Мужской";
                        echo "</label>";
                      echo "</div>";
                      echo '<div class="radio">';
                        echo '<label>';
                          echo '<input type="radio" name="Пол['.$key.']" value="Женский" checked>';
                          echo "Женский";
                        echo "</label>";
                      echo "</div>";
                      break;
                    default:
                      echo '<input type="text" class="form-control" name="'.$child.'[]" value="'.$value.'">';
                      break;
                  }
                echo '</div>';
              echo '</div>';
            }
          }
        }else{
          echo "<h4>Нет детей</h4>";
        }
        ?>
      </div>
      <a id="add_child" name="add_statement" class="btn btn-default btn-default">Добавить ребенка</a>
      <button type="submit" class="btn btn-md btn-success right" name="edit_statement">Сохранить изменения</button>
    </form>
    <form class="status_edit" method="post" role="form">
      <?
      $result = $mysqli->query("SELECT name, statusinfo FROM Statuses WHERE statement_id = '$st_id'");
      $row= $result->fetch_assoc();
      $statusinfo = json_decode($row['statusinfo']);
      $active_status = $row['name'];
      include('modules/status_select.php');
      ?>
    <button type="submit" class="btn btn-md btn-success right" name="edit_status">Изменить статус</button>
    <a href="<? echo $previous; ?>" type="submit" class="btn btn-md btn-danger" name="back">Назад</a>
    </form>
  </div>
</div>
<?php include("modules/footer.php"); ?>
