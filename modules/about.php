<?php
$htm = $tpl->get_tem('about');
$htm = $tpl->replace_tem($htm,$lang);
$main = $tpl->replace_tem($tpl->get_block($htm,'home',1),array(
    'skin.link' => $_SESSION['template'],
    'avatar'    => $web_avatar,
    'boy'       => about_info('boy'),
    'girl'       => about_info('girl'),
    )
);
$htm = $tpl->replace_block($htm,array(
    'html'  => $main,
    )
);
$tpl->show($htm);

function about_info($type){
    global $mysql,$lang,$tpl,$table_prefix;
    $htm = $tpl->get_tem('about');
    $htm = $tpl->replace_tem($htm,$lang);
    $t = $tpl->auto_get_block($htm);
    $s = $type=='boy'?'sex=1':'sex=2';
    $q = $mysql->query("select * from ".$table_prefix."member where ".$s."");
    while($r = $mysql->fetch_array($q)){
        $list = $tpl->replace_tem($t['about.info'],array(
            'name'  => $r['fullname'],
            'yahoo' => "<a href=\"ymsgr:sendim?".$r['yahoo']."\"><img src=\"http://opi.yahoo.com/online?u=".$r['yahoo']."t&m=g&t=1&l=us\" align=\"absmiddle\" border=\"0\" vspace=\"5\"></a>",
            'brithday'  => $r['brithday'],
            'avatar'    => member_gavatar($r['id']),
            )
        );
    }
    $main = $tpl->replace_tem($t['about.info.top'],array(
        'list'  => $list,
        'skin.link' => $_SESSION['template'],
        )
    );
    $htm = $tpl->replace_block($htm,array(
        'html'  => $list,
        )
    );
return $htm;
}



?>