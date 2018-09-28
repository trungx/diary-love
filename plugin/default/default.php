<?php

function emotion(){
    global $mysql,$lang,$tpl,$tabe_prefix,$emotion;
    $htm = $tpl->get_tem_p('default','emotion');
    $htm = $tpl->replace_tem($htm,$lang);
    $t = $tpl->auto_get_block($htm);
    while (list($title, $url) = each($emotion)) {
        $u = un_html($url);
        $icon = "<img src='images/smile/".$title.".gif' title='".$url."' onclick=\"insert_text('".$u."',true);hideform('emot');\">";
    $list .= $tpl->replace_tem($t['emotion.list'],array(
            'img'   => $icon,
            )
    );
    }
    $main = $tpl->replace_tem($t['emotion.top'],array(
        'list'  => $list,
        )
    );
    $htm = $tpl->replace_block($htm,array(
        'html'  => $main,
        )
    );
    return $htm;
}

function block_calendar($type,$year,$month){
  global $mysql,$lang,$tpl,$table_prefix,$LINK;
    $datefull = timezones(0);               /// lay ngay va thang theo timezone
    if($year=='') {
        $year = substr($datefull,0,4);
    }
    if($month == ''){
        $month = substr($datefull,4,2);
    }
        $day =  substr($datefull,6,2);

    $year_now = substr($datefull,0,4);
    $month_now = substr($datefull,4,2);
    $date_now = substr($datefull,6,2);

    $currentTimeStamp = strtotime("$year-$month-$day");
    $monthName = date("F", $currentTimeStamp);
    $numDays = date("t", $currentTimeStamp);
    $counter = 1;
    // xuat giao dien
    $htm = $tpl->get_tem_p('default','block.calendar');
    $htm = $tpl->replace_tem($htm,$lang);
    $h = $tpl->get_block($htm,'cal_top',1);
    $h2 = $tpl->get_block($htm,'cal_list',1);
    $hcal   = $tpl->get_block($htm,'calendar',1);
     switch($month){
        case "1" : $monthname = $lang['t1']; $top_class = 'spring'; break;
        case "2" : $monthname = $lang['t2']; $top_class = 'spring'; break;
        case "3" : $monthname = $lang['t3']; $top_class = 'spring'; break;
        case "4" : $monthname = $lang['t4']; $top_class = 'summer'; break;
        case "5" : $monthname = $lang['t5']; $top_class = 'summer'; break;
        case "6" : $monthname = $lang['t6']; $top_class = 'summer'; break;
        case "7" : $monthname = $lang['t7']; $top_class = 'fall'; break;
        case "8" : $monthname = $lang['t8']; $top_class = 'fall'; break;
        case "9" : $monthname = $lang['t9']; $top_class = 'fall'; break;
        case "10" : $monthname = $lang['t10']; $top_class = 'winter'; break;
        case "11" : $monthname = $lang['t11']; $top_class = 'winter'; break;
        case "12" : $monthname = $lang['t12']; $top_class = 'winter'; break;
    }
    $a = $_SESSION['template'];

    $top = $tpl->replace_tem($h,array(
            'class' => $top_class,
            'year' => $year,
            'year.url'  => "<a href='javascript: void(0);' onclick=\"viewpages('main','".$type."=list&type=archive&year=".$year."')\" title=\"".$lang['lang.view_in']." ".$lang['lang.year']." ".$year."\">",
            'monthname' => $monthname,
            'month.url' => "<a href='javascript: void(0);' onclick=\"viewpages('main','".$type."=list&type=archive&year=".$year."&month=".$month."')\" title='".$lang['lang.view_in']."&nbsp;".$lang['lang.month']."&nbsp;".$month."&nbsp;".$lang['lang.year']."&nbsp;".$year."'>",
            'month' => $month,
            'nextmonth'=>$year.$month+1,
            'premonth'=>$year.$month-1,
            'skin.link' =>$a,
            'type'  => $type,
            )
    );
    // phan list lich
        for($i = 1; $i < $numDays+1; $i++, $counter++)
    {
        $timeStamp = strtotime("$year-$month-$i");
        if($i == 1)
        {
        // tinh ngay dau tien cua thang
        $firstDay = date("w", $timeStamp);

        for($j = 0; $j < $firstDay; $j++, $counter++)
            $td .= "<td>&nbsp;</td>";
        }else
          $td = "";


        if($counter % 7 == 0)
            $tr = "</tr><tr>";
        else{
          $tr ="";
        }
        $icheck = $i<10?'0'.$i:$i;
        $check = $mysql->num_rows($mysql->query("select id from ".$table_prefix.$type." where draft=0 and year='".$year."' and month = '".$month."' and day = '".$icheck."'"));
        if($check){
                $url = "<a href='javascript: void(0);' onclick=\"viewpages('main','".$type."=list&type=archive&year=".$year."&month=".$month."&day=".$i."');\" title=\"".$lang['lang.view_in']."&nbsp;".$lang['lang.date']."&nbsp;".$i."&nbsp;".$lang['lang.month']."&nbsp;".$month."&nbsp;".$lang['lang.year']."&nbsp;".$year."\">";
                $eurl = "</a>";
                $class1 = "yes";
            }else{
                $url =$r['topic_day'];
                $eurl ="";
                $class1 = "no";
            }

        if($i == $date_now && $month == $month_now && $year == $year_now)
            $class = "class='today'";
        elseif(date("w", $timeStamp) == 0)
            $class = "class='weekend' id='weekend'";
        elseif($class1 == 'yes'){
            $class = "class='intopic'";
        }else
            $class = "class='normal'";



        $list .= $tpl->replace_tem($h2,array(
            'day'   => $i,
            'url'   => $url,
            'eurl'  => $eurl,
            'class' => $class,
            'td'    => $td,
            'tr'    => $tr,
            )
        );
    }

    $calendar = $tpl->replace_tem($hcal,array(
        'top'   => $top,
        'list'  => $list,
        )
    );
    $htm = $tpl->replace_block($htm,array(
        'list'  => $calendar,
        )
    );
    return $htm;
}


function block_about(){
  global $module,$mysql,$lang,$tpl,$table_prefix,$web_about,$web_about_array,$web_avatar,$web_about_f,$tpl_info;
  $htm = $tpl->get_tem_p('default','block.about');
  $t = $tpl->auto_get_block($htm);
  if($web_about==1){
        $main = $tpl->replace_tem($t['default'],array(
            'img'   => $web_avatar,
            'name'  => listname('love','<br>'),
            'width' => $tpl_info['block'],
            )
        );
  }elseif($web_about==2){
        $image_array = explode('||', $web_about_array);
        $width = $tpl_info['block'];
        for($i=0;$i<=count($image_array);$i++){
            $img_more .= "<span><a href=\"javascript:void(0);\"  onclick=\"change_about_img('".$image_array[$i]."',".$width.")\"><img src=\"".$image_array[$i]."\" width=\"".@($width/3)."\" height=\"".@($width/3)."\" border='0'></a></span>&nbsp;";
        }
        $main = $tpl->replace_tem($t['360'],array(
            'img'   => $image_array[0],
            'img.more'  => $img_more,
            'name'  => listname('love','<br>'),
            'width' => $tpl_info['block'],
            )
        );
  }elseif($web_about==3){
        $xml = $module.'gallery.php?album_xml='.$web_about_f;
        $main = $tpl->replace_tem($t['flash'],array(
            'xml.link'   => $xml,
            'width' => $tpl_info['block'],
            'name'  => listname('love','<br>'),
            'height'    => $tpl_info['block'],
            )
        );
  }
  $htm = $tpl->replace_block($htm,array(
    'html'  => $main,
    )
  );
return $htm;
}

function block_new($type,$sex='',$tab='no'){
    global $mysql,$lang,$tpl,$table_prefix;
    $htm = $tpl->get_tem_p('default','block.list');
    $t = $tpl->auto_get_block($htm);
    if($sex!=''){
        $userid = get_config('id','member','sex',$sex);
        $select = ' where userid='.$userid.'';
    }else{
        $select = '';
    }
    if($type=='gallery'){
        $q = $mysql->query('select * from '.$table_prefix.$type.' '.$select.' order by id DESC limit 0,9');
        while($r=$mysql->fetch_array($q)){
            $list .= $tpl->replace_tem($t['block.new.gallery'],array(
                'title' => $r['title'],
                'name'  => cut_str($r['title'],45),
                'url'   => 'viewpages(\'main\',\''.$type.'=view&id='.$r['id'].'\');web_title(\''.$type.' - '.$r['title'].'\');',
                'thumb' => $r['thumb'],
                )
            );
        }
    }
    // diary
    else{
      $q = $type=='diary'?$mysql->query('select title, id, userid, year, month, day from '.$table_prefix.$type.' '.$select.' order by id DESC limit 0,10'):$mysql->query('select title, id, userid from '.$table_prefix.$type.' '.$select.' order by id DESC limit 0,10');
      while($r=$mysql->fetch_array($q)){
        $url = $type=='diary'?'viewpages(\'main\',\''.$type.'=view&id='.$r['id'].'\');web_title(\''.$type.' - '.$r['title'].'\');':"play('music=play&id=".$r['id']."','music=song_info&id=".$r['id']."');";
        $list .= $tpl->replace_tem($t['block.new'],array(
              'title' => $r['title'],
              'name'  => cut_str($r['title'],55),
              'url'   =>       $url,
              )
          );
      }
    }
    if($tab=='yes'){
        $list = $tpl->replace_tem($t['block.new.tab'],array(
            'boy'   => block_new($type,1),
            'girl'  => block_new($type,2),
            'type'  => $type,
            )
        );
    }
    $main = $tpl->replace_block($htm,array(
        'html'  => $list,
        )
    );
return $main;
}

function block_singer(){
    global $mysql,$lang,$tpl,$table_prefix;
    $htm = $tpl->get_tem_p('default','block.list');
    $htm = $tpl->replace_tem($htm,$lang);
    $t = $tpl->auto_get_block($htm);
    $q = $mysql->query("select id, title from ".$table_prefix."singer order by title ASC");
    while($r=$mysql->fetch_array($q)){
        $list .= "&nbsp;<a href='javascript: void(0);' title='".$r['title']."' onclick=\"viewpages('main','music=list&type=singer&singerid=".$r['id']."');web_title('Music - Singer: ".$r['title']."');\">".cut_str($r['title'],30)."</a><br>";
    }
    $main = $tpl->replace_tem($t['singer.list'],array(
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


function block_category($type,$sex='',$submode=''){
    global $mysql,$lang,$tpl,$table_prefix;
    $table = $type=='gallery'?'album':$type;
    $htm = $tpl->get_tem_p('default','block.category');
    $htm = $tpl->replace_tem($htm,$lang);
    $t = $tpl->auto_get_block($htm);
    if($sex!=''){
        $userid = get_config('id','member','sex',$sex);
        $select = 'and userid = '.$userid.' ';
    }else{
        $select = '';
    }

    if($submode){
        $total_sub = $mysql->num_rows($mysql->query("select id from ".$table_prefix."category where cfor = '".$type."' ".$select." and subid = '".$submode."' order by corder ASC"));
        if($total_sub){
            $q2 = $mysql->query("select id, title, thumb from ".$table_prefix."category where cfor = '".$type."' ".$select." and subid = '".$submode."' order by corder ASC");
            while($r2 = $mysql->fetch_array($q2)){
                $total = $mysql->num_rows($mysql->query('select id from '.$table_prefix.$table.' where catid='.$r2['id'].''));
                $cat .= $tpl->replace_tem($t['sub'],array(
                    'id'    => $r2['id'],
                    'title' => $r2['title'],
                    'url'   => "viewpages('main','".$type."=category&catid=".$r2['id']."')",
                    'total' => $total,
                    )
                );
            }
        }else{
            $cat = '';
        }
    }else{
      $q1 = $mysql->query('select id, title, thumb from '.$table_prefix.'category where subid=0 and cfor="'.$type.'" '.$select.' order by corder ASC');
      while($r1=$mysql->fetch_array($q1)){
      // tong so bai viet
      $arr = $r1['id'].',';
      $q3= $mysql->query('select id from '.$table_prefix.'category where subid='.$r1['id'].'');
      while($r3=$mysql->fetch_array($q3)){
        $arr .= $r3['id'].',';
      }
      $arr = substr($arr,0,-1);
      $total = $mysql->num_rows($mysql->query('select id from '.$table_prefix.$table.' where catid IN ('.$arr.')'));
          $cat.= $tpl->replace_tem($t['cat'],array(
              'id'    => $r1['id'],
              'title' => $r1['title'],
              'url'   => "viewpages('main','".$type."=category&catid=".$r1['id']."')",
              'sub.list'  => block_category($type,$sex,$r1['id']),
              'total'   => $total,
              )
          );
      }
    }
    $htm = $tpl->replace_block($htm,array(
        'html'  => $cat,
        )
    );
return $htm;
}


?>