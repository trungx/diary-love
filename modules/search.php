<?php
if($_POST['search'=='list'){
    $type = $_POST['type'];
    $page = $_POST['page'];
    $mode = $_POST['mode'];
    $key = $_POST['key'];

    // tim kiem toan bo
    if(!$mode){
        $t = $type;
    }else{
        $t = $mode;
    }
    $select = "where title LIKE ('%".$key."%')";
    if(!$page) $page = 1;
    $page_size = 10;
    $limit = ($page-1)*$page_size;
    $q = $mysq->query("select * from ".$table_prefix.$t." ".$select." limit ".$limit.",".$page_size."");
    while($r = $mysql->fetch_array($q)){
        
    }


}


?>