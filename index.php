<?php
    if(!file_exists("includes/config.php")){
          header("Location: ./install/install.php");
    }
    include('includes/include.php');
    if($installed!='yes'){
        if(!file_exists("install/install.php")){
            echo "<center><b>Có l?i v?i file h? th?ng vui lòng cài d?t l?i!</b></center>";
        }else{
            header("Location: ./install/install.php");
        }
    }
    // LOAD LANGUAGE DEFAULT
    if ($_GET['langer']!=0 || $_GET['langer'] !=''){
        $langer = $_GET['langer'];
        $_SESSION['langer'] = $langer;
        header("Location: ./".$LINK.$_SESSION['current_page']);
    }
    if($_GET['playlist']){
        include($module.'music.php');
        exit();
    }
    if ($_SESSION['langer']=='' || !$_SESSION['langer']){
        $qlang = $web_language;
        $langer = get_config('url','lang','url',$qlang);
        require_once "language/".$langer.".php";
        //language java
        $lang_java = "language/".$langer.".js";
    }else{
        $langer = $_SESSION['langer'];
        $nn = get_config('url','lang','url',$langer);
        require_once "language/".$nn.".php";
        $lang_java = "language/".$nn.".js";
    }
    $_SESSION['language']   = $langer;
    // LOAD TEMPATE AND CHANGE TEMPLATE
    if($_GET['skin'] != '' || $_GET['skin'] !=0){
            if($_GET['skin'] =='-1') $skin = "default";         // back giao dien mac dinh cua he thong
            elseif($_GET['skin'] =='0') $skin = get_config('tpl_link','templates','tpl_name',$web_template);  // back giao dien mac dinh cua site
            else $skin = $_GET['skin'];
        $_SESSION['skin'] = $skin;
        header("Location: ./".$LINK.$_SESSION['current_page']);
    }
    # SESSION TENPLATE
    $template_name = ($_SESSION['skin']==''|| !$_SESSION['skin'])?$web_template:$_SESSION['skin'];
        $_SESSION['template']='template/'.$template_name;
        include($_SESSION['template']."/info.php");
    #END template
    if($_POST['emotion']){
      //  include('../../includes/include.php');
       // $editid = $_GET['editid'];
        echo emotion();
        exit();
    }



    // load default javascript
    $js .= "<script language='javascript' src='js/him.js'></script>
            <script language='javascript' src='js/jquery.js'></script>
            <script language='javascript' src='js/editor.js'></script>
            <script language='javascript' src='js/scroll.js'></script>
            <script language='javascript' src='js/countdown.js'></script>
            <script language='javascript' src='js/diarylove.js'></script>
            <script language='javascript' src='js/swfobject.js'></script>
            <script language=\"JavaScript\" src=\"js/highslide/highslide.js\"></script>
            <link rel=\"stylesheet\" type=\"text/css\" href=\"js/highslide/highslide.css\" />
            <script language='javascript' src='".$lang_java."'></script>
            <script type=\"text/javascript\">
	                hs.graphicsDir = 'js/highslide/graphics/';
	                hs.align = 'center';
	                hs.transitions = ['expand', 'crossfade'];
	                hs.outlineType = 'glossy-dark';
	                hs.wrapperClassName = 'dark';
	                hs.fadeInOut = true;
	                hs.numberPosition = 'caption';
	                hs.dimmingOpacity = 0.75;

	                // Add the controlbar
	                if (hs.addSlideshow) hs.addSlideshow({
		                //slideshowGroup: 'group1',
		                interval: 5000,
		                repeat: true,
		                useControls: true,
		                fixedControls: 'fit',
		                overlayOptions: {
			                opacity: .75,
			                position: 'bottom center',
			                hideOnMouseOut: true
		                }
	                });
            </script>
            <script type=\"text/javascript\">
                HSImageResizer.MAXWIDTH = ".$tpl_info['main'].";
                HSImageResizer.MAXHEIGHT = 0;
                var RATE_OBJECT_IMG = '".$_SESSION['template']."/images/star/full.png';
            	var RATE_OBJECT_IMG_HOVER = '".$_SESSION['template']."/images/star/full.png';
            	var RATE_OBJECT_IMG_HALF = '".$_SESSION['template']."/images/star/half.png';
            	var RATE_OBJECT_IMG_BG = '".$_SESSION['template']."/images/star/none.png';
            </script>";



    $htm = $tpl->get_tem('home');
    $t = $tpl->auto_get_block($htm);
    // LOAD LOGIN PAGE
    if($_SESSION['login']!='yes' || !$_SESSION['login'] || !$_SESSION['userid']){
        $web_title = 'DiaryLove - Login';
        $main = login();
    }else{
        $main = home();
    }

    if($_POST){
         include("includes/post.php");
         exit();
     }
     if($_GET){
        include("includes/get.php");
        exit();
     }




    $htm = $tpl->replace_tem($htm,$lang);
    $htm = $tpl->replace_tem($htm,array(
        'main'  => $main,
        'webname'   => $web_title,
        )
    );
    $htm = $tpl->replace_block($htm,array(
        'js'    => $js,
        )
    );
    $tpl->show($htm);

?>




