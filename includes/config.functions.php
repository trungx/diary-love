<?php



function get_config($f1,$table,$f2,$f2_value){
	global $mysql,$table_prefix;
	$q = "SELECT $f1 FROM ".$table_prefix.$table." WHERE $f2='".$f2_value."'";
	$r = $mysql->query($q);
	if ($r) {
	$rs = $mysql->fetch_array($r);
	$f1_value = $rs[$f1];
	}
	return $f1_value;
}

function filter($text,$badwrd){
    $badWords=explode(",",$badwrd);
        foreach ($badWords as $badWord){
            if (false !== stripos($text, $badWord)) {
                return true;
            }
        }
    return false;
}

function cut_str($str,$len){
	if ($str=='' || $str==NULL) return $str;
	if (is_array($str)) return $str;
	$str = trim($str);
	if (strlen($str) <= $len) return $str;
	$str = substr($str,0,$len);
	if ($str != '') {
		if (!substr_count($str," ")) {
			return $str;
		}
		while(strlen($str) && ($str[strlen($str)-1] != " ")) $str = substr($str,0,-1);
		$str = substr($str,0,-1);
	}
    $str = $str." ...";
    return $str;
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


function get_user_icon($type,$memid){
    global $lang;
    $sex = get_config('sex','member','id',$memid);
    $img = $sex=='1'?"<img src='images/icon/boy.jpg' border='0'>":"<img src='images/icon/girl.jpg' border='0'>";
    $title = $sex=='1'?" title='".$lang['lang.post_by']." ".$lang['lang.boy_love']."'":" title='".$lang['lang.post_by']." ".$lang['lang.girl_love']."'";
        return "<a href='javascript: void(0);' onclick=\"viewpages('main','".$type."=list&type=member&memid=".$memid."');\" ".$title.">".$img."</a>";
}



function bbcode(){
global $mysql,$tb,$lang,$tpl;
$bbcode = $tpl->get_tem_p('default','bbcode');
$h = $tpl->get_block($bbcode,'full',1);
$list = $tpl->replace_tem($h,array(
    'skin.link' => $_SESSION['template'],
    )
);
$bbcode = $tpl->replace_block($bbcode,array(
    'html'  => $list,
    )
);
$bbcode = $tpl->replace_tem($bbcode,$lang);
return $bbcode;
}

function bbcode_mini(){
global $mysql,$tb,$lang,$tpl;
$bbcode = $tpl->get_tem_p('default','bbcode');
$h = $tpl->get_block($bbcode,'mini',1);
$list = $tpl->replace_tem($h,array(
    'skin.link' => $_SESSION['template'],
    )
);
$bbcode = $tpl->replace_block($bbcode,array(
    'html'  => $list,
    )
);
$bbcode = $tpl->replace_tem($bbcode,$lang);
return $bbcode;
}

function un_bbcode($str){
global $web_domain,$emotion;
    $str = !substr_count($str,'[music=400,50]http://')?str_replace('[music=400,50]','[music=400,50]'.$web_domain,$str):$str;
    $bbcode = new bbcode($str);
    $content = $bbcode->content;
    $content = un_smile($content);

return $content;
}

function un_smile($s) {
	global $emotion;
    $emotions = $emotion;
	foreach ($emotions as $a => $b) {
		$x = array();
		if (is_array($b)) {
			for ($i=0;$i<count($b);$i++) {
				$b[$i] = un_html($b[$i]);
				$x[] = $b[$i];
				$v = strtolower($b[$i]);
				if ($v != $b[$i]) $x[] = $v;
			}
		}
		else {
			$b = un_html($b);
			$x[] = $b;
			$v = strtolower($b);
			if ($v != $b) $x[] = $v;
		}
		$p = '';
		for ($u=0;$u<strlen($x[0]);$u++) {
			$ord = ord($x[0][$u]);
			if ($ord < 65 && $ord > 90) $p .= '&#'.$ord.';';
			else $p .= $x[0][$u];
		}
		$s = str_replace($x,'<img title=\''.$p.'\' src=images/smile/'.$a.'.gif>',$s);
	}
	return $s;
}



function get_month($month){
    global $lang;
    switch($month){
        case "1" : $monthname = $lang['t1']; break;
        case "2" : $monthname = $lang['t2']; break;
        case "3" : $monthname = $lang['t3']; break;
        case "4" : $monthname = $lang['t4']; break;
        case "5" : $monthname = $lang['t5']; break;
        case "6" : $monthname = $lang['t6']; break;
        case "7" : $monthname = $lang['t7']; break;
        case "8" : $monthname = $lang['t8']; break;
        case "9" : $monthname = $lang['t9']; break;
        case "10" : $monthname = $lang['t10']; break;
        case "11" : $monthname = $lang['t11']; break;
        case "12" : $monthname = $lang['t12']; break;
    }
return $monthname;
}

function mini_full_date($date,$time){
global $lang;
    $month = substr($date,4,2);
    $date1 = substr($date,6,2);
    $year = substr($date,0,4);
    $t = $time?" | ".$lang['lang.at']." ".$time:"";
    $str = $lang['lang.date']." ".$date1." ".$lang['lang.month']." ".$month." ".$lang['lang.year']." ".$year.$t;
return $str;
}

function _next($type,$title,$id,$more=''){
    // type : ten bang
    // title : cot ten
    // id : id hien tai cua bai viet
    // more : dieu kien mo rong
    global $mysql,$table_prefix,$lang;
    $total = $mysql->num_rows($mysql->query('select * from '.$table_prefix.$type.' where id>'.$id.' '.$more.''));
    if(!$total){
        $code = '...';
    }else{
      $q = $mysql->query('select * from '.$table_prefix.$type.' where id>'.$id.' '.$more.' order by id ASC limit 0,1');
      while($r=$mysql->fetch_array($q)){
          $code = "<a href='javascript: void(0);' onclick=\"viewpages('main','".$type."=view&id=".$r['id']."');web_title('".$type." - ".$r['title']."');\" title='".$r[$title]."'>".cut_str($r[$title],25)."</a>";
      }
    }
return $code;
}

function _prev($type,$title,$id,$more=''){
        global $mysql,$table_prefix,$lang;
    $total = $mysql->num_rows($mysql->query('select * from '.$table_prefix.$type.' where id<'.$id.' '.$more.''));
    if(!$total){
        $code = '...';
    }else{
      $q = $mysql->query('select * from '.$table_prefix.$type.' where id<'.$id.' '.$more.' order by id DESC limit 0,1');
      while($r=$mysql->fetch_array($q)){
          $code = "<a href='javascript: void(0);' onclick=\"viewpages('main','".$type."=view&id=".$r['id']."');web_title('".$type." - ".$r['title']."');\" title='".$r[$title]."'>".cut_str($r[$title],25)."</a>";
      }
    }
return $code;
}



function timezones($a){
global $web_timezones;
// a = 0 : return yearmonthday
// a = 1 : return time
// select timezone from database
      // M?ng mô t? các timezone dã du?c tính toán .
    $pc_timezones = array(
  'GMT'  =>  0,          // Greenwich Mean
  'WAT'  =>  -1*3600,      // West Africa
  'AT'  =>  -2*3600,      // Azores
  'NFT'  =>  -3*3600-1800, // Newfoundland
  'BBAG'  =>  -3*3600,       // Newfoundland
  'AST'  =>  -4*3600,      // Atlantic Standard
  'EST'  =>  -5*3600,      // Eastern Standard
  'CST'  =>  -6*3600,      // Central Standard
  'MST'  =>  -7*3600,      // Mountain Standard
  'PST'  =>  -8*3600,      // Pacific Standard
  'YST'  =>  -9*3600,      // Yukon Standard
  'AHST' => -10*3600,      // Alaska-Hawaii Standard
  'NT'   => -11*3600,      // Nome
  'IDLW' => -12*3600,      // International Date Line West
  'CET'  =>  +1*3600,      // Central European
  'EET'  =>  +2*3600,      // Eastern Europe, USSR Zone 1
  'BT'  =>  +3*3600,      // Baghdad, USSR Zone 2
  'IT'  =>  +3*3600+1800, // Iran + 30'
  'ZP4'  =>  +4*3600,      // USSR Zone 3
  'ZP5'  =>  +5*3600,      // USSR Zone 4
  'IST'  =>  +5*3600+1800, // Indian Standard
  'ZP6'  =>  +6*3600,      // USSR Zone 5
  'SST'  =>  +7*3600,      // South Sumatra, USSR Zone 6
  'CCT'  =>  +8*3600,      // China Coast, USSR Zone 7
  'JST'  =>  +9*3600,      // Japan Standard, USSR Zone 8
  'CAST' =>  +9*3600+1800, // Central Australian Standard
  'EAST' => +10*3600,      // Eastern Australian Standard
  'MNS'  => +11*3600,      // Guam Standard, USSR Zone 9
  'NZT'  => +12*3600,      // New Zealand
);

// L?y th?i gian hi?n t?i c?a server .
$now = time();

//Ph?n này các b?n có th? m? r?ng
// T?i dây b?n có th? s? d?ng l?y IP d? tính toán ho?c l?y timezone c?a user t? DB...
//$usertimezone =  'SST'; // gi? VN.

// tính th?i gian chính xác
$now += $pc_timezones[$web_timezones];
/*$ar = localtime($now,true);
if ($ar['tm_isdst']) {
    $now += 3600;
} */
$year = gmstrftime('%Y',$now);
$month = gmstrftime('%m',$now);
$day = gmstrftime('%d',$now);
$g = gmstrftime('%g',$now);
$p = gmstrftime('%r',$now);
//$giay = gmstrftime('%s',$now);
$time = $p;
//Xu?t ra th?i gian.
if($a==0){
return $year.$month.$day;
return $gmt;
}else{
return $time;
}
}

// thời tiết
function weather($a,$type,$link=''){
    global $lang;
    /*  $type = 'icon' : Icon nhở
        $type = 'thumb' : Thumb lớn. */
    $img = array(
        1 => 'flur',  // Nắng mưa thất thường
        2 => 'fair',  // có mây
        3 => 'mclou', // Trời râm
        4 => 'clou',  // U ám
        5 => 'rain',  // Trời mưa
        6 => 'sunny', // Trời nắng
    );
    if($type == 'name'){
        return $img[$a];
    }
    $w = $type=='icon'?'-icon':'';
    if($link=='link'){
        return "images/weather/".$img[$a].$w.".png";
    }else{
        return "<img src='images/weather/".$img[$a].$w.".png' title='".$lang[$img[$a]]."' border='0'>";
    }
}

function un_weather($type){
    $arr = array(
        'flur'  => 1,  // Nắng mưa thất thường
        'fair' => 2,  // có mây
        'mclou' => 3, // Trời râm
        'clou' => 4,  // U ám
        'rain' => 5,  // Trời mưa
        'sunny' => 6, // Trời nắng
    );
    return $arr[$type];
}

// trang thai tinh cam
function feeling($type){
    global $lang;
    return "<img src='images/feeling/".$type.".gif' border='0' title='".$lang[$type]."'>";
}


// lua chon chu de khi dang
function select_category($type,$level='',$id=''){
    // level = 1 : gom ca cac danh sach chu de con
    global $mysql,$lang,$moshtml,$table_prefix;
    if($level=='' || !$level){
        $code[] = array(0,$lang['lang.cat_cat']);
    }
    if($id){
        $s = "and id<> ".$id."";
    }else{
        $s = '';
    }
    $q = $mysql->query("select id, title from ".$table_prefix."category where cfor='".$type."' ".$s." and subid=0 order by corder ASC");
    while($r=$mysql->fetch_array($q)){
        $total_sub = $mysql->num_rows($mysql->query("select id from ".$table_prefix."category where cfor='".$type."' and subid=".$r['id'].""));
        $code[] = array($r['id'],"-&nbsp;".$r['title']);
        if($level!='' && $total_sub){
            $q2 = $mysql->query("select id, title from ".$table_prefix."category where cfor='".$type."' and subid=".$r['id']." order by corder ASC");
            while($r2=$mysql->fetch_array($q2)){
                $code[] = array($r2['id'],'&nbsp;&nbsp;|--&nbsp;'.$r2['title']);
            }
        }
    }
return $code;
}

// lua chon album
function select_album($type,$mode=''){
    global $mysql,$lang,$table_prefix;
    if($mode){
        $code[] = array('',$lang['lang.select']);
    }
    $q = $mysql->query("select id, title from ".$table_prefix."album where afor='".$type."' order by title ASC");
    while($r=$mysql->fetch_array($q)){
        $code[] = array($r['id'],$r['title']);
    }
return $code;
}

function select_singer(){
    global $mysql,$lang,$table_prefix;
    $q = $mysql->query("select id, title from ".$table_prefix."singer order by title ASC");
    while($r=$mysql->fetch_array($q)){
        $code[] = array($r['id'],$r['title']);
    }
return $code;
}



function star($rates,$rates_num){
if ($rates_num ==0) $current_star = 0;
	else $rater_rating = $rates/$rates_num;
	if ($rater_rating <= 0  ){$star1 = "none"; $star2 = "none"; $star3 = "none"; $star4 = "none"; $star5 = "none";}
	if ($rater_rating >= 0.5){$star1 = "half"; $star2 = "none"; $star3 = "none"; $star4 = "none"; $star5 = "none";}
	if ($rater_rating >= 1  ){$star1 = "full"; $star2 = "none"; $star3 = "none"; $star4 = "none"; $star5 = "none";}
	if ($rater_rating >= 1.5){$star1 = "full"; $star2 = "half"; $star3 = "none"; $star4 = "none"; $star5 = "none";}
	if ($rater_rating >= 2  ){$star1 = "full"; $star2 = "full"; $star3 = "none"; $star4 = "none"; $star5 = "none";}
	if ($rater_rating >= 2.5){$star1 = "full"; $star2 = "full"; $star3 = "half"; $star4 = "none"; $star5 = "none";}
	if ($rater_rating >= 3  ){$star1 = "full"; $star2 = "full"; $star3 = "full"; $star4 = "none"; $star5 = "none";}
	if ($rater_rating >= 3.5){$star1 = "full"; $star2 = "full"; $star3 = "full"; $star4 = "half"; $star5 = "none";}
	if ($rater_rating >= 4  ){$star1 = "full"; $star2 = "full"; $star3 = "full"; $star4 = "full"; $star5 = "none";}
	if ($rater_rating >= 4.5){$star1 = "full"; $star2 = "full"; $star3 = "full"; $star4 = "full"; $star5 = "half";}
	if ($rater_rating >= 5  ){$star1 = "full"; $star2 = "full"; $star3 = "full"; $star4 = "full"; $star5 = "full";}
	return $rater_stars_img =  "<img border=\"0\" src=\"".$_SESSION['template']."/images/star/".$star1.".png\">
						        <img border=\"0\" src=\"".$_SESSION['template']."/images/star/".$star2.".png\">
						        <img border=\"0\" src=\"".$_SESSION['template']."/images/star/".$star3.".png\">
						        <img border=\"0\" src=\"".$_SESSION['template']."/images/star/".$star4.".png\">
						        <img border=\"0\" src=\"".$_SESSION['template']."/images/star/".$star5.".png\"> <i>( ".$rates_num." )</i>";
    }



function select_timezone(){
  $pc_timezones[] = array('IDLW',"(GMT - 12:00 hours) Enitwetok,..");
  $pc_timezones[] = array('NT' ,"(GMT - 11:00 hours) Midway Island");
  $pc_timezones[] = array('AHST',"(GMT - 10:00	hours) Hawaii");
  $pc_timezones[] = array('YST',"(GMT - 9:00 hours) Alaska");
  $pc_timezones[] = array('PST',"(GMT - 8:00 hours) Pacific Time..");
  $pc_timezones[] = array('MST',"(GMT - 7:00 hours) Mountain Time..");
  $pc_timezones[] = array('CST',"(GMT - 6:00 hours) Central Time..");
  $pc_timezones[] = array('EST',"(GMT - 5:00 hours) Eastern Time..");
  $pc_timezones[] = array('AST',"(GMT - 4:00 hours) Atlantic Time..");
  $pc_timezones[] = array('NFT',"(GMT - 3:30 hours) Newfoundland");
  $pc_timezones[] = array('BBAG',"(GMT - 3:00 hours) Brazil..");
  $pc_timezones[] = array('AT',"(GMT - 2:00 hours) Mid-Atlantic..");
  $pc_timezones[] = array('WAT',"(GMT - 1:00 hours) Azores..");
  $pc_timezones[] = array('GMT',"(GMT)&nbsp;0:00 Casablanca..");
  $pc_timezones[] = array('CET',"(GMT + 1:00 hours) Berlin..");
  $pc_timezones[] = array('EET',"(GMT + 2:00 hours) Kaliningrad..");
  $pc_timezones[] = array('BT',"(GMT + 3:00 hours) Baghdad..");
  $pc_timezones[] = array('IT',"(GMT + 3:30 hours) Tehran");
  $pc_timezones[] = array('ZP4',"(GMT + 4:00 hours) Adu Dhabi..");
  $pc_timezones[] = array('ZP5',"(GMT + 5:00 hours) Ekaterinburg..");
  $pc_timezones[] = array('IST',"(GMT + 5:30 hours) Bombay..");
  $pc_timezones[] = array('ZP6',"(GMT + 6:00 hours) Almaty..");
  $pc_timezones[] = array('SST',"(GMT + 7:00 hours) Hanoi..");
  $pc_timezones[] = array('CCT',"(GMT + 8:00	hours) Beijing..");
  $pc_timezones[] = array('JST',"(GMT + 9:00 hours) Osaka..");
  $pc_timezones[] = array('CAST',"(GMT + 9:30 hours) Adelaide..");
  $pc_timezones[] = array('EAST',"(GMT + 10:00	hours) Melbourne..");
  $pc_timezones[] = array('MNS',"(GMT + 11:00 hours) Magadan..");
  $pc_timezones[] = array('NZT',"(GMT + 12:00 hours) Auckland..");
return $pc_timezones;
}



function viewpages($div,$ttrow,$n,$pg,$link_page){
    // div : DIV se load lai khi next trang
    // ttrow : Tong so
    // $n : Page_size;
    // page : Trang so
    // link_page : Duong dan gui POST
	global $tpl,$ajax;
	$total = ceil($ttrow/$n);
	if ($total <= 1) return '';
	$v_f = 3;
	$v_a = 2;
	$v_l = 3;
	$max_pages = $v_f + $v_a + $v_l + 5;
	$z_1 = $z_2 = $z_3 = false;
	$html = $tpl->get_tem('pages');
	$block = $tpl->get_block($html,'page_block');
	$t = $tpl->auto_get_block($block);
	$block = '';
	$pgt = $pg-1;
	if ($pg != 1)
		$block .= $tpl->replace_tem($t['first_previous_page'],
			array(
				'page.F_LINK'	=>	"viewpages('".$div."',1,'".$link_page."&page=1')",
				'page.P_LINK'	=>	"viewpages('".$div."','".$link_page."&page=".$pg."')",
			)
		);
	for($m = 1; $m <= $total; $m++) {
		if ($total > $max_pages) {
			if (($m > $v_f) && (($m < $pg - $v_a) || ($m > $pg + $v_a)) && ($m < $total - $v_l + 1)) {
				if (!$z_1 && ($m > $v_f)) {
					$block .= $t['space_page'];
					$z_1 = true;
				}
				elseif (!$z_2 && ($m > $pg + $v_a)) {
					$block .= $t['space_page'];
					$z_2 = true;
				}
				continue;
			}
		}
		if($m == $pg)
			$block .= $tpl->replace_tem($t['current_page'],
				array(
					'page.NUMBER'	=>	$m,
				)
			);
		else
			$block .= $tpl->replace_tem($t['page_number'],
				array(
					'page.NUMBER'	=>	$m,
					'page.LINK'	=>	"viewpages('".$div."','".$link_page."&page=".$m."')",
				)
			);
	}
	$pgs = $pg + 1;
	if ($pg != $total)
		$block .= $tpl->replace_tem($t['last_next_page'],
			array(
				'page.L_LINK'	=>	"viewpages('".$div."','".$link_page."&page=".$total."')",
				'page.N_LINK'	=>	"viewpages('".$div."','".$link_page."&page=".$pgs."')",
			)
		);
	$html = $tpl->replace_block($html,
			array(
				'page_block'	=>	$block,
			)
		);
	return $html;
}



?>