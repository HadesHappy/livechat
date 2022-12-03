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
$wtplsett["customjs"] = "js/quickstart.js";
$wtplsett["customcss"] = "";

// Only for the edit page in the operator panel
if (isset($page) && $page == "widget") {

  /* Leave a var empty if not in use or set to false */
  $startsett = array();

  $startsett["previewchat"] = "preview/quick_start.jpg";

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

    $startsett["formoptions"] = array("1" => "2:#:".$jkl['cw21'].":#:0:#:0:#:start_welcome_text", "2" => "1:#:".$jkl['cw24'].":#:0:#:0:#:start_custom_logo", "3" => "5:#:".$jkl['cw22'].":#:blue,green,orange,red,pink,grey:#:0:#:start_colour_theme", "4" => "5:#:".$jkl['cw23'].":#:animate__fadeIn,animate__flash,animate__pulse,animate__headShake,animate__slideInUp,animate__slideInRight:#:0:#:start_animation");

} else {

// Check the vars for this start
$start_animate = "animate__fadeIn";
$start_custom = "";
$start_logo = '<i class="fa fa fa-user-circle '.(isset($ismobile) && !empty($ismobile) ? 'fa-3x' : 'fa-4x').' jaklcb_popup_avatar" aria-hidden="true"></i>';

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

    $msgtype = 10;
    
    if ($v["msgtype"] == $msgtype && $v["lang"] == $BT_LANGUAGE && $v["department"] == 0) {
    
      $phold = array("%operator%","%client%","%email%");
      $replace   = array("", "", JAK_EMAIL);
      $headermsg = str_replace($phold, $replace, $v["message"]);
      
    }
    
  }
}

// We go big
if (isset($page1) && $page1 == "big") {

  $livecode = '<div id="lccontainersize" class="jak_chatopen_panel'.(isset($ismobile) ? $ismobile : '').' animate__animated '.$start_animate.'">
  <form id="lcjak_ajaxform" method="post" action="'.$_SERVER['REQUEST_URI'].'">
  <section class="jaklcb_panel left'.$start_custom.'">
  <header class="jaklcb_panel_header">
  <aside style="flex:3">'.$start_logo.'
  </aside>
  <aside style="flex:7">
  <h1>'.$jakwidget['title'].'</h1>
  </aside>
  <aside style="flex:2;text-align:right;">
  <button class="jaklcb_minimize" onclick="lcjak_smallchat()" type="button"><i class="fa fa-window-restore" aria-hidden="true"></i></button>
  <button class="jaklcb_panelclose lcb_close" onclick="lcjak_closechat()" type="button"><i class="fa fa-times" aria-hidden="true"></i></button>
  </aside>
  </header>
  <main class="jaklcb_panel_main" style="flex:1">
  <h3>'.$headermsg.'</h3>
  '.(isset($widgetsettings["start_welcome_text"]) && !empty($widgetsettings["start_welcome_text"]) ? '<p>'.$widgetsettings["start_welcome_text"].'</p>' : '').'
  </main>
  <footer class="jaklcb_panel_footer">
  <aside class="jaklcb_start_extra">
  <i class="fa fa-camera" aria-hidden="true"></i>
  <i class="fa fa-smile emoticons" id="emoticons"></i>
  <div class="emoticons_btn animate__animated" id="emoticons_btn">
  <span class="icon1"><i class="fa fa-smile fa-lg" onclick="sendEmo(\':slight_smile:\')"></i></span>
  <span class="icon2"><i class="fa fa-laugh fa-lg" onclick="sendEmo(\':smile:\')"></i></span>
  <span class="icon3"><i class="fa fa-smile-wink fa-lg" onclick="sendEmo(\':wink:\')"></i></span>
  <span class="icon4"><i class="fa fa-frown fa-lg" onclick="sendEmo(\':frowning:\')"></i></span>
  <span class="icon5"><i class="fa fa-sad-tear fa-lg" onclick="sendEmo(\':cry:\')"></i></span>
  <span class="icon6"><i class="fa fa-heart fa-lg" onclick="sendEmo(\':heart:\')"></i></span>
  </div>
  </aside>
  <aside class="jaklcb_start_message">
  <textarea type="text" name="quickstart_chat_msg" id="quickstart_chat_msg" placeholder="'.$jkl["g71"].'" autofocus></textarea>
  </aside>
  <aside class="jaklcb_start_chat">
  <button id="start_chat_btn" type="submit"><i class="fa fa-paper-plane" id="start_chat_load" aria-hidden="true"></i></button>
  </aside>
  </footer>
  </section>
  <input type="hidden" name="start_name" value="">
  <input type="hidden" name="start_email" value="">
  </form>
  </div>';

} else {

  $livecode = '<div id="lccontainersize" class="jak_chatopen_sm'.(isset($ismobile) ? $ismobile : '').' animate__animated '.$start_animate.'">
  <button class="jaklcb_close left lcb_close" onclick="lcjak_closechat()">
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
  <main class="jaklcb_popup_main">
  <h3>'.$headermsg.'</h3>
  '.(isset($widgetsettings["start_welcome_text"]) && !empty($widgetsettings["start_welcome_text"]) ? '<p>'.$widgetsettings["start_welcome_text"].'</p>' : '').'
  </main>
  <footer class="jaklcb_popup_footer">
  <aside class="jaklcb_start_extra">
  <i class="fa fa-camera" aria-hidden="true"></i>
  <i class="fa fa-smile emoticons" id="emoticons"></i>
  <div class="emoticons_btn animate__animated" id="emoticons_btn">
  <span class="icon1"><i class="fa fa-smile fa-lg" onclick="sendEmo(\':slight_smile:\')"></i></span>
  <span class="icon2"><i class="fa fa-laugh fa-lg" onclick="sendEmo(\':smile:\')"></i></span>
  <span class="icon3"><i class="fa fa-smile-wink fa-lg" onclick="sendEmo(\':wink:\')"></i></span>
  <span class="icon4"><i class="fa fa-frown fa-lg" onclick="sendEmo(\':frowning:\')"></i></span>
  <span class="icon5"><i class="fa fa-sad-tear fa-lg" onclick="sendEmo(\':cry:\')"></i></span>
  <span class="icon6"><i class="fa fa-heart fa-lg" onclick="sendEmo(\':heart:\')"></i></span>
  </div>
  </aside>
  <aside class="jaklcb_start_message">
  <textarea type="text" name="quickstart_chat_msg" id="quickstart_chat_msg" placeholder="'.$jkl["g71"].'" autofocus></textarea>
  </aside>
  <aside class="jaklcb_start_chat">
  <button id="start_chat_btn" type="submit"><i class="fa fa-paper-plane" id="start_chat_load" aria-hidden="true"></i></button>
  </aside>
  </footer>
  </section>
  <input type="hidden" name="start_name" value="">
  <input type="hidden" name="start_email" value="">
  </form></div>';

}

}

?>