<?php
function add_log($manager_id, $description){
  $mysqli = new mysqli("192.168.100.2", "root", "", "fedor") or die(mysql_error());
  $sql="INSERT INTO Logs (manager_id, type) VALUES ('$manager_id', '$description')";
  $result= $mysqli->query($sql);
}
?>
