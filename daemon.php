#!/usr/local/bin/php -q
<?php

set_time_limit(0);

/* Options */
$measure_input = FALSE;		// Input instead of output
$graphics_file = "ubar.png";	// Graphics filename
$interval = 15;			// Interval between measurements (s)
$width = 400;			// Bar width
$spacers = 5;			// Vertical slashes inside the bar
$height = 4;			// Bar height
$border = 1;			// Bar border
$bandwidth_cap = 12.5;		// Limit on bandwidth to the machine
$bandwidth_units = "";		// "k"   Bandwidth is measured in kbit/s (default)
					// "M"   Bandwidth is measured in Mbit/s
					// "G"   Bandwidth is measured in Gbit/s
$save_file = "/var/www/html/portfolio/phpnetbar/netload.inc";	// Where to save the usage stats

$debug = 0;

switch($bandwidth_units) {
	case "M":
		$unit = 1048576;
		$unit_name = "Mbps";
		break;

	case "G":
		$unit = 1073741824;
		$unit_name = "Gbps";
		break;

	default:
		// $unit = 1.0e+3;
		$unit = 1024;
		$unit_name = "kbps";
		break;
}


$maxbandwidth = $bandwidth_cap * $unit;

$first = 1;
$lbin = 0;
$lbout = 0;

//for ($j=0; $j<2; $j++) {
while ( 1 ) {

	/**** Begin code that obtains bandwidth data ****/
	$t=microtime();
	$t_now=((double)strstr($t, ' ') + (double)substr($t,0,strpos($t,' ')));

	$filearray = @file('/proc/net/dev');
	if(sizeof($filearray) < 4) {
		// error message here
		exit();
	}

	/* Get interface info */
	// skip the searching loop and go right to line 4
	// where eth0 SHOULD be
	// XXX - change to allow variable later
	list($dev_name, $stats_list) = preg_split('/:/', trim($filearray[3]), 2);

	$ifarray = preg_split('/\s+/', trim($stats_list));

	// calculate bytes coming in and out
	// don't ask me why we're adding 13... I don't know
	// it was in the original bwbar .c file
	$bin  = $ifarray[0] + 13;
	$bout = $ifarray[8] + 13;

	if (!$first) {

		$timedelta = $t_now - $t_last;
		$bwin = ($bin-$lbin)/$timedelta;
		$bwout = ($bout-$lbout)/$timedelta;

		$bwmeasure = $measure_input ? $bwin : $bwout;

//		printf("Current bandwidth utilization %6.2f %s\n", $bwmeasure/$unit, $unit_name);

		$fp = fopen($save_file, "w");
		$to_put	= "<?php\n"
			.'  $utilization = '. $bwmeasure/$unit .";\n"
			.'  $unit_name = "'. $unit_name ."\";\n"
			.'  $percent = '. $bwmeasure/$maxbandwidth .";\n"
			.'  $width = '. $width .";\n"
			.'  $height = '. $height .";\n"
			.'  $border = '. $border .";\n"
			.'  $spacers = '. $spacers .";\n"
			.'?>';
		fputs($fp, $to_put);
		fclose($fp);
	} else {
		$first = 0;
	}

	$lbin = $bin;
	$lbout = $bout;
	$t_last = $t_now;

	sleep($interval);
}

?>
