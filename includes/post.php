<?php
########### Su ly cac du lieu gui len tu trinh khach ##########

if($_POST['home']){
    include($module.'home.php');
    exit();
}elseif($_POST['diary']){
    include($module.'diary.php');
    exit();
}elseif($_POST['gallery']){
    include($module.'gallery.php');
    exit();
}elseif($_POST['member']){
    include($module.'member.php');
    exit();
}elseif($_POST['block']){
    include($module.'block.php');
    exit();
}elseif($_POST['comment']){
    include($module.'comment.php');
    exit();
}elseif($_POST['music']){
    include($module.'music.php');
    exit();
}elseif($_POST['wish']){
    include($module.'wish.php');
    exit();
}elseif($_POST['about']){
    include($module.'about.php');
    exit();
}elseif($_POST['admin']){
    include($module.'admin.php');
    exit();
}elseif($_POST['calendar']){
    $id = $_POST['id'];
    $type = $_POST['calendar'];
    if($id=='' || $id == 0){
        echo block_calendar($type);
    }else{
      $year = substr($id,0,4);
      $month = substr($id,4,2);
      if($month==13){
          $year = $year+1;
          $month = 1;
      }elseif($month == 0){
          $year = $year-1;
          $month = 12;
      }
      echo block_calendar($type,$year,$month);
    }
    exit();
}
// post cho module
$q = $mysql->query('select * from '.$table_prefix.'module where md_active="yes"');
while($r = $mysql->fetch_array($q)){
    if($_POST[$r['md_link']] || $_POST['md_name']){
        include($module.$r['md_link']."/".$r['md_indexurl']);
        exit();
    }

}

// post cho plugin
$q = $mysql->query('select * from '.$table_prefix.'plugin where plugin_active="yes"');
while($r = $mysql->fetch_array($q)){
    if($_POST[$r['plugin_link']] || $_POST['plugin_name']){
        include($plugin.$r['plugin_link']."/".$r['plugin_indexurl']);
        exit();
    }

}
?>