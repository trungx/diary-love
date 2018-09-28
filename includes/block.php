<?php
/* Load các khối trên site */
function block($mode){
    global $mysql,$lang,$tpl,$table_prefix;
   // $q_block =  $mysql->query("select * from ".$table_prefix."block where active='yes' order by border ASC");
    if($mode=='all'){
        $q_block =  $mysql->query("select * from ".$table_prefix."block where active='yes' and bfor='all' order by border ASC");
    }else{
        $q_block =  $mysql->query("select * from ".$table_prefix."block where active='yes' and (bfor='all' or bfor='".$mode."') order by border ASC");
    }
    while($r=$mysql->fetch_array($q_block)){
        // $r['mode'] = 0 : default
        // $r['mode'] = 1 : plugin
        // $r['mode'] = 2 : html
       // if(filter($mode,$r['bfor'].',')){
            $code .= block_out($r['id'],$r['showname'],$r['mode'],$r['function']);
      //  }else{
     //       $code = '';
       // }
    }
return $code;
}

/* Xuất các khối trên site */
function block_out($id,$title,$mode,$function){
    global $mysql,$lang,$tpl,$table_prefix;
    $htm = $tpl->get_tem('block');
    $htm = $tpl->replace_tem($htm,$lang);
    $t = $tpl->auto_get_block($htm);
    if($mode == 0){
        $code = block_default($function);
    }elseif($mode == 1){   // plugin install
        $code = block_install($function);
    }else{                 // html
        $code = $function;
    }
    $list = $tpl->replace_tem($t['block.list'],array(
        'name'  => $title,
        'code'  => $code,
        'divid' => 'block_div_'.$id,
        )
    );
    $list = $tpl->replace_tem($list,$lang);
    $htm = $tpl->replace_block($htm,array(
        'html'  => $list,
        )
    );
return $htm;
}



function block_default($function){
            $default = array(

                1   => block_about(),               'calendar'  => block_calendar(''),

                // block mac dinh cho diary
                2   => block_calendar('diary'),        // 3   => block_archive('diary'),
                4   => block_new('diary','','yes'),   5   => block_category('diary'),

                // block mac dinh cho gallery
                6   => block_calendar('gallery'),     7   => block_new('gallery','','yes'),
                8   => block_category('gallery'),

                // block mac dinh cho music
                11  => block_new('music','','yes'),     12 => block_category('music'),
                13  => block_singer(),
                );

        while (list($k,$v) = each($default)) {
            switch($function){
					case $k:{
                        $code = $v;
                    } break;
		}
    }
return $code;
}

function block_install($id){
  global $plugin,$tpl,$lang,$table_prefix,$LINK,$mysql,$module;
  $plugin_link = $plugin.get_config('link','plugin','id',$id)."/";
  include($plugin.get_config('link','plugin','id',$id)."/".get_config('indexurl','plugin','id',$id));
return $show;
}








?>