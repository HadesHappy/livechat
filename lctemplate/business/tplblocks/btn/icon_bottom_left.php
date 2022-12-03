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

// Check the vars for this button
$btn_on_icon = "far fa-comments-alt";
$btn_off_icon = "far fa-envelope";
$btn_on_animate = "animate__fadeIn";
$btn_off_animate = "animate__fadeIn";
$btn_on_custom = $btn_off_custom = "";

/* Leave a var empty if not in use or set to false */
$wtplsett = array();

// Custom Settings
$wtplsett["chatposition"] = "position:fixed;z-index:9999;bottom:0;left:0;";
$wtplsett["customjs"] = "js/btn.js";
$wtplsett["customcss"] = "";

// We only load when we need to receive messages
if (isset($lcdrm) && $lcdrm === true) {
	$wtplsett["engagehtml"] = '<div class="jaklc_engage left animate__animated animate__fadeIn">'.(isset($eengimg) && !empty($engimg) ? '<p><img src="'.$engimg.'" alt="engage_img" max-width="100"></p>' : (isset($engicon) && !empty($engicon) ? '<p><i class="'.$engicon.' fa-lg"></i></p>' : '')).(isset($engtitle) && !empty($engtitle) ? '<h1>'.$engtitle.'</h1>' : '').'<p>'.(isset($engmsg) && !empty($engmsg) ? $engmsg : '').'</p><p><a href="javascript:void(0)" onclick="lcjak_openchat(this)" class="jakbtn btn-green btn-small btn-underline">'.(isset($engconfirm) && !empty($engconfirm) ? $engconfirm : $jkl['g72']).'</a> <a href="javascript:void(0)" onclick="lcjak_closechat(this)" class="jakbtn btn-red btn-small btn-underline">'.(isset($engcancel) && !empty($engcancel) ? $engcancel : $jkl['g73']).'</a></p></figcaption>';
}

if (isset($page) && $page == "widget") {

	/* Leave a var empty if not in use or set to false */
	$btnsett = array();

	$btnsett["previewbtn"] = '<button style="width: 52px;height: 52px;color: #fff;background-color: #039af2;background-position: center center;background-repeat: no-repeat;box-shadow: 12px 15px 20px 0 rgba(46, 61, 73, 0.15);border: 0;border-radius: 50%;cursor: pointer;margin: 16px;" type="button" id="lcjak_openchat">
		<i class="'.$btn_on_icon.' fa-lg" style="margin-top: 2px;"></i>
	</button>';

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

    ***

    Multiple (0 = No / 1 = Yes)

    ***

    The english language var for the input field

    */

    $btnsett["formoptions"] = array("1" => "1:#:".$jkl['cw10'].":#:icon:#:0:#:btn_icon", "2" => "1:#:".$jkl['cw11'].":#:colour:#:0:#:btn_icon_colour", "3" => "1:#:".$jkl['cw12'].":#:colour:#:0:#:btn_background_colour", "4" => "5:#:".$jkl['cw18'].":#:animate__fadeIn,animate__flash,animate__pulse,animate__headShake,animate__slideInUp,animate__slideInRight:#:0:#:btn_animation", "5" => "1:#:".$jkl['cw15'].":#:icon:#:0:#:btn_icon_offline", "6" => "1:#:".$jkl['cw16'].":#:colour:#:0:#:btn_icon_off_colour", "7" => "1:#:".$jkl['cw17'].":#:colour:#:0:#:btn_background_off_colour", "8" => "5:#:".$jkl['cw19'].":#:animate__fadeIn,animate__flash,animate__pulse,animate__headShake,animate__slideInUp,animate__slideInRight:#:0:#:btn_animation_offline");

}

// We can have custom online icon
if (isset($widgetsettings['btn_icon']) && !empty($widgetsettings['btn_icon'])) {
	$btn_on_icon = $widgetsettings['btn_icon'];
}

// We can have custom online icon
if (isset($widgetsettings['btn_animation']) && !empty($widgetsettings['btn_animation'])) {
	$btn_on_animate = $widgetsettings['btn_animation'];
}

// We can have custom online colours
if (isset($widgetsettings['btn_icon_colour']) && !empty($widgetsettings['btn_icon_colour']) || isset($widgetsettings['btn_background_colour']) && !empty($widgetsettings['btn_background_colour'])) {

	$btn_on_custom .= 'style="';

	if (isset($widgetsettings['btn_icon_colour']) && !empty($widgetsettings['btn_icon_colour'])) {
			$btn_on_custom .= 'color:'.$widgetsettings['btn_icon_colour'].';';
	}

	if (isset($widgetsettings['btn_background_colour']) && !empty($widgetsettings['btn_background_colour'])) {
		$btn_on_custom .= 'background-color:'.$widgetsettings['btn_background_colour'].';';
	}

	$btn_on_custom .= '"';

}

// We can have custom online icon
if (isset($widgetsettings['btn_icon_offline']) && !empty($widgetsettings['btn_icon_offline'])) {
	$btn_off_icon = $widgetsettings['btn_icon_offline'];
}

// We can have custom online icon
if (isset($widgetsettings['btn_animation_offline']) && !empty($widgetsettings['btn_animation_offline'])) {
	$btn_off_animate = $widgetsettings['btn_animation_offline'];
}

// We can have custom online colours
if (isset($widgetsettings['btn_icon_off_colour']) && !empty($widgetsettings['btn_icon_off_colour']) || isset($widgetsettings['btn_background_off_colour']) && !empty($widgetsettings['btn_background_off_colour'])) {

	$btn_off_custom .= 'style="';

	if (isset($widgetsettings['btn_icon_off_colour']) && !empty($widgetsettings['btn_icon_off_colour'])) {
			$btn_off_custom .= 'color:#'.$widgetsettings['btn_icon_off_colour'].';';
	}

	if (isset($widgetsettings['btn_background_off_colour']) && !empty($widgetsettings['btn_background_off_colour'])) {
		$btn_off_custom .= 'background-color:#'.$widgetsettings['btn_background_off_colour'].';"';
	}

	$btn_off_custom .= '"';

}

// The html code to display
$livecode_online = '<div id="lccontainersize" class="jak_roundbtn animate__animated '.$btn_on_animate.'">
	<button class="jaklcb_open left"'.$btn_on_custom.' type="button" id="lcjak_openchat">
		<i class="'.$btn_on_icon.' fa-lg"></i>
	</button>
</div>';

$livecode_offline = '<div id="lccontainersize" class="jak_roundbtn animate__animated '.$btn_off_animate.'">
	<button class="jaklcb_open left offline"'.$btn_off_custom.' type="button" id="lcjak_openchat">
		<i class="'.$btn_off_icon.' fa-lg"></i>
	</button>
</div>';

$livecode_hide = '<div id="lccontainersize" class="jak_hidewhenoff"></div>';

?>