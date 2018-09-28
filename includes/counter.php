<?php
// ONLINE
$deletetime=time()-$web_user_online;
$ssid=session_id();

$now=time();
$nicktv = $_SESSION['userid'];
if(($nicktv!='')||($nicktv!='0')){
    $mysql->query("update ".$table_prefix."online set online_mem='$nicktv' where online_ssid='$ssid'");
}else{
    $mysql->query("update ".$table_prefix."online set online_mem='' where online_ssid='$ssid'");
}
$check = $mysql->num_rows($mysql->query("SELECT online_id FROM ".$table_prefix."online WHERE online_ssid='$ssid'"));
if($check>0)
    $mysql->query("update ".$table_prefix."online set online_time='$now' where online_ssid='$ssid'");
else{
    $mysql->query("insert into ".$table_prefix."online (online_id,online_mem,online_ssid,online_time) values ('','".$_SESSION['user_id']."','$ssid','$now')");
    $mysql->query("delete from ".$table_prefix."online where online_time<$deletetime"); }

// COUNTER
// lay gia tri thoi gian theo timezone
$date_full = timezones(0);
$daynow = substr($date_full,0,4).'-'.substr($date_full,4,2).'-'.substr($date_full,6,2);
$r = $mysql->query("SELECT * FROM ".$table_prefix."counter where counter_total>0");
$rs = $mysql->fetch_array($r);
$Hits = $rs["counter_total"];
$total = $mysql->num_rows($r);
if(!$total) $mysql->query("INSERT INTO ".$table_prefix."counter(counter_total,counter_date,counter_ofdate) values ('1','".$daynow."','1')");
if (@empty($_SESSION["count"])){
    $mysql->query("update ".$table_prefix."counter set counter_total = counter_total+1,counter_ofdate = counter_ofdate+1 where counter_total = ".$rs['counter_total']."");
	$_SESSION["count"] = "true";
}
if ($rs['counter_date']!=$daynow){
    $mysql->query("update ".$table_prefix."counter set counter_date = '".$daynow."', counter_atotalc=counter_ofdate, counter_ofdate = '1' where counter_total = ".$rs['counter_total']."");
}
?>