<?
include('config.php');
ob_start();
if(!session_id()) session_start();
header("Content-Type: text/html; charset=UTF-8");
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if (!ini_get('register_globals')) {
    @$_GET = $HTTP_GET_VARS;
	@$_POST = $HTTP_POST_VARS;
	@$_COOKIE = $HTTP_COOKIE_VARS;
	extract($_GET);
	extract($_POST);
}
define('NOW',time());
define('IP',$_SERVER['REMOTE_ADDR']);
define('USER_AGENT',$_SERVER['HTTP_USER_AGENT']);
define('URL_NOW',$_SERVER["REQUEST_URI"]);
if (!USER_AGENT || !IP) exit();
if (!$_COOKIE['SID']) {
	$sid = md5(session_id());
	setcookie('SID',$sid,60*60*1);
	define('SID',$sid);
	unset($sid);
}
else define('SID',$_COOKIE['SID']);
##################### Ket noi server ######################
include ('sql.class.php');
    $mysql = new mysql;
    $mysql->connect($db_host,$db_user,$db_pass,$db_data);
##################### Function cookie #####################
function get_cookie($name, $value = ""){
    $expires = time() + 60*60*24*365; //sec
    setcookie($name, $value, $expires,"/",""); }
function call_cookie($name){
    if (isset($_COOKIE[$name]))
        { return urldecode($_COOKIE[$name]);
        } else {
        return FALSE; }
}
?>