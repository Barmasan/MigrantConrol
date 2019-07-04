<?
$st1 = '';
$st2 = '';
$st3 = '';
$st4 = '';
$std1 = '';
$std2 = 'disabled';
$std3 = 'disabled';
$std4 = 'disabled';
$ss1 = 'selected';
$ss2 = '';
$ss3 = '';
$ss4 = '';
if(isset($active_status)){
  switch ($active_status) {
    case 'st-1':
      $ss1 = 'selected';
      $st2 = 'style="display: none;"';
      $st3 = 'style="display: none;"';
      $st4 = 'style="display: none;"';
      $std2 = 'disabled';
      $std3 = 'disabled';
      $std4 = 'disabled';
      break;
    case 'st-2':
      $ss2 = 'selected';
      $st1 = 'style="display: none;"';
      $st3 = 'style="display: none;"';
      $st4 = 'style="display: none;"';
      $std1 = 'disabled';
      $std3 = 'disabled';
      $std4 = 'disabled';
      break;
    case 'st-3':
      $ss3 = 'selected';
      $st1 = 'style="display: none;"';
      $st2 = 'style="display: none;"';
      $st4 = 'style="display: none;"';
      $std1 = 'disabled';
      $std2 = 'disabled';
      $std4 = 'disabled';
      break;
    case 'st-4':
      $ss4 = 'selected';
      $st1 = 'style="display: none;"';
      $st2 = 'style="display: none;"';
      $st3 = 'style="display: none;"';
      $std1 = 'disabled';
      $std2 = 'disabled';
      $std3 = 'disabled';
      break;

    default:
      // code...
      break;
  }
}
else {
  $st2 = 'style="display: none;"';
  $st3 = 'style="display: none;"';
  $st4 = 'style="display: none;"';
}
?>
<div class="status">
  <h3 class="status-title">Статус заявления</h3>
  <div class="form-group">
    <select name="status_number" id="state_select" class="form-control">
      <option <?php echo $ss1 ?> value="st-1">Подал заявление об участии в Государственной программе</option>
      <option <?php echo $ss2 ?> value="st-2">Направлен запрос в МВК района</option>
      <option <?php echo $ss3 ?> value="st-3">Принято решение о согласовании</option>
      <option <?php echo $ss4 ?> value="st-4">Принято решение об отказе</option>
    </select>
  </div>
  <?
  include('st-1.php');
  include('st-2.php');
  include('st-3.php');
  include('st-4.php');
  ?>
</div>
