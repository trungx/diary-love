<?php

 // insert comment
 if($_POST['comment']=='submit_ok'){
        $c_catid = $_POST['catid'];
        $c_type = $_POST['type'];
        $c_level =  $_POST['level'];
        $c_comment = $_POST['content'];
        $c_date = timezones(0);
        $c_time = timezones(1);
        $c_year = substr($c_date,0,4);
        $c_month = substr($c_date,4,2);
        $c_day = substr($c_date,6,2);
        $arr = array(
            'cfor'  => $c_type,
            'userid'    => $_SESSION['userid'],
            'catid' => $c_catid,
            'content'   => $c_comment,
            'level' => $c_level,
            'year'  => $c_year,
            'month' => $c_month,
            'day'  =>   $c_day,
            'time'  => $c_time,
           );
        $mysql->insert('comment',$arr);
        echo comment(1,$c_type,$c_catid);
        exit();
 }


 // delete comment
 if($_POST['comment']=='delete'){
    $mysql->query('delete from '.$table_prefix.'comment where id='.$id.'');
    // delete comment cap 2
    $mysql->query('delete from '.$table_prefix.'comment where level=1 and catid='.$id.'');
    exit();
 }



?>