<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0.3                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2022 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

// Language file goes global
global $jkl;

/* Leave a var empty if not in use or set to false */
$wtplsett = array();

// Custom Settings
$wtplsett["chatposition"] = "position:fixed;z-index:9999;bottom:0;left:0;";
$wtplsett["customjs"] = "js/btn.js";
$wtplsett["customcss"] = "";
// Standard vars for the button
$wtplsett["standardvars"] = array("btn_image" => "space_on.png", "btn_image_mobile" => "space-mobile_on.png");

// We only load when we need to receive messages
if (isset($lcdrm) && $lcdrm === true) {
	$wtplsett["engagehtml"] = '<div class="jaklc_engage_img left animate__animated animate__fadeIn">'.(isset($eengimg) && !empty($engimg) ? '<p><img src="'.$engimg.'" alt="engage_img" max-width="100"></p>' : (isset($engicon) && !empty($engicon) ? '<p><i class="'.$engicon.' fa-lg"></i></p>' : '')).(isset($engtitle) && !empty($engtitle) ? '<h1>'.$engtitle.'</h1>' : '').'<p>'.(isset($engmsg) && !empty($engmsg) ? $engmsg : '').'</p><p><a href="javascript:void(0)" onclick="lcjak_openchat(this)" class="jakbtn btn-green btn-small btn-underline">'.(isset($engconfirm) && !empty($engconfirm) ? $engconfirm : $jkl['g72']).'</a> <a href="javascript:void(0)" onclick="lcjak_closechat(this)" class="jakbtn btn-red btn-small btn-underline">'.(isset($engcancel) && !empty($engcancel) ? $engcancel : $jkl['g73']).'</a></p></figcaption>';
}

if (isset($page) && $page == "widget") {

	/* Leave a var empty if not in use or set to false */
	$btnsett = array();

	$btnsett["previewbtn"] = '<div><img src="" id="btn_preview" alt="live chat" class="img-responsive"></div>';
	$btnsett["previewbtnmobile"] = '<div><img src="" id="btn_preview_mobile" alt="live chat" class="img-responsive"></div>';

  /* Now we need custom input fields */
  /* Following options are available:

    1. Input
    2. Textarea
    3. Radio
    4. Checkbox
    5. Select

    ***

    Title (you can use the lang vars from the operator/lang language files)

    ***

    Options (for radio 3, checkbox 4, select 5) = Green,Red,Blue
    Options (for Input) = colour or icon
    Options for select = btn
    Options for select = slideimg

    ***

    Multiple (0 = No / 1 = Yes)

    ***

    The english language var for the input field

    */

    $btnsett["formoptions"] = array("1" => "5:#:".$jkl['cw41'].":#:btn:#:0:#:btn_image", "2" => "5:#:".$jkl['cw42'].":#:btn:#:0:#:btn_image_mobile", "3" => "5:#:".$jkl['cw18'].":#:animate__fadeIn,animate__flash,animate__pulse,animate__headShake,animate__slideInUp,animate__slideInRight:#:0:#:btn_animation", "4" => "5:#:".$jkl['cw19'].":#:animate__fadeIn,animate__flash,animate__pulse,animate__headShake,animate__slideInUp,animate__slideInRight:#:0:#:btn_animation_offline");

}

// Check the vars for this button
$btn_on_icon = "far fa-comments-alt";
$btn_off_icon = "far fa-envelope";
$btn_on_animate = "animate__fadeIn";
$btn_off_animate = "animate__fadeIn";
$btnimg = "";

// Custom Button animation
if (isset($widgetsettings['btn_animation']) && !empty($widgetsettings['btn_animation'])) {
	$btn_on_animate = $widgetsettings['btn_animation'];
}


// Custom button animation for offline use
if (isset($widgetsettings['btn_animation_offline']) && !empty($widgetsettings['btn_animation_offline'])) {
	$btn_off_animate = $widgetsettings['btn_animation_offline'];
}

// Set the button image
if (isset($widgetsettings['btn_image']) && !empty($widgetsettings['btn_image'])) {

	$btnimgmodify = $widgetsettings['btn_image'];
	if (isset($_SESSION["clientismobile"]) && isset($widgetsettings['btn_image_mobile']) && !empty($widgetsettings['btn_image_mobile'])) {
		$btnimgmodify = $widgetsettings['btn_image_mobile'];
	}

	// Get image size
	if (isset($online_op) && $online_op) {
		list($btnwidth, $btnheight) = getimagesize(APP_PATH.JAK_FILES_DIRECTORY.'/buttons/'.$btnimgmodify);
		$buttonimg = $btnimgmodify;
	} else {
		list($btnwidth, $btnheight) = getimagesize(APP_PATH.JAK_FILES_DIRECTORY.'/buttons/'.str_replace("_on", "_off", $btnimgmodify));
		$buttonimg = str_replace("_on", "_off", $btnimgmodify);
	}

	// Get the width of the button and add 50px for the hover
	$btnwidthdiv = 'style="width:'.($btnwidth+20).'px;height:'.($btnheight+20).'px;"';

	$btnimg = '<div class="jaklcb_custom left"><img src="'.str_replace('include/', '', BASE_URL).JAK_FILES_DIRECTORY.'/buttons/'.$buttonimg.'" id="lcjak_openchat" width="'.$btnwidth.'" height="'.$btnheight.'" alt="live chat"></div>';

}

$livecode_online = '<div id="lccontainersize" class="jak_custombtn animate__animated '.$btn_on_animate.'"'.$btnwidthdiv.'>
	'.$btnimg.'
</div>';

$livecode_offline = '<div id="lccontainersize" class="jak_custombtn animate__animated '.$btn_off_animate.'"'.$btnwidthdiv.'>
	'.$btnimg.'
</div>';

$livecode_hide = '<div id="lccontainersize" class="jak_hidewhenoff"></div>';

?>