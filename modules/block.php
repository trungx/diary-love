<?php
  $type = $_POST['block'];
  $num = strpos($type,"=");
  $type = substr($type,0,$num);
  echo block($type);
?>