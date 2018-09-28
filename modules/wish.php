<?php
if($_POST['wish']=='home'){
    echo wish_list();
    exit();
}

if($_POST['wish']=='view'){
    $id = $_POST['id'];
    echo wish_view($id);
    exit();
}

if($_POST['wish']=='new'){
    echo wish_new();
    exit();
}

if($_POST['wish']=='list'){
    echo wish_list($page);
    exit();
}

if($_POST['wish']=='wish_submit'){
    $content = $_POST['content'];
    $cat =  $_POST['category'];
    $date = timezones(0);
    $time = timezones(1);
    $year = substr($date,0,4);
    $month = substr($date,4,2);
    $day = substr($date,6,2);
    $arr = array(
        'userid'    => $_SESSION['userid'],
        'wishfor'   => $cat,
        'content'   => $content,
        'year'  => $year,
        'month' => $month,
        'day'   => $day,
        'time'  => $time,
    );
    $mysql->insert('wish',$arr);
    $order = 'new';
    echo wish_list();
    exit();
}


function wish_home(){
    global $mysql,$lang,$tpl,$table_prefix;
    $htm = $tpl->get_tem('wish');
    $htm    = $tpl->replace_tem($htm,$lang);
    $t = $tpl->auto_get_block($htm);
    $main = $tpl->replace_tem($t['wish.home'],array(
        'skin.link' => $_SESSION['template'],

        )
    );
    $htm = $tpl->replace_block($htm,array(
        'html'  => $main,
        )
    );
return $htm;
}

function wish_list($page,$m=''){
    global $mysql,$lang,$tpl,$table_prefix,$order,$type,$wcatid,$wmemid,$web_wish_limit;
    $htm = $tpl->get_tem('wish');
    $htm = $tpl->replace_tem($htm,$lang);
    $t = $tpl->auto_get_block($htm);
    if($type='category'){
        $s = 'and category='.$wcatid;
    }elseif($type=='member'){
        $s = "and userid=".$wmemid."";
    }else{
        $s = "";
    }
    $total = $mysql->num_rows($mysql->query("select id from ".$table_prefix."wish"));
    if(!$total){
        $total = 0;
        $list = "Chua co uoc nguyen nao!";
    }else{
        if(!$page) $page = 1;
        $page_size = $web_wish_limit;
        $limit = ($page-1)*$page_size;
        if($order=='new'){
            $o = "order by id DESC";
        }else{
            $o = "order by id ASC";
        }
        if($m){
            $l = "0,".$m;
        }else{
            $l = "".$limit.",".$page_size."";
        }
        $q = $mysql->query("select * from ".$table_prefix."wish where id>0 ".$o." limit ".$l."");
        while($r=$mysql->fetch_array($q)){
            $check = get_config('sex','member','id',$r['userid']);
            $name = $check==1?$lang['lang.boy_love']:$lang['lang.girl_love'];
            $color = $check==1?"blue":"red";
            $list .= $tpl->replace_tem($t['wish.list'],array(
                'id'    => $r['id'],
                'content'    => un_bbcode($r['content']),
                'for'   => $r['wishfor'],
                'user' => get_user_icon('wish',$r['userid']),
                'username'  => $name,
                'color'  => $color,
                )
            );
        }
    }
    $main = $tpl->replace_tem($t['wish.top.list'],array(
        'wish.list' => $list,
        'total' => $total,
        'skin.link' => $_SESSION['template'],
        'pages' => viewpages('main',$total,$page_size,$page,'wish=list&order='.$order.'&type='.$type.'&catid='.$wcatid.'&memid='.$wmemid.''),
        )
     );
     $htm = $tpl->replace_block($htm,array(
        'html'  => $main,
        )
     );
return $htm;
}

function wish_view($id){
    global $mysql,$lang,$tpl,$table_prefix;
    $htm = $tpl->get_tem('wish');
    $htm = $tpl->replace_tem($htm,$lang);
    $t = $tpl->auto_get_block($htm);
    $q = $mysql->query("select * from ".$table_prefix."wish where id=".$id."");
    while($r=$mysql->fetch_array($q)){
        $list = $tpl->replace_tem($t['wish.view'],array(
            'id'    => $r['id'],
            'content'   => un_bbcode($r['content']),
            'day'  =>   $r['day'],
            'month' => get_month($r['month']),
            'year'  => $r['year'],
            'time'  => $r['time'],
            'skin.link' => $_SESSION['template'],
            'user' => get_user_icon('wish',$r['userid']),
            'for'   => $r['wishfor'],
            'user' => get_user_icon('wish',$r['userid']),
            'mini.date' => mini_full_date($r['year'].$r['month'].$r['day'],$r['time']),
            )
        );
    }
    $htm = $tpl->replace_block($htm,array(
        'html'  => $list,
        )
    );
return $htm;
}

function wish_new(){
    global $mysql,$lang,$tpl,$table_prefix,$moshtml;
    $htm = $tpl->get_tem('form');
    $htm = $tpl->replace_tem($htm,$lang);
    $t = $tpl->auto_get_block($htm);
    $catarr[] = array('life',$lang['lang.life']);
    $catarr[] = array('love',$lang['lang.love']);
    $catarr[] = array('study',$lang['lang.study']);
    $catarr[] = array('heal',$lang['lang.heal']);
    $catarr[] = array('family',$lang['lang.family']);
    $main = $tpl->replace_tem($t['wish.post'],array(
        'skin.link' => $_SESSION['template'],
        'cat.select'    => $moshtml->select($catarr,'post-category','',''),
        'editor'    => bbcode_mini(),
        )
    );
    $htm = $tpl->replace_block($htm,array(
        'html'  => $main,
        )
    );
return $htm;
}

function wish_type($type){
    global $mysql,$lang,$tpl,$table_prefix;
    return "";
}
?>