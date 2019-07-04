<div class="st st-3" <?echo $st3;?>>
  <div class="form-group">
    <label for="state_number">Номер</label>
    <input type="number" class="form-control" id="state_number" name="state_number" value='<?php echo $statusinfo->Номер ?>' placeholder="Номер" required <?php echo $std3 ?>>
  </div>
  <div class="form-group">
    <label for="state_date">Дата</label>
    <input type="date" class="form-control" id="state_date" name="state_date" value='<?php echo $statusinfo->Дата ?>' placeholder="Дата" required <?php echo $std3 ?>>
  </div>
  <div class="form-group">
    <label>Район согласования</label>
    <select name="status_region" class="form-control" <?php echo $std3 ?>>
      <?
      $query = $mysqli->query("SELECT * FROM Districts WHERE active = 1");
      while($row= $query->fetch_assoc())
      {
        $selected = ($row['name'] == $statusinfo->Район) ? 'selected' : '' ;
        echo '<option value="'.$row['name'].'"'.$selected.'>'.$row['name'].'</option>';
      }
      ?>
    </select>
  </div>
  <div class="form-group">
    <label for="state_reason">Причина согласия</label>
    <input type="text" class="form-control" id="state_reason" name="state_reason" value='<?php echo $statusinfo->Причина_согласия ?>' placeholder="Причина" <?php echo $std3 ?>>
  </div>
</div>
