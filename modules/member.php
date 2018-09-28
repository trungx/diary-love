<?php
    $htm = $tpl->get_tem_p('default','cpanel');
    $htm = $tpl->replace_tem($htm,$lang);
    $t = $tpl->auto_get_block($htm);
// login
if($_POST['member']=='login'){
    $password = md5($_POST['password']);
    $q = $mysql->query('select * from '.$table_prefix.'member where password="'.$password.'"');
    if(!$mysql->num_rows($q)){
      echo 'err';
      exit();
    }
    while($row=$mysql->fetch_array($q)){
                $_SESSION['level'] = $row['level'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['login']  = 'yes';
                $_SESSION['userid'] = $row['id'];
                $_SESSION['sex'] = $row['sex'];
                $_SESSION['fullname'] = $row['fullname'];
                echo $row['sex'];
    exit();
    }
}

if($_POST['member']=='checklogout'){
    echo logout();
    exit();
}

if($_POST['member']=='logout'){
    session_unregister('level');
	session_unregister('username');
	session_unregister('userid');
    session_unregister('login');
    session_unregister('sex');
    session_unregister('fullname');
  //  header("Location: index.php");
  echo login();
}

// skin
if($_POST['member']=='skin'){
    echo member_skin();
    exit();
}

// doi skin
if($_POST['member']=='skin_active'){
    $arr = array(
        'web_template' => $_POST['link'],
    );
    update_file_php('includes/site.config.php',$arr);
    session_unregister('skin');
    $_SESSION['skin']=$_POST['link'];
    exit();
}


// thay doi mat khau
if($_POST['member']=='change_pass'){
    echo  member_changepass();
    exit();
}
if($_POST['member']=='change_pass_ok'){
    $check = get_config('password','member','id',$_SESSION['userid']);
    if($check!=md5($pass_old)){
        echo 'err';
    }elseif($_SESSION['antifloodimage']!=$_POST['capcha']){
        echo 'capcha';
    }else{
        $arr = array(
            'password'  => md5($_POST['pass_new']),
        );
        $mysql->update('member',$arr,'id='.$_SESSION['userid']);
        echo member_changepass();
    }
}

// thay doi thong tin
if($_POST['member']=='change_info'){
    echo member_info();
    exit();
}

if($_POST['member']=='change_info_ok'){
    // check capcha
    if($_SESSION['antifloodimage']!=$_POST['capcha']){
        echo 'err';
    }else{
      $arr =  array(
          'username'  => $_POST['username'],
          'fullname'  => $_POST['fullname'],
          'brithday'  => $_POST['brithday'],
          'yahoo' => $_POST['yahoo'],
          'avatar'    => $_POST['avatar'],
          'email' => $_POST['email'],
          'web'   => $_POST['website'],
          'gavatar'   => $_POST['gavatar'],
          'skype' => $_POST['skype'],
          'icq'   => $_POST['icq'],
          );
      $mysql->update('member',$arr,'id='.$_SESSION['userid']);
      echo member_info();
    }
    exit();
}

// quan ly nhan nhu
if($_POST['member']=='note'){
    echo member_note();
    exit();
}

if($_POST['member']=='change_note_ok'){
    if($_SESSION['sex']==1){
        $arr = array(
           'web_boy_comment'    => $_POST['content'],
        );
    }else{
        $arr =  array(
            'web_girl_comment'  => $_POST['content'],
        );
    }
    update_file_php('includes/site.config.php',$arr);
    echo member_note();
    exit();
}

// ngay trong dai
if($_POST['member']=='memory'){
    echo member_memory();
    exit();
}
// edit va add ngay trong dai
if($_POST['member']=='memory_edit' || $_POST['member']=='memory_add'){
    $id = $_POST['id'];
    echo member_memory_add($id);
    exit();
}

// xoa ngay trong dai
if($_POST['member']=='memory_delete'){
    $id = $_POST['id'];
    $mysql->query("delete from ".$table_prefix."memory where id=".$id."");
    exit();
}

// them ngay trong dai
if($_POST['member']=='memory_add_ok'){
    $date = timezones(0);
    $yearn = substr($date,0,4);
    $total = $yearn - $_POST['year'];
    $arr = array(
        'title' => $_POST['title'],
        'content'   => $_POST['content'],
        'day'   => $_POST['day'],
        'month' => $_POST['month'],
        'year'  => $_POST['year'],
        'total' => $total,
    );
    if($id){
        $mysql->update("memory",$arr,"id=".$id);
    }else{
        $mysql->insert("memory",$arr);
    }
    echo member_memory();
    exit();
}



// quan ly thong tin
if($_POST['member']=='config' || $_POST['member']=='panel'){
    echo member_cpanel();
    exit();

}

// quan ly playlist
if($_POST['member']=='playlist'){
    $page = $_POST['page'];
    echo member_playlist($page);
    exit();
}

// xoa khoi playlist
if($_POST['member']=='playlist_remove'){
    $favorite = get_config('playlist','member','id',$_SESSION['userid']);
    $suserid = $_SESSION['userid'];
    $id = $_POST['id'];
    $z = split(',',$favorite);
    	if(in_array($id,$z)){
    	    unset($z[array_search($id,$z)]);
    		$str = implode(',',$z);
    		if (!$str) {   // neu chua co ca khuc nao trong playlist
                $mysql->query("update ".$table_prefix."member set playlist='' where id=".$suserid."");
    		}else{         // neu da co
          	    $mysql->query("UPDATE ".$table_prefix."member SET playlist = '".$str."' WHERE id = '".$suserid."'");
    	    }
       }
}

// info code and update
if($_POST['member']=='update'){
    echo member_update();
    exit();
}

// tin nhan
if($_POST['member']=='message' || $_POST['member']=='mess'){
    $type = $_POST['type'];
    echo member_message($type);
    exit();
}

// kiem tra dinh ky xem co tin nhan moi khong
if($_POST['member']=='mess_test' || $_POST['member']=='message_test'){
    $total = $mysql->num_rows($mysql->query("select id from ".$table_prefix."message where mto=".$_SESSION['userid']." and readed=0 and todel=0"));
    if(!$total){
        echo '0';
    }else{
        $q = $mysql->query("select id, title, readed from ".$table_prefix."message where mto=".$_SESSION['userid']." and readed=0 and todel=0 order by id DESC");
        if($total=='1'){
            // check id cua tin nhan
            while($r=$mysql->fetch_array($q)){
                echo member_message_view($r['id'],'to');
            }
        }else{
        while($r=$mysql->fetch_array($q)){
            $url = "show_wish(".$r['id'].",'member=message_read&type=to','message')";
            $img = "<img style='marin-right: 5px;' src='images/icon/unread.gif' title='Unread'>";
            $list .= $tpl->replace_tem($t['message.listnew'],array(
                'url'    => $url,
                'title' => cut_str($r['title'],35),
                'img'   => $img,
                )
            );
        }
            $a = $tpl->replace_tem($t['message.listnew.top'],array(
                'skin.link' => $_SESSION['template'],
                'num'   => $total,
                'list'  => $list,
                )
            );
            $show = $tpl->replace_block($htm,array(
                'html'  => $a,
                )
            );
            echo $show;
            //echo member_message('in','new');

        }
    }
   exit();
}

// gui tin nhan
if($_POST['member']=='message_new'){
    echo member_message_new();
    exit();
}

if($_POST['member']=='message_submit'){
    // check
    if($_SESSION['sex']==1){
        $to = get_config('id','member','sex','2');
    }else{
        $to = get_config('id','member','sex','1');
    }
    $date = timezones(0);
    $time = timezones(1);
    $year = substr($date,0,4);
    $month = substr($date,4,2);
    $day = substr($date,6,2);
    $arr = array(
        'mfrom'  => $_SESSION['userid'],
        'mto'    => $to,
        'title' => $_POST['title'],
        'message'   => $_POST['content'],
        'date'  => $day.'-'.$month.'-'.$year,
        'time'  => $time,
        );
    $mysql->insert('message',$arr);
    echo member_message('out');
    exit();
}

// doc tin nhan
if($_POST['member']=='message_read'){
    $type = $_POST['type'];
    $id = $_POST['id'];
    echo member_message_view($id,$type);
    exit();
}

// xoa tin
if($_POST['member']=='message_del'){
    $type = $_POST['type'];
    $id = $_POST['id'];
    if($type=='from'){
        $check = get_config('todel','message','id',$id);
    }else{
        $check = get_config('fromdel','message','id',$id);
    }
    if($check=='1'){
        $mysql->query('delete from '.$table_prefix.'message where id='.$id.'');
    }else{
        if($type=='from'){
            $mysql->query('update '.$table_prefix.'message set fromdel=1 where id='.$id.'');
        }else{
            $mysql->query('update '.$table_prefix.'message set todel=1 where id='.$id.'');
        }
    }
}


function member_cpanel(){
    global $mysql,$lang,$tpl,$table_prefix,$htm,$t;
    if($_SESSION['sex']==1){
        $admin = $tpl->replace_tem($t['admin'],array(
            'skin.link' => $_SESSION['template'],
            )
        );
    }else{
        $admin = '';
    }
    $main = $tpl->replace_tem($t['cpanel'],array(
        'admin.tool'    => $admin,
        'skin.link' => $_SESSION['template'],
        'main'  => '',
        )
    );
    $htm = $tpl->replace_block($htm,array(
        'html'  => $main,
        )
    );
return $htm;
}

function member_changepass(){
    global $mysql,$lang,$tpl,$table_prefix,$htm,$t;
    $main = $tpl->replace_tem($t['changepass'],array(
        'skin.link' => $_SESSION['template'],
        'capcha'    => 'includes/security.php?'.time(),
        )
    );
    $htm = $tpl->replace_block($htm,array(
        'html'  => $main,
        )
    );
return $htm;
}

function member_info(){
    global $mysql,$lang,$tpl,$table_prefix,$moshtml,$htm,$t;
    $q = $mysql->query("select * from ".$table_prefix."member where id = ".$_SESSION['userid']."");
    while($r=$mysql->fetch_array($q)){
        $day = explode("-",$r['brithday']);
        $list = $tpl->replace_tem($t['change_info'],array(
            'username'  => $r['username'],
            'fullname'  => $r['fullname'],
            'yahoo' => $r['yahoo'],
            'icq'   => $r['icq'],
            'skype' => $r['skype'],
            'website'   => $r['web'],
            'email' => $r['email'],
            'avatar'    => $r['avatar'],
            'gavatar'   => $moshtml->yesno("gavatar","",$r['gavatar']),
            'day'   => $day[0],
            'month' => $day[1],
            'year'  => $day[2],
            'gavatar.link'  => "http://www.gravatar.com/avatar/".md5($r['email']),
            'capcha'    => 'includes/security.php?'.time(),
            )
        );
    }
    $htm = $tpl->replace_block($htm,array(
        'html'  => $list,
        )
    );
return $htm;
}

function member_note(){
    global $web_girl_comment,$tpl,$lang,$htm,$t;
    include('includes/site.config.php');
    if($_SESSION['sex']==1){
        $note = $web_boy_comment;
    }else{
        $note = $web_girl_comment;
    }
    $main = $tpl->replace_tem($t['note'],array(
        'skin.link' => $_SESSION['template'],
        'note'  => $note,
        'editor'  => bbcode_mini(),
        )
    );
    $htm = $tpl->replace_block($htm,array(
        'html'  => $main,
        )
    );
return $htm;
}

function member_memory($more=''){        // more != '' => cac ngay trong dai
    global $mysql,$lang,$tpl,$htm,$t,$table_prefix,$m_month;
    $date = timezones(0);
    $month = !$m_month?substr($date,4,2):$m_month;
    if($more){
        if($more=='top'){
            $total = $mysql->num_rows($mysql->query("select id from ".$table_prefix."memory where top='yes'"));
            $q = $mysql->query("select * from ".$table_prefix."memory where top='yes' order by id ASC");
        }elseif($more=='now'){
            $total = $mysql->num_rows($mysql->query("select * from ".$table_prefix."memory where month=".$month.""));
            $q = $mysql->query("select * from ".$table_prefix."memory where month=".$month." order by day ASC");
        }else{
            $total = $mysql->num_rows($mysql->query("select * from ".$table_prefix."memory where top='no' and month<>".$month.""));
            $q = $mysql->query("select * from ".$table_prefix."memory where top='no' and month<>".$month." order by id ASC");
        }
        if(!$total){
            $main = "<center>".$lang['lang.empty']."</center>";
        }else{
          while($r=$mysql->fetch_array($q)){
              $total1 = $r['total'] + 1;
              $main .= $tpl->replace_tem($t['memory.list'],array(
                  'id'    => $r['id'],
                  'content'   => $r['content'],
                  'title' => $r['title'],
                  'num'   => "<i>".$lang['lang.num']."&nbsp;<b>".$total1."</b></i>",
                  'date'  => mini_full_date($r['year'].$r['month'].$r['day']),
                  'edit'    => "<img src='images/icon/edit.gif' style='cursor: pointer;' title='Edit' border=0 onclick=\"edit('member=memory_edit&type',".$r['id'].",'','memory_new');\">",
                  'del'    => "<img src='images/icon/delete.gif' style='cursor: pointer;' title='Delete' border=0 onclick=\"del('member=memory_delete&type',".$r['id'].",'','','memory');\">",
                  )
              );
          }
          $main = $tpl->replace_tem($main,$lang);
        }
        return $main;
    }else{
    $all = $tpl->replace_tem($t['memory.top'],array(
          'now'   => member_memory('now'),
          'top'   => member_memory('top'),
          'more'  => member_memory('list'),
          )
        );
        $htm = $tpl->replace_block($htm,array(
          'html'  => $all,
          )
        );
    return $htm;
    }
}

function member_memory_add($id=''){
    global $mysql,$lang,$tpl,$htm,$table_prefix,$moshtml,$t;
    if($id){
        $q = $mysql->query("select * from ".$table_prefix."memory where id=".$id."");
        while($r=$mysql->fetch_array($q)){
            $title =  $r['title'];
            $content = $r['content'];
            $day = $r['day'];
            $month = $r['month'];
            $year = $r['year'];
        }
    }else{
        $title = $content =  $day = $month = $year = '';
    }
    $main = $tpl->replace_tem($t['memory.add'],array(
        'id'    => $id,
        'title' => $title,
        'content'   => $content,
        'day'   => $day,
        'month' => $month,
        'year'  => $year,
        'editor'  =>   bbcode_mini(),
        )
    );
    $htm = $tpl->replace_block($htm,array(
        'html'  => $main,
        )
    );
return $htm;
}


// quan ly playlist
function member_playlist($page,$mode=''){
    global $mysql,$lang,$tpl,$table_prefix,$order,$t,$htm;
    $html = $tpl->get_tem('music');
    $html = $tpl->replace_tem($html,$lang);
    $t2 = $tpl->auto_get_block($html);
    if(!$page) $page = 1;
    $playlist = get_config('playlist','member','id',$_SESSION['userid']);
    $arr = explode(",",$playlist);
    $total = count($arr);
    if($playlist==''){
        $total =  0;
        $list = "<center>".$lang['lang.empty']."</center>";
    }else{
        $page_size = 12;
        $limit =  ($page-1)*$page_size;
        $q = $mysql->query("select * from ".$table_prefix."music where id IN (".$playlist.") ".$o." limit ".$limit.",".$page_size."");
        while($r=$mysql->fetch_array($q)){
            $list .= $tpl->replace_tem($t2['song.list'],array(
                'id'    => $r['id'],
                'edit'  => '',
                'delete'     => '',
                'title' => $r['title'],
                'name'  => cut_str($r['title'],35),
                'url'   => "play('music=play&id=".$r['id']."','music=song_info&id=".$r['id']."');",
                'type'  => music_type($r['type']),
                'user'  => "<span style='cursor: pointer;' onclick=\"viewpages('main','music=list&type=member&muserid=".$r['userid']."')\">".user_icon($r['userid'],'icon')."</span>",
                'played'=> $r['played'],
                'singer'    => get_singer($r['singer']),
                'downloaded'  => $r['download'],
                'fav'   => "<span style='cursor: pointer;' onclick=\"del('member=playlist_remove&type','".$r['id']."','','','music');\" title='Remove'><img src='images/icon/delete.gif' border=0></span>",
                )
            );

        }
    }
    $main = $tpl->replace_tem($t['top.list'],array(
            'song.list' => $list,
            'total' => $total,
            'pages' => $pageview,
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

function member_skin(){
    global $mysql,$tpl,$htm,$t,$table_prefix,$lang,$web_template;
    $handle=opendir("template/");
    while (false !== ($file=readdir($handle))) {
		if (!empty($file) && $file!="." && $file!=".." && file_exists("template/".$file."/info.php") ) {
        include('template/'.$file.'/info.php');
        $color = $file==$web_template?"red":"#000";
        $color =  $file==$web_template?"red":"#000";
        $url = $file==$web_template?"alert('Giao dien ".$tpl_info['name']." hien dang kich hoat');":"change_template('".$tpl_info['name']."','".$file."')";
        $list .= $tpl->replace_tem($t['skin.list'],array(
            'name'  => $tpl_info['name'],
            'link'  => $file,
            'thumb' => 'template/'.$file.'/thumb.jpg',
            'url'   => $url,
            'color' => $color,
            'author'    => $tpl_info['author'],
            )
        );
    	}
    }
    $main = $tpl->replace_tem($t['skin.top'],array(
        'list'  => $list,
        'skin.link' => $_SESSION['template'],
        )
    );
    $htm = $tpl->replace_block($htm,array(
        'html'  => $main,
        )
    );
return $htm;
}


function member_update(){
    global $mysql,$lang,$tpl,$table_prefix,$htm,$t;
    include('includes/config.php');
    $ud = get_url($update_link.'update.txt');
    $update = explode('||', $ud);
    if($update[0]>$version){
        $update[2] = "<a href='".$update[3]."' title='T?i b?n cài d?t DiaryLove v".$update[0]."' target='_blank'>B?n Full</a>";
        $update[3] = "<a href='".$update[4]."' title='T?i b?n update DiaryLove v".$update[0]."' target='_blank'>B?n Update</a>";
        $update[0] .= '&nbsp;<img src="images/icon/new.gif">';

    }
    $main = $tpl->replace_tem($t['update'],array(
        'name'  => '<a href="mailto: duonghoanglong85@gmail.com">DiaryLove</a>',
        'version'   =>  $version,
        'code.date' => $codedate,
        'diary.date'    => $date,
        'newver'    => $update[0],
        'newdate'    => $update[1],
        'newfunction'   => $update[4],
        'newfull'   => $update[2],
        'newupdate'   => $update[3],
        'logo'  => "<div style='text-align: center;'><a href='".$update[5]."' onclick=\"return hs.expand(this)\"><img border=0 onload=\"HSImageResizer.createOn(this);\" src='".$update[5]."'></a></div>",
        )
    );
    $htm = $tpl->replace_block($htm,array(
        'html'  => $main,
        )
    );
return $htm;
}


function member_message($type='',$new=''){
    // new: danh sach cac tin nhan moi khi thong bao
    //type: in / out => tin da nhan / tin da gui
    global $mysql,$lang,$tpl,$table_prefix,$htm,$t;
    $userid = $_SESSION['userid'];
    if($new){
        $n = "and readed=0";
    }
    if($type=='out'){
        $q = $mysql->query("select * from ".$table_prefix."message where mfrom=".$userid." ".$n." and fromdel=0 order by id DESC");
    }else{
        $q = $mysql->query("select * from ".$table_prefix."message where mto=".$userid." ".$n." and todel=0 order by id DESC");
    }
  //  $q = $mysql->query("select * from ".$table_prefix."message");
    while($r=$mysql->fetch_array($q)){
        $date = timezones(0);
        $year = substr($date,0,4);
        $month = substr($date,4,2);
        $day = substr($date,6,2);
        if($r['date']==$day.'-'.$month.'-'.$year){
            $time =  $r['time'];
        }else{
            $time = $r['date'];
        }
        if($type=='out'){
            $img = "<img src='images/icon/sended.gif' title='Sended'>";
            $tool = "<img src='images/icon/delete.gif' height='25' onclick=\"del('member=message_del&type=from&abc',".$r['id'].",'','','message');\" class='folder' style='cursor: pointer;' title='Delete'>";
            $url = "show_wish(".$r['id'].",'member=message_read&type=from','message')";
        }else{
            if($r['readed']=='0'){
                $img = "<img src='images/icon/unread.gif' title='Unread'>";
            }else{
                $img = "<img src='images/icon/readed.gif' title'Readed'>";
            }
            $tool = "<img src='images/icon/delete.gif' height='25' onclick=\"del('member=message_del&type=to&abc',".$r['id'].",'','','message');\" class='folder' style='cursor: pointer;' title='Delete'>";
            $url = "show_wish(".$r['id'].",'member=message_read&type=to','message')";
        }
        $list .= $tpl->replace_tem($t['message.list'],array(
            'id'    => $r['id'],
            'title' => $r['title'],
            'time'  => $time,
            'content'   => cut_str($r['message'],40),
            'readed'    => $readed,
            'img'   => $img,
            'tool'  => $tool,
            'url'   => $url,
            )
        );
    }
    $show = $new?'none':'block';
    $main = $tpl->replace_tem($t['message.top'],array(
        'skin.link' => $_SESSION['template'],
        'list'  => $list,
        'tool.show' => $show,
        )
    );
    $htm = $tpl->replace_block($htm,array(
        'html'  => $main,
        )
    );
return $htm;
}

function member_message_view($id,$type=''){
    global $mysql,$lang,$tpl,$table_prefix,$htm,$t;
    if($type=='to'){
        $mysql->query("update ".$table_prefix."message set readed='1' where id=".$id."");
    }
    $q = $mysql->query("select * from ".$table_prefix."message where id=".$id."");
    while($r=$mysql->fetch_array($q)){
        $date = timezones(0);
        $year = substr($date,0,4);
        $month = substr($date,4,2);
        $day = substr($date,6,2);
        if($r['date']==$day.'-'.$month.'-'.$year){
            $time =  $lang['lang.at'].'&nbsp;'.$r['time'];
        }else{
            $a = explode('-',$r['date']);
            $time = mini_full_date($a[2].$a[1].$a[0],$r['time']);
        }
        $main = $tpl->replace_tem($t['message.view'],array(
            'id'    => $r['id'],
            'title' => $r['title'],
            'content'   => un_bbcode($r['message']),
            'skin.link' => $_SESSION['template'],
            'time' => $time,
            )
        );
    }
    $htm = $tpl->replace_block($htm,array(
        'html'  => $main,
        )
    );
return $htm;
}

function member_message_new(){
    global $mysql,$lang,$tpl,$table_prefix,$htm,$t;
    $main = $tpl->replace_tem($t['message.add'],array(
        'skin.link' => $_SESSION['template'],
        'to'    => get_user(),
        'editor'    => bbcode_mini(),
        )
    );
    $htm = $tpl->replace_block($htm,array(
        'html'  => $main,
        )
    );
return $htm;
}



?>
