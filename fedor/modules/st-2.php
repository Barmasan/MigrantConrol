<div class="st st-2" <?echo $st2;?>>
  <div class="form-group">
    <label>Район</label>
    <select name="status_region" class="form-control" <?php echo $std2 ?>>
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
</div>
