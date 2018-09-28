<?php

session_start();

mt_srand((double)microtime()*1000000);
$seccode = mt_rand(10000, 99999);

$_SESSION['antifloodimage'] = $seccode;
// choice color for img --------------------------------
function html2rgb( $color ) {
  if (substr($color,0,1)=="#") $color=substr($color,1,6);
  $tablo[0] = hexdec(substr($color, 0, 2));
  $tablo[1] = hexdec(substr($color, 2, 2));
  $tablo[2] = hexdec(substr($color, 4, 2));
  return $tablo;
}

$bgc=html2rgb('FFF4EA');
$fc=html2rgb('FF8000');
$lc=html2rgb('FFC896');
$bc=html2rgb('FF8000');
// end choice color for img ----------------------------


header("Content-Type: image/png");
$im = imagecreate(60, 18) or die('Image create error!');// imagecreate(BREITE, HÖHE)

$bgcolor = imagecolorallocate($im, $bgc[0], $bgc[1], $bgc[2]);		// imagecolorallocate($im, R, G, B) Nur R,G,B ändern!
$fontcolor = imagecolorallocate($im, $fc[0], $fc[1], $fc[2]);		// imagecolorallocate($im, R, G, B) Nur R,G,B ändern!
$linecolor = imagecolorallocate($im, $lc[0], $lc[1], $lc[2]);		// imagecolorallocate($im, R, G, B) Nur R,G,B ändern!
$bordercolor = imagecolorallocate($im, $bc[0], $bc[1], $bc[2]);	    // imagecolorallocate($im, R, G, B) Nur R,G,B ändern!
// Gitter
for($x=10; $x <= 100; $x+=10)
    imageline($im, $x, 0, $x, 50, $linecolor);
// Mittellinie
imageline($im, 0, 9, 100, 9, $linecolor);
// Rahmen
imageline($im, 0, 0, 0, 50, $bordercolor);
imageline($im, 0, 0, 100, 0, $bordercolor);
imageline($im, 0, 17, 100, 17, $bordercolor);
imageline($im, 59, 0, 59, 17, $bordercolor);

imagestring($im, 5, 8, 1, $seccode, $fontcolor);
imagepng($im);
imagedestroy($im);
?>