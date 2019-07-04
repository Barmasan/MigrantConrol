<div class="st st-1" <?echo $st1;?>>
  <div class="form-group">
    <label for="state_number">Номер заявления на участие в государственной программе</label>
    <input type="number" maxlength="6" class="form-control" id="state_number" name="state_number" placeholder="Номер заявления на участие в государственной программе" value='<?php echo $statusinfo->Номер ?>' required <?php echo $std1 ?>>
  </div>
  <div class="form-group">
    <label for="state_date">Дата</label>
    <input type="date" class="form-control" id="state_date" name="state_date" placeholder="Дата" value='<?php echo $statusinfo->Дата ?>' required <?php echo $std1 ?>>
  </div>
</div>
