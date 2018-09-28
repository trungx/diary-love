<?php
// $id : id cua plugin
// $plugin_admin_link : duong dan dan file quan ly plugin

$htm=$tpl->get_tem_p('default','admin.default');
$htm = $tpl->replace_tem($htm,$lang);
$t = $tpl->auto_get_block($htm);

// kiem tra id cua plugin default
$df_check = get_config('indexurl','plugin','id',$id);
if($df_check=='about'){
    if($act=='home' || !$act){
        echo about();
    }
}

if($_POST['act']=='about_select'){
    $type = $_POST['type'];
    echo about_code($type);
    exit();
}

if($_POST['act']=='about_submit'){
    $w_a = $_POST['web_about'];
    $l = $_POST['link'];
    if($w_a == '1'){
        $arr = array(
            'web_about'     => $w_a,
            'web_avatar'    => $l,
        );
    }elseif($w_a == '2'){
        $arr = array(
            'web_about' => $w_a,
            'web_about_array'   => $l,
        );
    }else{
        $arr = array(
            'web_about' => $w_a,
            'web_about_f'   => $l,
        );
    }
    update_file_php('includes/site.config.php',$arr);
    echo about();
    exit();
}



function about(){
    global $mysql,$lang,$tpl,$table_prefix,$id,$plugin_admin_link,$htm,$t,$moshtml;
    include('includes/site.config.php');
    $avatar_mode[] = array(1,"Default");
    $avatar_mode[] = array(2,"Yahoo360");
    $avatar_mode[] = array(3,"Flash");      // lua chon anh trong album
    if($web_about==1){
        $preview = "<img src='images/cpanel/about/default.jpg' border=0>";
    }elseif($web_about==2){
        $preview = "<img src='images/cpanel/about/360.jpg' border=0>";
    }else{
        $preview = "<img src='images/cpanel/about/flash.jpg' border=0>";
    }
    $main = $tpl->replace_tem($t['about'],array(
        'avatar.select' => $moshtml->select($avatar_mode,'avatar-mode','onChange="about_select_mode(this.options[this.selectedIndex].value);"',$web_about),
        'skin.link' => $_SESSION['template'],
        'id'    => $id,
        'admin.url' => $plugin_admin_link,
        'code'  => about_code($web_about),
        'preview'   => $preview,
        )
    );
    $htm = $tpl->replace_block($htm,array(
        'html'  => $main,
        )
    );
return $main;
}

function about_code($code,$source){
    global $moshtml,$web_avatar,$web_about_array,$table_prefix,$mysql,$lang,$web_about_f;
    if($code=='1'){        // default
        $show = "<b>Photo: </b><input id='avatar' size='25' value='".$web_avatar."'>";
    }elseif($code=='2'){  // yahoo360
        $arr1 = explode('||',$web_about_array);
        $show = "<b>Pic1:&nbsp;</b><input style='margin:1px;' id='pic1' size='25' value='".$arr1[0]."'><br>";
        $show .= "<b>Pic2:&nbsp;</b><input style='margin:1px;' id='pic2' size='25' value='".$arr1[1]."'><br />";
        $show .= "<b>Pic3:&nbsp;</b><input style='margin:1px;' id='pic3' size='25' value='".$arr1[2]."'>";
    }else{      // lua chon album

        $q = $mysql->query("select id, title from ".$table_prefix."album where afor='gallery' order by title ASC");
        while($r=$mysql->fetch_array($q)){
            $select[] = array($r['id'],$r['title']);
        }
        $show = "<b>".$lang['lang.select']."&nbsp;album:&nbsp;</b>".$moshtml->select($select,"avatar","",$web_about_f);
    }
return $show;
}



