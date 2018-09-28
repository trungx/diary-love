<?php

##################### HOME FUNCTION ##############################
function home(){
    global $mysql,$lang,$tpl,$table_prefix;
    $htm = $tpl->get_tem('main');
    $htm = $tpl->replace_tem($htm,$lang);
    $t = $tpl->auto_get_block($htm);
    $main = $tpl->replace_tem($t['home'],array(
        'mem.panel' => member_panel(),
        'avatar'    => get_avatar('me'),
        'playlist'  => playlist(),
        'skin.link' => $_SESSION['template'],
        'block' => block('home'),
        'main'  => main(),
        'today' => "<script language='javascript'>today();</script>",
        )
    );
    $htm = $tpl->replace_block($htm,array(
        'html'  => $main,
        )
    );
    return $htm;
}

function main(){
    global $mysql,$lang,$tpl,$table_prefix;
    $home = $tpl->get_tem('main');
    $home = $tpl->replace_tem($home,$lang);
    $t = $tpl->auto_get_block($home);
    $main = $tpl->replace_tem($t['home.main'],array(
            'diary.new_boy'  => diary_new(1,'boy'),
            'diary.new_girl'  => diary_new(1,'girl'),
            'gallery.new'   => gallery_new(6),
            'music.new' => music_new(5),
          //  'topwish'  => wish_top(),
           // 'topimage'  => gallery_top(),
           // 'note'  => note_top(),
        )
    );
    $home = $tpl->replace_block($home,array(
        'html'  => $main,
        )
    );
return $home;
}






################ DIARY FUNCTION #################################

function diary_new($limit,$sex=''){
    global $mysql,$lang,$tpl,$table_prefix;
    $htm = $tpl->get_tem('diary');
    $htm = $tpl->replace_tem($htm,$lang);
    $t = $tpl->auto_get_block($htm);
    if($sex){
      if($sex=='boy'){
          $checkid = get_config('id','member','sex',1);
      }else{
        $checkid = get_config('id','member','sex',2);
      }
      $s = "where userid=".$checkid."";
    }
    $q = $mysql->query("select * from ".$table_prefix."diary ".$s." order by id DESC limit 0,".$limit."");
    while($r=$mysql->fetch_array($q)){
            if($r['userid']==$_SESSION['userid']){
                $delete = '<img style=\'cursor:pointer;\' onclick="del(\'diary\','.$r['id'].');" title="Delete" src=\'images/icon/delete.gif\'>';
                $edit = '<img style=\'cursor:pointer;\' onclick="edit(\'diary\','.$r['id'].');" title="Edit" src=\'images/icon/edit.gif\'>';
            }else{
                $delete = '';
                $edit = '';
            }
            $list .= $tpl->replace_tem($t['diary.list'],array(
                'id'    => $r['id'],
                'skin.link' => $_SESSION['template'],
                'delete'    => $delete,
                'edit'    => $edit,
                'name'  => cut_str($r['title'],35),
                'title' => $r['title'],
                'url'    => 'viewpages(\'main\',\'diary=view&id='.$r['id'].'\');web_title(\'Diary - '.$r['title'].'\');loadblock(\'diary=home\');',
                'date'  => mini_full_date($r['year'].$r['month'].$r['day'],$r['time']),
                'head'  => un_bbcode($r['head']),
                'weather.icon'  => weather($r['weather'],'icon'),
                'weather.thumb'  => weather($r['weather'],'thumb'),
                'feeling'   => feeling($r['icon']),
                'year'  => $r['year'],
                'month' => $r['month'],
                'day'   => $r['day'],
                'user' => get_user_icon('diary',$r['userid']),

                )
            );
    }
    $htm = $tpl->replace_block($htm,array(
        'html'  => $list,
        )
    );
return $htm;
}


function gallery_new($limit,$tab=''){
    global $mysql,$lang,$tpl,$table_prefix;
    $htm = $tpl->get_tem('gallery');
    $htm = $tpl->replace_tem($htm,$lang);
    $t= $tpl->auto_get_block($htm);
    $q = $mysql->query("select * from ".$table_prefix."gallery where draft='no' order by id DESC limit 0,".$limit."");
    while($r=$mysql->fetch_array($q)){
        if($_SESSION['userid']==$r['userid']){
                $edit_show = 'block';
                $delete_link = "del('gallery',".$r['id'].")";
                $edit_link = "edit('gallery',".$r['id'].",'&type=pic')";
                $all_url = '';
            }else{
                $edit_show = 'none';
                $delete_link = '';
                $edit_link = '';
                $all_url = "viewpages('main','gallery=view&id=".$r['id']."');web_title('Gallery - ".$r['title']."');";
            }
            $list .= $tpl->replace_tem($t['gallery.list'],array(
                'id'    => $r['id'],
                'tool.show' => $edit_show,
                'edit.link' => $edit_link,
                'del.link'  => $delete_link,
                'skin.link' => $_SESSION['template'],
                'title' => $r['title'],
                'name'  => cut_str($r['title'],25),
                'thumb' => $r['thumb'],
                'allurl'    => $all_url,
                'url'   => "viewpages('main','gallery=view&id=".$r['id']."');web_title('Gallery - ".$r['title']."');loadblock('gallery=home');",
                )
            );
    }
    $htm = $tpl->replace_block($htm,array(
        'html'  => $list,
        )
    );
return $htm;
}

function music_new($limit){
    global $mysql,$lang,$tpl,$table_prefix,$module;
    $htm = $tpl->get_tem('music');
    $htm = $tpl->replace_tem($htm,$lang);
    $t = $tpl->auto_get_block($htm);
    $q = $mysql->query("select * from ".$table_prefix."music order by id DESC limit 0,".$limit."");
    while($r = $mysql->fetch_array($q)){
        if($r['userid']==$_SESSION['userid'] || $_SESSION['sex']=='1'){
                $delete =  "<img height='13' src='images/icon/delete.gif' style='background-color: #fff; cursor: pointer; padding: 1px; margin: 1px; border: 0px solid red;' onclick=\"del('music',".$r['id'].");\" title='delete'>";
                $edit = "<img height='13' src='images/icon/edit.gif' style='background-color: #fff; cursor: pointer; padding: 1px; border: 0px solid red;' onclick=\"edit('music',".$r['id'].",'&type=song');\" title='edit'>";
            }else{
                $delete = '';
                $edit = '';
            }
            $list .= $tpl->replace_tem($t['song.list'],array(
                'id'    => $r['id'],
                'edit'  => $edit,
                'delete' => $delete,
                'title' => $r['title'],
                'name'  => cut_str($r['title'],35),
                'url'   => "play('music=play&id=".$r['id']."','music=song_info&id=".$r['id']."');",
                'type'  => music_type($r['type']),
                'user'  => "<span style='cursor: pointer;' onclick=\"viewpages('main','music=list&type=member&muserid=".$r['userid']."')\">".user_icon($r['userid'],'icon')."</span>",
                'played'=> $r['played'],
                'singer'    => get_singer($r['singer']),
                'downloaded'  => $r['download'],
                'fav'   => "<span style='cursor: pointer;' id='favorite-image-".$r['id']."'>".music_favicon($r['id'])."</span>",
                )
            );
    }
    $main = $tpl->replace_tem($t['song.top.list'],array(
              'info' => '',
              'show'  => 'none',
              'song.list' => $list,
              'total' => '',
              'new.link'  => "viewpages('main','music=list&type=".$type."&order=new')",
              'old.link'  => "viewpages('main','music=list&type=".$type."&order=old')",
              'name1.link'  => "viewpages('main','music=list&type=".$type."&order=name1')",
              'name2.link'  => "viewpages('main','music=list&type=".$type."&order=name2')",
              'pages' => '',
              'skin.link' => $_SESSION['template'],
              'playlist.link' => "play('music=playlist');",
              )
            );
    $htm = $tpl->replace_block($htm,array(
        'html'  => $main,
        )
    );
return $htm;
}




########## COMMENT LIST ##########
function comment($page,$type,$catid,$level=''){
    // $page : trang so
    // catid : id cua diary, gallery, hoac comment ( neu level 2 )
    // type : dinh dang cho diary, gallery
    // level : '' => danh sach comment chinh; 1 => danh sach loi binh cap 2
    global $mysql,$lang,$tpl,$table_prefix;
    $htm  = $tpl->get_tem('comment');
    $htm = $tpl->replace_tem($htm,$lang);
    $t = $tpl->auto_get_block($htm);
    // check total
    if($level){
        $l = "and level=1";
        $h = $t['comment.list.lv2'];
    }else{
        $l = "and level=0";
        $h = $t['comment.list'];
    }
        $total = $mysql->num_rows($mysql->query("select id from ".$table_prefix."comment where catid=".$catid." and cfor='".$type."' ".$l.""));
    if(!$total){
        $total = 0;
        $list = $level==''?"<center>".$lang['lang.empty_comment']."</center></br>":"";
    }else{
        if(!$page) $page = 1;
        $page_size = 5;
        $limit = ($page-1)*$page_size;
        $n = 1;
        $q = $mysql->query("select * from ".$table_prefix."comment where catid=".$catid." ".$l." and cfor='".$type."' order by id DESC limit ".$limit.",".$page_size."");
        while($r=$mysql->fetch_array($q)){
            $n++;
            if($n%2==1){
                $color = '#ffffff';
            }else{
                $color = '#efefef';
            }
            if($r['level']=='0'){
                $sub = comment(1,$type,$r['id'],1);
            }else{
                $sub = '';
            }
            if($_SESSION['userid']==$r['userid']){
                $delete = "<img src='images/icon/delete.gif' border='0' style='cursor: pointer;' onclick=\"del('comment',".$r['id'].")\" title='Delete'>";
            }else{
                $delete = '';
            }
            $list .= $tpl->replace_tem($h,array(
                'id'    => $r['id'],
                'date'  => mini_full_date($r['year'].$r['month'].$r['day'],$r['time']),
                'content' => un_bbcode($r['content']),
                'gavatar'   => member_gavatar($r['userid']),
                'sub'   => $sub,
                'color' => $color,
                'delete'    => $delete,
                )
            );
        }
    }
    $htm = $tpl->replace_block($htm,array(
        'html'  => $list,
        )
    );
return $htm;
}


function send_comment($type,$catid,$level=''){
    // type : Dinh dang diary, gallery ...
    // catid : id cua diary, gallery hoac cua comment cap 1
    // level : tuy chon comment cap 1 hoac 2
    global $mysql,$lang,$tpl,$table_prefix;
    $htm = $tpl->get_tem('comment');
    $htm = $tpl->replace_tem($htm,$lang);
    $t = $tpl->auto_get_block($htm);
    $l = $level?'1':'0';
    $list = $tpl->replace_tem($t['comment.form'],array(
        'editor'  => bbcode_mini(),
        'type'  => $type,
        'catid' => $catid,
        'level' => $l,
        )
    );
    $htm = $tpl->replace_block($htm,array(
        'html'  => $list,
        )
    );
return $htm;
}








#################### MEMBER FUNCTION ##############################
function login(){
    global $web_for,$table_prefix,$tpl,$web_avatar,$web_title,$lang;
        /* Kiểm tra site được kích hoạt là nhật ký tình yêu hay nhật ký bạn bè
              + Nếu là nhật ký tình yêu => Chỉ cần nhập mật khẩu.
              + Nếu là nhật ký bạn bè   => Cần nhập cả username và mật khẩu. */
        if($web_for=='love'){
            $show_username = 'none';
            $en_username = 'no';
            $name = listname('love',' - ');
        }else{
            $show_username = 'block';
            $en_username = 'yes';
            $name = listname('friend',' - ');
        }
        $htm = $tpl->get_tem('login');
        $htm = $tpl->replace_tem($htm,$lang);
        $htm = $tpl->replace_tem($htm,array(
            'avatar' => $web_avatar,
            'name'  => $name,
            )
        );
        return $htm;
}


function logout(){
    global $mysql,$lang,$tpl,$table_prefix;
    $htm = $tpl->get_tem('member');
    $t = $tpl->get_block($htm,'member.logout',1);
    if($_SESSION['sex']=='1'){
        $you = $lang['lang.he'];
    }else{
        $you = $lang['lang.she'];
    }
    $message = $you."&nbsp;".$lang['lang.close_diary'];
    $avatar = get_avatar('me');
    $main = $tpl->replace_tem($t,array(
        'avatar'    => $avatar,
        'message'   => $message,
        )
    );
    $main = $tpl->replace_tem($main,$lang);
    $htm = $tpl->replace_block($htm,array(
        'html'  => $main,
        )
    );
return $htm;
}

function un_html($str) {
	return str_replace(
		array('&', '<', '>', '"', chr(92), chr(39)),
		array('&amp;', '&lt;', '&gt;', '&quot;', '&#92;', '&#39'),
		$str
	);
}

function listname($mode,$type){
    global $mysql,$table_prefix;
    if($mode=='love'){
        return get_config('fullname','member','sex','1').$type.get_config('fullname','member','sex','2');
    }else{
        $q = $mysql->query('select fullname from '.$table_prefix.'member order by id DESC');
        while($r=$mysql->fetch_array($q)){
            $name .= $r['fullname'].$type;
        }
        return substr($name,0,-strlen($type));
    }
}

function get_user($type){
    global $lang;
    if($type=='you'){
        $name = $_SESSION['sex']==1?$lang['lang.boy_love']:$lang['lang.girl_love'];
    }else{
        $name = $_SESSION['sex']==1?$lang['lang.girl_love']:$lang['lang.boy_love'];
    }
    return $name;
}

function user_icon($id,$type=''){
    global $lang;
    $sex = get_config('sex','member','id',$id);
    $name = $sex==1?"<font color='blue'>".$lang['lang.boy_love']."</font>":"<font color='red'>".$lang['lang.girl_love']."</font>";
    $img = $sex==1?"<img title='".$lang['lang.boy_love']."' src='images/icon/boy.jpg' boder='0'>":"<img title='".$lang['girl_love']."' src='images/icon/girl.jpg' boder='0'>";
    if($type){    // tra ve icon
        return   $img;
    }else{
        return $name;
    }
}

function member_gavatar($id){
    // id : id cua nguoi can lay gavatar
    global $web_gavatar,$web_domain;
    $email = get_config('email','member','sex',$id);
    $gavatar = get_config('gavatar','member','sex',$id);
    $yahoo = get_config('yahoo','member','sex',$id);
    $avatar = get_config('avatar','member','sex',$id);
    $back = "http://img.msg.yahoo.com/avatar.php?yids=".$yahoo;
    if($gavatar==1){
        return "http://www.gravatar.com/avatar/".md5(strtolower($email))."?s=120&d=".$back;
    }else{
        if($avatar==''){
            return $back;
        }else{
            return $avatar;
        }
    }
}

function get_avatar($type){
    global $lang;
    if($type=='you'){    // cua nguoi dang online
        $name = $_SESSION['sex']==1?member_gavatar(1):member_gavatar(2);
    }else{
        $name = $_SESSION['sex']==1?member_gavatar(2):member_gavatar(1);
    }
    return $name;
}

function member_content(){
    global $mysql,$web_boy_comment,$web_girl_comment;
    if($_SESSION['sex']==1){
        return un_bbcode(cut_str($web_girl_comment,125));
    }else{
        return un_bbcode(cut_str($web_boy_comment,125));
    }
}

function member_panel(){
    global $mysql,$lang,$tpl,$table_prefix,$web_for;
    $htm = $tpl->get_tem('member');
    $htm = $tpl->replace_tem($htm,$lang);
    $t = $tpl->get_block($htm,'member.panel',1);
    $list = $tpl->replace_tem($t,array(
        'you'   => get_user('you'),
        'me'   => get_user('me'),
        'content'   => member_content(),
        'note'  => '',
        'avatar'    => get_avatar('me'),
        'memory'    => memory(0),
        )
    );
    $htm = $tpl->replace_block($htm,array(
        'html'  => $list,
        )
    );
return $htm;
}


/// memory
function memory($id=''){ // id = 0 => ngay ky niem gan day nhat
    global $mysql,$lang,$tpl,$table_prefix;
    if(!$id || $id == 0){
        $date = timezones(0);
        $time = timezones(1);
        $year = substr($date,0,4);
        $month = substr($date,4,2);
        $day = substr($date,6,2);
        // kiem tra trong thang co bao nhieu ngay ky niem
        $total = $mysql->num_rows($mysql->query("select id from ".$table_prefix."memory where month = ".$month." and day>=".$day.""));
        if(!$total){
            return $lang['lang.nokyniem'];
        }else{
            $q = $mysql->query("select * from ".$table_prefix."memory where month=".$month." and day>=".$day." order by day ASC limit 0,1");
            while($r=$mysql->fetch_array($q)){
                if($day==$r['day']){
                    $total = ($year-$r['year'])+1;
                    $mysql->query("update ".$table_prefix."memory set total=".$total." where id=".$r['id']."");
                    return $lang['lang.kyniem']."&nbsp;".$r['total']."&nbsp;".$lang['lang.date']."&nbsp;".$r['title']."";
                }
            }
        }
    }
    echo $month;
}













################### MUSIC FUNCTION ##############################
// box nghe nhạc khi truy cập
function playlist($id=''){
    global $mysql,$lang,$tpl,$table_prefix,$module,$type;
    $check = get_config('playlist','member','id',$_SESSION['userid']);
    if(!$check || $check == ''){
        $xml = $module.'music.php?playlist=random';
    }else{
        $xml = $module.'music.php?playlist='.$_SESSION['userid'];
    }
    // play nhac
    if($id){
        if($type=='song'){
            $xml = $module.'music.php?play='.$id;
        }elseif($type=='singer'){
            $xml = $module.'music.php?play_singer='.$id;
        }elseif($type=='album'){
            $xml = $module.'music.php?play_album='.$id;
        }
    }
    $htm = $tpl->get_tem_p('default','musicbox');
    $t = $tpl->auto_get_block($htm);
    $main = $tpl->replace_tem($t['playlist'],array(
        'xml'   => $xml,
        )
    );
    $main = $tpl->replace_block($htm,array(
        'html'  => $main,
        )
    );
return $main;
}





function get_link($link,$type=''){
    if(substr_count($link ,'http://nhaccuatui.com') || substr_count($link, 'http://www.nhaccuatui.com')){
        if($type){
            return "../includes/getlink.php?url=".$link;
        }else{
            return "includes/getlink.php?url=".$link;
        }
    }else{
        return $link;
    }
}

// type
function music_type($type1){
    $type = str_replace('.','',$type1);
    if($type == 'mp3' || $type == 'wma'){
        return "<img src='images/icon/music.gif' border='0' title='".$type."'>";
    }elseif($type == 'wmv'|| $type == 'mp4'){
        return "<img src='images/icon/video.gif' border='0' title='".$type."'>";
    }elseif($type == 'flv' || $type == 'swf'){
        return "<img src='images/icon/flash.gif' border='0' title='".$type."'>";
    }else{
        return "<img src='images/icon/other.gif' border='0' title='".$type."'>";
    }
}

function get_url($url) {
	$url_parsed = parse_url($url);
	$host = $url_parsed["host"];
	$port = 0;
	$in = '';
	if (!empty($url_parsed["port"])) {
  	$port = $url_parsed["port"];
	}
	if ($port==0) {
		$port = 80;
	}
	$path = $url_parsed["path"];
	if ($url_parsed["query"] != "") {
		$path .= "?".$url_parsed["query"];
	}
	$out = "GET $path HTTP/1.0\r\nHost: $host\r\n\r\n";
	$fp = fsockopen($host, $port, $errno, $errstr, 30);
	fwrite($fp, $out);
	$body = false;
	while (!feof($fp)) {
		$s = fgets($fp, 1024);
		if ( $body ) {
			$in .= $s;
		}
		if ( $s == "\r\n" ) {
			$body = true;
		}
	}
	fclose($fp);
	return $in;
}

// get singer
function get_singer($id){
    global $lang;
    $name = get_config('title','singer','id',$id);
    return "<a href='javascript: void(0);' onclick=\"viewpages('main','music=list&type=singer&singerid=".$id."');web_title('Music - Singer: ".$name."');\" title='".$name."'>".$name."</a>";
}

// check favorite
function music_favicon($id){
    global $lang;
    $playlist = get_config('playlist','member','id',$_SESSION['userid']);
    if(filter($id,$playlist)){
        return "<img onclick=\"playlist('".$id."','remove');\" title='".$lang['lang.remove_fav']."' src='images/icon/faved.gif'>";
    }else{
        return "<img onclick=\"playlist('".$id."','add');\" title='".$lang['lang.add_fav']."' src='images/icon/unfav.gif'>";
    }
}

function home_memory($page,$month,$type=''){     // type: block hoac main
    global $mysql,$lang,$tpl,$table_prefix;
    $htm = $tpl->get_tem_p('default','memory');
    $htm = $tpl->replace_tem($htm,$lang);
    $t = $tpl->auto_get_block($htm);
    $date = timezones(0);
    $year_n = substr($date,0,4);
    $month_n = substr($date,4,2);
    $day_n = substr($date,6,2);
    if(!$month) $month = $month_n;
    $t = $mysql->num_rows($mysql->query("select id from ".$table_prefix."memory where month=".$month." and day>".$day_n.""));
    if(!$total){
        $list = "trong thang nay khong co ngay ky niem nao";
    }else{
        if($type){
            $l = "limit 0,".$type."";
            $h = $t['block.list'];
        }else{
            if(!$page) $page = 1;
            $page_size = 5;
            $limit = ($page-1)*$page_size;
            $l = "limit ".$limit.",".$page_size."";
            $h = $t['main.list'];
        }
        $q = $mysql->query("select * from ".$table_prefix."memory where month=".$month." and day>".$day_n." order by day ASC ".$l."");
        while($r = $mysql->fetch_array($q)){
            $list .= $tpl->replace_tem($h,array(
                'id'    => $r['id'],
                'title' => $r['title'],
                )
            );
        }
    }
    $htm = $tpl->replace_block($htm,array(
        'html'  => $list,
        )
    );
return $htm;
}


/*======================================================================*\
|| #################################################################### ||
|| # NCT CLONE V3.1                                                   # ||
|| # ---------------------------------------------------------------- # ||
|| # Copyright © 2008-2009 AnhTrang.Org . All Rights Reserved.        # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ----------- NCT CLONE IS FREE SOFTWARE - OPEN SOURCE ----------- # ||
|| # http://www.anhtrang.org | http://www.anhtrang.org/support.html   # ||
|| #################################################################### ||
\*======================================================================*/
function grab_link($url,$referer='',$var=''){
    $headers = array(
        "User-Agent: Mozilla/5.0 (compatible; Anhtrangbot/2.1; +http://giaidieunhac.biz)",
        "X-AjaxPro-Method: Download",
        "Content-Length: ".strlen($var),
        "Content-Type: application/x-www-form-urlencoded"
        );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_REFERER, $referer);
    curl_setopt($ch, CURLOPT_COOKIE, "UserSC2=BAFBA85D71D79DBCAC6EC10DF38CCF6C; ASP.NET_SessionId=i1e0yc55dkpyifztx5pzbhq2;");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    if($var) {
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $var);
    }
    curl_setopt($ch, CURLOPT_URL,$url);

    return curl_exec($ch);
}






















?>