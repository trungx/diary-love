<?php
if(file_exists("../includes/config.php")){
    include('../includes/config.php');
}
if($_POST['db']=='check'){
    $dbhost = $_POST['dbhost'];
    $dbuser = $_POST['dbuser'];
    $dbpass = $_POST['dbpass'];
    $dbname = $_POST['dbname'];
    $connect=@mysql_connect($dbhost,$dbuser,$dbpass);
    if ($connect){
        if(@mysql_select_db($dbname,$connect))
        {
             $show .= '<font color=blue><b><center><br />Kết nối thành công!</font></b><br>';
             $ok = 'yes';
        }else{
             $show = "<br><font color=red> <center><b>Không kết nối được với dữ liệu ".$dbname.":</b></font><br /><br />".mysql_error()."</br>";
             $ok = 'no';
        }
    }else{
        $show = "<br><font color=red><b>Không kết nối được với server ".$dbhost.":<br /> </b></font></b><br />".mysql_error()."</br>";
        $ok = 'no';
    }
if($_POST['dbtype']=='check'){
    echo $show;
}elseif($_POST['dbtype']=='submit'){
    echo $ok;
}
exit();

}

function tb($a,$b){
    global $dbprefix;
    $kq = $b==false ? '<font color=red><b>Thất bại.</b></font><br />': '<font color=blue><b>Thành công!</b></font><br />';
    return "&nbsp;&nbsp;+ Tạo bảng  <b>".$dbprefix.$a." </b> : ".$kq;
}

function in($a){
    global $dbprefix,$q;
    $ok = !$q ? '<font color=red><b>Thất bại.</b></font><br />': '<font color=blue><b>Thành công!</b></font><br />';
    return "<i>&nbsp;&nbsp;&nbsp;&nbsp;- Chèn giữ liệu vào bảng <b>".$dbperfix.$a."</b>: ".$ok."</i>";
}


if($_POST['db']=='submit'){
    $dbhost = $_POST['dbhost'];
    $dbuser = $_POST['dbuser'];
    $dbpass = $_POST['dbpass'];
    $dbname = $_POST['dbname'];
    $dbdrop= $_POST['dbdrop'];
    $dbprefix= $_POST['dbprefix'];
    $connect=@mysql_connect($dbhost,$dbuser,$dbpass);
    @mysql_select_db($dbname,$connect);
    // drop table
    if ($dbdrop=='yes') {
		mysql_query("DROP TABLE IF EXISTS `".$dbprefix."album`, `".$dbprefix."block`, `".$dbprefix."category`, `".$dbprefix."comment`, `".$dbprefix."diary`, `".$dbprefix."gallery`, `".$dbprefix."lang`, `".$dbprefix."member`, `".$dbprefix."memory`, `".$dbprefix."message`, `".$dbprefix."music`, `".$dbprefix."plugin`, `".$dbprefix."singer`, `".$dbprefix."wish`");
	}
    $tb = '';
    $in = '';

    // Table album
    $sql_query = "CREATE TABLE `".$dbprefix."album` (
                    `id` int(10) NOT NULL auto_increment,
                    `userid` varchar(100) NOT NULL default '',
                    `afor` varchar(100) NOT NULL default 'diary',
                    `thumb` varchar(255) NOT NULL default '',
                    `title` varchar(255) NOT NULL default '',
                    `content` text NOT NULL,
                    `viewed` int(10) NOT NULL default '0',
                    `aorder` varchar(4) NOT NULL default '0',
                    `catid` varchar(10) NOT NULL default '0',
                    PRIMARY KEY  (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
                ";

    $q = mysql_query($sql_query);
    if(!$q) $tb .= tb('album',false);
    else $tb .= tb('album',true);

    // Table block
    $sql_query = "CREATE TABLE `".$dbprefix."block` (
                    `id` int(3) NOT NULL auto_increment,
                    `name` varchar(100) NOT NULL default '0',
                    `showname` varchar(100) NOT NULL default '',
                    `content` varchar(255) NOT NULL default '',
                    `bfor` varchar(255) NOT NULL default 'home',
                    `mode` varchar(1) NOT NULL default '',
                    `function` text NOT NULL,
                    `border` int(10) NOT NULL default '0',
                    `active` varchar(4) NOT NULL default 'yes',
                    PRIMARY KEY  (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
    $q = mysql_query($sql_query);
    if(!$q) $tb .= tb('block',false);
    else $tb .= tb('block',true);

    // Table category
    $sql_query = "CREATE TABLE `".$dbprefix."category` (
                    `id` int(10) NOT NULL auto_increment,
                    `userid` varchar(100) NOT NULL default '',
                    `cfor` varchar(100) NOT NULL default 'blog',
                    `thumb` varchar(255) NOT NULL default '',
                    `title` varchar(255) NOT NULL default '',
                    `content` text NOT NULL,
                    `corder` varchar(4) NOT NULL default '0',
                    `draft` varchar(4) NOT NULL default 'no',
                    `subid` varchar(10) NOT NULL default '0',
                    PRIMARY KEY  (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
    $q = mysql_query($sql_query);
    if(!$q) $tb .= tb('category',false);
    else $tb .= tb('category',true);

    // Table COMMENT
    $sql_query = "CREATE TABLE `".$dbprefix."comment` (
                    `id` int(10) NOT NULL auto_increment,
                    `userid` varchar(100) NOT NULL default '',
                    `catid` int(10) NOT NULL default '0',
                    `cfor` varchar(50) NOT NULL default 'diary',
                    `content` text NOT NULL,
                    `time` varchar(10) NOT NULL default '',
                    `level` varchar(1) NOT NULL default '0',
                    `year` varchar(4) NOT NULL default '0000',
                    `month` varchar(2) NOT NULL default '00',
                    `day` varchar(2) NOT NULL default '00',
                    PRIMARY KEY  (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
    $q = mysql_query($sql_query);
    if(!$q) $tb .= tb('comment',false);
    else $tb .= tb('comment',true);


    // Table diary
    $sql_query = "CREATE TABLE `".$dbprefix."diary` (
                    `id` int(10) NOT NULL auto_increment,
                    `title` varchar(250) NOT NULL,
                    `head` text NOT NULL,
                    `content` longtext NOT NULL,
                    `userid` varchar(5) NOT NULL,
                    `catid` varchar(10) NOT NULL default '0',
                    `weather` int(1) NOT NULL default '0',
                    `year` varchar(4) NOT NULL default '0000',
                    `month` varchar(2) NOT NULL default '00',
                    `day` varchar(4) NOT NULL default '00',
                    `time` varchar(15) NOT NULL default '000000',
                    `icon` varchar(255) NOT NULL default '',
                    `draft` int(1) NOT NULL default '0',
                    `lock` varchar(100) NOT NULL default '',
                    `readed` int(10) NOT NULL default '0',
                    `top` int(1) NOT NULL default '0',
                    PRIMARY KEY  (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
    $q = mysql_query($sql_query);
    if(!$q) $tb .= tb('diary',false);
    else $tb .= tb('diary',true);


    // Table Gallery
    $sql_query = "CREATE TABLE `".$dbprefix."gallery` (
                    `id` int(10) NOT NULL auto_increment,
                    `userid` varchar(100) NOT NULL default '',
                    `thumb` varchar(255) NOT NULL default '',
                    `link` varchar(255) NOT NULL default '',
                    `title` varchar(255) NOT NULL default '',
                    `catid` varchar(10) NOT NULL default '',
                    `content` text NOT NULL,
                    `share` varchar(4) NOT NULL default 'yes',
                    `draft` varchar(4) NOT NULL default 'no',
                    `viewed` varchar(10) NOT NULL default '0',
                    `weather` int(1) NOT NULL default '0',
                    `rate` varchar(10) NOT NULL default '0',
                    `numrate` varchar(10) NOT NULL default '0',
                    `year` varchar(4) NOT NULL default '0000',
                    `month` varchar(2) NOT NULL default '00',
                    `day` varchar(2) NOT NULL default '00',
                    PRIMARY KEY  (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
    $q = mysql_query($sql_query);
    if(!$q) $tb .= tb('gallery',false);
    else $tb .= tb('gallery',true);

    // Table LANG
    $sql_query = "CREATE TABLE `".$dbprefix."lang` (
                    `id` int(11) NOT NULL auto_increment,
                    `name` text,
                    `url` text,
                    `img` text,
                    PRIMARY KEY  (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
    $q = mysql_query($sql_query);
    if(!$q) $tb .= tb('lang',false);
    else $tb .= tb('lang',true);

    // Table MEMBER
    $sql_query = "CREATE TABLE `".$dbprefix."member` (
                    `id` int(10) NOT NULL auto_increment,
                    `username` varchar(50) NOT NULL default '',
                    `password` varchar(50) NOT NULL default '',
                    `fullname` varchar(255) NOT NULL default '',
                    `email` varchar(255) NOT NULL default '',
                    `brithday` varchar(20) NOT NULL default '',
                    `sex` int(1) NOT NULL default '0',
                    `level` int(1) NOT NULL default '1',
                    `avatar` varchar(255) NOT NULL default '',
                    `mavatar` text NOT NULL,
                    `gavatar` varchar(3) NOT NULL default 'no',
                    `yahoo` varchar(255) NOT NULL default '',
                    `icq` varchar(255) NOT NULL default '',
                    `skype` varchar(255) NOT NULL default '',
                    `web` varchar(255) NOT NULL default '',
                    `numvisit` int(10) NOT NULL default '0',
                    `lastvisit` varchar(30) NOT NULL default '',
                    `playlistmode` varchar(10) NOT NULL default '',
                    `playlist` varchar(255) NOT NULL default '',
                    PRIMARY KEY  (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
    $q = mysql_query($sql_query);
    if(!$q) $tb .= tb('member',false);
    else $tb .= tb('member',true);


    // Table MENU
    $sql_query = "CREATE TABLE `".$dbprefix."memory` (
                    `id` int(10) NOT NULL auto_increment,
                    `title` varchar(250) NOT NULL,
                    `userid` varchar(100) NOT NULL default '',
                    `head` text NOT NULL,
                    `content` longtext NOT NULL,
                    `type` varchar(20) NOT NULL default '',
                    `top` varchar(4) NOT NULL default 'no',
                    `year` varchar(4) NOT NULL default '0000',
                    `month` varchar(2) NOT NULL default '00',
                    `day` varchar(4) NOT NULL default '00',
                    `time` varchar(15) NOT NULL default '000000',
                    `icon` varchar(255) NOT NULL default '',
                    `total` int(10) NOT NULL default '0',
                    PRIMARY KEY  (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
    $q = mysql_query($sql_query);
    if(!$q) $tb .= tb('memory',false);
    else $tb .= tb('memorey',true);


    // Table MESSAGE
    $sql_query = "CREATE TABLE `".$dbprefix."message` (
                    `id` int(3) NOT NULL auto_increment,
                    `mfrom` varchar(80) NOT NULL default '0',
                    `mto` varchar(80) NOT NULL default '0',
                    `title` varchar(150) NOT NULL default '0',
                    `message` varchar(255) NOT NULL default '0',
                    `readed` int(10) NOT NULL default '0',
                    `date` varchar(20) NOT NULL default '00000000',
                    `time` varchar(10) NOT NULL default '00:00:00',
                    `fromdel` int(1) NOT NULL default '0',
                    `todel` int(1) NOT NULL default '0',
                    PRIMARY KEY  (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
    $q = mysql_query($sql_query);
    if(!$q) $tb .= tb('message',false);
    else $tb .= tb('message',true);

    // Table music
    $sql_query = "CREATE TABLE `".$dbprefix."music` (
                    `id` int(10) NOT NULL auto_increment,
                    `userid` varchar(100) NOT NULL default '',
                    `title` varchar(255) NOT NULL default '',
                    `catid` int(10) NOT NULL default '0',
                    `albumid` varchar(10) NOT NULL default '0',
                    `singer` int(10) NOT NULL default '0',
                    `link` varchar(255) NOT NULL default '',
                    `type` varchar(10) NOT NULL default '',
                    `lyric` text NOT NULL,
                    `played` int(10) NOT NULL default '0',
                    `download` int(10) NOT NULL default '0',
                    `rate` int(10) NOT NULL default '0',
                    `numrate` int(10) NOT NULL default '0',
                    `date` varchar(50) NOT NULL default '',
                    `err` varchar(4) NOT NULL default 'no',
                    PRIMARY KEY  (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
    $q = mysql_query($sql_query);
    if(!$q) $tb .= tb('music',false);
    else $tb .= tb('music',true);



    // Table Plugin
    $sql_query = "CREATE TABLE `".$dbprefix."plugin` (
                    `id` int(3) NOT NULL auto_increment,
                    `name` varchar(255) NOT NULL default '',
                    `content` varchar(255) NOT NULL default '',
                    `author` varchar(100) NOT NULL default '',
                    `authormail` varchar(255) NOT NULL default '',
                    `authorweb` varchar(255) NOT NULL default '',
                    `version` varchar(100) NOT NULL default '',
                    `update` varchar(100) NOT NULL default '',
                    `link` varchar(225) NOT NULL default '',
                    `admin` varchar(5) NOT NULL default 'no',
                    `adminurl` varchar(255) NOT NULL default '',
                    `indexurl` varchar(225) NOT NULL default '',
                    `active` varchar(10) NOT NULL default 'no',
                    `pdefault` varchar(10) NOT NULL default 'no',
                    PRIMARY KEY  (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
    $q = mysql_query($sql_query);
    if(!$q) $tb .= tb('plugin',false);
    else $tb .= tb('plugin',true);



    // Table singer
    $sql_query = "CREATE TABLE `".$dbprefix."singer` (
                    `id` int(10) NOT NULL auto_increment,
                    `title` varchar(255) NOT NULL default '',
                    `category` int(10) NOT NULL default '0',
                    `thumb` varchar(255) NOT NULL default '',
                    `content` text NOT NULL,
                    `played` int(10) NOT NULL default '0',
                    PRIMARY KEY  (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;";
    $q = mysql_query($sql_query);
    if(!$q) $tb .= tb('singer',false);
    else $tb .= tb('singer',true);


    // Table wish
    $sql_query = "CREATE TABLE `".$dbprefix."wish` (
                    `id` int(10) NOT NULL auto_increment,
                    `userid` int(10) NOT NULL default '0',
                    `wishfor` varchar(255) NOT NULL default '',
                    `category` varchar(255) NOT NULL default '',
                    `content` text NOT NULL,
                    `year` varchar(4) NOT NULL default '0000',
                    `month` varchar(2) NOT NULL default '00',
                    `day` varchar(4) NOT NULL default '00',
                    `time` varchar(15) NOT NULL default '000000',
                    `icon` varchar(255) NOT NULL default '',
                    `lock` varchar(4) NOT NULL default 'no',
                    PRIMARY KEY  (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;";
    $q = mysql_query($sql_query);
    if(!$q) $tb .= tb('wish',false);
    else $tb .= tb('wish',true);



  //// INSERT
  $q = mysql_query("INSERT INTO `".$dbprefix."block` VALUES (1, 'Lịch trang nhật ký', '{lang.calendar}', 'Duyệt nhật ký theo lịch', 'diary', '0', '2', 1, 'yes');");

  $sql_query = "INSERT INTO `".$dbprefix."block` VALUES (2, 'Nhật ký mới viết', '{lang.diary_new}', 'Danh sách nhật ký mới viết của anh và em', 'diary', '0', '4', 2, 'yes');";
  $q = mysql_query($sql_query);
  $sql_query = "INSERT INTO `".$dbprefix."block` VALUES (3, 'Sử dụng mã html', 'DiaryLove 2.0', 'Bạn có thể sử dụng mã html để tạo cho mình các block theo ý muốn', 'all', '2', '<center><img src=\'images/logo.jpg\' width=\200\' border=\'0\'><br><b>DiaryLove 2.0</b></center>', 3, 'yes');";
  $q = mysql_query($sql_query);
  $sql_query = "INSERT INTO `".$dbprefix."block` VALUES (4, 'Thông tin chủ nhật ký', '{lang.about}', '', 'all', '0', '1', 0, 'yes');";
  $q = mysql_query($sql_query);
  $sql_query = "INSERT INTO `".$dbprefix."block` VALUES (5, 'Chủ đề ảnh', '{lang.category}', 'Danh sách chủ đề ảnh', 'gallery', '0', '8', 1, 'yes');";
  $q = mysql_query($sql_query);
  $sql_query = "INSERT INTO `".$dbprefix."block` VALUES (6, 'Lịch đăng ảnh', '{lang.calendar}', 'Duyệt ảnh theo lịch ngày đăng', 'gallery', '0', '6', 0, 'yes');";
  $q = mysql_query($sql_query);
  $sql_query = "INSERT INTO `".$dbprefix."block` VALUES (7, 'Ảnh mới đăng', '{lang.gallery_new}', 'Danh sách ảnh mới đăng', 'gallery', '0', '7', 3, 'yes');";
  $q = mysql_query($sql_query);
  $sql_query = "INSERT INTO `".$dbprefix."block` VALUES (8, 'Chủ đề nhật ký', '{lang.category}', 'Danh sách chủ đề nhật ký', 'diary', '0', '5', 1, 'yes');";
  $q = mysql_query($sql_query);
  $sql_query = "INSERT INTO `".$dbprefix."block` VALUES (9, 'Lịch năm', '{lang.calendar}', 'Lịch năm trên trang chủ', 'home', '0', 'calendar', 1, 'yes');";
  $q = mysql_query($sql_query);

  $sql_query = "INSERT INTO `".$dbprefix."block` VALUES (10, 'Nhật ký mới viết', '{lang.diary_new}', 'Danh sách nhật ký mới viết', 'home', '0', '4', 2, 'yes');";
  $q = mysql_query($sql_query);
  $sql_query = "INSERT INTO `".$dbprefix."block` VALUES (11, 'Ảnh mới đăng', '{lang.gallery_new}', 'Danh sách ảnh mới đăng', 'home', '0', '7', 2, 'yes');";
  $q = mysql_query($sql_query);
  $sql_query = "INSERT INTO `".$dbprefix."block` VALUES (12, 'Chủ đề nhạc', '{lang.category}', 'Danh sách chủ đề nhạc', 'music', '0', '12', 1, 'yes');";
  $q = mysql_query($sql_query);
  $sql_query = "INSERT INTO `".$dbprefix."block` VALUES (13, 'Ca khúc mới đăng', '{lang.new_post}', 'Danh sách các ca khúc mới đăng', 'music', '0', '11', 2, 'yes');";
  $q = mysql_query($sql_query);
  $sql_query = "INSERT INTO `".$dbprefix."block` VALUES (14, 'Danh sách ca sỹ', '{lang.singer_list}', 'Danh sách các ca sỹ', 'music', '0', '13', 3, 'yes');";
  $q = mysql_query($sql_query);
  $in .= in('block');


  $sql_query = "INSERT INTO `".$dbprefix."category` VALUES (1, '1', 'diary', '', 'Chủ đề mặc định', 'Chủ đề mặc định cho nhật ký viết thử', '0', 'no', '0');";
  $q = mysql_query($sql_query);
  $sql_query = "INSERT INTO `".$dbprefix."category` VALUES (2, '1', 'gallery', '', 'Chủ đề mặc định', 'Chủ đề mặc định cho thư viện ảnh viết thử', '0', 'no', '0');";
  $q = mysql_query($sql_query);
  $sql_query = "INSERT INTO `".$dbprefix."category` VALUES (3, '1', 'music', '', 'Chủ đề mặc định', 'Chủ đề mặc định cho mục nhạc viết thử', '0', 'no', '0');";
  $q = mysql_query($sql_query);
  $in .= in('category');


  $sql_query = "INSERT INTO `".$dbprefix."diary` VALUES (1, 'Nhật ký viết thử', 'Chúc mừng bạn đã cài đặt thành công phiên bản DiaryLove 2.0', 'Chúc mừng bạn đã cài đặt thành công phiên bản DiaryLove 2.0\nĐây là bài viết thử bạn có thể edit hoặc xóa bỏ bằng cách click vào edit hoặc delete.', '1', '1', 2, '".date('Y')."', '".date('m')."', '".date('d')."', '', 'happy', 0, '', 0, 0);";
  $q = mysql_query($sql_query);
  $in .= in('diary');

  $sql_query = "INSERT INTO `".$dbprefix."lang` VALUES (1, 'vietnam', 'vietnam', 'language/vietnam.gif');";
  $q =  mysql_query($sql_query);
  $in .= in('lang');

  $sql_query = "INSERT INTO `".$dbprefix."message` VALUES (1, '1', '1', 'Tin nhắn chào mừng', 'Chúc mừng bạn đã cài đặt thành công phiên bản DiaryLove 2.0\nĐây là tin nhắn viết thử bạn có thể xóa bỏ trong mục [b]Message[/b].', 0, '19-03-2010', '12:32:00 pm', 1, 0);";
  $q =  mysql_query($sql_query);
  $in .= in('message');

  $sql_query = "INSERT INTO `".$dbprefix."music` VALUES (1, '1', 'MyLove', 3, '', 1, 'http://www.nhaccuatui.com/nghe?M=WoyVBggFOj', 'mp3', '', 0, 0, 0, 0, '', 'no');";
  $q =  mysql_query($sql_query);
  $in .= in('music');

  $sql_query = "INSERT INTO `".$dbprefix."plugin` VALUES (1, 'About', '{lang.about}', 'Dương Hoàng Long', 'duonghoanglong85@gmail.com', 'http://nhocyeu.plus.vn', '1.0', 'http://giadinhsieuquay.orgfree.com/diarylove/2.0/plugin/update.php?go=about', 'default', 'yes', 'default.admin.php', 'about', 'yes', 'yes');";
  $q =  mysql_query($sql_query);
  $in .= in('plugin');

  $sql_query = "INSERT INTO `".$dbprefix."singer` VALUES (1, 'Westlife', 3, 'http://giaitriamnhac.info/Images/upload/Westlife.jpg', 'Westlife là một boyband nhạc pop đến từ Ireland và được thành lập năm 1998, ông bầu của nhóm là Louis Walsh. Nhóm đã đạt được thành công lớn ở Anh và Ireland cũng như ở các nước khác tại Châu Âu và một số nơi như Úc, Châu Á và Châu Phi. Westlife đã có 14 đĩa đơn đạt vị trí #1 trong bảng xếp hạng Anh (tính từ năm 1999 đến năm 2006), xếp thứ 3 trong số những nghệ sĩ và ban nhạc có nhiều đĩa đơn #1 nhất tại Anh (chỉ sau Elvis Presley và The Beatles, xếp ngang với Cliff Richard). Westlife là ban nhạc duy nhất trong lịch sử bảng xếp hạng Anh có 7 đĩa đơn liên tiếp đạt vị trí #1 và giành giải \"Ghi Âm của Năm\" tại Anh 4 lần. Ban nhạc đã bán được tổng cộng 40 triệu album tại hơn 40 nước, 14 lần đĩa đơn #1 tại Anh và 13 lần đĩa đơn #1 tại Ireland.\n\n[b][right]Theo http://www.giaitriamnhac.info.[/right][/b]', 0);";
  $q =  mysql_query($sql_query);
  $in .= in('singer');

// tao file config.php
    $val = '<?
    $db_host    = \''.$dbhost.'\';
    $db_user    = \''.$dbuser.'\';
    $db_pass    = \''.$dbpass.'\';
    $db_data    = \''.$dbname.'\';
    $table_prefix = \''.$dbprefix.'\';
    $module     = \'modules/\';
    $plugin     = \'plugin/\';
    $update_link = \'http://giadinhsieuquay.orgfree.com/diarylove/update/\';
    $admin_mail = \'Duonghoanglong85@gmail.com\';
    $version = \'2.0\';
    $date = \''.date('d-m-Y').'\';
    $codedate = \'23-1-2010\';
    $installed = \'no\';
    ?>';
    @file_put_contents('../includes/config.php', $val);

    ?>
    <div class='log'>Thông tin cài đặt cơ sở dữ liệu</div>
            <div class='mes'><div><form method='post' onsubmit='return false;'>
               <b>.: Kết quả tạo bảng trong CDSL :.</b><br />
                <div style='width: 90%;' align='left'><br />
                    <? echo $tb; ?>  <br />               <br />
                 <b>.: Kết quả chèn dữ liệu mặc định trong CDSL :.</b><br />  <br />
                    <? echo $in; ?>
                </div>
            </div>
        <div align='center'>
            <input type='submit' value='Tiếp tục' class='inbut' onclick="next('act=setadmin');">
             </form></div></div>  <br />
<?
    exit();
}


/////////////////////////////////////  THIET LAP THONG TIN BLOG ////////////////////////////////////

if($_POST['act']=='setadmin'){
?>
<div class='log'>Thiết lập thông tin trang nhật ký</div>
   <div class='mes'>
    <form method='post' onsubmit='return false;'>

    <div style='text-align: center; float: left; width: 100%;'>
        Tên cuốn nhật ký : <input style='font-weight: bold;' type='text' size='40' id='diaryname' name='diaryname' value='DiaryLove 2.0'><br><br>
    </div>

    <b><font color='blue'>Thông tin của bạn trai:</b></font><br /><br />
    <div style='float: left; padding-left: 55px;'>
        <div style='float: left; width: 120px;'>
            Họ và tên :
        </div>
        <div style='float: left; width: 320px;'>
            <input style='margin: 1px;' type='text' size='32' id='fullname1' value='' name='fullname1'>&nbsp;<font color=red><b>*</b></font>
        </div>

        <div style='float: left; width: 120px;'>
            Tài khoản truy cập :
        </div>
        <div style='float: left; width: 320px;'>
            <input style='margin: 1px;' type='text' size='32' id='username1' value='' name='username1'>&nbsp;<font color=red><b>*</b></font>
        </div>

        <div style='float: left; width: 120px;'>
            Mật khẩu truy cập :
        </div>
        <div style='float: left; width: 320px;'>
            <input style='margin: 1px;' type='password' size='32' id='password1' value='' name='password1'>&nbsp;<font color=red><b>*</b></font>
        </div>

        <div style='float: left; width: 120px;'>
            Xác nhận mật khẩu :
        </div>
        <div style='float: left; width: 320px;'>
            <input style='margin: 1px;' type='password' size='32' id='c_password1' value='' name='c_password1'>&nbsp;<font color=red><b>*</b></font>
        </div>

        <div style='float: left; width: 120px;'>
            Sinh nhật (<b>D/M/Y</b>):
        </div>
        <div style='float: left; width: 320px;'>
            <input style='margin: 1px;' type='text' size='4' id='day1' value='' name='day1'>/
            <input style='margin: 1px;' type='text' size='4' id='month1' value='' name='month1'>/
            <input style='margin: 1px;' type='text' size='10' id='year1' value='' name='year1'>&nbsp;<font color=red><b>*</b></font>
        </div>

        <div style='float: left; width: 120px;'>
            Yahoo :
        </div>
        <div style='float: left; width: 320px;'>
            <input style='margin: 1px;' type='text' size='32' id='yahoo1' value='' name='yahoo1'>&nbsp;<font color=red><b>*</b></font>
        </div>
    </div>



    <b><font color='red'>Thông tin của bạn gái:</b></font><br />
    <div style='float: left; padding-left: 55px;'>
        <div style='float: left; width: 120px;'>
            Họ và tên :
        </div>
        <div style='float: left; width: 320px;'>
            <input style='margin: 1px;' type='text' size='32' id='fullname2' value='' name='fullname1'>&nbsp;<font color=red><b>*</b></font>
        </div>

        <div style='float: left; width: 120px;'>
            Tài khoản truy cập :
        </div>
        <div style='float: left; width: 320px;'>
            <input style='margin: 1px;' type='text' size='32' id='username2' value='' name='username1'>&nbsp;<font color=red><b>*</b></font>
        </div>

        <div style='float: left; width: 120px;'>
            Mật khẩu truy cập :
        </div>
        <div style='float: left; width: 320px;'>
            <input style='margin: 1px;' type='password' size='32' id='password2' value='' name='password1'>&nbsp;<font color=red><b>*</b></font>
        </div>

        <div style='float: left; width: 120px;'>
            Xác nhận mật khẩu :
        </div>
        <div style='float: left; width: 320px;'>
            <input style='margin: 1px;' type='password' size='32' id='c_password2' value='' name='c_password1'>&nbsp;<font color=red><b>*</b></font>
        </div>

        <div style='float: left; width: 120px;'>
            Sinh nhật (<b>D/M/Y</b>):
        </div>
        <div style='float: left; width: 320px;'>
            <input style='margin: 1px;' type='text' size='4' id='day2' value='' name='day1'>/
            <input style='margin: 1px;' type='text' size='4' id='month2' value='' name='month1'>/
            <input style='margin: 1px;' type='text' size='10' id='year2' value='' name='year1'>&nbsp;<font color=red><b>*</b></font>
        </div>

        <div style='float: left; width: 120px;'>
            Yahoo :
        </div>
        <div style='float: left; width: 320px;'>
            <input style='margin: 1px;' type='text' size='32' id='yahoo2' value='' name='yahoo1'>&nbsp;<font color=red><b>*</b></font>
        </div>
    </div>

      <div align='center'>
      <i><b>Chú ý:</b> Các mục có dấu <font color=red>*</font> cần khai báo hết.</i><br /><br />
        <input type='submit' onclick="checkinfo();" value='Tiếp theo' class='inbut'>
        <input type='reset' value='Cấu hình lại'  class='inbut'>
      </div>
    </form>
   </div>
 <?
 exit();
 }



 function update_file_php($link,$array){
    $str = @file_get_contents($link);
    $str = trim($str);
    if (is_array($array)) {
	    reset ($array);
		while (list($k,$v) = each($array)) {
		    $str = preg_replace("/[$]$k\s*\=\s*[\"'].*?[\"'];/is", "\$$k = '".$v."';", $str);
		}
	}
    $str = @file_put_contents($link, $str);
   return $str;
}

 // cau hinh thong tin nhat ky
 if($_POST['act']=='diaryinfo'){
 include('../includes/connect.php');
 // thong tin ban trai
    $fullname1 = $_POST['fullname1'];
    $username1 = $_POST['username1'];
    $password1 = $_POST['password1'];
    $yahoo1 = $_POST['yahoo1'];
    $day1 = $_POST['day1'];
    $month1 =  $_POST['month1'];
    $year1 = $_POST['year1'];

 // thong tin ban gai
    $fullname2 = $_POST['fullname2'];
    $username2 = $_POST['username2'];
    $password2 = $_POST['password2'];
    $yahoo2 = $_POST['yahoo2'];
    $day2 = $_POST['day2'];
    $month2 =  $_POST['month2'];
    $year2 = $_POST['year2'];

    $diaryname = $_POST['diaryname'];
 // khoi tao thanh vien nam
 $boy = array(
    'username'  => $username1,
    'fullname'  => $fullname1,
    'yahoo' => $yahoo1,
    'password'  => md5($password1),
    'brithday'  => $day1."-".$month1."-".$year1,
    'sex'   => '1',
    );
    $mysql->insert("member",$boy);
    // cap nhat ngay sinh nhat cua ban trai vao ngay dang nho
    $t1 = (date('Y')-$year1) + 1;;
    $m1 = array(
        'title' => '{lang.brithday_boy}',
        'head'  => '{lang.brithday_boy}',
        'content'  => '{lang.brithday_boy}',
        'type'  => 'brithday_boy',
        'year'  => $year1,
        'month' => $month1,
        'day'   => $day1,
        'total' => $t1,
    );
    $mysql->insert("memory",$m1);

// khoi tao thanh vien nam
 $girl = array(
    'username'  => $username2,
    'fullname'  => $fullname2,
    'yahoo' => $yahoo2,
    'password'  => md5($password2),
    'brithday'  => $day2."-".$month2."-".$year2,
    'sex'   => '2',
    );
    $mysql->insert("member",$girl);
    // cap nhat ngay sinh nhat cua ban trai vao ngay dang nho
    $t2 = (date('Y')-$year2) + 1;;
    $m2 = array(
        'title' => '{lang.brithday_girl}',
        'head'  => '{lang.brithday_girl}',
        'content'  => '{lang.brithday_girl}',
        'type'  => 'brithday_girl',
        'year'  => $year2,
        'month' => $month2,
        'day'   => $day2,
        'total' => $t2,
    );
    $mysql->insert("memory",$m2);

// cap nhat thong tin trang nhat ky
$arr = array(
    'web_title' => $diaryname,
    );
 update_file_php('../includes/site.config.php',$arr);
 $arr = array(
    'installed' => 'yes',
    );
 update_file_php('../includes/config.php',$arr);
?>
    <div class='log'>Cài đặt thành công</div>
   <div class='mes' align='center'>
        <b>Chúc mừng bạn đã cài đặt thành công!.</b><br />Bạn hãy đổi tên hoặc xóa thư mục <b>install</b> khỏi server để tránh việc có người truy cập làm mất dữ liệu.<br /><Br />
        Giờ bạn có thể truy cập vào trang<a href="../index.php" title='Tới trang chủ'><b>Nhật Ký</b></a>.<br />Chúc bạn sẽ có 1 cuốn nhật ký tình yêu thật đẹp!.
   </div>
 <?
 exit();
 }

?>
<html>
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" rev="stylesheet" href="install.css" type="text/css" media="all" />
        <script language='javascript' src='../js/jquery.js'></script>
        <title>Trình cài đặt DiaryLove 2.0!</title>
        <script language="javascript">
            function db_checkconnect(){
                var db_host = $('#db_host').val();
                var db_user = $('#db_user').val();
                var db_pass = $('#db_pass').val();
                var db_name = $('#db_name').val();
                if(db_host == ''){
                    alert('Bạn chưa nhập địa chỉ Mysql!');
                    $('#db_host').focus();
                    return false;
                }
                if(db_user == ''){
                    alert('Bạn chưa nhập tên tài khoản Mysql!');
                    $('#db_user').focus();
                    return false;
                }
                if(db_pass == ''){
                    alert('Bạn chưa nhập mật khẩu truy cập Mysql!');
                    $('#db_pass').focus();
                    return false;
                }
                if(db_name ==''){
                    alert('Bạn chưa nhập tên CSDL!');
                    $('#db_name').focus();
                    return false;
                }
                $.ajax({
                    beforeSend: function(){
                        $('#db_showcheck').fadeTo('slow', 1).html('<br><center>Đang kiểm tra kết nối...<br><img src="images/loading.gif"></center></br>');
                    },
                    type: "post",
                    url: "install.php",
                    data: { db: 'check',
                            dbtype: 'check',
                            dbhost: db_host,
                            dbuser: db_user,
                            dbpass: db_pass,
                            dbname: db_name
                    },
                    success: function(data){
                        $('#db_showcheck').html(data);
                        return false;
                    }
                });
            }

            function dbcheck(){
                var db_host = $('#db_host').val();
                var db_user = $('#db_user').val();
                var db_pass = $('#db_pass').val();
                var db_name = $('#db_name').val();
                var db_prefix = $('#db_prefix').val();
                var drop = document.getElementById("db_drop").checked;
                if(!drop){
                    db_drop = 'no';
                }else{
                    db_drop = 'yes';
                }
                db_checkconnect();
                if(db_host == '' || db_user == '' || db_pass=='' || db_name == '') return false;
                $.ajax({
                    type: "post",
                    url: "install.php",
                    data: { db: 'check',
                            dbtype: 'submit',
                            dbhost: db_host,
                            dbuser: db_user,
                            dbpass: db_pass,
                            dbname: db_name
                    },
                    success: function(data){
                        err = data.toString();
                        if(err=='no'){
                            alert('Có lỗi khi kết nỗi với CSDL! Vui lòng kiểm tra lại!');
                            return false;
                        }else{
                            $.ajax({
                                beforeSend: function(){
                                    $('#main').fadeTo('slow', 1).html("<center><br>Đăng tải dữ liệu...<br><img src='images/loading.gif'></center>");
                                },
                                type: "post",
                                url: "install.php",
                                data: {
                                    db: 'submit',
                                    dbhost: db_host,
                                    dbuser: db_user,
                                    dbpass: db_pass,
                                    dbname: db_name,
                                    dbprefix: db_prefix,
                                    dbdrop: db_drop
                                 },
                                 success: function(data){
                                    $('#main').html(data);
                                 }
                            });
                        }
                        return false;
                    }
                });
            }

            function next(link){
                $.ajax({
                    beforeSend: function(){
                        $('#main').fadeTo('slow', 1).html("<center><br>Đăng tải dữ liệu...<br><img src='images/loading.gif'></center>");
                    },
                    type: "post",
                    url: "install.php",
                    data: ''+link,
                    success: function(data){
                        $('#main').fadeTo('slow', 1).html(data);
                    }
                });
            }

              function checkinfo(){
                  fullname1 = $('#fullname1').val();
                  username1 = $('#username1').val();
                  password1 = $('#password1').val();
                  c_pass1 = $('#c_password1').val();
                  day1 = $('#day1').val();
                  month1 = $('#month1').val();
                  year1 = $('#year1').val();
                  yahoo1 = $('#yahoo1').val();

                  fullname2 = $('#fullname2').val();
                  username2 = $('#username2').val();
                  password2 = $('#password2').val();
                  c_pass2 = $('#c_password2').val();
                  day2 = $('#day2').val();
                  month2 = $('#month2').val();
                  year2 = $('#year2').val();
                  yahoo2 = $('#yahoo2').val();

                  diaryname = $('#diaryname').val();

                  if(fullname1 == '' || username1 == '' || password1 == '' || c_pass1 == '' || day1 == '' || month1 == '' || year1 == '' || yahoo1 == ''){
                      alert('Chưa khai báo đầy đủ thông tin bạn trai!');
                      return false;
                  }
                  if(password1!==c_pass1){
                      alert('Xác nhận mật khẩu bạn trai không chính xác!');
                      $('#pass').focus();
                      return false;
                  }

                  if(fullname2 == '' || username2 == '' || password2 == '' || c_pass2 == '' || day2 == '' || month2 == '' || year2 == '' || yahoo2 == ''){
                      alert('Chưa khai báo đầy đủ thông tin bạn gái!');
                      return false;
                  }
                  if(password2!==c_pass2){
                      alert('Xác nhận mật khẩu bạn gái không chính xác!');
                      $('#pass').focus();
                      return false;
                  }
                  $.ajax({
                      beforeSend: function(){
                          $('#main').fadeTo('slow').html("<center><br>Đang tải dữ liệu ...<br><img src='images/loading.gif'><br></center>");
                      },
                      type: "post",
                      url: "install.php",
                      data: { act: 'diaryinfo',
                              fullname1: fullname1,
                              username1: username1,
                              password1: password1,
                              yahoo1: yahoo1,
                              day1: day1,
                              month1: month1,
                              year1: year1,
                              fullname2: fullname2,
                              username2: username2,
                              password2: password2,
                              yahoo2: yahoo2,
                              day2: day2,
                              month2: month2,
                              year2: year2,
                              diaryname: diaryname

                      },
                      success: function(data){
                          $('#main').html(data);
                      }
                  });
              }
        </script>
        </head>
        <div id="titles">
	        Trình cài đặt DiaryLove
        </div>
           <div id="tips">
            <div id='main'>

<?
if(!$_GET['act'] || $installed=='yes'){
   if($installed=='yes'){
?>
        <div class='log'>Thông báo</div>
            <div class='mes'>
                <div align='center' style='width: 95%;'>Gói cài đặt đã hoàn tất! Nếu muốn cài đặt lại bạn phải xóa hoặc đổi tên file <b>includes/config.php</b> khỏi hệ thống!</div><br />
                <p align='center'>Click vào <b><a href='../index.php'><font color='cc0000'>Đây</font></a></b> để trở về trang chủ</p>
        </div>
        <br />
</div>
<div class='log' style='height: 15px; margin-bottom: -10px;'><center>Phiên bản cài đặt <a href='mailto: duongghoanglong85@gmail.com'><font color='#ffffff'>DiaryLove 2.0</font></center></div>
</div>
</div>
</body>
</html>
<?
exit();
}else{
    ?>
            <div class='log'>Đọc thỏa thuận sử dụng</div>
            <div class='mes'><div align='center'><form action="install.php?act=install" method='post'>
            <div style="padding: 5px; text-align: left; border: 1px solid rgb(204, 204, 204); overflow: auto; height: 250px; width: 90%;">
            <p align='center'><b>DiaryLove 2.0</b><br />
            ------------------------------------------<br /> </p>
            <p>Trước khi cài đặt và sử dụng mã nguồn bạn phải đọc trước các điều khoản về bản quyền chương trình
        	cũng như các thỏa thuận dưới đây. Nếu có bất cứ vi phạm gì về bản quyền bạn
        	phải chịu hoàn toàn trách nhiệm.</p>
        	<p>&nbsp;- Phiên bản được viết trên mã nguồn php sử dụng cơ sở dữ liệu Mysql. Được
        	viết bởi <a href="mailto:Duonghoanglong85@gmail.com">
        	Duonghoanglong85@gmail.com</a> với chức năng nhật ký cá nhân và nhật ký tình yêu. Mã
        	nguồn có sử dụng thư viện <a href='http://jquery.com' target='_blank'><b>JQUERY</b></a> và 1 số mã nguồn khác được chia sẻ trên
        	internet.</p>
        	<p>&nbsp;- Đây là mã nguồn mở và ai cũng có thể tải và sử dụng miễn phí trên cơ sở
        	không tiến hành thương mại hóa. Bạn chỉ cần đọc và làm đúng các quy định
        	dưới đây:</p>

        	<p><b>1. Sử dụng chương trình và bản quyền:</b><br>
        	<p style='padding-left: 15px;'>
            (1) Bất cứ ai đều có thể tải về bản ổn định mới nhất cũng như bản thử nghiệm
        	tại trang chủ hay các trang tải về.<br>
        	(2) Bất cứ ai ngoại trừ các trường hợp dưới đây đều có thể miễn phí cài đặt
        	và sử dụng chương trình này mà không phải trả thêm chi phí, các trường hợp
        	vi phạm:<br>
        	a. Ghi đĩa, phát hành phi pháp, gắn kèm các thông tin xấu, vi phạm quy định
        	của pháp luật.<br>
        	b. Các website kinh doanh;<br>
        	(3) Các website và các cá nhân có thể truyền phát với điều kiện miễn phí và
        	đóng gói hoàn chỉnh.<br> </p>

        	<b>2. Miễn trách nhiệm.</b><br>
            <p style='padding-left: 15px;'>
        	(1) Tác giả không chịu trách nhiệm về việc mất dữ liệu, mật khẩu trong quá
        	trình sử dụng mã nguồn, cũng như các hậu quả do nội dung bài viết của người
        	sử dụng.<br>
        	(2) Nếu có vấn đề về sử dụng, tác giả sẽ cố gắng giúp đỡ nhưng đây không
        	phải là nghĩa vụ của tác giả.<br>
        	Cuối cùng cám ơn bạn đã sử dụng mã nguồn DiaryLove !&nbsp; Mọi thắc mắc cũng
        	như ý kiến đóng góp phản hồi các bạn có thể gửi về địa chỉ email
        	<a href="mailto:Duonghoanglong85@gmail.com">Duonghoanglong85@gmail.com</a> .</p>

        	<p align="center"><b><i>" Chúc các bạn 1 cuộc sống hạnh phúc và 1 tình yêu vĩnh cửu! "</i></b></p><p align="center">&nbsp;</p>
        </div></div>
        <div align='center'>
            <input type='submit' value='Đồng ý' class='inbut'>
            <input type='button' value='Không đồng ý' onclick='window.location="install.php?act=cancel";' class='inbut'>
        </form></div></div>
<?
}
}if($_GET['act']=='cancel'){
    ?>
      <div class='log'>Cài đặt kết thúc</div>
      <div class='mes'><div align='left'>
      &nbsp;Bạn phải đồng ý với các thỏa thuận mới có thể tiến hành cài đặt tiếp
      </div> </div>
    <?
}
if($_GET['act']=='install'){
    $b = "http://".$_SERVER['HTTP_HOST'].str_replace('/install/install.php', '', $_SERVER['PHP_SELF']);
    ?>
        <div class='log'>Thiết lập CSDL</div>
        <div class='mes'>
            <form method='post' onsubmit="return false;">
            <table width="100%">
                <tr>
                    <td>
             <div id='db_khaibao' style='width: 150px; float: left;'>
                Địa chỉ MySQL:<br>
                <input type='text' size='20' value='localhost' name='db_host' id='db_host'><br><br>
                Tên người dùng MySQL:<br><input type='text' size='20' value='' name='db_user' id='db_user'><br><br>
                Mật khẩu MySQL:<br><input type='password' size='20' value='' name='db_pass' id='db_pass'><br><br>
                Tên CSDL MySQL:<br><input type='text' size='20' value='' name='db_name' id='db_name'><br>
            </div>
            <div id='db_check' style='width: 350px; float: left;'>
                 <div id='db_showcheck' style='height: 165px;'></div>
                 <div>
                    <a href='javascript: void(0);' onclick='db_checkconnect();' title='Kiểm tra kết nối với CSDL'><font color="blue">Kiểm tra kết nối.</font></a>
                 </div>
            </div>
                    </td>
                </tr>
            </table>
             <table width="100%">
                <tr>
                    <td>
            <div style='width: 100%;'>
                Chú ý: chương trình không tự động tạo CSDL, vì vậy cần tạo trước 1 CSDL.<br><br>
                Tiền tố bảng dữ liệu:<br>
                <input type='text' size='20' value='vn_' name='db_prefix' id='db_prefix'><br>
                Điền ký tự tùy ý, kiến nghị nên thêm gạch dưới ở sau ký tự cuối cùng<br><br>
                Vị trí của blog<br><input id='blogurl' type='text' size='20' value='<? echo $b; ?>' name='blogurl'>/index.php<br>
                 Điền đường dẫn <b>tuyệt đối</b> của trang chủ nhật ký<br><br>
                 Xử lý dữ liệu trùng tên:<br>
                 <input type='checkbox' id='db_drop' name='db_drop' onclick="alert('Chú ý: Những bảng dữ liệu này có thể lưu dữ liệu của lần cài đặt trước, sau khi đè lên sẽ mất hết dữ liệu này!');">Đè lên và tiếp tục cài đặt
                 <div align='center'><br>
                 <input type='submit' value='Tiếp theo' onclick='dbcheck();' class='inbut'>
                 <input type='reset' value='Cấu hình lại'  class='inbut'></div></form>
                </div>
                </td>
                </tr>
            </table>
             </div>
<?
}
?>
<br />
</div>
<div class='log' style='height: 15px; margin-bottom: -10px;'><center>Phiên bản cài đặt <a href='mailto: duongghoanglong85@gmail.com'><font color='#ffffff'>DiaryLove 2.0</font></center></div>
</div>
</div>
</body>
</html>