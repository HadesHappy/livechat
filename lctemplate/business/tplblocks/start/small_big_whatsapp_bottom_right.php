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
$wtplsett["chatposition"] = "bottom:0;right:0;";
$wtplsett["customjs"] = "js/start.js";
$wtplsett["customcss"] = "";

// Only for the edit page in the operator panel
if (isset($page) && $page == "widget") {

  /* Leave a var empty if not in use or set to false */
  $startsett = array();

  $startsett["previewchat"] = "preview/whatsapp_start.jpg";

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

    $startsett["formoptions"] = array("1" => "1:#:".$jkl['cw24'].":#:0:#:0:#:start_custom_logo", "2" => "1:#:".$jkl['cw40'].":#:0:#:0:#:whatsapp_message", "3" => "5:#:".$jkl['cw22'].":#:blue,green,orange,red,pink,grey:#:0:#:start_colour_theme", "4" => "3:#:".$jkl['cw43'].":#:Yes,No:#:0:#:start_show_avatars", "5" => "5:#:".$jkl['cw23'].":#:animate__fadeIn,animate__flash,animate__pulse,animate__headShake,animate__slideInUp,animate__slideInRight:#:0:#:start_animation");

} else {

// Check the vars for this start
$start_animate = "animate__fadeIn";
$start_custom = "";
$start_logo = '<i class="fa fa-user-circle '.(isset($ismobile) && !empty($ismobile) ? 'fa-3x' : 'fa-4x').' jaklcb_popup_avatar" aria-hidden="true"></i>';

// We can have custom online icon
if (isset($widgetsettings['start_animation']) && !empty($widgetsettings['start_animation'])) {
  $start_animate = $widgetsettings['start_animation'];
}

// We can have custom online icon
if (isset($widgetsettings['start_colour_theme']) && !empty($widgetsettings['start_colour_theme']) && $widgetsettings['start_colour_theme'] != "blue") {
  $start_custom = " ".$widgetsettings['start_colour_theme'];
}

// We can have custom online icon
if (isset($widgetsettings['start_custom_logo']) && !empty($widgetsettings['start_custom_logo'])) {
  $start_logo = '<img src="'.$widgetsettings['start_custom_logo'].'" class="jaklcb_popup_avatar" alt="logo">';
}

// Let's get the header welcome message
$headermsg = '';
if (empty($headermsg)) {
  if (!empty($LC_ANSWERS) && is_array($LC_ANSWERS)) foreach ($LC_ANSWERS as $v) {

    $msgtype = 26;
    
    if ($v["msgtype"] == $msgtype && $v["lang"] == $BT_LANGUAGE && $v["department"] == 0) {
    
      $phold = array("%operator%","%client%","%email%");
      $replace   = array("", "", JAK_EMAIL);
      $headermsg = str_replace($phold, $replace, $v["message"]);
      
    }
    
  }
}

// Get all Operators that have WhatsApp
$online_oplist_whatsapp = online_operator_list_whatsapp($LC_DEPARTMENTS, $jakwidget['depid'], $jakwidget['opid']);

$op_wame = "";
if (isset($online_oplist_whatsapp) && !empty($online_oplist_whatsapp)) foreach ($online_oplist_whatsapp as $o) {

  $op_wame .= '<div class="whatsapp_list">
        <a href="'.(isset($ismobile) && !empty($ismobile) ? 'https://api.whatsapp.com/send?phone=' : 'https://web.whatsapp.com/send?phone=').$o["whatsappnumber"].(isset($widgetsettings["whatsapp_message"]) && !empty($widgetsettings["whatsapp_message"]) ? '&amp;text='.urlencode($widgetsettings["whatsapp_message"]) : "").'" data-number="'.$o["whatsappnumber"].'" data-auto-text="'.(isset($widgetsettings["whatsapp_message"]) && !empty($widgetsettings["whatsapp_message"]) ? $widgetsettings["whatsapp_message"] : "").'" target="_blank"><div class="wp_item avatar_wp">
          <img src="'.BASE_URL.JAK_FILES_DIRECTORY.'/'.$o["picture"].'" alt="'.$o["name"].'" width="60" class="wo_op_avatar">
          <img src="'.BASE_URL.'img/whatsapp_'.($o["isonline"] ? 'on' : 'off').'.png" alt="whatsapp_'.($o["isonline"] ? 'online' : 'offline').'" class="avatar_whatsapp">
        </div></a>
        <div class="wp_item whatsapp_body">
          <h4>'.$o["name"].'</h4>
          <p>'.$o["aboutme"].'</p>
          <p>'.($o["title"] ? sprintf($jkl["g63"], $o["title"]) : $jkl["g88"]).'</p>
        </div>
      </div>';

}

// We go big
if (isset($page1) && $page1 == "big") {

  $livecode = '<div id="lccontainersize" class="jak_chatopen_panel'.(isset($ismobile) ? $ismobile : '').' animate__animated '.$start_animate.'">
  <form id="lcjak_ajaxform" method="post" action="'.$_SERVER['REQUEST_URI'].'">
  <section class="jaklcb_panel'.$start_custom.'">
  <header class="jaklcb_panel_header">
  <aside style="flex:3">'.$start_logo.'
  </aside>
  <aside style="flex:6">
  <h1>'.$jakwidget['title'].'</h1>
  </aside>
  <aside style="flex:3;text-align:right;">
  <button class="jaklcb_minimize" onclick="lcjak_smallchat()" type="button"><i class="fa fa-window-restore" aria-hidden="true"></i></button>
  <button class="jaklcb_panelclose lcb_close" onclick="lcjak_closechat()" type="button"><i class="fa fa-times" aria-hidden="true"></i></button>
  </aside>
  </header>
  <main class="jaklcb_panel_main">
  <h2>'.$headermsg.'</h2>
  '.$op_wame.'
  </main>
  </section>
  <input type="hidden" name="start_name" value="">
  <input type="hidden" name="start_email" value="">
  </form>
  </div>';

} else {

  $livecode = '<div id="lccontainersize" class="jak_chatopen_sm'.(isset($ismobile) ? $ismobile : '').' animate__animated '.$start_animate.'">
  <button class="jaklcb_close lcb_close" onclick="lcjak_closechat()">
  <i class="fa fa-times fa-lg" aria-hidden="true"></i>
  </button>
  <form id="lcjak_ajaxform" method="post" action="'.$_SERVER['REQUEST_URI'].'">
  <section class="jaklcb_popup'.$start_custom.'">
  <header class="jaklcb_popup_header">
  <aside style="flex:3">'.$start_logo.'
  </aside>
  <aside style="flex:8">
  <h1>'.$jakwidget['title'].'</h1>
  </aside>
  <aside style="flex:1">
  <button class="jaklcb_maximise" onclick="lcjak_bigchat()" type="button"><i class="fa fa-window-maximize" aria-hidden="true"></i></button>
  </aside>
  </header>
  <main class="jaklcb_form_main">
  <h2>'.$headermsg.'</h2>
  '.$op_wame.'
  </main>
  </section>
  <input type="hidden" name="start_name" value="">
  <input type="hidden" name="start_email" value="">
  </form></div>';

}

}

?>