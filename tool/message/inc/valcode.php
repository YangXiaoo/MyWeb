<?php
	header("content-type:image/png");
	$num = $_GET['num'];
	$imagewidth=60;
	$imageheight=30;
	srand(microtime() * 100000);

	$numimage = imagecreate($imagewidth,$imageheight);
	imagecolorallocate($numimage,183,180,83);
	for($i=0;$i<strlen($num);$i++){
		$font = mt_rand(3,5);
		$x = mt_rand(1,8)+$imagewidth*$i/4;
		$y = mt_rand(1,$imageheight/4);
		$color=imagecolorallocate($numimage,mt_rand(0,100),mt_rand(0,150),mt_rand(0,200));
		imagestring($numimage,$font,$x,$y,$num[$i],$color);
	}
	imagepng($numimage);
	imagedestroy($numimage);
?>