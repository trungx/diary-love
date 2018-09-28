<?php
/*  code lấy link nhạc các trang nhạc.
    Các trang hỗ trợ:
        + http://www.nhaccuatui.com
*/
    $link = $_GET['url'];
    return nhaccuatui($link);


function nhaccuatui($link){
   $string = $link;
   $string = str_replace('.mp3','',$string);
   $string = str_replace('http://www.nhaccuatui.com/nghe?M=','',$string);
   $string = str_replace('.flv','',$string);
    $url = "http://www.nhaccuatui.com/m2/".$string;
    $a = get_headers($url, 1);
    $b = $a['Location'];
    $b = str_replace('http://static.nhaccuatui.com/Flash/NCTplayer33.swf?file=','',$b);
    $c = get_url($b);
    $d = explode('</location>',$c);
    $n = count($d);
    $urlsong = stristr($d[2],"<location>");
    $urlsong = str_replace("<location>","",$urlsong);
    return header("Location: ".$urlsong);
 }

function get_url($url) {
	$url_parsed = parse_url($url);
	$host = $url_parsed["host"];
	$port = 0;
	$in = '';
	if (!empty($url_parsed["port"])) {
  	$port = $url_parsed["port"];
	}
	if ($port==0) {
		$port = 80;
	}
	$path = $url_parsed["path"];
	if ($url_parsed["query"] != "") {
		$path .= "?".$url_parsed["query"];
	}
	$out = "GET $path HTTP/1.0\r\nHost: $host\r\n\r\n";
	$fp = fsockopen($host, $port, $errno, $errstr, 30);
	fwrite($fp, $out);
	$body = false;
	while (!feof($fp)) {
		$s = fgets($fp, 1024);
		if ( $body ) {
			$in .= $s;
		}
		if ( $s == "\r\n" ) {
			$body = true;
		}
	}
	fclose($fp);
	return $in;
}






?>
