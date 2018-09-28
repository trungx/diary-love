<?php
 /// xuat xml de chieu slide anh
 if($_GET['album_xml'] || $_GET['archive_xml'] || $_GET['member_xml']){
   include('../includes/connect.php');
   include('../includes/site.config.php');
   include('../includes/config.functions.php');
   if($_GET['album_xml']){
        $abid = $_GET['album_xml'];
        $q = $mysql->query('select id, title, link from '.$table_prefix.'gallery where catid="'.$abid.'" order by id DESC');
   }elseif($_GET['archive_xml']){
        $arr = explode('-',$_GET['archive_xml']);
        $year = $arr[0];
        $month = $arr[1];
        $day = $arr[2];
     if($year && $month && $day){
              $a .= "and year='".$year."' and month = '".$month."' and day = '".$day."'";
          }elseif($year && $month && !$day){
              $a .= 'and year = "'.$year.'" and month = "'.$month.'"';
          }elseif($year && !$month && !$day){
              $a .= 'and year = "'.$year.'"';
          }
            $q = $mysql->query("select id, link, title from ".$table_prefix."gallery where draft='no' ".$a."");
   }elseif($_GET['member_xml']){
        $userid = $_GET['member_xml'];
        $q = $mysql->query('select id, title, link from '.$table_prefix.'gallery where userid='.$userid.'');
   }
        while($r=$mysql->fetch_array($q)){
            $pic_list .= "<track>
			                <title>".$r['title']."</title>
                            <id>".$r['id']."</id>
			                <location>".$r['link']."</location>
			                <info>javascript: viewpages('main','gallery=view&id=".$r['id']."');web_title('Gallery - ".get_config('title','gallery','id',$r['id'])."');</info>
		                </track> ";
        }
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
                <playlist version="1" xmlns="http://xspf.org/ns/0/">
                    <trackList>
                        '.$pic_list.'
                    </trackList>
                </playlist>';
        echo $xml;
    exit();
 }





if($_POST['gallery']=='home'){
    echo gallery_home();
    exit();
}
if($_POST['gallery']=='list'){
   // $mysql,$lang,$tpl,$table_prefix,$order,$web_gallery_limit,$type,$year,$month,$day,$memid;
    $type = $_POST['type'];
    $year = $_POST['year'];
    $month = $_POST['month'];
    $day = $_POST['day'];
    $memid = $_POST['memid'];
    $page = $_POST['page'];
    echo gallery_list($page);
  //  echo gallery_album_list(1);
    exit();
}

// xoa anh
if($_POST['gallery']=='delete'){
    $id = $_POST['id'];
    $type = $_POST['type'];
    if($type=='album'){
        // tinh tong so anh
        $test = $mysql->num_rows($mysql->query('select id from '.$table_prefix.'gallery where catid='.$id.''));
        if($test){
            echo 'error';
        }else{
            $mysql->query('delete from '.$table_prefix.'album where id='.$id.'');
        }
    }else{
        $mysql->query('delete from '.$table_prefix.'gallery where id='.$id.'');
    // xoa loi binh cho anh nay
        $mysql->query("delete from ".$table_prefix."comment where cfor='gallery' and catid=".$id."");
    }
    exit();
}

// dang anh
if($_POST['gallery']=='add' || $_POST['gallery']=='edit'){
    $type = $_POST['type'];
    $id = $_POST['id'];
    $abid = $_POST['abid'];
    echo gallery_post($type,$id);
    exit();
}

if($_POST['gallery']=='category_list'){
    echo gallery_category();
    exit();
}

if($_POST['gallery']=='rate'){
    $rate = $_POST['star'];
    $id = $_POST['id'];

    $mysql->query('update '.$table_prefix.'gallery set rate=rate+'.$rate.', numrate=numrate+1 where id='.$id.'');
    echo star(get_config('rate','gallery','id',$id),get_config('numrate','gallery','id',$id));
    exit();
}

// chieu anh
if($_POST['gallery']=='slide'){
    $type = $_POST['type'];
    $year =  $_POST['year'];
    $month = $_POST['month'];
    $day = $_POST['day'];
    $userid = $_POST['userid'];
    echo gallery_slide($id);
    exit();
}

// xem danh sach anh trong album
if($_POST['gallery']=='album' || $_POST['gallery']=='list'){
    $abid = $_POST['id'];
    $type = $_POST['type'];
    $year = $_POST['year'];
    $memid = $_POST['memid'];
    $month = $_POST['month'];
    $day = $_POST['day'];
    $page = $_POST['page'];
    $order = $_POST['order'];
    echo gallery_list($page,$abid);
    exit();
}

// xem anh
if($_POST['gallery']=='view'){
//    $id = $_POST['id'];
    echo gallery_view($id);
    exit();
}

// danh sach album
if($_POST['gallery']=='album_list' || $_POST['gallery']=='category'){
    $type = $_POST['gallery']=='category'?'category':$_POST['type'];
    $page = $_POST['page'];
    $catid = $_POST['catid'];
    $order = $_POST['order'];
    echo gallery_album_list($page);
    exit();
}


// dang anh/album/category
if($_POST['gallery']=='add_submit'){
    if($type=='album'){ // add album
      $arr =  array(
          'title'   => $title,
          'content' => $content,
          'thumb'   => $thumb,
          'catid'   => $category,
          'afor'    => 'gallery',
          );
      if($id){
        $mysql->update('album',$arr,'id='.$id);
      }else{
        $mysql->insert('album',$arr);
      }
      echo gallery_album_list($page);
    }elseif($type=='category'){
        $arr = array(
            'cfor'  => 'gallery',
            'thumb' => $thumb,
            'subid' => $category,
            'title' => $title,
            'content'   => $content,
        );
        if($id){
            $mysql->update('category',$arr,'id='.$id);
        }else{
            $mysql->insert('category',$arr);
        }
        echo gallery_category();
    }else{
        $date = timezones(0);
        $time = timezones(1);
        $year = substr($date,0,4);
        $month = substr($date,4,2);
        $day = substr($date,6,2);
        $arr = array(
            'title' => $title,
            'content'   => $content,
            'thumb' => $thumb,
            'catid' => $album,
            'link'  => $link,
            'year'  => $year,
            'month' => $month,
            'day'   => $day,
            'userid'=> $_SESSION['userid'],
        );
        if($id){
            $mysql->update('gallery',$arr,'id='.$id);
        }else{
            $mysql->insert('gallery',$arr);
        }
        echo gallery_list(1,$album);
    }
exit();
}


function gallery_home(){
    global $mysql,$lang,$tpl,$table_prefix;
    $htm = $tpl->get_tem('gallery');
    $htm = $tpl->replace_tem($htm,$lang);
    $t = $tpl->auto_get_block($htm);
    $add = "viewpages('main','gallery=add&type=pic');web_title('Gallery - ".$lang['lang.add_pic']."');";
    $home = $tpl->replace_tem($t['home'],array(
        'skin.link' => $_SESSION['template'],
        'album.new' => gallery_album_list(1,3),
        'pic.new'   => gallery_new(6),
        'cat.new'   => gallery_category(3),
        'upload.link'   => $add,
        )
    );
    $htm = $tpl->replace_block($htm,array(
        'html'  => $home,
        )
    );
return $htm;
}


// danh sach chu de
function gallery_category($limit='',$subid=''){
    // limit => cac chu de moi nhat
    // subid => danh sach chu de con trong chu de chinh
    global $mysql,$lang,$tpl,$table_prefix;
    $htm = $tpl->get_tem('category');
    $htm = $tpl->replace_tem($htm,$lang);
    $t = $tpl->auto_get_block($htm);
    if($subid){
        $s = "and subid=".$subid;
    }else{
        $s = "and subid=0";
    }
    $q = $mysql->query("select id, title from ".$table_prefix."category where cfor='gallery' ".$s."");
    $total = $mysql->num_rows($q);
    if(!$total){
        $total = 0;
        $list = "<br><center><b>".$lang['empty']."</b></center></br>";
    }else{
        if($limit) $q =  $mysql->query("select id, title from ".$table_prefix."category where cfor='gallery' order by id DESC limit 0,".$limit."");
        while($r=$mysql->fetch_array($q)){
            $list .= $tpl->replace_tem($t['gallery.list'],array(
                'id'    => $r['id'],
                'title' => $r['title'],
                'name'  => cut_str($r['title'],35),
                'skin.link' => $_SESSION['template'],
                'url'   => "viewpages('main','gallery=category&type=category&catid=".$r['id']."')",
                )
            );
        }
    }
    if($limit || $subid){
        $main = $list;
    }else{
        $main = $tpl->replace_tem($t['gallery.top.list'],array(
            'skin.link' => $_SESSION['template'],
            'cat.list'  => $list,
            'total' => $total,
            'add.category'  => "viewpages('main','gallery=add&type=category');web_title('Gallery - ".$lang['lang.add_category']."');",
            )
        );
    }
    $htm = $tpl->replace_block($htm,array(
        'html'  => $main,
        )
    );
return $htm;
}

function gallery_album_list($page,$a='',$om=''){
    // a : so anh. neu a != '' <=> cac anh khac
    // om : order more
    global $mysql,$lang,$tpl,$table_prefix,$order,$catid,$g_userid,$type;
    $htm = $tpl->get_tem('gallery');
    $htm = $tpl->replace_tem($htm,$lang);
    $t = $tpl->auto_get_block($htm);
    // category
    if($type=='category'){
        $c .= "and catid='".$catid."'";
    }
    // user
    if($g_userid>0){
        $u .= " and userid='".$g_userid."'";
    }
    $total = $mysql->num_rows($mysql->query("select id from ".$table_prefix."album where afor = 'gallery' ".$c.""));
    if(!$total){
        $total = 0;
        $list = "<center>".$lang['lang.empty']."</center>";
    }else{
      if(!$page) $page =1;
      $page_size = 12;
      $limit = ($page-1)*$page_size;

      // on index
      if($a){
          $s = 'limit 0,'.$a.'';
      }else{
          $s = "limit ".$limit.",".$page_size."";
      }
      // order
      if($order=='new'){
          $o = "order by id DESC";
      }elseif($order == 'name1'){ // giam dan
          $o = "order by title DESC";
      }elseif($order == 'name2'){ // tang dan
          $o = "order by title ASC";
      }elseif($om){
          $o = "and id<>".$om." order by RAND() ";
      }elseif($a){
            $o = "order by id DESC";
      }
          $q = $mysql->query("select id, title, userid, thumb, content, catid from ".$table_prefix."album where afor='gallery' ".$c." ".$u." ".$o." ".$s."");
          while($r=$mysql->fetch_array($q)){
              $total_pic = $mysql->num_rows($mysql->query("select id from ".$table_prefix."gallery where catid=".$r['id'].""));
              $slide_link = !$total_pic?"alert('".$lang['lang.not_slide']."'); return false;":"viewpages('main','gallery=slide&type=album&id=".$r['id']."');web_title('Gallery - Album: ".get_config('title','album','id',$r['id'])." ( Slide show )')";
              $list .= $tpl->replace_tem($t['album.list'],array(
                  'skin.link' => $_SESSION['template'],
                  'title' => $r['title'],
                  'name'  => cut_str($r['title'],30),
                  'thumb' => $r['thumb'],
                  'id'    => $r['id'],
                  'slide.link'    => $slide_link,
                  'total.pic' => $total_pic,
                  'content'   => cut_str(un_bbcode($r['content']),75),
                  'url'   => "viewpages('main','gallery=album&id=".$r['id']."');web_title('Gallery - Album: ".$r['title']."');",
                  )
              );
          }
    }
    if($a){
        $htm = $tpl->replace_block($htm,array(
          'html'  => $list,
          )
      );
    }else{
      if($type=='category'){
        // check chu de chinh
        $cat_check = $mysql->num_rows($mysql->query("select id from ".$table_prefix."category where cfor='gallery' and subid=".$catid.""));
        if($cat_check){
            $sub_cat = gallery_category('',$catid);
            $sub_show = 'block';
        }else{
            $sub_cat = '';
            $sub_show = 'none';
        }
      }else{
            $sub_cat = '';
            $sub_show = 'none';
        }
      $main = $tpl->replace_tem($t['album.top.list'],array(
            'album.list'    => $list,
            'sub.cat'       => $sub_cat,
            'sub.show'      => $sub_show,
            'upload.link' => "viewpages('main','gallery=add&type=pic');web_title('Gallery - ".$lang['lang.add_pic']."');",
            'add.album' => "viewpages('main','gallery=add&type=album');web_title('Gallery - ".$lang['lang.add_album']."');",
            'total' => $total,
            'skin.link' => $_SESSION['template'],
            'pages' => viewpages('main',$total,$page_size,$page,'gallery=album_list&order='.$order.'&type='.$type.'&catid='.$catid.''),
            )
        );
        $htm = $tpl->replace_block($htm,array(
            'html'  => $main,
            )
        );
    }
return $htm;
}


// Danh sach anh
function gallery_list($page,$abid=''){
    global $mysql,$lang,$tpl,$table_prefix,$order,$web_gallery_limit,$type,$year,$month,$day,$memid;
    $htm = $tpl->get_tem('gallery');
    $htm = $tpl->replace_tem($htm,$lang);
    $t = $tpl->auto_get_block($htm);
    $show = $_SESSION['sex']==1?"block":"none";
    if(!$page) $page = 1;
        //$page_size = 1;
        $page_size = $web_gallery_limit;
        $limit = ($page-1)*$page_size;
        if($order == 'new'){
            $o = "order by id DESC";
        }elseif($order =='name1'){  // ten tang dan
            $o = "order by title ASC";
        }elseif($order =='name2'){
            $o = "order by title DESC";
        }else{
            $o = "order by id ASC";
        }
        $a = '';
        if($type=='archive'){
            if($year && $month && $day){
              $a .= "and year='".$year."' and month = '".$month."' and day = '".$day."'";
              $albumname = $day."-".$month."-".$year;
          }elseif($year && $month && !$day){
              $a .= 'and year = "'.$year.'" and month = "'.$month.'"';
              $albumname = $month."-".$year;
          }elseif($year && !$month && !$day){
              $a .= 'and year = "'.$year.'"';
              $albumname = $year;
          }

            $q = $mysql->query("select * from ".$table_prefix."gallery where draft='no' ".$a." ".$o." limit ".$limit.",".$page_size."");
            $total = $mysql->num_rows($mysql->query("select * from ".$table_prefix."gallery where draft='no' ".$a.""));
            $slide_link = !$total?"alert('".$lang['lang.not_slide']."'); return false;":"viewpages('main','gallery=slide&type=archive&year=".$year."&month=".$month."&day=".$day."');web_title('Gallery - ".$day."/".$month."/".$year." ( Slide Show )');";
            $boyid = get_config('id','member','sex',1);
            $girlid = get_config('id','member','sex',2);
            $user = "<a href=\"javascript:;\" onclick=\"viewpages('main','gallery=list&amp;type=member&amp;memid=".$boyid."');\" title='".$lang['lang.post_by']."&nbsp;".$lang['lang.boy_love']."'><font color=blue>".$lang['lang.he']."</font></a> & <a href=\"javascript:;\" onclick=\"viewpages('main','gallery=list&amp;type=member&amp;memid=".$girlid."');\" title='".$lang['lang.post_by']."&nbsp;".$lang['lang.girl_love']."'><font color=red>".$lang['lang.she']."</font></a>";
        }elseif($type=='member'){
            $a .= 'and userid='.$memid;
            $s = get_config('sex','member','id',$memid);
            $u2 = $s =='1'?"<font color=blue>".$lang['lang.boy_love']."</font>":"<font color=red>".$lang['lang.girl_love']."</font>";
            $user = get_user_icon('gallery',$memid)."&nbsp;".$u2;
            $albumname = $user;

            $q = $mysql->query("select * from ".$table_prefix."gallery where draft='no' ".$a." ".$o." limit ".$limit.",".$page_size."");
            $total = $mysql->num_rows($mysql->query("select * from ".$table_prefix."gallery where draft='no' ".$a.""));
            $slide_link = !$total?"alert('".$lang['lang.not_slide']."'); return false;":"viewpages('main','gallery=slide&type=member&userid=".$memid."');web_title('Gallery - ".$username." ( Slide show )')";
        }else{
            $q = $mysql->query("select * from ".$table_prefix."gallery where draft='no' and catid=".$abid." ".$o." limit ".$limit.",".$page_size."");
            $total = $mysql->num_rows($mysql->query("select id from ".$table_prefix."gallery where draft='no' and catid=".$abid.""));
            $slide_link = !$total?"alert('".$lang['lang.not_slide']."'); return false;":"viewpages('main','gallery=slide&type=album&id=".$abid."');web_title('Gallery - Album: ".get_config('title','album','id',$abid)." ( Slide show )')";
            $user = '<font color=blue>'.$lang['lang.he'].'</font> & <font color=red>'.$lang['lang.she'].'</font>';
            // update luot xem
            $mysql->query('update '.$table_prefix.'album set viewed=viewed+1 where id='.$abid.'');
        }
        $add = "viewpages('main','gallery=add&type=pic&abid=".$abid."')";
    if(!$total){
        $total = 0;
        $list = "<br><center>".$lang['empty']."</center><br>";
    }else{
        while($r = $mysql->fetch_array($q)){
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
                'url'   => "viewpages('main','gallery=view&id=".$r['id']."');web_title('Gallery - ".$r['title']."');",
                )
            );
        }
    }
    $edit = $_SESSION['sex']==1?"<img height='16' src='images/icon/edit.gif' style='cursor: pointer;' title='Edit' onclick=\"edit('gallery',".$abid.",'&amp;type=album');\">":"";
    $delete = $_SESSION['sex']==1?"<img height='16' src='images/icon/delete.gif' style='cursor: pointer;' title='Delete' onclick=\"del('gallery',".$abid.",'view','type=album');\">":"";
    $main = $tpl->replace_tem($t['gallery.top.list'],array(
        'album.name' => $albumname,
        'view' => get_config('viewed','album','id',$abid),
        'content'   => un_bbcode(get_config('content','album','id',$abid)),
        'total' => $total,
        'skin.link' => $_SESSION['template'],
        'gallery.list'  => $list,
        'user'      => $user,
        'slide.link'    => $slide_link,
        'upload.link'   => $add,
        'edit'  => $edit,
        'delete'    => $delete,
        'tool.show' => $show,
        'pages' => viewpages('main',$total,$page_size,$page,'gallery=album&id='.$abid.'&order='.$order.'&type='.$type.'&year='.$year.'&month='.$month.'&day='.$day.'&memid='.$memid.''),
        )
    );
    $htm = $tpl->replace_block($htm,array(
        'html'  => $main,
        )
    );
return $htm;
}


function gallery_view($id){
    global $mysql,$lang,$tpl,$table_prefix;
    $htm = $tpl->get_tem('gallery');
    $htm = $tpl->replace_tem($htm,$lang);
    $t = $tpl->auto_get_block($htm);
    $mysql->query('update '.$table_prefix.'gallery set viewed=viewed+1 where id='.$id.'');
    $q = $mysql->query('select * from '.$table_prefix.'gallery where id='.$id.'');
    while($r = $mysql->fetch_array($q)){
        $s = get_config('sex','member','id',$r['userid']);
        $u2 = $s =='1'?"<font color=blue>".$lang['lang.boy_love']."</font>":"<font color=red>".$lang['lang.girl_love']."</font>";
        $u = get_user_icon('gallery',$r['userid'])."&nbsp;".$u2;
       // $total = $mysql->num_rows($mysql->query("select id from ".$table_prefix."gallery where catid=".$r['catid']." and id<>".$r['id'].""));
//        $slide_link = !$total?"alert('".$lang['lang.not_slide']."'); return false;":"viewpages('main','gallery=slide&amp;id=".$r['catid']."');web_title('Gallery - Album: ".get_config('title','album','id',$r['catid'])." ( Slide show )')";
        $slide_link = "viewpages('main','gallery=slide&type=album&id=".$r['catid']."');web_title('Gallery - Album: ".get_config('title','album','id',$r['catid'])." ( Slide show )')";
        $list = $tpl->replace_tem($t['gallery.view'],array(
            'id'    => $r['id'],
            'title' => $r['title'],
            'user'  => $u,
            'link'  => $r['link'],
            'album.name'    => get_config('title','album','id',$r['catid']),
            'album.url' => "viewpages('main','gallery=album&amp;id=".$r['catid']."');web_title('Gallery - Album: ".get_config('title','album','id',$r['catid'])."')",
            'content'   => un_bbcode($r['content']),
            'next'  => _next('gallery','title',$r['id'],'and catid='.$r['catid']),
            'prev'  => _prev('gallery','title',$r['id'],'and catid='.$r['catid']),
            'star'  => star($r['rate'],$r['numrate']),
            'numrate'   => $r['numrate'],
            'skin.link' => $_SESSION['template'],
            'view'  => $r['viewed'],
            'date'  => mini_full_date($r['year'].$r['month'].$r['day'],$r['time']),
            'slide.link'    => $slide_link,
            'comment.total' => $mysql->num_rows($mysql->query("select id from ".$table_prefix."comment where cfor='gallery' and catid=".$r['id']."")),
            'comment.list'   => comment(1,'gallery',$r['id']),
            'comment.new'   => send_comment('gallery',$r['id']),
            )
        );
    }
    $htm = $tpl->replace_block($htm,array(
        'html'  => $list,
        )
    );
return $htm;
}


// slide
function gallery_slide($catid){
    global $mysql,$lang,$tpl,$table_prefix,$tpl_info,$type,$year,$month,$day,$userid;
    $htm = $tpl->get_tem('gallery');
    $htm = $tpl->replace_tem($htm,$lang);
    $t = $tpl->auto_get_block($htm);
    $width = $tpl_info['main'];
    if($type=='album'){
        $xml = 'modules/gallery.php?album_xml='.$catid;
        $catname = get_config('title','album','id',$catid);
    }elseif($type=='archive'){
        $xml = 'modules/gallery.php?archive_xml='.$year.'-'.$month.'-'.$day;
        $catname = $day."-".$month."-".$year;
    }elseif($type=='member'){
        $xml = 'modules/gallery.php?member_xml='.$userid;
        $s = get_config('sex','member','id',$userid);
        $u2 = $s =='1'?"<font color=blue>".$lang['lang.boy_love']."</font>":"<font color=red>".$lang['lang.girl_love']."</font>";
      //  $catname = get_user_icon('gallery',$userid)."&nbsp;".$u2;
        $catname = $u2;
    }
    //$xml = "modules/gallery.php?xml=".$type."&id=".$catid."&year=".$year."&month=".$month."&day=".$day."&userid=".$userid."";
    $list = $tpl->replace_tem($t['gallery.slide'],array(
        'album.name'    => $catname,
        'album.url'    => "viewpages('main','gallery=album&amp;id=".$catid."&type=".$type."&year=".$year."&month=".$month."&day=".$day."&userid=".$userid."');web_title('Gallery - Album: ".get_config('title','album','id',$catid)."')",
        'width'         => $width,
        'height'        => '280',
        'xml.link'      => $xml,

        'skin.link'     => $_SESSION['template'],
        'album.more'    => gallery_album_list(1,3,$catid),
        )
    );
    $htm = $tpl->replace_block($htm,array(
        'html'  => $list,
        )
    );
return $htm;
}


// dang anh
function gallery_post($type,$id=''){
    // type : photo <=> dang anh; album <=> dang album; category <=> tao chu de
    global $mysql,$lang,$tpl,$table_prefix,$moshtml,$catid,$abid;
    $htm = $tpl->get_tem('form');
    $htm = $tpl->replace_tem($htm,$lang);
    $t = $tpl->auto_get_block($htm);
    $add_pic = "viewpages('main','gallery=add&type=pic');web_title('Gallery - ".$lang['lang.add_pic']."');";
    $add_album = "viewpages('main','gallery=add&type=album');web_title('Gallery - ".$lang['lang.add_album']."');";
    $add_category = "viewpages('main','gallery=add&type=category');web_title('Gallery - ".$lang['lang.add_category']."');";
    if(!$id || $id==''){
        $id = '';
        $title = '';
        $thumb = '';
        $link  = '';
        $content= '';
        if($type=='album'){
            $g_lang = $lang['lang.add_album'];
        }elseif($type=='category'){
            $g_lang = $lang['lang.add_category'];
        }else{
            $g_lang = $lang['lang.add_pic'];
        }
    }else{
        if($type=='album'){
          $thumb = get_config('thumb','album','id',$id);
          $title = get_config('title','album','id',$id);
          $content = get_config('content','album','id',$id);
          $link = '';
          $g_lang = $lang['lang.add_album'];
        }elseif($type=='category'){
          $thumb = get_config('thumb','category','id',$id);
          $title = get_config('title','category','id',$id);
          $link = get_config('link','category','id',$id);
          $content = get_config('content','category','id',$id);
          $g_lang = $lang['lang.edit_category'];
          $type = 'gallery';
        }else{
          $thumb = get_config('thumb','gallery','id',$id);
          $title = get_config('title','gallery','id',$id);
          $link = get_config('link','gallery','id',$id);
          $content = get_config('content','gallery','id',$id);
          $g_lang = $lang['lang.edit_diary'];
          $type = 'gallery';
        }
    }
    if($type=='album'){
        $ab_s = 'none';
        $cat_s = 'block';
        $pic_show = 'none';
        $cat_lv = 1;
    }elseif($type=='category'){
        $ab_s = 'none';
        $cat_s = 'block';
        $pic_show = 'none';
        $cat_lv = '';
    }else{
        $ab_s = 'block';
        $cat_s = 'none';
        $pic_show = 'block';
        $cat_lv='';
    }
    $list = $tpl->replace_tem($t['gallery.post'],array(
        'lang'  => $g_lang,
        'cat.select'    => $moshtml->select(select_category('gallery',$cat_lv),'post-category','',$catid),
        'album.select'    => $moshtml->select(select_album('gallery'),'post-album','',$abid),
        'title' => $title,
        'link'  => $link,
        'thumb' => $thumb,
        'id'    => $id,
        'type'  => $type,
        'content'   => $content,
        'cat.show' => $cat_s,
        'album.show'  => $ab_s,
        'pic.show'  => $pic_show,
        'editor'    => bbcode_mini(),
        'skin.link' => $_SESSION['template'],
        'add.pic'   => $add_pic,
        'add.album'   => $add_album,
        'add.category'   => $add_category,
        )
    );
    $htm = $tpl->replace_block($htm,array(
        'html'  => $list,
        )
    );
return $htm;
}




?>