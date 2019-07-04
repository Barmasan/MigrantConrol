<?php session_start();
  if(!isset($_SESSION["username"])):
  header("location:index.php");
  elseif($_SESSION['username'] != 'admin'):
  header("location:index.php");
  else:
?>
<?php require_once("functions/connection.php"); ?>
<?php require_once('modules/SimpleXLSX.php'); ?>
<?php include("functions/logs.php")?>
<?
$ffem = '';
$fmal = 'checked';
$fFIO = '';
$fbirthday = '';
$fworkpoint = '';
if(isset($_POST['from_file']))
    {
    $uploaddir = 'uploads/';
    $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

    move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile);

    if( $xlsx = SimpleXLSX::parse($uploadfile)) {
      $fFIO = $xlsx->rows()[1][0];
      switch ($xlsx->rows()[1][1]) {
        case 'Male':
          $ffem = '';
          $fmal = 'checked';
          break;
        case 'Female':
          $ffem = 'checked';
          $fmal = '';
          break;

        default:
          $ffem = '';
          $fmal = 'checked';
          break;
      }
      $fbirthday = substr($xlsx->rows()[1][2], 0, -9);
      $fworkpoint = $xlsx->rows()[1][3];

    } else {
      $m_type = 'warning';
      $message = SimpleXLSX::parse_error();
    }
  }
if(isset($_POST["add_manager"])){

  if(!empty($_POST['username']) && !empty($_POST['password'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = md5(htmlspecialchars($_POST['password']));

    $query= $mysqli->query("SELECT * FROM Managers WHERE username = '$username'");
    $numrows = $query->num_rows;
      if($numrows==0){
         $sql="INSERT INTO Managers (username, password) VALUES ('$username', '$password')";
         $result= $mysqli->query($sql);
          if($result){
            $m_type = 'success';
            $message = "Аккаунт менеджера успешно добавлен";
          } else {
            $m_type = 'warning';
            $message = "Ошибка в системе!";
          }
      } else {
        $m_type = 'warning';
        $message = "Такое имя менеджера уже существует!";
      }
  }
}
if(isset($_POST["add_district"])){

  if(!empty($_POST['district'])) {
    $district = htmlspecialchars($_POST['district']);

    $query= $mysqli->query("SELECT * FROM Districts WHERE name = '$district'");
    $numrows = $query->num_rows;
      if($numrows==0){
         $sql="INSERT INTO Districts (name) VALUES ('$district')";
         $result= $mysqli->query($sql);
          if($result){
            $m_type = 'success';
            $message = "Район успешно добавлен";
          } else {
            $m_type = 'warning';
            $message = "Ошибка в системе!";
          }
      } else {
        $m_type = 'warning';
        $message = "Такой Район уже существует!";
      }
  }
}
if(isset($_POST["mg_del"])){
  $mg_id = htmlspecialchars($_POST['mg_id']);

  $query= $mysqli->query("UPDATE Managers SET active = 0 WHERE ID = '$mg_id'");
  if($query){
    $m_type = 'success';
    $message = "Менеджер успешно удален!";
  } else {
    $m_type = 'warning';
    $message = "Ошибка в системе!";
  }
}
if(isset($_POST["add_statement"])){
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
  $manager_id = 0;
  $FIO = htmlspecialchars($_POST['FIO']);
  $gender = htmlspecialchars($_POST['gender']);
  $birthday = htmlspecialchars($_POST['birthday']);
  $workpoint = htmlspecialchars($_POST['workpoint']);
  $citizenship = htmlspecialchars($_POST['citizenship']);

  $query= $mysqli->query("SELECT FIO FROM Statements WHERE FIO = '$FIO'");
  $numrows = $query->num_rows;
    if($numrows==0){
       if(!isset($_POST['ch_FIO'])){
         $sql="INSERT INTO Statements (manager_id, FIO, gender, birthday, workpoint, citizenship) VALUES ('$manager_id', '$FIO', '$gender', '$birthday', '$workpoint', '$citizenship')";
         $result= $mysqli->query($sql);
       }
       else{
         foreach ($_POST['ch_FIO'] as $key => $item) {
           $child[] = array(
             'ФИО' => htmlspecialchars($_POST['ch_FIO'][$key]),
             'Пол' => $_POST['ch_gender'][$key],
             'День рождения' => htmlspecialchars($_POST['ch_birthday'][$key]),
             'Место работы' => htmlspecialchars($_POST['ch_workpoint'][$key]),
           );
         }
         $children = json_encode($child, JSON_UNESCAPED_UNICODE);
         $sql="INSERT INTO Statements (manager_id, FIO, gender, birthday, workpoint, citizenship, children) VALUES ('$manager_id', '$FIO', '$gender', '$birthday', '$workpoint', '$citizenship', '$children')";
         $result= $mysqli->query($sql);
       }
        if($result){
          $new_id_st = $mysqli->insert_id;
          $m_type = 'success';
          $message = "Заявка успешно добавлена";
          add_log($manager_id, "Добавил заявку №".$mysqli->insert_id);
          $sql="INSERT INTO Statuses (statement_id, name, statusinfo) VALUES ('$new_id_st', '$st_name', '$statusinfo')";
          $result= $mysqli->query($sql);
        } else {
          $m_type = 'warning';
          $message = "Ошибка в системе!";
        }
    } else {
      $m_type = 'warning';
      $message = "Такой заявитель уже существует!";
    }
}
if(isset($_POST["st_del"])){
  $st_id = htmlspecialchars($_POST['st_id']);

  $query= $mysqli->query("UPDATE Statements SET active = 0 WHERE ID = '$st_id'");
  if($query){
    $m_type = 'success';
    $message = "Заявление успешно удалено!";
  } else {
    $m_type = 'warning';
    $message = "Ошибка в системе!";
  }
}
?>
<?php include("modules/header.php"); ?>
<?php include("modules/massages.php"); ?>
<div class="container admin">
  <div class="welcome">
    <h2>Добро пожаловать, <?php echo $_SESSION['username'];?>!
      <form action="logout.php" method="POST">
        <button type="submit" class="btn btn-danger">Выйти</button>
      </form>
    </h2>
  </div>
  <div id="main">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs">
      <li class="active"><a href="#managers" data-toggle="tab">Добавить менеджера</a></li>
      <li><a href="#statement_add" data-toggle="tab">Добавить заявление</a></li>
      <li><a href="#statements" data-toggle="tab">Список заявлений</a></li>
      <li><a href="#trash_st" data-toggle="tab">Список удаленных заявлений</a></li>
      <li><a href="#regions" data-toggle="tab">Районы</a></li>
      <li><a href="#logs" data-toggle="tab">Лог действий</a></li>
      <li id='showstats'><a href="#stats" data-toggle="tab">Статистика</a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
      <div class="tab-pane active" id="managers">
        <form role="form" class="col-xs-12" method="POST">
          <div class="form-group col-xs-5">
            <label for="username">Имя менеджера</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Имя менеджера">
          </div>
          <div class="form-group col-xs-5">
            <label for="password">Пароль менеджера</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Пароль менеджера">
          </div>
          <button type="submit" name="add_manager" class="btn btn-default btn-default" style="margin-top: 25px;">Добавить менеджера</button>
        </form>
        <div class="col-xs-12">
          <table class="table table-striped col-xs-12">
            <thead>
              <tr>
                <th>Имя</th>
                <th class="actions">Действия</th>
              </tr>
            </thead>
            <tbody>
              <?
                $query = $mysqli->query("SELECT * FROM Managers WHERE active = 1");
                while($row= $query->fetch_assoc())
                {
                  echo '<tr>';
                    echo '<td>'.$row['username'].'</td>';
                    echo '<td>';
                      echo '<form method="POST">';
                        echo '<input type="hidden" name="mg_id" value="'.$row['ID'].'">';
                        echo '<button type="submit" class="btn btn-sm btn-danger" name="mg_del"><span class="glyphicon glyphicon-trash"><span></button>';
                      echo '</form>';
                    echo '</td>';
                  echo '</tr>';
                }
              ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="tab-pane" id="statement_add">
        <form role="form" class="fr_file" action="#statement_add" method="post" enctype=multipart/form-data>
          <input type="file" name="userfile" id="InputFile">
          <button type="submit" name="from_file" class="btn btn-default">Загрузить из файла</button>
        </form>
        <form role="form" class="add-st col-xs-8 col-xs-offset-2" method="POST">
          <div class="man">
            <h3 class="status-title">Заявитель</h3>
            <div class="form-group">
              <label for="FIO">ФИО</label>
              <input type="text" class="form-control" id="FIO" name="FIO" placeholder="ФИО" value="<?php echo $fFIO; ?>" required>
            </div>
            <div class="form-group">
              <label for="password">Пол заявителя</label>
              <div class="radio">
                <label>
                  <input type="radio" name="gender" id="gender1" value="male" <?php echo $fmal ?>>
                  Мужской
                </label>
              </div>
              <div class="radio">
                <label>
                  <input type="radio" name="gender" id="gender2" value="female" <?php echo $ffem ?>>
                  Женский
                </label>
              </div>
            </div>
            <div class="form-group">
              <label for="birthday">Дата рождения</label>
              <input type="date" class="form-control" id="birthday" name="birthday" value="<?php echo $fbirthday; ?>" required>
            </div>
            <div class="form-group">
              <label for="workpoint">Место работы</label>
              <input type="text" class="form-control" id="workpoint" name="workpoint" placeholder="Место работы" value="<?php echo $fworkpoint; ?>" required>
            </div>
            <select name="citizenship" class="form-control">
              <option selected disabled>Гражданство</option>
              <option>Азербайджан</option>
              <option>Антигуа и Барбуда</option>
              <option>Армения</option>
              <option>Великобритания</option>
              <option>Гонконг</option>
              <option>Казахстан</option>
              <option>Канада</option>
              <option>Кипр</option>
              <option>Киргизия</option>
              <option>Китай</option>
              <option>Латвия</option>
              <option>Мальта</option>
              <option>Португалия</option>
              <option>Республика Беларусь</option>
              <option>Република Молдова</option>
              <option>Россия</option>
              <option>Сент-Китс и Невис</option>
              <option>Сомали</option>
              <option>США</option>
              <option>Таджикистан</option>
              <option>Турция</option>
              <option>Узбекистан</option>
              <option>Украина</option>
              <option>Франция</option>
            </select>
          </div>
          <? include('modules/status_select.php'); ?>
          <a id="add_child" name="add_statement" class="btn btn-default btn-default">Добавить ребенка</a>
          <button type="submit" name="add_statement" class="btn btn-default btn-default right">Добавить заявление</button>
        </form>
      </div>
      <div class="tab-pane" id="statements">
        <? include('modules/statements_list.php'); ?>
      </div>
      <div class="tab-pane" id="trash_st">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Номер</th>
              <th>ФИО</th>
              <th class="actions">Действия</th>
            </tr>
          </thead>
          <tbody>
            <?
              $query = $mysqli->query("SELECT ID, FIO FROM Statements WHERE active = 0");
              while($row= $query->fetch_assoc())
              {
                echo '<tr>';
                  echo '<td>'.$row['ID'].'</td>';
                  echo '<td>'.$row['FIO'].'</td>';
                  echo '<td>';
                    echo '<form method="GET" action="statement-info.php" class="col-xs-4">';
                      echo '<input type="hidden" name="st_id" value="'.$row['ID'].'">';
                      echo '<button type="submit" class="btn btn-sm btn-info" name="st_info"><span class="glyphicon glyphicon-eye-open"><span></button>';
                    echo '</form>';
                  echo '</td>';
                echo '</tr>';
              }
            ?>
          </tbody>
        </table>
      </div>
      <div class="tab-pane" id="regions">
        <form role="form" class="col-xs-12" method="POST">
          <div class="form-group col-xs-10">
            <label for="district">Район</label>
            <input type="text" class="form-control" id="district" name="district" placeholder="Район">
          </div>
          <button type="submit" name="add_district" class="btn btn-default btn-default" style="margin-top: 25px;">Добавить Район</button>
        </form>
        <div class="col-xs-12">
          <table class="table table-striped col-xs-12">
            <thead>
              <tr>
                <th>Название Района</th>
                <th class="actions">Действия</th>
              </tr>
            </thead>
            <tbody>
              <?
                $query = $mysqli->query("SELECT * FROM Districts WHERE active = 1");
                while($row= $query->fetch_assoc())
                {
                  echo '<tr>';
                    echo '<td>'.$row['name'].'</td>';
                    echo '<td>';
                      echo '<form method="POST">';
                        echo '<input type="hidden" name="mg_id" value="'.$row['ID'].'">';
                        echo '<button type="submit" class="btn btn-sm btn-danger" name="mg_del"><span class="glyphicon glyphicon-trash"><span></button>';
                      echo '</form>';
                    echo '</td>';
                  echo '</tr>';
                }
              ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="tab-pane" id="logs">
        <?php include("modules/logs.php"); ?>
      </div>
      <div class="tab-pane" id="stats">
        <?php include("modules/stats.php"); ?>
      </div>
    </div>
  </div>
</div>

<?php include("modules/footer.php"); ?>

<?php endif; ?>
