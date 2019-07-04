<?php session_start();?>
<?php require_once("functions/connection.php");?>
<?php include("functions/logs.php")?>
<?
if(!isset($_GET["st_info"])){
  header("Location: admin.php");
  exit;
}
$manager_id = $_SESSION['manager_id'];
$st_id = $_GET["st_id"];
$query = $mysqli->query("SELECT * FROM Statements WHERE ID = '$st_id'");
$result= $query->fetch_assoc();
$doc_text = array(
  'ФИО' => $result['FIO'],
  'Пол' => $result['gender'],
  'День рождения' => $result['birthday'],
  'Место работы' => $result['workpoint'],
  'Гражданство' => $result['citizenship'],
  'Дата заявления' => $result['date'],
);
$res = json_decode($result['children']);
$children = json_decode($result['children']);
$previous = "javascript:history.go(-1)";
add_log($manager_id, "Посмотрел заявку №". $st_id);
?>
<?php include("modules/header.php"); ?>
<div class="container">
  <h2 class="col-xs-11 col-xs-offset-1">Заявление № <?php echo $result['ID']; ?>. От <?php echo $result['date'] ?></h2>
  <div class="state-info col-xs-11 col-xs-offset-1">
    <form action="<? echo $previous; ?>" method="post" role="form" class="form-horizontal">
      <div class="man">
        <h3>Заявитель</h3>
        <div class="form-group">
          <label class="col-xs-3 control-label">ФИО</label>
          <div class="col-xs-9">
            <p class="form-control-static"><? echo $result['FIO'];?></p>
          </div>
        </div>
        <div class="form-group">
          <label class="col-xs-3 control-label">Пол</label>
          <div class="col-xs-9">
            <p class="form-control-static">
              <?
              switch ($result['gender']) {
                case 'male':
                  echo 'Мужской';
                  break;
                case 'female':
                  echo 'Женский';
                  break;
              }
              ?>
            </p>
          </div>
        </div>
        <div class="form-group">
          <label class="col-xs-3 control-label">Место работы</label>
          <div class="col-xs-9">
            <p class="form-control-static"><? echo $result['workpoint'];?></p>
          </div>
        </div>
        <div class="form-group">
          <label class="col-xs-3 control-label">Гражданство</label>
          <div class="col-xs-9">
            <p class="form-control-static"><? echo $result['citizenship'];?></p>
          </div>
        </div>
      </div>
      <div class="child">
        <h3>Родственники</h3>
        <?
        if(!empty($children)){
          $count = 1;
          foreach ($res as $key => $num) {
            echo "<h4>Родственник".$count."</h4>";
            foreach ($num as $child => $value) {
              $doc_text['Родственник'.$count.'-'.$child] = $value;
              echo '<div class="form-group">';
                echo '<label class="col-xs-3 control-label">'.$child.'</label>';
                echo '<div class="col-xs-9">';
                  echo '<p class="form-control-static">';
                  switch ($value) {
                    case 'male':
                      echo 'Мужской';
                      break;
                    case 'female':
                      echo 'Женский';
                      break;
                    default:
                      echo $value;
                      break;
                  }
                  echo '</p>';
                echo '</div>';
              echo '</div>';
            }
            $count++;
          }
        }else{
          echo "<h4>Нет детей</h4>";
        }
        ?>
      </div>
      <div class="status">
        <h3>Статус</h3>
        <?
          $result = $mysqli->query("SELECT statusinfo FROM Statuses WHERE statement_id = '$st_id'");
          $res = json_decode($result->fetch_assoc()['statusinfo']);
          foreach ($res as $key => $value) {
            $doc_text[$key] = $value;
            echo '<div class="form-group">';
              echo '<label class="col-xs-3 control-label">'.$key.'</label>';
              echo '<div class="col-xs-9">';
                echo '<p class="form-control-static">'.$value.'</p>';
              echo '</div>';
            echo '</div>';
          }
        ?>
      </div>
      <button type="submit" class="btn btn-md btn-danger" name="back">Назад</button>
    </form>
    <a href="docs/Заявка №<?echo $st_id;?>.doc" class="download btn btn-md right btn-success" download>Скачать документом</a>
  </div>
</div>
<?
$file_name = "docs/Заявка №". $st_id.'.doc';
foreach ($doc_text as $key => $value) {
  file_put_contents($file_name, $key.': '.$value. PHP_EOL, FILE_APPEND | LOCK_EX);
}
?>
<?php include("modules/footer.php"); ?>
