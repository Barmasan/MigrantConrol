<?
if(!empty($message)){
  echo '<div class="alert alert-'.$m_type.' fade in">';
  echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
  echo $message;
  echo '</div>';
}
?>
