<?

######################## TEMPLATE ENGINE CLASS EDIT FROM CODE XTRE #################################
class template {
	var $ext = ".tpl.html";
	var $cache_tpl = array();
	function get_tem($filename,$blockname = '',$c = false) {
		$full_link = $_SESSION['template']."/tpl/".$filename.$this->ext;
		if (!file_exists($full_link)) {
        global $lang,$template_name,$admin_mail;
        die("".$lang[1]." : <b>".$full_link."</b><br><a href='?skin=0'>".$lang[44]."</a><br><a href='?skin=-1'>".$lang[2]."</a>");
		}
		if ($this->cache_tpl['file_'.$filename]) $file_content = $this->cache_tpl['file_'.$filename];
		else {
			$this->cache_tpl['file_'.$filename] = $file_content = file_get_contents($full_link);
		}
		return $file_content;
	}

    ########## XUAT GIAO DIEN CHO MODULE ########################
    # Uu tien tim kiem trong goi giao dien hien hanh neu khong  #
    # tim thay file cho modules se tu dong load template mac    #
    # dinh cua module ben trong thu muc chua module do /tpl     #
    #############################################################

    function get_tem_m($m,$filename,$blockname='',$c=false){
        $link = $_SESSION['template']."/tpl/".$m.'/'.$filename.$this->ext;
        // kiem tra su ton tai cua file giao dien
        if(!file_exists($link)){
            global $lang,$module;
            $link = $module.$m."/tpl/".$filename.$this->ext;
            if(!file_exists($link)){
                die("Không tìm thấy file giao diện ".$link);
            }
            //  load giao dien mac dinh cua module
        }
        if ($this->cache_tpl['file_'.$filename]) $file_content = $this->cache_tpl['file_'.$filename];
		else {
			$this->cache_tpl['file_'.$filename] = $file_content = file_get_contents($link);
		}
		return $file_content;
    }

    // xuat template cho plugin
        function get_tem_p($m,$filename,$blockname='',$c=false){
        $link = $_SESSION['template']."/tpl/".$m.'/'.$filename.$this->ext;
        // kiem tra su ton tai cua file giao dien
        if(!file_exists($link)){
            global $lang,$plugin;
            $link = $plugin.$m."/tpl/".$filename.$this->ext;
            if(!file_exists($link)){
                die("Không tìm thấy file giao diện ".$link);
            }
            //  load giao dien mac dinh cua plugin
        }
        if ($this->cache_tpl['file_'.$filename]) $file_content = $this->cache_tpl['file_'.$filename];
		else {
			$this->cache_tpl['file_'.$filename] = $file_content = file_get_contents($link);
		}
		return $file_content;
    }


	function get_block($str,$block = '',$c = false) {

		if (!$this->cache_tpl['block_'.$block]) {
			preg_replace('#<!-- '.(($c)?'\#':'').'BEGIN '.$block.' -->[\r\n]*(.*?)[\r\n]*<!-- '.(($c)?'\#':'').'END '.$block.' -->#se','$s = stripslashes("\1");',$str);
			if ($s != $str)	$str = $s;
			else $str = '';
			$this->cache_tpl['block_'.$block] = $str;
		}
		return $this->cache_tpl['block_'.$block];
	}
	function replace_tem($code,$arr) {
		foreach ($arr as $block => $val) {
				$code = str_replace('{'.$block.'}',$val,$code);
		}
		return $code;
	}
	function replace_block($code,$arr) {
		foreach ($arr as $block => $val) {
			$code = preg_replace('#<!-- BEGIN '.$block.' -->[\r\n]*(.*?)[\r\n]*<!-- END '.$block.' -->#s', $val, $code);
		}
		return $code;
	}
	function auto_get_block($str) {
		preg_match_all('#<!-- \#BEGIN (.*?) -->[\r\n]*(.*?)[\r\n]*<!-- \#END (.*?) -->#s', $str, $arr, PREG_PATTERN_ORDER);
		$a = array();
		for ($i=0; $i<count($arr[0]); $i++) {
			$a[$arr[1][$i]] = $arr[0][$i];
		}
		return $a;
	}
    function unset_block($code,$arr,$c = false) {
		foreach ($arr as $block) {
			$code = preg_replace('#<!-- '.(($c)?'\#':'').'BEGIN '.$block.' -->[\r\n]*(.*?)[\r\n]*<!-- '.(($c)?'\#':'').'END '.$block.' -->#s', '', $code);
		}
		return $code;
	}
    	function assign_vars($code,$arr) {
		foreach ($arr as $block => $val) {
				$code = str_replace('{'.$block.'}',$val,$code);
		}
		return $code;
	}
	function eval_main($func,$exp = '') {
		$exp = trim(stripslashes($exp));
		if ($exp) $code = eval("return ".$func."(".$exp.");");
	  	else $code = eval("return ".$func."();");
		return $code;
	}
	function show($code) {
		global $web_name,$web_title,$skin_link,$web_domain,$LINK,$ajax,$web_wait_post,$web_comment,$module,$web_key,$web_warring_ok,$web_warring_message;

        if($web_warring_ok=='yes'){
            $web_show_warring = 'block';
            $web_warring_message = $web_warring_message;
        }else{
            $web_show_warring = 'none';
            $web_warring_message = '';
        }
        $html = "/index.html";
		$code = preg_replace('#<!-- MAIN (.*?)\((.*?)\) -->#se', '$this->eval_main("\\1","\\2");', $code);
		$code = preg_replace('#<!-- BEGIN (.*?) -->[\r\n]*(.*?)[\r\n]*<!-- END (.*?) -->#s', '\\2', $code);
		$code = str_replace('{web.title}', $web_title, $code);
		$code = str_replace('{show.warring}', $web_show_warring, $code);
		$code = str_replace('{warring}', $web_warring_message, $code);
		$code = str_replace('{web.name}', $web_name, $code);
		$code = str_replace('{web.comment}', $web_comment, $code);
		$code = str_replace('{web.link}', $web_domain, $code);
		$code = str_replace('{web.key}', $web_key, $code);
		$code = str_replace('{skin.link}', $_SESSION['template'], $code);
		$code = str_replace('{link}', $LINK, $code);
		$code = str_replace('{html}', $html, $code);
		$code = str_replace('{ajax}', $ajax, $code);
		$code = str_replace('{modules.dir}', $module, $code);
		$code = str_replace('{time_post}', $web_wait_post, $code);
		$code = str_replace('{back.page}', $LINK.$_SESSION['current_page'], $code);
		echo $code;
	}
}

?>