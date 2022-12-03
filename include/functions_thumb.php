<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH                                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2015 jakweb All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/
 
//    Unsharp Mask for PHP - version 2.1.1   
//   
//    Unsharp mask algorithm by Torstein Hønsi 2003-07.   
//    thoensi_at_netcom_dot_no.

function Image_Sharpen($img, $amount, $radius, $threshold) {
	if ($amount > 500) { $amount = 500; }
	$amount = $amount * 0.016;
	if ($radius > 50) { $radius = 50; }
	$radius = $radius * 2;
	if ($threshold > 255) { $threshold = 255; }

	$radius = abs(round($radius));
	if ($radius == 0) { return $img; imagedestroy($img); }

	$w = imagesx($img); $h = imagesy($img);
	$imgCanvas = imagecreatetruecolor($w, $h);
	$imgBlur = imagecreatetruecolor($w, $h);

	if (function_exists('imageconvolution')) {
		$matrix = array(
			array( 1, 2, 1 ),
			array( 2, 4, 2 ),
			array( 1, 2, 1 )
		);
		imagecopy($imgBlur, $img, 0, 0, 0, 0, $w, $h);
		imageconvolution($imgBlur, $matrix, 16, 0);
	} else {
		for ($i = 0; $i < $radius; $i++)    { 
			imagecopy($imgBlur, $img, 0, 0, 1, 0, $w - 1, $h);
			imagecopymerge($imgBlur, $img, 1, 0, 0, 0, $w, $h, 50);
			imagecopymerge($imgBlur, $img, 0, 0, 0, 0, $w, $h, 50);
			imagecopy($imgCanvas, $imgBlur, 0, 0, 0, 0, $w, $h); 

			imagecopymerge($imgBlur, $imgCanvas, 0, 0, 0, 1, $w, $h - 1, 33.33333 );
			imagecopymerge($imgBlur, $imgCanvas, 0, 1, 0, 0, $w, $h, 25);
		}
	}

	if($threshold>0) {
		for ($x = 0; $x < $w-1; $x++) {
			for ($y = 0; $y < $h; $y++) {

				$rgbOrig = ImageColorAt($img, $x, $y);
				$rOrig = (($rgbOrig >> 16) & 0xFF);
				$gOrig = (($rgbOrig >> 8) & 0xFF);
				$bOrig = ($rgbOrig & 0xFF);

				$rgbBlur = ImageColorAt($imgBlur, $x, $y);

				$rBlur = (($rgbBlur >> 16) & 0xFF);
				$gBlur = (($rgbBlur >> 8) & 0xFF);
				$bBlur = ($rgbBlur & 0xFF);

				$rNew = (abs($rOrig - $rBlur) >= $threshold)
					? max(0, min(255, ($amount * ($rOrig - $rBlur)) + $rOrig))
					: $rOrig;
				$gNew = (abs($gOrig - $gBlur) >= $threshold)
					? max(0, min(255, ($amount * ($gOrig - $gBlur)) + $gOrig))
					: $gOrig;
				$bNew = (abs($bOrig - $bBlur) >= $threshold)
					? max(0, min(255, ($amount * ($bOrig - $bBlur)) + $bOrig))
					: $bOrig;

				if (($rOrig != $rNew) || ($gOrig != $gNew) || ($bOrig != $bNew)) {
					$pixCol = ImageColorAllocate($img, $rNew, $gNew, $bNew);
					ImageSetPixel($img, $x, $y, $pixCol);
				}
			}
		}
	}
	else {
		for ($x = 0; $x < $w; $x++) {
			for ($y = 0; $y < $h; $y++) {
				$rgbOrig = ImageColorAt($img, $x, $y);
				$rOrig = (($rgbOrig >> 16) & 0xFF);
				$gOrig = (($rgbOrig >> 8) & 0xFF);
				$bOrig = ($rgbOrig & 0xFF);

				$rgbBlur = ImageColorAt($imgBlur, $x, $y);

				$rBlur = (($rgbBlur >> 16) & 0xFF);
				$gBlur = (($rgbBlur >> 8) & 0xFF);
				$bBlur = ($rgbBlur & 0xFF);

				$rNew = ($amount * ($rOrig - $rBlur)) + $rOrig;
				
				if($rNew > 255) { $rNew = 255; }
				else if($rNew < 0) { $rNew = 0; }
				
				$gNew = ($amount * ($gOrig - $gBlur)) + $gOrig; 
				
				if($gNew > 255) {$gNew = 255;}
				else if($gNew < 0) { $gNew = 0; }
				
				$bNew = ($amount * ($bOrig - $bBlur)) + $bOrig; 

				if( $bNew > 255) { $bNew = 255; }
				else if ( $bNew < 0 ) { $bNew = 0; }

				$rgbNew = ($rNew << 16) + ($gNew <<8) + $bNew; 
				ImageSetPixel($img, $x, $y, $rgbNew); 
			}
		}
	}
	imagedestroy($imgCanvas);
	imagedestroy($imgBlur);

	return $img;
}

function create_thumbnail($targetPath, $targetFile, $sourceFile, $widthNew, $heightNew, $qualityNew)

{

$imgsize = getimagesize($targetFile);
switch(strtolower(substr($targetFile, -3))){
    case "jpg":
        $image = imagecreatefromjpeg($targetFile);    
    break;
    case "png":
        $image = imagecreatefrompng($targetFile);
    break;
    case "gif":
        $image = imagecreatefromgif($targetFile);
    break;
    default:
        exit;
    break;
}

$width = $widthNew; // New width of image    
$height = $heightNew; // New height of image

// Original size
$src_w = $imgsize[0];
$src_h = $imgsize[1];

// Create new size
if ($widthNew && ($src_w < $src_h)) {
    $width = ($heightNew / $src_h) * $src_w;
} else {
    $height = ($widthNew / $src_w) * $src_h;
}

$picture = imagecreatetruecolor($width, $height);
imagealphablending($picture, false);
imagesavealpha($picture, true);
$bool = imagecopyresampled($picture, $image, 0, 0, 0, 0, $width, $height, $src_w, $src_h);

// Sharpen Image
$picture = Image_Sharpen($picture, 80, 0.5, 3);

if($bool){
    switch(strtolower(substr($targetFile, -3))){
        case "jpg":
            $bool2 = imagejpeg($picture,$targetPath."/".$sourceFile,$qualityNew);
        break;
        case "png":
            imagepng($picture,$targetPath."/".$sourceFile);
        break;
        case "gif":
            imagegif($picture,$targetPath."/".$sourceFile);
        break;
    }
}

imagedestroy($picture);
imagedestroy($image);

}
?>