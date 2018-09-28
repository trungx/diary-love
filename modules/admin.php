<?php
$htm = $tpl->get_tem_p('default','admin');
$htm = $tpl->replace_tem($htm,$lang);
$t = $tpl->auto_get_block($htm);

if($_POST['admin']=='config'){
    echo admin_config();
    exit();
}

if($_POST['admin']=='config_ok'){
    $arr = array(
        'web_title' => $_POST['web_title'],
        'web_avatar'    => $_POST['web_avatar'],
        'web_language'  => $_POST['web_language'],
        'web_template' => $_POST['web_template'],
        'web_timezones' => $_POST['web_timezone'],
        'web_badword'   => $_POST['web_badword'],
        'web_diary_limit'   => $_POST['diary_limit'],
        'web_gallery_limit'   => $_POST['gallery_limit'],
        'web_music_limit'   => $_POST['music_limit'],
        'web_wish_limit'   => $_POST['wish_limit'],
    );
    update_file_php('includes/site.config.php',$arr);
    if($_POST['web_template'] == $_SESSION['skin']){
        echo admin_config();
    }else{
        session_unregister('skin');
        $_SESSION['skin']=$_POST['web_template'];
        echo 'change_skin';
    }
}


// category
if($_POST['admin']=='category'){
    $type = $_POST['type'];
    echo admin_category($type);
    exit();
}

if($_POST['admin']=='category_add'){
    $type = $_POST['type'];
    $id = $_POST['id'];
    echo admin_categore_add($id);
    exit();
}

// xoa category
if($_POST['admin']=='category_delete'){
    $id = $_POST['id'];
    $mysql->query('delete from '.$table_prefix.'category where id='.$id.'');
    exit();
}

// tuy chon danh sach chu de khi dang
if($_POST['admin']=='category_option'){
    $type = $_POST['type'];
    echo admin_cat_select($type);
    exit();
}

// tao va chinh sua chu de
if($_POST['admin']=='category_submit'){
    $arr = array(
        'content' => $_POST['content'],
        'title' => $_POST['title'],
        'cfor' => $_POST['cfor'],
        'corder'    => $_POST['order'],
        'subid' => $_POST['subid'],
        );
    $id = $_POST['id'];
    if($id){
        // check xem la chu de chinh hay chu de con
        $test = get_config('subid','category','id',$id);
        if($test==0 && $_POST['subid']!=0){
            // chuyen toan bo cac thu muc con cua thu muc nay vao thu muc chinh moi
            $mysql->query("update ".$table_prefix."category set subid=".$_POST['subid']." where subid=".$id."");
        }
        $mysql->update("category",$arr,"id=".$id);
    }else{
        $mysql->insert("category",$arr);
    }
    $type = $_POST['cfor'];
    echo admin_category($type);
    exit();
}

// block
if($_POST['admin']=='block'){
    $for = $_POST['type'];
    echo admin_block($for);
    exit();
}

// them hoac edit block
if($_POST['admin']=='block_add'){
    $id = $_POST['id'];
    $for = $_POST['for'];
    echo admin_block_add($id);
    exit();
}

// lua chon tuy chon ma block
if($_POST['admin']=='block_code_select'){
    $type=$_POST['type'];
    echo admin_block_function($type,'');
    exit();
}

// them block
if($_POST['admin']=='block_submit'){
    $arr = array(
        'name'  => $_POST['name'],
        'showname'  => $_POST['showname'],
        'mode'  => $_POST['mode'],
        'bfor' => $_POST['bfor'],
        'function'  => $_POST['bfunction'],
        'border'    => $_POST['border'],
        'active'    => $_POST['active'],
    );
    $id = $_POST['id'];
    if($id){
        $mysql->update("block",$arr,'id='.$id);
    }else{
        $mysql->insert("block",$arr);
    }
    $for = $_POST['bfor'];
    echo admin_block($for);
    exit();
}

if($_POST['admin']=='block_active'){
    $id = $_POST['id'];
    $test = get_config('active','block','id',$id);
    if($test=='no'){
        $mysql->query("update ".$table_prefix."block set active='yes' where id=".$id."");
    }else{
        $mysql->query("update ".$table_prefix."block set active='no' where id=".$id."");
    }
    $active = $test=='no'?"<img width='24' src='images/icon/active.gif' style='cursor: pointer;' onclick=\"active('block-active-".$id."','admin=block_active&id=".$id."');\" title='Off'>":"<img width='24' src='images/icon/unactive.gif' style='cursor: pointer;' onclick=\"active('block-active-".$id."','admin=block_active&id=".$id."');\" title='On'>";
    echo $active;
    exit();
}

// xoa block
if($_POST['admin']=='block_del'){
    $id = $_POST['id'];
    $mysql->query('delete from '.$table_prefix.'block where id='.$id.'');
    exit();
}

// quan ly add-ons
if($_POST['admin']=='addon'){
    echo admin_addons();
    exit();
}

// kich hoat
if($_POST['admin']=='addon_active'){
    $id = $_POST['id'];
    $test = get_config('active','plugin','id',$id);
    if($test=='no'){
        $mysql->query("update ".$table_prefix."plugin set active='yes' where id=".$id."");
    }else{
        $mysql->query("update ".$table_prefix."plugin set active='no' where id=".$id."");
    }
    $active = $test=='no'?"<img width='24' src='images/icon/active.gif' style='cursor: pointer;' onclick=\"active('addon-active-".$id."','admin=addon_active&id=".$id."');\" title='Off'>":"<img width='24' src='images/icon/unactive.gif' style='cursor: pointer;' onclick=\"active('addon-active-".$id."','admin=addon_active&id=".$id."');\" title='On'>";
    echo $active;
    exit();
}

// xoa plugin
if($_POST['admin']=='addon_del'){
    $id = $_POST['id'];
    $mysql->query("delete from ".$table_prefix."plugin where id=".$id."");
    exit();
}

// tu dong tim va cai dat goi plugin
if($_POST['admin']=='addon_install'){
    $handle=opendir('plugin/');
    while (false !== ($file=readdir($handle))) {
        $q = $mysql->query("select link from ".$table_prefix."plugin");
        while($row = $mysql->fetch_array($q)){
            $check .= $row['link'].",";
        }
		if (!empty($file) && $file!="." && $file!=".." && !substr_count($check,$file) && file_exists("plugin/".$file."/install.php") ) {
         // $file la ten cua thu muc
         include("plugin/".$file."/install.php");
         // kiem tra tinh hop le cua phien ban blog
            if(!$pl_info['default']){
                    $default = 'no';
                    $link = $file;
                }
                else{
                    $default = 'yes';
                    $link = 'default';
                }
                $arr = array(
                    'name'  => $pl_info['name'],            //
                    'link'  => $link,                        //
                    'content'   => $pl_info['content'],         //
                    'author'    => $pl_info['author'],          //
                    'authormail'    => $pl_info['author.email'],  //
                    'authorweb' => $pl_info['author.web'],       //
                    'version'   => $pl_info['version'],          //
                   // 'update'    => $pl_info['update'],
                    'active'    => 'no',                       //
                    'admin'     => $pl_info['admin'],            //
                    'adminurl'  => $pl_info['admin.url'],         //
                    'indexurl'  => $pl_info['index.url'],          //
                    'pdefault'   => $default,                       //
                );
                $mysql->insert("plugin",$arr);
       }
    }
    echo admin_addons();
exit();
}


// quan ly plugin
if($_POST['admin']=='plugin_acp'){
    $id = $_POST['id'];
    $act = $_POST['act'];
    // check link plugin
    $admin_link = get_config('adminurl','plugin','id',$id);
    $link    = get_config('link','plugin','id',$id);
    $plugin_admin_link  = "plugin/".$link."/".$admin_link;
    include($plugin_admin_link);
exit();
}


function admin_config(){
    global $mysql,$lang,$tpl,$table_prefix,$htm,$t,$moshtml;
    include('includes/site.config.php');
    // chon ngon ngu
    $q = $mysql->query('select * from '.$table_prefix.'lang order by name ASC');
    while($r = $mysql->fetch_array($q)){
        $langa[] = array($r['url'],$r['name']);
    }
    $handle=opendir("template/");
    while (false !== ($file=readdir($handle))) {
		if (!empty($file) && $file!="." && $file!=".." && file_exists("template/".$file."/info.php") ) {
        include('template/'.$file.'/info.php');
            $skin[] = array($file,$tpl_info['name']);
    	}
    }
    $main = $tpl->replace_tem($t['config'],array(
        'web.title' => $web_title,
        'web.avatar'    => $web_avatar,
        'web.language'  => $moshtml->select($langa,'web_language','',$web_language),
        'web.skin'  => $moshtml->select($skin,'web_template','',$web_template),
        'web.timezone'  => $moshtml->select(select_timezone(),'web_timezone','',$web_timezones),
        'web.badword'   => $web_badword,
        'diary.limit'   => $web_diary_limit,
        'gallery.limit' => $web_gallery_limit,
        'music.limit'   => $web_music_limit,
        'wish.limit'    => $web_wish_limit,
        )
    );
    $htm = $tpl->replace_block($htm,array(
        'html'  => $main,
        )
    );
return $htm;
}


function admin_category($type=''){
    global $mysql,$lang,$tpl,$table_prefix,$t,$htm,$moshtml;
    if(!$type || $type=='') $type='diary';
    $table = $type=='gallery'?'album':$type;
    $total = $mysql->num_rows($mysql->query("select id from ".$table_prefix."category where cfor='".$type."' and subid='0'"));
    if(!$total){
        $cat = "<center>".$lang['lang.empty']."</center>";
    }else{
        $q = $mysql->query("select * from ".$table_prefix."category where cfor='".$type."' and subid='0' order by id ASC");
        while($r=$mysql->fetch_array($q)){
            $total_sub = $mysql->num_rows($mysql->query("select id from ".$table_prefix."category where cfor='".$type."' and subid=".$r['id'].""));
            $cat_arr = $r['id'].",";
            if($total_sub){
                $q2 = $mysql->query("select * from ".$table_prefix."category where cfor='".$type."' and subid=".$r['id']." order by id ASC");
                while($r2=$mysql->fetch_array($q2)){
                    $total1 = $mysql->num_rows($mysql->query("select id from ".$table_prefix.$table." where catid=".$r2['id'].""));
                    $del1 = $total1==0?"del('admin=category_delete',".$r2['id'].",'','','cat')":"alert('Chu de co chua bai viet ban khong the xoa');";
                    $delete = "<a href='javascript:;' onclick=\"".$del1."\" title='Delete'>Delete</a>";
                    $sub .= $tpl->replace_tem($t['sub.list'],array(
                        'id'    => $r2['id'],
                        'name'  => $r2['title'],
                        'url'   => "viewpages('cmain','diary=category&amp;catid=".$r2['id']."')",
                        'total' => $total1,
                        'edit'  => "<a href='javascript:;' onclick=\"edit('admin=category_add&abc',".$r2['id'].",'type=".$type."','cmain');\" title='Edit'>Edit</a>",
                        'delete'    => $delete,
                        )
                    );
                    $cat_arr .= $r2['id'].",";
                }
            }else{
                $sub = '';
            }
            $cat_arr = substr($cat_arr,0,-1);
            $total2 = $mysql->num_rows($mysql->query("select id from ".$table_prefix.$table." where catid IN (".$cat_arr.")"));
            $del2 = $total2==0?"del('admin=category_delete',".$r['id'].",'','','cat')":"alert('Chu de co chua bai viet ban khong the xoa');";
            $delete2 = "<a href='javascript:;' onclick=\"".$del2."\" title='Delete'>Delete</a>";
            $cat .= $tpl->replace_tem($t['cat.list'],array(
                'id'    => $r['id'],
                'name'  => $r['title'],
                'sub.list'  => $sub,
                'url'   => "viewpages('cmain','diary=category&amp;catid=".$r['id']."')",
                'total' => $total2,
                'edit'  => "<a href='javascript:;' onclick=\"edit('admin=category_add&abc',".$r['id'].",'type=".$type."','cmain');\" title='Edit'>Edit</a>",
                'delete'    => $delete2,
                )
            );

        }
    }
    $mode[] = array('diary',$lang['lang.diary']);
    $mode[] = array('gallery',$lang['lang.gallery']);
    $mode[] = array('music',$lang['lang.music']);
    $main = $tpl->replace_tem($t['cat.top'],array(
        'skin.link' => $_SESSION['template'],
        'select.mode'   => $moshtml->select($mode,'cat-mode','onChange="cat_select_mode(this.options[this.selectedIndex].value);"',$type),
        'cat.list'  => $cat,
        'type'  => $type,
        )
    );
    $htm = $tpl->replace_block($htm,array(
        'html'  => $main,
        )
    );
return $htm;
}

function admin_categore_add($id=''){
    global $mysql,$lang,$tpl,$table_prefix,$htm,$t,$moshtml,$type;
    if($id){
        $q = $mysql->query("select * from ".$table_prefix."category where id=".$id."");
        while($r=$mysql->fetch_array($q)){
          $tp = $r['cfor'];
          $tpa = $lang['lang.'.$tp.''];
          $for = "<input id='cfor' value='".$tp."' type='hidden' size='1' /><b>".$tpa."</b>";
          $title = $r['title'];
         // $thumb = $r['thumb'];
          $content = $r['content'];
          $subid = $r['subid'];
          $order = $r['corder'];
        }
    }else{
        $mode[] = array('diary',$lang['lang.diary']);
        $mode[] = array('gallery',$lang['lang.gallery']);
        $mode[] = array('music',$lang['lang.music']);
        $for = $moshtml->select($mode,'cfor','onChange="cat_select_option(this.options[this.selectedIndex].value);"',$type);
        $subid = 0;
        $title = $thumb = $content = "";
        $order = 0;
    }
    $main = $tpl->replace_tem($t['category.post'],array(
        'id'    => $id,
        'title' => $title,
        'for'   => $for,
        'content'   => $content,
        'cat.select'    => $moshtml->select(select_category($type,'',$id),"category",'',$subid),
        'editor'    => bbcode_mini(),
        'order' => $order,
        )
    );
    $htm = $tpl->replace_block($htm,array(
        'html'  => $main,
        )
    );
return $htm;
}

function admin_cat_select($type){
    global $moshtml;
    return $moshtml->select(select_category($type,''),"category",'');
}


// quan ly khoi
function admin_block($for=''){
    global $mysql,$lang,$tpl,$table_prefix,$t,$htm,$moshtml;
    if(!$for) $for = 'all';
    $total = $mysql->num_rows($mysql->query("select id from ".$table_prefix."block where bfor='".$for."'"));
    if(!$total){
        $list = "<center>".$lang['lang.empty']."</center>";
    }else{
        $q = $mysql->query("select * from ".$table_prefix."block where bfor='".$for."' order by border ASC");
        while($r=$mysql->fetch_array($q)){
            $active = $r['active']=='yes'?"<img width='24' src='images/icon/active.gif' style='cursor: pointer;' onclick=\"active('block-active-".$r['id']."','admin=block_active&id=".$r['id']."');\" title='Off'>":"<img width='24' src='images/icon/unactive.gif' style='cursor: pointer;' onclick=\"active('block-active-".$r['id']."','admin=block_active&id=".$r['id']."');\" title='On'>";
            $mode = $r['mode'];
            if($mode==0){
                $code = 'Default';
            }elseif($mode==1){
                $code = 'Add-on';
            }else{
                $code = 'Html';
            }
            $list .= $tpl->replace_tem($t['block.list'],array(
                'id'    => $r['id'],
                'name'  => $r['name'],
                'code'  => $code,
                'edit'  => "<img height='20' title='Edit' onclick=\"edit('admin=block_add&abc',".$r['id'].",'for=".$r['bfor']."','cmain');\" class='folder' style='background-color: #3e3eff; margin: 2px; cursor: pointer; border: 1px solid #c0c0c0;' src='images/icon/edit.gif'>",
                'del'  => "<img height='20' title='Delete' onclick=\"del('admin=block_del&abc',".$r['id'].",'','','block-list');\" class='folder' style='background-color: #ff7575; margin: 2px; cursor: pointer; border: 1px solid #c0c0c0;' src='images/icon/delete.gif'>",
                'active'    => "<span id='block-active-".$r['id']."'>".$active."</span>",
                )
            );
        }
    }
    $mode_arr[] = array('all',$lang['lang.all_site']);
    $mode_arr[] = array('home','|-'.$lang['lang.home']);
    $mode_arr[] = array('diary','|-'.$lang['lang.diary']);
    $mode_arr[] = array('gallery','|-'.$lang['lang.gallery']);
    $mode_arr[] = array('music','|-'.$lang['lang.music']);

    $main = $tpl->replace_tem($t['block.top'],array(
        'list'  => $list,
        'for.select'    => $moshtml->select($mode_arr,"mode",'onChange="block_select_mode(this.options[this.selectedIndex].value);"',$for),
        'skin.link' => $_SESSION['template'],
        'for'   => $for,
        )
    );
    $htm = $tpl->replace_block($htm,array(
        'html'  => $main,
        )
    );
return $htm;
}


/// tao block hoac edit block
function admin_block_add($id=''){
    global $mysql,$lang,$tpl,$table_prefix,$htm,$t,$moshtml,$for;
    if($id){
        $q = $mysql->query("select * from ".$table_prefix."block where id=".$id."");
        while($r=$mysql->fetch_array($q)){
            $name = $r['name'];
            $showname = $r['showname'];
            $function = $r['function'];
            $m = $r['mode'];
            $order = $r['border'];
            $active = $r['active'];
            $mode = $r['mode'];
        }
    }else{
        $name = $showname = $function = "";
        $active = "yes";
        $order = 0;
        $mode = 0;
        $m = "all";
    }
    // danh sach tuy chon
    $mode_arr[] = array('all',$lang['lang.all_site']);
    $mode_arr[] = array('home','|-'.$lang['lang.home']);
    $mode_arr[] = array('diary','|-'.$lang['lang.diary']);
    $mode_arr[] = array('gallery','|-'.$lang['lang.gallery']);
    $mode_arr[] = array('music','|-'.$lang['lang.music']);

    // tuy chon ma
    $code_arr[] = array(0,'Default');
    $code_arr[] = array(1,'Add-ons');
    $code_arr[] = array(2,'html');
    $main = $tpl->replace_tem($t['block.add'],array(
        'id'    => $id,
        'name'  => $name,
        'showname'  => $showname,
        'mode'    => $moshtml->select($code_arr,"mode",'onChange="block_select_code(this.options[this.selectedIndex].value);"',$mode),
        'for'    => $moshtml->select($mode_arr,"for",'',$for),
        'function'  => admin_block_function($mode,$function),
        'active'    => $moshtml->yesno('active','',$active),
        'order' => $order,
        'type'  => $for,
        )
    );
    $htm = $tpl->replace_block($htm,array(
        'html'  => $main,
        )
    );
return $htm;
}


// Add-ons
function admin_addons(){
    global $mysql,$lang,$tpl,$table_prefix,$htm,$t;
    $total = $mysql->num_rows($mysql->query("select id from ".$table_prefix."plugin"));
    if(!$total){
        $total = 0;
        $list = "<center>".$lang['lang.empty']."</center>";
    }else{
        $q = $mysql->query("select * from ".$table_prefix."plugin");
        while($r=$mysql->fetch_array($q)){
            $cat=$r['pdefault']=='yes'?'<font color=blue>Default</font>':'<font color=red>Install</font>';
            $admin = $r['admin']=='yes'?"<img width='24' src='images/icon/admin_y.gif' style='cursor: pointer;' onclick=\"viewpages('cmain','admin=plugin_acp&id=".$r['id']."&act=home');\" title='Cpanel'>":"<img width='24' src='images/icon/admin_n.gif'>";
            if($r['pdefault']=='yes'){
                $active="<img src='images/icon/active.gif' style='cursor: pointer;' onclick=\"alert('Always active!');\">";
            }else{
                $active = $r['active']=='yes'?"<img width='24' src='images/icon/active.gif' style='cursor: pointer;' onclick=\"active('addon-active-".$r['id']."','admin=addon_active&id=".$r['id']."');\" title='Off'>":"<img width='24' src='images/icon/unactive.gif' style='cursor: pointer;' onclick=\"active('addon-active-".$r['id']."','admin=addon_active&id=".$r['id']."');\" title='On'>";
            }
            $delete = $r['pdefault']=='yes'?"<img width='24' src='images/icon/del_n.gif'>":"<img width='24' src='images/icon/del_y.gif' style='cursor: pointer;' onclick=\"del('admin=addon_del&abc',".$r['id'].",'','','addon');\" title='Uninstall'>";
            $list .= $tpl->replace_tem($t['addon.list'],array(
                'id'    => $r['id'],
                'name'  => $r['name'],
                'author'    => "<a href='mailto: ".$r['authormail']."'>".$r['author']."</a>",
                'cat'   => $cat,
                'admin' => $admin,
                'content'   => $r['content'],
                'del'   => $delete,
                'active'    => "<span id='addon-active-".$r['id']."'>".$active."</span>",
                )
            );
        }
        $list = $tpl->replace_tem($list,$lang);
    }
    $top = $tpl->replace_tem($t['addon.top'],array(
        'skin.link' => $_SESSION['template'],
        'list'  => $list,
        )
    );
    $htm = $tpl->replace_block($htm,array(
        'html'  => $top,
        )
    );
return $htm;
}



// tuy chon dinh dang block
function admin_block_function($mode,$source){
    // mode : tuy chon mac dinh, html, hoac add-on
    // source: ma hoac id function cua block
    global $mysql,$lang,$moshtml,$table_prefix;
    if($mode==0){    // mac dinh
        // danh sach cac block mac dinh cho tat ca cac trong dung duoc
        $select[] = array(1,'About');
        $select[] = array(2,'Diary - Calendar');
        $select[] = array(4,'Diary - New');
        $select[] = array(5,'Diary - Category');
        $select[] = array(6,'Gallery - Calendar');
        $select[] = array(7,'Gallery - New');
        $select[] = array(8,'Gallery - Category');
        $select[] = array(11,'Music - New');
        $select[] = array(12,'Music - Category');
        $select[] = array(13,'Music - Singer');
        $show = $moshtml->select($select,"code",'',$source);
    }elseif($mode==1){  // add-on
        $total = $mysql->num_rows($mysql->query("select id from ".$table_prefix."plugin where active='yes' and pdefault='no'"));
        if(!$total){
            $select[] = array('no-plugin',$lang['lang.empty']);
        }else{
            $q = $mysql->query("select id, name from ".$table_prefix."plugin where active='yes' and pdefault='no' order by id ASC");
            while($r=$mysql->fetch_array($q)){
                $select[] = array($r['id'],$r['name']);
            }
        }
        $show = $moshtml->select($select,"code",'',$source);
    }else{  // tuy chon nhap ma html
        $show = "<textarea id=\"code\" style=\"height: 150px; width: 250px; background-color: #ffffff;\">".$source."</textarea>&nbsp;<font color=\"red\"><b>*</b></font>";
    }
return $show;
}





?>