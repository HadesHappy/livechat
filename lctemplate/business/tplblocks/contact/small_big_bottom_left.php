<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0                   # ||
|| # ----------------------------------------- # ||
|| # Copyright 2021 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

// Language file goes global
global $jkl;
global $BT_LANGUAGE;

/* Leave a var empty if not in use or set to false */
$wtplsett = array();

// Custom Settings
$wtplsett["chatposition"] = "bottom:0;left:0;";
$wtplsett["customjs"] = "js/contact.js";
$wtplsett["customcss"] = "";

// Only for the edit page in the operator panel
if (isset($page) && $page == "widget") {

  /* Leave a var empty if not in use or set to false */
  $contactsett = array();

  $contactsett["previewchat"] = "preview/contact.jpg";

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

    $contactsett["formoptions"] = array("1" => "1:#:".$jkl['cw44'].":#:0:#:0:#:contact_custom_logo", "2" => "3:#:".$jkl['cw35'].":#:Yes,No:#:0:#:contact_phone_required", "3" => "5:#:".$jkl['cw36'].":#:blue,green,orange,red,pink,grey:#:0:#:contact_colour_theme", "4" => "5:#:".$jkl['cw37'].":#:animate__fadeIn,animate__flash,animate__pulse,animate__headShake,animate__slideInUp,animate__slideInRight:#:0:#:contact_animation");

} else {

// Check the vars for this start
$contact_animate = "animate__fadeIn";
$contact_custom = "";
$btn_custom = "blue";
$contact_logo = '<i class="fa fa fa-user-circle '.(isset($ismobile) && !empty($ismobile) ? 'fa-3x' : 'fa-4x').' jaklcb_popup_avatar" aria-hidden="true"></i>';

// We can have custom online icon
if (isset($widgetsettings['contact_animation']) && !empty($widgetsettings['contact_animation'])) {
  $contact_animate = $widgetsettings['contact_animation'];
}

// We can have custom online icon
if (isset($widgetsettings['contact_colour_theme']) && !empty($widgetsettings['contact_colour_theme']) && $widgetsettings['contact_colour_theme'] != "blue") {
  $contact_custom = " ".$widgetsettings['contact_colour_theme'];
  $btn_custom = $widgetsettings['contact_colour_theme'];
}

// We can have custom online icon
if (isset($widgetsettings['contact_custom_logo']) && !empty($widgetsettings['contact_custom_logo'])) {
  $contact_logo = '<img src="'.$widgetsettings['contact_custom_logo'].'" class="jaklcb_popup_avatar" alt="logo">';
}

// Let's get the header welcome message
$headermsg = '';
if (empty($headermsg)) {
  if (!empty($LC_ANSWERS) && is_array($LC_ANSWERS)) foreach ($LC_ANSWERS as $v) {

    $msgtype = 8;
    
    if ($v["msgtype"] == $msgtype && $v["lang"] == $BT_LANGUAGE && $v["department"] == 0) {
    
      $phold = array("%operator%","%client%","%email%");
      $replace   = array("", "", JAK_EMAIL);
      $headermsg = str_replace($phold, $replace, $v["message"]);
      
    }
    
  }
}

// Big or small
if (isset($page1) && $page1 == "big") {

  $livecode = '<div id="lccontainersize" class="jak_chatopen_panel'.(isset($ismobile) ? $ismobile : '').' animate__animated '.$contact_animate.'">
  <form id="lcjak_ajaxform" method="post" action="'.$_SERVER['REQUEST_URI'].'">
  <section class="jaklcb_panel left'.$contact_custom.'">
  <header class="jaklcb_panel_header">
  <aside style="flex:3">'.$contact_logo.'
  </aside>
  <aside style="flex:6">
  <h1>'.$jkl['g1'].'</h1>
  </aside>
  <aside style="flex:3;text-align:right;">
  <button class="jaklcb_minimize lcb_backtochat" onclick="lcjak_smallchat(\'slideOutDown\')" type="button"><i class="fa fa-window-restore" aria-hidden="true"></i></button>
  <button class="jaklcb_panelclose lcb_close" onclick="lcjak_closechat()" type="button"><i class="fa fa-times" aria-hidden="true"></i></button>
  </aside>
  </header>
  <main class="jaklcb_panel_main" id="lcjak_formfields" style="flex:1">
  <h2>'.$headermsg.'</h2>
  <div class="jaklcb_input">
    <label for="name">'.$jkl["g4"].'</label>
    <input id="name" name="name" type="text" class="lcjak_input" placeholder="'.$jkl["g4"].'">
  </div>
  <div class="jaklcb_input">
    <label for="email">'.$jkl["g5"].'</label>
    <input id="email" name="email" type="text" class="lcjak_input" placeholder="'.$jkl["g5"].'">
  </div>
  <div class="jaklcb_input">
    <label for="phone">'.$jkl["g49"].'</label>
    <input id="phone" name="phone" type="text" class="lcjak_input" placeholder="'.$jkl["g49"].'">
  </div>
  <div class="jaklcb_input">
    <label for="message">'.$jkl["g6"].'</label>
    <textarea id="message" name="message" placeholder="'.$jkl['g25'].'"></textarea>
  </div>
  '.(isset($jakwidget['dsgvo']) && !empty($jakwidget['dsgvo']) ? '<div class="jaklcb_input">
  <label>
  <input type="checkbox" name="dsgvo" id="dsgvo" value="1"> '.$jakwidget['dsgvo'].'
  </label>
  </div>' : '<input type="hidden" name="dsgvo" value="0">').'
  <button class="lcb_sendcontact jakbtn btn-simple btn-'.$btn_custom.'" onclick="lcjak_contactchat()" type="button" id="send_c_button"><i class="fa fa-paper-plane" id="send_contact" aria-hidden="true"></i> '.$jkl['g10'].'</button>
  </main>
  </section>
  <input type="hidden" name="mycustomfields" value="">
  </form>
  </div>';

} else {

  $livecode = '<div id="lccontainersize" class="jak_chatopen_sm'.(isset($ismobile) ? $ismobile : '').' animate__animated '.$contact_animate.'">
  <form id="lcjak_ajaxform" method="post" action="'.$_SERVER['REQUEST_URI'].'">
  <button class="jaklcb_sendcontact left lcb_sendcontact" type="button" onclick="lcjak_contactchat()" id="send_c_button">
  <i class="fa fa-paper-plane" id="send_contact" aria-hidden="true"></i>
  </button>
  <button class="jaklcb_close left lcb_close" type="button" onclick="lcjak_closechat()">
  <i class="fa fa-times fa-lg" aria-hidden="true"></i>
  </button>
  <section class="jaklcb_popup'.$contact_custom.'">
  <header class="jaklcb_popup_header">
  <aside style="flex:3">'.$contact_logo.'
  </aside>
  <aside style="flex:8">
  <h1>'.$jkl['g1'].'</h1>
  </aside>
  <aside style="flex:1">
  <button class="jaklcb_maximise lcb_max" onclick="lcjak_bigchat(\'slideOutUp\')" type="button"><i class="fa fa-window-maximize" aria-hidden="true"></i></button>
  </aside>
  </header>
  <main class="jaklcb_form_main" id="lcjak_formfields">
  <h2>'.$headermsg.'</h2>
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
    <input id="phone" name="phone" type="text" class="lcjak_input" placeholder="'.$jkl["g49"].'">
  </div>
  <div class="jaklcb_input">
    <label for="message">'.$jkl["g6"].'</label>
    <textarea id="message" name="message" placeholder="'.$jkl['g25'].'"></textarea>
  </div>
  '.(isset($jakwidget['dsgvo']) && !empty($jakwidget['dsgvo']) ? '<div class="jaklcb_input">
  <label>
  <input type="checkbox" name="dsgvo" id="dsgvo" value="1"> '.$jakwidget['dsgvo'].'
  </label>
  </div>' : '<input type="hidden" name="dsgvo" value="0">').'
  </main>
  <input type="hidden" name="mycustomfields" value="">
  </section></form></div>';

}

}

?>