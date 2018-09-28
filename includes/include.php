<?
include('connect.php');
include('html.php');
include('site.config.php');
include('template.class.php');
include('bbcode.class.php');
$tpl =& new Template;
$moshtml = & new mosHTML;
include('config.functions.php');
include('site.functions.php');
include($plugin.'default/default.php');
include('block.php');

?>