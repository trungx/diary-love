<?php

class bbcode
{
	var $content;
	var $content_original;
	function bbcode($content)
	{
        global $module,$lang;
		$this->content = $this->content_original = stripslashes($content);

		$this->content = htmlspecialchars($content);

// font
    $this->content = preg_replace('#\[i\](.*?)\[\/i\]#is','<i>\\1</i>',$this->content);
	$this->content = preg_replace('#\[b\](.*?)\[\/b\]#is','<b>\\1</b>',$this->content);
    $this->content = preg_replace('#\[u\](.*?)\[\/u\]#is','<u>\\1</u>',$this->content);
    $this->content = preg_replace('#\[s\](.*?)\[\/s\]#is','<s>\\1</s>',$this->content);
    $this->content = preg_replace('#\[font=(.*?)](.*?)[\/font]#is','<font face="\\1">\\2</font>',$this->content);
    $this->content = preg_replace('#\[size=(.*?)\](.*?)\[/size\]#is','<font size="\\1">\\2</font>',$this->content);
    $this->content = preg_replace('#\[color=(.*?)\](.*?)\[/color\]#is','<font color="\\1">\\2</font>',$this->content);
    $this->content = preg_replace('#\[hl=(.*?)\](.*?)\[/hl\]#is','<span style="background-color: \\1">\\2</span>',$this->content);


// smile
    $this->content = preg_replace('#\[emot](.*?)\[\/emot\]#is','<img src="images/smiles/full/\\1.gif" border=0>',$this->content);

// for admin show
    $this->content = preg_replace('#\[emot..](.*?)\[\/emot\]#is','<img src="../images/smiles/full/\\1.gif" border=0>',$this->content);

// smile for comment
    $this->content = preg_replace('#\[emots](.*?)\[\/emots\]#is','<img src="images/smiles/mini/\\1.gif" border=0>',$this->content);
    $this->content = preg_replace('#\[emots..](.*?)\[\/emots\]#is','<img src="../images/smiles/mini/\\1.gif" border=0>',$this->content);

// left right center
    $this->content = preg_replace('#\[left\](.*?)\[\/left\]#is','<div style="text-align: left">\\1</div>',$this->content);
	$this->content = preg_replace('#\[right\](.*?)\[\/right\]#is','<div style="text-align: right">\\1</div>',$this->content);
	$this->content = preg_replace('#\[center\](.*?)\[\/center\]#is','<div style="text-align: center">\\1</div>',$this->content);
// reflect images
    include('site.config.php');
    if($web_reflect=='yes'){
        $height = $web_reflect_height!=''?"&height=".$web_reflect_height:"";
        $fadestart = $web_reflect_fadestart !=''?"&fade_start=".$web_reflect_fadestart:"";
        $fadeend = $web_reflect_fadeend !=''?"&fade_end=".$web_reflect_end:"";
        $tint = $web_reflect_color !=''?"&tint=".$web_reflect_color:"";
        $reflect = "<br><img src=\"includes/reflect.php?img=../\\1".$height.$fadestart.$fadeend.$tint."\" border=\"0\" onload=\"HSImageResizer.createOn(this);\" />";
    }else{
        $reflect ='';
    }
// images
     if($_SESSION['watermark']=='yes'){
    // from http://
        if(substr_count($this->content,'[img]http://') || substr_count($this->content,'[img..]http://') || substr_count($this->content,'[img align=l]http://') || substr_count($this->content,'[img align=r]http://') || substr_count($this->content,'.gif[/img]')){
            $this->content = preg_replace('#\[img\](.*?)\[\/img\]#is','<a href="\\1" class="highslide" id="'.time().'" onclick="return hs.expand(this)"><img src="\\1" border="0" onload="HSImageResizer.createOn(this);" /></a>',$this->content);
            $this->content = preg_replace('#\[img align=l\](.*?)\[\/img\]#is','<a href="\\1" class="highslide" id="'.time().'" onclick="return hs.expand(this)"><img src="\\1" border="0" onload="HSImageResizer.createOn(this);" align="left" /></a>',$this->content);
            $this->content = preg_replace('#\[img align=l w=(.*?)\](.*?)\[\/img\]#is','<a href="\\2" class="highslide" id="'.time().'" onclick="return hs.expand(this)"><img src="\\2" width=\\1 border="0" onload="HSImageResizer.createOn(this);" align="left" /></a>',$this->content);
            $this->content = preg_replace('#\[img align=r\](.*?)\[\/img\]#is','<a href="\\1" class="highslide" id="'.time().'" onclick="return hs.expand(this)"><img src="\\1" border="0" onload="HSImageResizer.createOn(this);" align="right" /></a>',$this->content);
            $this->content = preg_replace('#\[img align=r w=(.*?)\](.*?)\[\/img\]#is','<a href="\\2" class="highslide" id="'.time().'" onclick="return hs.expand(this)"><img src="\\2" width=\\1 border="0" onload="HSImageResizer.createOn(this);" align="right" /></a>',$this->content);
         }else{
            $this->content = preg_replace('#\[img\](.*?)\[\/img\]#is','<a href="?watermark=1&id=\\1" class="highslide" id="'.time().'" onclick="return hs.expand(this)"><img src="?watermark=1&id=\\1" border="0" onload="HSImageResizer.createOn(this);" />'.$reflect.'</a>',$this->content);
            $this->content = preg_replace('#\[img align=l\](.*?)\[\/img\]#is','<a href="?watermark=1&id=\\1" class="highslide" id="'.time().'" onclick="return hs.expand(this)"><img src="?watermark=1&id=\\1" border="0" onload="HSImageResizer.createOn(this);" align="left" />'.$reflect.'</a>',$this->content);
            $this->content = preg_replace('#\[img align=l w=(.*?)\](.*?)\[\/img\]#is','<a href="?watermark=1&id=\\2" class="highslide" id="'.time().'" onclick="return hs.expand(this)"><img src="?watermark=1&id=\\2" width=\\1 border="0" onload="HSImageResizer.createOn(this);" align="left" /></a>',$this->content);
            $this->content = preg_replace('#\[img align=r\](.*?)\[\/img\]#is','<a href="?watermark=1&id=\\1" class="highslide" id="'.time().'" onclick="return hs.expand(this)"><img src="?watermark=1&id=\\1" border="0" onload="HSImageResizer.createOn(this);" align="right" />'.$reflect.'</a>',$this->content);
            $this->content = preg_replace('#\[img align=r w=(.*?)\](.*?)\[\/img\]#is','<a href="\\2" class="highslide" id="'.time().'" onclick="return hs.expand(this)"><img src="\\2" width=\\1 border="0" onload="HSImageResizer.createOn(this);" align="right" /></a>',$this->content);
         //  $this->content = preg_replace('#\[img align=r w=(.*?)\](.*?)\[\/img\]#is','<a href="?watermark=1&id=\\2" class="highslide" id="'.time().'" onclick="return hs.expand(this)"><img src="?watermark=1&id=\\2" width=\\1 border="0" onload="HSImageResizer.createOn(this);" align="right" /></a>',$this->content);
        }
    }else{
        $this->content = preg_replace('#\[img\](.*?)\[\/img\]#is','<a href="\\1" class="highslide" id="'.time().'" onclick="return hs.expand(this)"><img src="\\1" border="0" onload="HSImageResizer.createOn(this);" /></a>',$this->content);
    }

// file link
    $this->content = preg_replace('#\[file=(.*?)](.*?)\[\/file]#is','<a href="\\1" target="_blank">\\2</a>',$this->content);
// file link for member
    if($_SESSION['login']=='yes'){
    $this->content = preg_replace('#\[mfile=(.*?)](.*?)\[\/mfile]#is','<a href="\\1" target="_blank"><b>\\2</b></a>',$this->content);
    }else{
    $this->content = preg_replace('#\[mfile=(.*?)](.*?)\[\/mfile]#is','<b><font color="red">'.$lang[165].'</b></font>&nbsp;',$this->content);
    }
// url
    $this->content = preg_replace('#\[url=(.*?)\,(.*?)](.*?)\[\/url\]#is','<a href="\\1" title="\\2" target="_blank">\\3</a>',$this->content);
// email
    $this->content = preg_replace('#\[email\](.*?)\[\/email\]#is','<a href="mailto:\\1" title="'.$lang[7].' : \\1" target="_blank">\\1</a>',$this->content);
// quote
    $this->content = preg_replace('#\[quote\](.*?)\[/quote\]#si','<div class="quote">\\1</div>',$this->content);

// CODE
	$this->content = nl2br($this->content);
		/* replace code php */
		if (preg_match('/<\?[php]?\s?(.*)\s?\?>/si',$this->content_original)) {
			$stringPHP = highlight_string($this->content_original, true);
			preg_match_all('#\[php\](.*?)\[\/php\]#is',$this->content,$array1);
			preg_match_all('#\[php\](.*?)\[\/php\]#is',$stringPHP,$array2);
			$this->content = str_replace($array1[1],$array2[1],$this->content);
			$this->content = preg_replace('#\[php\](.*?)\[\/php\]#is','<code>\\1</code>',$this->content);
		}

// flash
    $this->content = preg_replace('#\[flash=(.*?)\,(.*?)\](.*?)\[\/flash\]#is','<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="\\1" height="\\2">
						  <param name="movie" value="\\1" />
						  <param name="quality" value="high" />
						  <embed src="\\3" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="\\1" height="\\2"></embed>
						  </object>',$this->content);

// window media
    $this->content = preg_replace('#\[music=(.*?)\,(.*?)\](.*?)\[\/music\]#is',str_replace("<br />","",'<object width="\\1" height="\\2" classid="CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6">
		                  <param value="\\3" name="url"/>
						  <embed width="\\1" height="\\2" src="\\3" type="application/x-mplayer2"/>
						  </object>'),$this->content);

// FLV video
    $this->content = preg_replace('#\[flv=(.*?)\,(.*?)\](.*?)\[\/flv]#is',str_replace("<br />","",'<object id="player" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" name="player" width="\\1" height="\\2">
		                <param name="movie" value="images/flvplayer.swf" />
		                <param name="allowfullscreen" value="true" />
		                <param name="allowscriptaccess" value="always" />
		                <param name="flashvars" value="file=\\3&image=images/logo.gif" />
		                <object type="application/x-shockwave-flash" data="images/flvplayer.swf" width="\\1" height="\\2">
			            <param name="movie" value="images/flvplayer.swf" />
			            <param name="allowfullscreen" value="true" />
			            <param name="allowscriptaccess" value="always" />
			            <param name="flashvars" value="file=\\3&image=images/logo.gif" />
			            <p><a href="http://get.adobe.com/flashplayer">Get Flash</a> to see this player.</p>
		                </object>
	                    </object>'),$this->content);

// code
		preg_match_all('#\[code\](.*?)\[\/code\]#is',$this->content,$array1);
		preg_match_all('#\[code\](.*?)\[\/code\]#is',$this->content_original,$array2);
		$this->content = str_replace($array1[1],$array2[1],$this->content);
		$this->content = preg_replace('#\[code\](.*?)\[\/code\]#is','<code>\\1</code>',$this->content);

	}
}
?>