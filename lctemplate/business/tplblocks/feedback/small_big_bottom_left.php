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
global $BT_LANGUAGE;

/* Leave a var empty if not in use or set to false */
$wtplsett = array();

// Custom Settings
$wtplsett["chatposition"] = "bottom:0;left:0;";
$wtplsett["customjs"] = "js/feedback.js";
$wtplsett["customcss"] = "";

// Only for the edit page in the operator panel
if (isset($page) && $page == "widget") {

  /* Leave a var empty if not in use or set to false */
  $feedbacksett = array();

  $feedbacksett["previewchat"] = "preview/feedback.jpg";

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

    $feedbacksett["formoptions"] = array("1" => "1:#:".$jkl['cw44'].":#:0:#:0:#:feedback_custom_logo", "2" => "3:#:".$jkl['cw33'].":#:Yes,No:#:0:#:feedback_email_required", "3" => "3:#:".$jkl['cw34'].":#:Yes,No:#:0:#:feedback_phone_required", "4" => "5:#:".$jkl['cw31'].":#:blue,green,orange,red,pink,grey:#:0:#:feedback_colour_theme", "5" => "5:#:".$jkl['cw32'].":#:animate__fadeIn,animate__flash,animate__pulse,animate__headShake,animate__slideInUp,animate__slideInRight:#:0:#:feedback_animation");

} else {

// Check the vars for this start
$feedback_animate = "animate__fadeIn";
$feedback_custom = "";
$btn_custom = "blue";
$feedback_logo = '<i class="fa fa fa-user-circle '.(isset($ismobile) && !empty($ismobile) ? 'fa-3x' : 'fa-4x').' jaklcb_popup_avatar" aria-hidden="true"></i>';

// We can have custom online icon
if (isset($widgetsettings['feedback_animation']) && !empty($widgetsettings['feedback_animation'])) {
  $feedback_animate = $widgetsettings['feedback_animation'];
}

// We can have custom online icon
if (isset($widgetsettings['feedback_colour_theme']) && !empty($widgetsettings['feedback_colour_theme']) && $widgetsettings['feedback_colour_theme'] != "blue") {
  $feedback_custom = " ".$widgetsettings['feedback_colour_theme'];
  $btn_custom = $widgetsettings['feedback_colour_theme'];
}

// We can have custom online icon
if (isset($widgetsettings['feedback_custom_logo']) && !empty($widgetsettings['feedback_custom_logo'])) {
  $feedback_logo = '<img src="'.$widgetsettings['feedback_custom_logo'].'" class="jaklcb_popup_avatar" alt="logo">';
}

// Let's get the header welcome message
$headermsg = '';
if (empty($headermsg)) {
  if (!empty($LC_ANSWERS) && is_array($LC_ANSWERS)) foreach ($LC_ANSWERS as $v) {

    $msgtype = 9;
    
    if ($v["msgtype"] == $msgtype && $v["lang"] == $BT_LANGUAGE && $v["department"] == 0) {
    
      $phold = array("%operator%","%client%","%email%");
      $replace   = array("", "", JAK_EMAIL);
      $headermsg = str_replace($phold, $replace, $v["message"]);
      
    }
    
  }
}

if (isset($page1) && $page1 == "bigfeedback") {

  $livecode = '<div id="lccontainersize" class="jak_chatopen_panel'.(isset($ismobile) ? $ismobile : '').' animate__animated '.$feedback_animate.'">
  <form id="lcjak_ajaxform" method="post" action="'.$_SERVER['REQUEST_URI'].'">
  <section class="jaklcb_panel left'.$feedback_custom.'">
  <header class="jaklcb_panel_header">
  <aside style="flex:3">'.$feedback_logo.'
  </aside>
  <aside style="flex:5">
  <h1>'.$jkl['g24'].'</h1>
  <h3>'.$headermsg.'</h3>
  </aside>
  <aside style="flex:4;text-align:right;">
  <button class="lcb_back" onclick="lcjak_backtochat(\'slideOutLeft\', \'bigfeedback\', \'big\')" type="button" id="back_f_button"><i class="fas fa-arrow-left" aria-hidden="true"></i></button>
  <button class="jaklcb_minimize lcb_change" onclick="lcjak_windowchange(\'slideOutDown\', \'bigfeedback\', \'feedback\')" type="button"><i class="fa fa-window-restore" aria-hidden="true"></i></button>
  <button class="jaklcb_panelclose lcb_close" onclick="lcjak_closechat(\'rotateOut\', \'bigfeedback\', \'closed\')" type="button"><i class="fa fa-times" aria-hidden="true"></i></button>
  </aside>
  </header>
  <main class="jaklcb_panel_main" style="flex:1" id="lcjak_formfields">
  <div class="rating" style="width: 20rem">
  <input id="rating-5" type="radio" name="rating" value="5"/><label for="rating-5"><i class="fas fa-star fa-lg"></i></label>
  <input id="rating-4" type="radio" name="rating" value="4" checked /><label for="rating-4"><i class="fas fa-star fa-lg"></i></label>
  <input id="rating-3" type="radio" name="rating" value="3"/><label for="rating-3"><i class="fas fa-star fa-lg"></i></label>
  <input id="rating-2" type="radio" name="rating" value="2"/><label for="rating-2"><i class="fas fa-star fa-lg"></i></label>
  <input id="rating-1" type="radio" name="rating" value="1"/><label for="rating-1"><i class="fas fa-star fa-lg"></i></label>
  </div>
  <div class="jaklcb_input">
    <label for="name">'.$jkl["g4"].'</label>
    <input id="name" name="name" type="text" class="lcjak_input" placeholder="'.$jkl["g4"].'" value="">
  </div>
  <div class="jaklcb_input">
    <label for="email">'.$jkl["g5"].'</label>
    <input id="email" name="email" type="text" class="lcjak_input" placeholder="'.$jkl["g5"].'" value="">
  </div>
  <div class="jaklcb_input">
    <label for="phone">'.$jkl["g49"].'</label>
    <input id="phone" name="phone" type="text" class="lcjak_input" placeholder="'.$jkl["g49"].'" value="">
  </div>
  <div class="jaklcb_input">
    <label for="feedback">'.$jkl["g49"].'</label>
    <textarea id="feedback" name="feedback" placeholder="'.$jkl['g25'].'"></textarea>
  </div>
  '.(JAK_SEND_TSCRIPT == 1 ? '<div>
  <label>
  <input type="checkbox" name="send_email" id="send_email" value="1"> '.$jkl["g38"].'
  </label>
  </div>' : '<input type="hidden" name="send_email" value="0">').'
  <button class="lcb_end jakbtn btn-simple btn-'.$btn_custom.'" onclick="lcjak_endchat()" type="button" id="send_f_button"><i class="fas fa-power-off" id="end_save" aria-hidden="true"></i> '.$jkl['g11'].'</button>
  </main>
  </section>
  <input type="hidden" name="mycustomfields" value="">
  </form>
  </div>';

} else {

  $livecode = '<div id="lccontainersize" class="jak_chatopen_sm'.(isset($ismobile) ? $ismobile : '').' animate__animated '.$feedback_animate.'">
  <form id="lcjak_ajaxform" method="post" action="'.$_SERVER['REQUEST_URI'].'">
  <button class="jaklcb_backtochat left lcb_back" type="button" onclick="lcjak_backtochat(\'slideOutLeft\', \'feedback\', \'open\')" id="back_f_button">
  <i class="fas fa-arrow-left" aria-hidden="true"></i>
  </button>
  <button class="jaklcb_end left lcb_end" type="button" onclick="lcjak_endchat()" id="send_f_button">
  <i class="fas fa-power-off" id="end_save" aria-hidden="true"></i>
  </button>
  <button class="jaklcb_close left lcb_close" type="button" onclick="lcjak_closechat(\'rotateOut\', \'feedback\', \'closed\')">
  <i class="fa fa-times fa-lg" aria-hidden="true"></i>
  </button>
  <section class="jaklcb_popup'.$feedback_custom.'">
  <header class="jaklcb_popup_header">
  <aside style="flex:3">'.$feedback_logo.'
  </aside>
  <aside style="flex:8">
  <h1>'.$jkl['g24'].'</h1>
  <h3>'.$headermsg.'</h3>
  </aside>
  <aside style="flex:1">
  <button class="jaklcb_maximise lcb_change" onclick="lcjak_windowchange(\'slideOutUp\', \'feedback\', \'bigfeedback\')" type="button"><i class="fa fa-window-maximize" aria-hidden="true"></i></button>
  </aside>
  </header>
  <main class="jaklcb_form_main" id="lcjak_formfields">
  <div class="rating" style="width: 20rem">
  <input id="rating-5" type="radio" name="rating" value="5"/><label for="rating-5"><i class="fas fa-star fa-lg"></i></label>
  <input id="rating-4" type="radio" name="rating" value="4" checked /><label for="rating-4"><i class="fas fa-star fa-lg"></i></label>
  <input id="rating-3" type="radio" name="rating" value="3"/><label for="rating-3"><i class="fas fa-star fa-lg"></i></label>
  <input id="rating-2" type="radio" name="rating" value="2"/><label for="rating-2"><i class="fas fa-star fa-lg"></i></label>
  <input id="rating-1" type="radio" name="rating" value="1"/><label for="rating-1"><i class="fas fa-star fa-lg"></i></label>
  </div>
  <div class="input_group">
  <div class="jaklcb_input">
    <label for="name">'.$jkl["g4"].'</label>
    <input id="name" name="name" type="text" class="lcjak_input" placeholder="'.$jkl["g4"].'" value="">
  </div>
  <div class="jaklcb_input flex-right">
    <label for="email">'.$jkl["g5"].'</label>
    <input id="email" name="email" type="text" class="lcjak_input" placeholder="'.$jkl["g5"].'" value="">
  </div>
  </div>
  <div class="jaklcb_input">
    <label for="phone">'.$jkl["g49"].'</label>
    <input id="phone" name="phone" type="text" class="lcjak_input" placeholder="'.$jkl["g49"].'" value="">
  </div>
  <div class="jaklcb_input">
    <label for="feedback">'.$jkl["g49"].'</label>
    <textarea id="feedback" name="feedback" placeholder="'.$jkl['g25'].'"></textarea>
  </div>
  '.(JAK_SEND_TSCRIPT == 1 ? '<div>
  <label>
  <input type="checkbox" name="send_email" id="send_email" value="1"> '.$jkl["g38"].'
  </label>
  </div>' : '<input type="hidden" name="send_email" value="0">').'
  </main>
  <input type="hidden" name="mycustomfields" value="">
  </section></form></div>';

}

}

?>