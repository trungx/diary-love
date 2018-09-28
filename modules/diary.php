<?php

if($_POST['diary']=='home'){
    $type = 'diary';
    echo diary_list(1);
    exit();
}

if($_POST['diary']=='list'){
    $type= $_POST['type'];
    $page = $_POST['page'];
    $order = $_POST['order'];
    $year = $_POST['year'];
    $month = $_POST['month'];
    $day = $_POST['day'];
    $memid = $_POST['memid'];
    echo diary_list($page);
    exit();
}

if($_POST['diary']=='view'){
    echo diary_view($_POST['id']);
    exit();
}

if($_POST['diary']=='new'){
    $type = $_POST['type'];
    echo diary_post($type);
    exit();
}
// edit
if($_POST['diary']=='edit'){
    $type = $_POST['type'];
    if(!$type) $type ='';
    $id = $_POST['id'];
    echo diary_post($type,$id);
    exit();
}

// danh sach chu de
if($_POST['diary']=='category'){
    $catid =  $_POST['catid'];
    $type = 'category';
    echo diary_list(1);
    exit();
}


if($_POST['diary']=='delete'){
    $id = $_POST['id'];
    $del = $mysql->query('delete from '.$table_prefix.'diary where id='.$id.'');
    if(!$del){
        echo 'error';
    }
    exit();
}

if($_POST['diary']=='submit' || $_POST['diary']=='edit_ok'){
    $message = ($HTTP_POST_VARS['content']);
    $title = $_POST['title'];
    $userid = $_SESSION['userid'];
    $icon = $_POST['feeling'];
    $weather = un_weather($_POST['weather']);
    $type = $_POST['type'];
    $catid = $_POST['catid'];
    $id = $_POST['id'];
    //$lock=$_POST['lock'];
    $len = strlen($message);
        $content = stristr($message,"\n");
        $headlen = strpos($message,"\n");
        $head = substr($message,0,$headlen);
    $date = timezones(0);
    $time = timezones(1);
    $year = substr($date,0,4);
    $month = substr($date,4,2);
    $day = substr($date,6,2);
    if($type == 'diary'){
      if($_POST['diary']=='submit'){
          $mysql->query("insert into ".$table_prefix."diary(title,head,content,userid,catid,weather,year,month,day,time,icon) values ('".$title."','".$head."','".$message."','".$userid."','".$catid."','".$weather."','".$year."','".$month."','".$day."','".$time."','".$icon."')");
      }elseif($_POST['diary']=='edit_ok'){
           $mysql->query("update ".$table_prefix."diary set catid = '".$catid."', title = '".$title."', head = '".$head."', content = '".$message."', weather = '".$weather."', icon = '".$icon."' where id=".$id."");
      }
    }elseif($type == 'category'){
        $arr = array(
            'title' => $title,
            'content'   => $message,
            'cfor'  => 'diary',
            'subid' => $catid,
            );
        if($_POST['diary']=='submit'){
            $mysql->insert('category',$arr);
        }else{
            $mysql->update('category',$arr,'id='.$id);
        }
    }

   exit();
}


function diary_category($catid){
    global $mysql,$lang,$tpl,$table_prefix;
    $htm = $tpl->get_tem('category');
    $htm = $tpl->replace_tem($htm,$lang);
    $t = $tpl->auto_get_block($htm);
    $q = $mysql->query("select * from ".$table_prefix."category where cfor='diary' and subid=".$catid." order by corder ASC");
    while($r = $mysql->fetch_array($q)){
        $list .= $tpl->replace_tem($t['diary.list'],array(
            'id'    => $r['id'],
            'title' => $r['title'],
            'name'  => cut_str($r['title'],32),
            'url'   => "viewpages('main','diary=category&catid=".$r['id']."');",
            )
        );
    }
  $htm = $tpl->replace_block($htm,array(
      'html'  => $list,
      )
  );
return $htm;
}


function diary_list($page){
    global $type,$catid,$mysql,$lang,$tpl,$table_prefix,$order,$year,$month,$day,$memid,$web_diary_limit,$moshtml;
    $htm = $tpl->get_tem('diary');
    $htm = $tpl->replace_tem($htm,$lang);
    $t = $tpl->auto_get_block($htm);
    if($day && $day<10){
        $day = '0'.$day;
    }
    if($order=='new'){
        $sql_order = 'order by id DESC';
    }elseif($order=='old'){
        $sql_order = 'order by id ASC';
    }elseif($order =='readed2'){
        $sql_order = 'order by readed DESC';
    }elseif($order =='readed1'){
        $sql_order = 'order by readed ASC';
    }else{
        $sql_order = 'order by id ASC';
    }

    $sql_select = '';
    if($type == 'member'){
        $sql_select = 'and userid = "'.$memid.'"';
    }elseif($type=='archive'){
        if($year && $month && $day){
            $sql_select .= "and year='".$year."' and month = '".$month."' and day = '".$day."'";
        }elseif($year && $month && !$day){
            $sql_select .= 'and year = "'.$year.'" and month = "'.$month.'"';
        }elseif($year && !$month && !$day){
            $sql_select .= 'and year = "'.$year.'"';
        }
    }elseif($type=='category'){
        $sql_select .= "and catid=".$catid."";
    }
    $total = $mysql->num_rows($mysql->query("select id from ".$table_prefix."diary where draft=0 ".$sql_select.""));
    if(!$total){
        $total = 0;
        $list = '<br><center><font color=red>'.$lang['lang.empty'].'</font></center><br>';
    }else{
        $page_size = $web_diary_limit;
        if(!$page) $page =1;
        $limit = ($page-1)*$page_size;

        $q = $mysql->query("select * from ".$table_prefix."diary where draft=0 ".$sql_select." ".$sql_order." limit ".$limit.",".$page_size."");
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
                'url'    => 'viewpages(\'main\',\'diary=view&id='.$r['id'].'\');web_title(\'Diary - '.$r['title'].'\');',
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
    }
    // check category
        if($type == 'category'){
            $check_cat = $mysql->num_rows($mysql->query("select id from ".$table_prefix."category where cfor='diary' and subid=".$catid.""));
            if($check_cat){
                $sub_cat =  diary_category($catid);
                $sub_show = 'block';
            }else{
                $sub_cat = '';
                $sub_show = 'none';
            }
        }else{
           $sub_cat = '';
           $sub_show = 'none';
        }
    $main = $tpl->replace_tem($t['diary.toplist'],array(
        'list'  => $list,
        'total' => $total,
        'sub.cat'   => $sub_cat,
        'sub.show'  => $sub_show,
        'boyurl'  => "<a href='javascript: void(0);' title='".$lang['lang.post_by']." ".$lang['lang.boy_love']."' onclick=\"viewpages('main','diary=list&type=member&memid=1')\">",
        'allurl'  => "<a href='javascript: void(0);' title='".$lang['lang.post_by']." ".$lang['lang.he']." & ".$lang['lang.she']."' onclick=\"viewpages('main','diary=list')\">",
        'girlurl'  => "<a href='javascript: void(0);' title='".$lang['lang.post_by']." ".$lang['lang.girl_love']."' onclick=\"viewpages('main','diary=list&type=member&memid=2')\">",
        'newurl'  => "<a href='javascript: void(0);' onclick=\"viewpages('main','diary=list&type=".$type."&memid=".$memid."&year=".$year."&month=".$month."&day=".$day."&catid=".$catid."&order=new')\">",
        'oldurl'  => "<a href='javascript: void(0);' onclick=\"viewpages('main','diary=list&type=".$type."&memid=".$memid."&year=".$year."&month=".$month."&day=".$day."&catid=".$catid."&order=old')\">",
        'readdurl'  => "<a href='javascript: void(0);' onclick=\"viewpages('main','diary=list&type=".$type."&memid=".$memid."&year=".$year."&month=".$month."&day=".$day."&catid=".$catid."&order=read2')\">",
        'readaurl'  => "<a href='javascript: void(0);' onclick=\"viewpages('main','diary=list&type=".$type."&memid=".$memid."&year=".$year."&month=".$month."&day=".$day."&catid=".$catid."&order=read1')\">",
        'pages' => viewpages('main',$total,$page_size,$page,'diary=list&type='.$type.'&order='.$order.'&year='.$year.'&month='.$month.'&day='.$day.'&memid='.$memid.'&catid='.$catid),
        )
    );
    $main = $tpl->replace_block($htm,array(
        'html'  => $main,
        )
    );
return $main;
}

function diary_view($id){
    global $mysql,$lang,$tpl,$table_prefix;
    $htm = $tpl->get_tem('diary');
    $htm = $tpl->replace_tem($htm,$lang);
    $t = $tpl->auto_get_block($htm);
    $check = $mysql->num_rows($mysql->query('select id from '.$table_prefix.'diary where id='.$id.''));
    if(!$check){
        $main = "<br><center>".$lang['404']."</br></center>";
    }else{
        $q = $mysql->query('select * from '.$table_prefix.'diary where id='.$id.'');

        while($r=$mysql->fetch_array($q)){
            $w = $r['weather'];
            if($w==1){
                $w_class='sky-flur';
            }elseif($w == 4 || $w == 5){
                $w_class='sky-black';
            }else{
                $w_class="sky-blue";
            }
            if($r['userid']==$_SESSION['userid']){
                $delete = '<img style=\'cursor:pointer;\' onclick="del(\'diary\','.$r['id'].',\'view\');" title="Delete" src=\'images/icon/delete.gif\'>';
                $edit = '<img style=\'cursor:pointer;\' onclick="edit(\'diary\','.$r['id'].',\'view\');" title="Edit" src=\'images/icon/edit.gif\'>';
            }else{
                $delete = '';
                $edit = '';
            }
            $main = $tpl->replace_tem($t['diary.view'],array(
                'id'    => $id,
                'delete'    => $delete,
                'edit'  => $edit,
                'name'  => $r['title'],
                'next'  => _next('diary','title',$id),
                'prev'  => _prev('diary','title',$id),
                'day'   => $r['day'],
                'month' => get_month($r['month']),
                'year'  => $r['year'],
                'weather.icon'  => weather($r['weather'],'icon'),
                'weather.thumb'  => weather($r['weather'],'thumb'),
                'weather.thumb.link'  => weather($r['weather'],'thumb','link'),
                'weather.class' => $w_class,
                'feeling'   => feeling($r['icon']),
                'minidate'  => mini_full_date($r['year'].$r['month'].$r['day'],$r['time']),
                'user'  => get_user_icon('diary',$r['userid']),
              //  'head'  => un_bbcode($r['head']),
                'content'   => un_bbcode($r['content']),
                'comment.total' => $mysql->num_rows($mysql->query("select id from ".$table_prefix."comment where cfor='diary' and catid=".$r['id']."")),
                'comment.list'   => comment(1,'diary',$r['id']),
                'comment.new'   => send_comment('diary',$r['id']),

                )
            );
        }
    }
    $htm = $tpl->replace_block($htm,array(
        'html'  => $main,
        )
    );
return $htm;
}


function diary_post($type='',$id=''){
    // type : diary <=> che do dang bai / category <=> dang chu de
    global $mysql,$lang,$tpl,$table_prefix,$moshtml;
    $htm = $tpl->get_tem('form');
    $h = $tpl->get_block($htm,'diary.post',1);
    if(!$id || $id==''){
        $feeling = 'happy';
        $id = 0;
        $title = '';
        $content= '';
        $weather = 1;
        if($type=='category'){
            $d_lang = $lang['lang.add_category'];
        }else{
            $d_lang = $lang['lang.new_diary'];
        }
    }else{
        if($type=='category'){
            $feeling = '';
            $title = get_config('title','category','id',$id);
            $content = get_config('content','category','id',$id);
            $d_lang = $lang['lang.edit_category'];
            $weather = '';
        }else{
            $feeling = get_config('icon','diary','id',$id);
            $title = get_config('title','diary','id',$id);
            $content = get_config('content','diary','id',$id);
            $d_lang = $lang['lang.edit_diary'];
            $weather = get_config('weather','diary','id',$id);
            $catid = get_config('catid','diary','id',$id);
            if(!$weather) $weather = 1;
        }
    }
    if($type=='category'){
        $f_show = 'none';
        $cat_show = 'block';
        $cat_lv = '';
        $type = 'category';
    }else{
        $f_show = 'block';
        $cat_show = 'block';
        $cat_lv = 1;
        $type = 'diary';
    }

    $feeling_arr = array(
				$moshtml->makeOption( 'happy', '<img src="images/feeling/happy.gif" title="'.$lang['happy'].'">' ),
				$moshtml->makeOption( 'funny', '<img src="images/feeling/funny.gif" title="'.$lang['funny'].'">' ),
				$moshtml->makeOption( 'sad', '<img src="images/feeling/sad.gif" title="'.$lang['sad'].'">' ),
				$moshtml->makeOption( 'bored', '<img src="images/feeling/bored.gif" title="'.$lang['bored'].'">' ),
				$moshtml->makeOption( 'cry', '<img src="images/feeling/cry.gif" title="'.$lang['cry'].'">' ),
				$moshtml->makeOption( 'angry', '<img src="images/feeling/angry.gif" title="'.$lang['angry'].'">' ),
	);
    $weather_list .= select_weather('flur,fair,mclou,rain,sunny');
    $main = $tpl->replace_tem($h,$lang);
    $main = $tpl->replace_tem($h,array(
          'id'  => $id,
          'feeling.list'  => $moshtml->radiolist($feeling_arr,'post-feeling','',$feeling),
          'weather.list'    => $weather_list,
          'content' => $content,
          'title'   => $title,
          'editor'  => bbcode(),
          'weather' => weather($weather,'name'),
          'weather.img' => weather($weather,'thumb'),
          'lang'    => $d_lang,
          'skin.link'   => $_SESSION['template'],

          'type'    => $type,
          'cat.show'    => $cat_show,
          'tool.show'   => $f_show,
          'cat.select'  => $moshtml->select(select_category('diary',$cat_lv),'post-category','',$catid),
          'add.diary'   => "viewpages('main','diary=new');web_title('Diary - ".$lang['lang.new_diary']."');",
          'add.category'=> "viewpages('main','diary=new&type=category');web_title('Diary - ".$lang['lang.new_category']."');",
        )
    );
    $htm = $tpl->replace_block($htm,array(
        'html'  => $main,
        )
    );
    $htm = $tpl->replace_tem($htm,$lang);
    return $htm;
}

function select_weather($arr){
    global $lang;
    $type = explode(',', $arr);
    $total = count($type);
    for($i=0;$i<$total;$i++){
        $img .= "<span style='cursor: pointer;' class='weather-select'  title=\"".$lang[''.$type[$i].'']."\" onclick=\"select_weather('<center><img src=\'images/weather/".$type[$i].".png\' title=\'".$lang[''.$type[$i].'']."\'></center>','".$type[$i]."')\"><img src=\"images/weather/".$type[$i]."-icon.png\"></span>&nbsp;";
   }
    return $img;
}






?>