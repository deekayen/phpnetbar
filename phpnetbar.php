<?php

// do something better with calling this stuff later

include 'netload.inc';

// You can optionally set the below variables to load from
// a PHP shell script by uncommenting the above line
// or include them as part of the get request to this file

// set default values
if(!isset($percent))
	$percent=0;

if(!isset($width))
	$width=400;

if(!isset($height))
	$height=4;

if(!isset($border))
	$border=1;

if(!isset($spacers))
	$spacers=5;

header ("Content-type: image/png");

$bwidth = $width + ($border*2);
$bheight = $height + ($border*2);

$usage_fill = floor($width * $percent);
$empty_fill = 100 - $usage_fill;
$spacer_distance = round($width/($spacers+1));

$im = @ImageCreate ($bwidth+1, $bheight+1) or die ("Cannot Initialize new GD image stream");

$background_color = ImageColorAllocate ($im, 255, 255, 255);
$text_color_pink = ImageColorAllocate ($im, 214, 136, 131);
$text_color_gray = ImageColorAllocate ($im, 204, 204, 227);
$text_color_black = ImageColorAllocate ($im, 0, 0, 0);

imagefilledrectangle ($im, 0, 0, $bwidth, $border-1, $text_color_black);	// top line
imagefilledrectangle ($im, 0, 0, $border-1, $bheight, $text_color_black);	// left line
imagefilledrectangle ($im, 0, $bheight-$border+1, $bwidth-$border+1, $bheight, $text_color_black);	// bottom line
imagefilledrectangle ($im, $bwidth-$border+1, 0, $bwidth, $bheight, $text_color_black);	// right line

// this one is the usage rectangle
imagefilledrectangle ($im, $border, $border, $border+$usage_fill, $border+$height, $text_color_pink);

// empty part of the meter
imagefilledrectangle ($im, $border+$usage_fill+1, $border, $width+$border, $height+$border, $text_color_gray);

for($i=1; $i<$spacers+1; $i++) {
	imageline ($im, $border+($spacer_distance*$i), $border, $border+($spacer_distance*$i), $height+$border, $background_color);
}

ImagePng ($im);

?>
