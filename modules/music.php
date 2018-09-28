<?php

// get playlist
if($_GET['playlist'] || $_GET['play'] || $_GET['play_singer'] || $_GET['play_album'] || $_GET['download']){
    include('../includes/connect.php');
    include('../includes/site.config.php');
    include('../includes/config.functions.php');
    include('../includes/site.functions.php');
    if($_GET['download']){
            $id = $_GET['download'];
            $mysql->query("update ".$table_prefix."music set download=download+1 where id=".$id."");
            $link = get_link(get_config('link','music','id',$id),'download');
            header("Location: ".$link);
    exit();
    }
    if($_GET['playlist']){
      if($_GET['playlist']=='random'){
          $select = "order by RAND() limit 0,10";
      }else{
          $list_array = get_config('playlist','member','id',$_GET['playlist']);
          $select = "where id IN ($list_array) order by RAND()";
        //  $select = " order by id DESC";
      }
    }elseif($_GET['play']){
         $select = "where id=".$_GET['play']."";
         $mysql->query("update ".$table_prefix."music set played=played+1 where id=".$_GET['play']."");
    }elseif($_GET['play_singer']){
        $select = "where singer=".$_GET['play_singer']."";
    }elseif($_GET['play_album']){
        $select = "where albumid=".$_GET['play_album']."";
    }
    $q = $mysql->query("select * from ".$table_prefix."music ".$select."");
    while($r=$mysql->fetch_array($q)){
       $song .=  "<song download=\"".$module."music.php?download=".$r['id']."\" path=\"".get_link($r['link'])."\" downed=\"".$r['download']."\" played=\"".$r['played']."\" singer=\"".cut_str(get_config('title','singer','id',$r['singer']),25)."\" title=\"".cut_str($r['title'],25)."\" imgsong=\"images/cover.jpg\"/>";
    }
    $xml = '<?xml version="1.0" encoding="utf-8" ?>
                <player urlskin="images/nero.jpg" colorcircle="12171C" title_color="blue" ombra="no" buttoncolor="ffffff" autoStart="yes" download="yes">
                    '.$song.'
                </player>';
    echo $xml;
    exit();
}

// add song
if($_POST['music']=='new' || $_POST['music']=='edit'){
    $type = $_POST['type'];
    $id = $_POST['id'];
    echo music_add($type,$id);
    exit();
}

// delete
if($_POST['music']=='delete'){
    $type = $_POST['type'];
    if($type=='album'){
        $mysql->query("delete from ".$table_prefix."album where id=".$id."");
    }else{
        $mysql->query("delete from ".$table_prefix."music where id=".$id."");
    }
    exit();
}




// insert or update
if($_POST['music']=='new_submit'){
    $type = $_POST['type'];
    $id = $_POST['id'];
    $catid = $_POST['catid'];
    $abid = $_POST['abid'];
    $thumb = $_POST['thumb'];
    $singid = $_POST['singid'];
    $content = $_POST['content'];
    $title  = $_POST['title'];
    if($type == 'category'){
        $category = array(
                'subid' => $catid,
                'thumb' => $thumb,
                'content'   => $content,
                'cfor'  => 'music',
                'title' => $title,
        );
        if($id){ // update
            $mysql->update('category',$category,'id='.$id);
        }else{
            $mysql->insert('category',$category);
        }
    }elseif($type=='album'){
        $album = array(
            'title' => $title,
            'thumb' => $thumb,
            'afor'  => 'music',
            'content'   => $content,
            'catid' => $catid,
            );
        if($id){
            $mysql->update('album',$album,'id='.$id);

        }else{
            $mysql->insert('album',$album);
        }

    }elseif($type=='song'){
        $song = array(
            'title' => $title,
            'catid'  => $catid,
            'singer'    => $singid,
            'albumid'   => $abid,
            'link'  => $link,
            'type'  => 'mp3',
            'userid'    => $_SESSION['userid'],
            );
        if($id){
            $mysql->update('music',$song,'id='.$id);
        }else{
            $mysql->insert('music',$song);
        }
    }elseif($type=='singer'){
        $singer = array(
            'title' => $title,
            'category'  => $catid,
            'thumb' => $thumb,
            'content'   => $content,
        );
        if($id){
            $mysql->update('singer',$singer,'id='.$id);
        }else{
            $mysql->insert('singer',$singer);
        }
    }
    echo music_list(1);
    exit();
}



if($_POST['music']=='home'){
    echo music_home();
    exit();
}

// danh sach chu de
if($_POST['music']=='category'){
    $type = 'category';
    $catid = $_POST['catid'];
    echo music_list(1);
    exit();
}

// them ca khuc vao playlist
if($_POST['music']=='favorite_add'){
    $type = $_POST['f'];
    $id = $_POST['id'];
    music_favorite($id,$type);
    echo music_favicon($id);
    exit();
}

if($_POST['music']=='refesh_fav'){
    $id = $_POST['id'];
    echo music_favicon($id);
    exit();
}

// danh sach ca khuc
if($_POST['music']=='list'){
    $type = $_POST['type'];
    $catid =  $_POST['catid'];
    $page =  $_POST['page'];
    $muserid = $_POST['muserid'];
    $singerid = $_POST['singerid'];
    $order = $_POST['order'];
    echo music_list($page);
    exit();
}

// danh sach album
if($_POST['music']=='album_list'){
    $page = $_POST['page'];
    $catid = $_POST['catid'];
    $order = $_POST['order'];
    echo music_album($page);
    exit();
}

// playlist
if($_POST['music']=='playlist'){
    echo playlist();
    exit();
}

if($_POST['music']=='playall'){
    $id = $_POST['id'];
    $type =  $_POST['type'];
    echo playlist($id);
    exit();
}
// play ca khuc
if($_POST['music']=='play' ||$_POST['view']){
    $id = $_POST['id'];
    $type = 'song';
    echo playlist($id);
    exit();
}

function music_home(){
    global $tpl,$mysql,$lang,$table_prefix;
    $htm = $tpl->get_tem('music');
    $htm = $tpl->replace_tem($htm,$lang);
    $t = $tpl->auto_get_block($htm);
    $main = $tpl->replace_tem($t['home'],array(
        'album.new' => music_album(1,3),
        'skin.link' => $_SESSION['template'],
        'song.new'  => music_list(1,5),
        'playlist.link' => "play('music=playlist');",
        'upload.link'   => "viewpages('main','music=new&amp;type=song');web_title('Music - Ðang nh?c');",
        )
    );
    $htm = $tpl->replace_block($htm,array(
        'html'  => $main,
        )
    );
    return $htm;
}




function music_album($page,$a='',$om=''){
    global $mysql,$lang,$tpl,$table_prefix,$order,$m_userid,$type,$catid;
    $htm = $tpl->get_tem('music');
    $htm = $tpl->replace_tem($htm,$lang);
    $t = $tpl->auto_get_block($htm);
    // category
    if($type == 'category'){
        $c = "and catid=".$catid."";
    }
    // dang boi thanh vien
    if($m_userid>0){
        $u = "and userid=".$m_userid;
    }
    $total = $mysql->num_rows($mysql->query("select id from ".$table_prefix."album where afor = 'music' ".$c." ".$u.""));
    if(!$total){
        $total = 0;
        $list = "<center>".$lang['lang.empty']."</center>";
    }else{
        if(!$page) $page = 1;
        $page_size = 12;
        $limit = ($page-1)*$page_size;
        // top album new;
        if($a){
            $l = "limit 0,".$a."";
        }else{
            $l = "limit ".$limit.",".$page_size."";
        }

        // order
        if($order=='new'){
            $o = "order by id DESC";
        }elseif($order=='name1'){   // ten tang dan
            $o =  "order by title ASC";
        }elseif($order=='name2'){   // ten giam dan
            $o = "order by title DESC";
        }elseif($om){             // sap xep ngau nhien
            $o = "and id<>".$om." order RAND()";
        }elseif($a){              // cac album moi
            $o = "order by id DESC";
        }
        $q = $mysql->query("select id, title, thumb from ".$table_prefix."album where afor='music' ".$c." ".$u." ".$o." ".$l."");
        while($r=$mysql->fetch_array($q)){
            $list .= $tpl->replace_tem($t['album.list'],array(
                'id'    => $id,
                'title' => $r['title'],
                'name'  => cut_str($r['title'],32),
                'thumb' => $r['thumb'],
                'url'   => "viewpages('main','music=list&type=album&abid=".$r['id']."');web_title('Music - Album: ".$r['title']."');",
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
                // check cat
                $check_cat = $mysql->num_rows($mysql->query("select id from ".$table_prefix."album where afor='album' and subid=".$catid.""));
                if($check_cat){
                    $sub_cat = 'danh sach chu de con';
                    $sub_show = 'block';
                }
            }else{
                $sub_cat = '';
                $sub_show = 'none';
            }
            $main = $tpl->replace_tem($t['album.top.list'],array(
                'album.list'    => $list,
                'sub.cat'   => $sub_cat,
                'sub.show'  => $sub_show,
                'playlist.link' => "play('music=playlist');",
                'upload.link'   => "viewpages('main','music=new&amp;type=song');web_title('Music - Ðang nh?c');",
                'total' => $total,
                'skin.link' => $_SESSION['template'],
                'pages' => viewpages('main',$total,$page_size,$page,'music=album_list&order='.$order.'&type='.$type.'&catid='.$catid.''),
                )
            );
            $htm = $tpl->replace_block($htm,array(
                'html'  => $main,
                )
            );
    }
return $htm;
}


function music_add($type,$id=''){
    /* type :   song = them bai hat
                album = them album
                category = them chu de
                singer = them ca sy
       id : id cua album hoac chu de khi sua */
    global $mysql,$lang,$tpl,$table_prefix,$moshtml;
    if(!$id || $id == ''){
        $title = '';
        $content = '';
        $thumb = '';

    }else{
        if($type=='album'){
            $title =  get_config('title','album','id',$id);
            $content =  get_config('content','album','id',$id);
            $thumb =  get_config('thumb','album','id',$id);
            $catid =  get_config('catid','album','id',$id);
            $link = '';
            $singer = '';
        }elseif($type=='category'){
            $title = get_config('title','category','id',$id);
            $content = get_config('content','category','id',$id);
            $thumb =  get_config('thumb','category','id',$id);
            $link = '';
            $singer = '';
        }elseif($type=='song'){
            $title = get_config('title','music','id',$id);
            $content = get_config('lyric','music','id',$id);
            $catid = get_config('catid','music','id',$id);
            $albumid = get_config('albumid','music','id',$id);
            $thumb = '';
            $link = get_config('link','music','id',$id);
            $singer = get_config('singer','music','id',$id);
        }elseif($type=='singer'){
            $title = get_config('title','singer','id',$id);
            $content = get_config('lyric','singer','id',$id);
            $catid = get_config('category','singer','id',$id);
            $thumb = get_config('thumb','singer','id',$id);
            $link = '';
            $singer = '';
        }
    }
    if($type == 'category'){
        $cat_lv = '';
        $l = $lang['lang.add_category'];
        $thumb_show =  'block';
        $link_show = 'none';
        $album_show = 'none';
        $cat_show = 'block';
    }else{
        $cat_lv = 1;
        $l = $type=='album'?$lang['lang.add_album']:$lang['lang.add_music'];
        if($type=='singer') $l = $lang['lang.add_singer'];
        $thumb_show = ($type =='album' || $type=='singer')?'block':'none';
        $link_show = ($type == 'album' || $type=='singer')?'none':'block';
        $album_show = ($type == 'album' || $type=='singer')?'none':'block';
        $cat_show = 'block';
    }
    if($thumb!=''){
        $pic = $thumb;
    }else{
        $pic = $_SESSION['template']."/images/nopic.gif";
    }
    $htm = $tpl->get_tem('form');
    $htm = $tpl->replace_tem($htm,$lang);
    $t = $tpl->auto_get_block($htm);
    $main = $tpl->replace_tem($t['music.post'],array(
            'skin.link' => $_SESSION['template'],
            'type'  => $type,
            'lang'  => $l,
            'cat.show'  => $cat_show,
            'link.show' => $link_show,
            'thumb.show'    => $thumb_show,
            'album.show'    => $album_show,
            'thumb' => $thumb,
            'link'  => $link,
            'pic'   => $pic,
            'add.music' => "viewpages('main','music=new&type=song');web_title('Music - ".$lang['lang.add_music']."');",
            'add.album' => "viewpages('main','music=new&type=album');web_title('Music - ".$lang['lang.add_album']."');",
            'add.singer' => "viewpages('main','music=new&type=singer');web_title('Music - ".$lang['lang.add_singer']."');",
            'add.category' => "viewpages('main','music=new&type=category');web_title('Music - ".$lang['lang.add_category']."');",



            'id'    => $id,
            'title' => $title,
            'editor'  => bbcode_mini(),
            'content'   => $content,
            'cat.select' => $moshtml->select(select_category('music',$cat_lv),'post-category','',$catid),
            'album.select'  => $moshtml->select(select_album('music',$cat_lv),'post-album','',$albumid),
            'singer.select'  => $moshtml->select(select_singer(),'post-singer','',$singer),
        )
    );
    $htm = $tpl->replace_block($htm,array(
        'html'  => $main,
        )
    );
    return $htm;
}



function music_list($page,$a=''){
    global $mysql,$lang,$tpl,$table_prefix,$type,$abid,$catid,$order,$muserid,$singerid;
    $htm = $tpl->get_tem('music');
    $htm = $tpl->replace_tem($htm,$lang);
    $t = $tpl->auto_get_block($htm);
    $show = $_SESSION['sex']==1?"block":"none";
    // type
    if($type=='category'){
        $c = 'and catid='.$catid;
    }elseif($type=='album'){
        $c = 'and albumid='.$abid;
    }elseif($type=='member'){
        $c  = 'and userid='.$muserid;
    }elseif($type=='singer'){
        $c = "and singer=".$singerid."";
    }else{
        $c = '';
    }
    $total = $mysql->num_rows($mysql->query("select id from ".$table_prefix."music where id>0 ".$c.""));
    if(!$total){
        $total = 0;
        $list = "<center>".$lang['lang.empty']."</center>";
    }else{
        if(!$page) $page =1;
        $page_size = 30;
        $limit = ($page-1)*$page_size;

        // limit
        if($a){
            $l = "limit 0,".$a;
        }else{
            $l = "limit ".$limit.",".$page_size."";
        }
        // order
        if($order=='new'){ // moi dang
            $o = "order by id DESC";
        }elseif($order=='name1'){   // ten giam dan
            $o = "order by title DESC";
        }elseif($order=='name2'){   // ten tang dan
            $o = "order by title ASC";
        }elseif($order=='play'){    // luot nghe
            $o = "order by played DESC";
        }elseif($order=='download'){    // luot tai
            $o = "order by download DESC";
        }elseif($order=='rate'){    // danh gia
            $o = "order by rate/numrate DESC";
        }elseif($a){
            $o = "order by id DESC";
        }else{
            $o = "order by id ASC";
        }
        $q = $mysql->query("select * from ".$table_prefix."music where id>0 ".$c." ".$o." ".$l."");
        while($r=$mysql->fetch_array($q)){
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
    }
    if($type=='album'){
        $mysql->query("update ".$table_prefix."singer set viewed=viewed+1 where id=".$abid."");
        if($r['userid']==$_SESSION['userid'] || $_SESSION['sex']=='1'){
                $a_delete =  "<img height='20' src='images/icon/delete.gif' style='background-color: #fff; cursor: pointer;' onclick=\"del('music',".$abid.",'view','type=album');\" title='delete'>";
                $a_edit = "<img height='20' src='images/icon/edit.gif' style='background-color: #fff; cursor: pointer;' onclick=\"edit('music',".$abid.",'&type=album');\" title='edit'>";
            }else{
                $a_delete = '';
                $a_edit = '';
            }
        $info = $tpl->replace_tem($t['info'],array(
            'edit'  => $a_edit,
            'id'    => $abid,
            'delete'    => $a_delete,
            'skin.link' => $_SESSION['template'],
            'name'    => get_config('title','album','id',$abid),
            'thumb'     => get_config('thumb','album','id',$abid),
            'content'   => un_bbcode(get_config('content','album','id',$abid)),
            'category'  => get_config('title','category','id',get_config('catid','album','id',$abid)),
            'total.song'    => $total,
            'viewed'    => get_config('viewed','album','id',$abid),
            'playall'   => $lang['lang.play_album']."&nbsp;".get_config('title','album','id',$abid),
            'playall.link'  => "play('music=playall&type=album&id=".$abid."')",
            'tool.show' => $show,

            )
        );
    }elseif($type=='singer'){
        $mysql->query("update ".$table_prefix."singer set played=played+1 where id=".$singerid."");
        if($r['userid']==$_SESSION['userid'] || $_SESSION['sex']=='1'){
                $s_delete =  "<img height='20' src='images/icon/delete.gif' style='background-color: #fff; cursor: pointer;' onclick=\"del('music',".$singerid.",'view','type=singer');\" title='delete'>";
                $s_edit = "<img height='20' src='images/icon/edit.gif' style='background-color: #fff; cursor: pointer;' onclick=\"edit('music',".$singerid.",'&type=singer');\" title='edit'>";
            }else{
                $s_delete = '';
                $s_edit = '';
            }
        $info = $tpl->replace_tem($t['info'],array(
            'skin.link' => $_SESSION['template'],
            'edit'  => $s_edit,
            'delete'    => $s_delete,
            'id'    => $singerid,
            'name'   => get_config('title','singer','id',$singerid),
            'thumb'     => get_config('thumb','singer','id',$singerid),
            'content'   => un_bbcode(get_config('content','singer','id',$singerid)),
            'category'  => get_config('title','category','id',get_config('category','singer','id',$singerid)),
            'total.song'    => $total,
            'viewed'    => get_config('played','singer','id',$singerid),
            'playall'   => $lang['lang.play_singer']."&nbsp;".get_config('title','singer','id',$singerid),
            'playall.link'  => "play('music=playall&type=singer&id=".$singerid."')",
            'tool.show' => $show,
            )
        );
    }else{
        $info = '';
    }
    if($a){
        $pageview = '';
        $show = 'none';   // an hien tool
    }else{
        $pageview = viewpages('main',$total,$page_size,$page,'music=list&type='.$type.'&abid='.$abid.'&singerid='.$singerid.'&catid='.$catid.'&muserid='.$muserid.'&order='.$order.'');
        $show = 'block';   // hien tool
    }
    $main = $tpl->replace_tem($t['song.top.list'],array(
            'info' => $info,
            'show'  => $show,
            'song.list' => $list,
            'total' => $total,
            'new.link'  => "viewpages('main','music=list&type=".$type."&order=new')",
            'old.link'  => "viewpages('main','music=list&type=".$type."&order=old')",
            'name1.link'  => "viewpages('main','music=list&type=".$type."&order=name1')",
            'name2.link'  => "viewpages('main','music=list&type=".$type."&order=name2')",
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
   /*

function music_play($id){
    global $mysql,$lang,$tpl,$table_prefix;

}    */



function music_favorite($id,$type=''){
    global $mysql,$table_prefix;
    //type = remove or add
    $favorite = get_config('playlist','member','id',$_SESSION['userid']);
    $suserid = $_SESSION['userid'];

    if($type=='remove'){
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
    }else{
        if($favorite==''){
            $mysql->query("update ".$table_prefix."member set playlist='".$id."' where id=".$suserid."");
        }else{
          $z = split(',',$favorite);
          if(!in_array($id,$z)){
              $mysql->query("UPDATE ".$table_prefix."member SET playlist = CONCAT('".$id.",',playlist) where id=".$suserid."");
          }else{
              echo "daco";
          }
        }
    }
}


?>