<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0.4                 # ||
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
$wtplsett["customjs"] = "js/start.js";
$wtplsett["customcss"] = "";

// Only for the edit page in the operator panel
if (isset($page) && $page == "widget") {

  /* Leave a var empty if not in use or set to false */
  $startsett = array();

  $startsett["previewchat"] = "preview/operator_start.jpg";

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

    $startsett["formoptions"] = array("1" => "1:#:".$jkl['cw24'].":#:0:#:0:#:start_custom_logo", "2" => "3:#:".$jkl['cw38'].":#:Yes,No:#:0:#:start_email_required", "3" => "3:#:".$jkl['cw39'].":#:Yes,No:#:0:#:start_phone_required", "4" => "3:#:".$jkl['cw43'].":#:Yes,No:#:0:#:start_show_avatars", "5" => "5:#:".$jkl['cw22'].":#:blue,green,orange,red,pink,grey:#:0:#:start_colour_theme", "6" => "5:#:".$jkl['cw23'].":#:animate__fadeIn,animate__flash,animate__pulse,animate__headShake,animate__slideInUp,animate__slideInRight:#:0:#:start_animation");

} else {

// Check the vars for this start
$start_animate = "animate__fadeIn";
$start_custom = "";
$btn_custom = "blue";
$start_logo = '<i class="fa fa fa-user-circle '.(isset($ismobile) && !empty($ismobile) ? 'fa-3x' : 'fa-4x').' jaklcb_popup_avatar" aria-hidden="true"></i>';

// We can have custom online icon
if (isset($widgetsettings['start_animation']) && !empty($widgetsettings['start_animation'])) {
  $start_animate = $widgetsettings['start_animation'];
}

// We can have custom online icon
if (isset($widgetsettings['start_colour_theme']) && !empty($widgetsettings['start_colour_theme']) && $widgetsettings['start_colour_theme'] != "blue") {
  $start_custom = " ".$widgetsettings['start_colour_theme'];
  $btn_custom = $widgetsettings['start_colour_theme'];
}

// We can have custom online icon
if (isset($widgetsettings['start_custom_logo']) && !empty($widgetsettings['start_custom_logo'])) {
  $start_logo = '<img src="'.$widgetsettings['start_custom_logo'].'" class="jaklcb_popup_avatar" alt="logo">';
}

// Let's get the header welcome message
$headermsg = '';
if (empty($headermsg)) {
  if (!empty($LC_ANSWERS) && is_array($LC_ANSWERS)) foreach ($LC_ANSWERS as $v) {

    $msgtype = 14;
    
    if ($v["msgtype"] == $msgtype && $v["lang"] == $BT_LANGUAGE && $v["department"] == 0) {
    
      $phold = array("%operator%","%client%","%email%");
      $replace   = array("", "", JAK_EMAIL);
      $headermsg = str_replace($phold, $replace, $v["message"]);
      
    }
    
  }
}

// Let's get the operators
$online_oplist = online_operator_list($LC_DEPARTMENTS, $jakwidget['depid'], $jakwidget['opid']);

$op_list = "";
if (isset($online_oplist) && !empty($online_oplist)) foreach ($online_oplist as $o) {

  $op_list .= '<div class="operator_list">
  <div class="op_item avatar_op">
  <label>
  <input type="radio" name="start_opid_select" value="'.$o["id"].'">
  <img src="'.BASE_URL.JAK_FILES_DIRECTORY.'/'.$o["picture"].'" alt="'.$o["name"].'" width="60" class="wo_op_avatar">
  </label>
  </div>
  <div class="op_item oplist_body">
  <h4>'.$o["name"].'</h4>
  <p>'.$o["aboutme"].'</p>
  <p>'.($o["title"] ? sprintf($jkl["g63"], $o["title"]) : $jkl["g88"]).'</p>
  <p>'.($o['sum'] ? sprintf($jkl["g62"], $o['sum']) : '-').'</p>
  </div>
  </div>';

}

// We go big
if (isset($page1) && $page1 == "big") {

  $livecode = '<div id="lccontainersize" class="jak_chatopen_panel'.(isset($ismobile) ? $ismobile : '').' animate__animated '.$start_animate.'">
  <form id="lcjak_ajaxform" method="post" action="'.$_SERVER['REQUEST_URI'].'">
  <section class="jaklcb_panel left'.$start_custom.'">
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
  '.$op_list.'
  '.(isset($widgetsettings['start_show_avatars']) && $widgetsettings['start_show_avatars'] == "Yes" ? '
  <div class="avatars">
  <div class="ava_item">
  <label>
  <span><?php echo $jkl["g18"];?></span>
  <input type="radio" name="avatar" value="/lctemplate/business/avatar/'.$jakwidget['avatarset'].'/standard.jpg">
  <img src="'.BASE_URL.'lctemplate/business/avatar/'.$jakwidget['avatarset'].'/standard.jpg" width="50" alt="avatar">
  </label>
  </div>
  <div class="ava_item">
  <label>
  <span><?php echo $jkl["g18"];?></span>
  <input type="radio" name="avatar" value="/lctemplate/business/avatar/'.$jakwidget['avatarset'].'/1.jpg">
  <img src="'.BASE_URL.'lctemplate/business/avatar/'.$jakwidget['avatarset'].'/1.jpg" width="50" alt="avatar">
  </label>
  </div>
  <div class="ava_item">
  <label>
  <span><?php echo $jkl["g18"];?></span>
  <input type="radio" name="avatar" value="/lctemplate/business/avatar/'.$jakwidget['avatarset'].'/2.jpg">
  <img src="'.BASE_URL.'lctemplate/business/avatar/'.$jakwidget['avatarset'].'/2.jpg" width="50" alt="avatar">
  </label>
  </div>
  <div class="ava_item">
  <label>
  <span><?php echo $jkl["g18"];?></span>
  <input type="radio" name="avatar" value="/lctemplate/business/avatar/'.$jakwidget['avatarset'].'/3.jpg">
  <img src="'.BASE_URL.'lctemplate/business/avatar/'.$jakwidget['avatarset'].'/3.jpg" width="50" alt="avatar">
  </label>
  </div>
  <div class="ava_item">
  <label>
  <span><?php echo $jkl["g18"];?></span>
  <input type="radio" name="avatar" value="/lctemplate/business/avatar/'.$jakwidget['avatarset'].'/4.jpg">
  <img src="'.BASE_URL.'lctemplate/business/avatar/'.$jakwidget['avatarset'].'/4.jpg" width="50" alt="avatar">
  </label>
  </div>
  <div class="ava_item">
  <label>
  <span><?php echo $jkl["g18"];?></span>
  <input type="radio" name="avatar" value="/lctemplate/business/avatar/'.$jakwidget['avatarset'].'/5.jpg">
  <img src="'.BASE_URL.'lctemplate/business/avatar/'.$jakwidget['avatarset'].'/5.jpg" width="50" alt="avatar">
  </label>
  </div>
  </div>' : '').'
  <div class="jaklcb_input">
    <label for="start_name">'.$jkl["g4"].'</label>
    <input id="start_name" name="start_name" type="text" class="lcjak_input" placeholder="'.$jkl["g4"].'" value="'.(isset($_REQUEST["name"]) ? $_REQUEST["name"] : '').'">
  </div>
  <div class="jaklcb_input">
    <label for="start_email">'.$jkl["g5"].'</label>
    <input id="start_email" name="start_email" type="text" class="lcjak_input" placeholder="'.$jkl["g5"].'" value="'.(isset($_REQUEST["start_email"]) ? $_REQUEST["start_email"] : '').'">
  </div>
  <div class="jaklcb_input">
    <label for="start_phone">'.$jkl["g49"].'</label>
    <input id="start_phone" name="start_phone" type="text" class="lcjak_input" placeholder="'.$jkl["g49"].'" value="'.(isset($_REQUEST["start_phone"]) ? $_REQUEST["start_phone"] : '').'">
  </div>
  <div class="jaklcb_input">
    <label for="start_chat_msg">'.$jkl["g71"].'</label>
    <textarea id="start_chat_msg" name="start_chat_msg" placeholder="'.$jkl['g71'].'">'.(isset($_REQUEST["start_chat_msg"]) ? $_REQUEST["start_chat_msg"] : '').'</textarea>
  </div>
  '.(isset($jakwidget['dsgvo']) && !empty($jakwidget['dsgvo']) ? '<div class="jaklcb_input">
  <label>
  <input type="checkbox" name="dsgvo" id="dsgvo" value="1"> '.$jakwidget['dsgvo'].'
  </label>
  </div>' : '<input type="hidden" name="dsgvo" value="0">').'
  <button class="lcb_startchat jakbtn btn-simple btn-'.$btn_custom.'" onclick="lcjak_startChat()" type="button"><i class="fa fa-paper-plane" id="start_chat_btn" aria-hidden="true"></i> '.$jkl['g10'].'</button>
  </main>
  </section>
  <input type="hidden" name="mycustomfields" value="">
  </form>
  </div>';

} else {

  $livecode = '<div id="lccontainersize" class="jak_chatopen_sm'.(isset($ismobile) ? $ismobile : '').' animate__animated '.$start_animate.'">
  <button class="jaklcb_sendcontact left lcb_startchat" type="button" onclick="lcjak_startChat()">
  <i class="fa fa-paper-plane" id="start_chat_btn" aria-hidden="true"></i>
  </button>
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
  <main class="jaklcb_form_main">
  <h2>'.$headermsg.'</h2>
  '.$op_list.'
  '.(isset($widgetsettings['start_show_avatars']) && $widgetsettings['start_show_avatars'] == "Yes" ? '
  <div class="avatars">
  <div class="ava_item">
  <label>
  <span><?php echo $jkl["g18"];?></span>
  <input type="radio" name="avatar" value="/lctemplate/business/avatar/'.$jakwidget['avatarset'].'/standard.jpg">
  <img src="'.BASE_URL.'lctemplate/business/avatar/'.$jakwidget['avatarset'].'/standard.jpg" width="50" alt="avatar">
  </label>
  </div>
  <div class="ava_item">
  <label>
  <span><?php echo $jkl["g18"];?></span>
  <input type="radio" name="avatar" value="/lctemplate/business/avatar/'.$jakwidget['avatarset'].'/1.jpg">
  <img src="'.BASE_URL.'lctemplate/business/avatar/'.$jakwidget['avatarset'].'/1.jpg" width="50" alt="avatar">
  </label>
  </div>
  <div class="ava_item">
  <label>
  <span><?php echo $jkl["g18"];?></span>
  <input type="radio" name="avatar" value="/lctemplate/business/avatar/'.$jakwidget['avatarset'].'/2.jpg">
  <img src="'.BASE_URL.'lctemplate/business/avatar/'.$jakwidget['avatarset'].'/2.jpg" width="50" alt="avatar">
  </label>
  </div>
  <div class="ava_item">
  <label>
  <span><?php echo $jkl["g18"];?></span>
  <input type="radio" name="avatar" value="/lctemplate/business/avatar/'.$jakwidget['avatarset'].'/3.jpg">
  <img src="'.BASE_URL.'lctemplate/business/avatar/'.$jakwidget['avatarset'].'/3.jpg" width="50" alt="avatar">
  </label>
  </div>
  <div class="ava_item">
  <label>
  <span><?php echo $jkl["g18"];?></span>
  <input type="radio" name="avatar" value="/lctemplate/business/avatar/'.$jakwidget['avatarset'].'/4.jpg">
  <img src="'.BASE_URL.'lctemplate/business/avatar/'.$jakwidget['avatarset'].'/4.jpg" width="50" alt="avatar">
  </label>
  </div>
  <div class="ava_item">
  <label>
  <span><?php echo $jkl["g18"];?></span>
  <input type="radio" name="avatar" value="/lctemplate/business/avatar/'.$jakwidget['avatarset'].'/5.jpg">
  <img src="'.BASE_URL.'lctemplate/business/avatar/'.$jakwidget['avatarset'].'/5.jpg" width="50" alt="avatar">
  </label>
  </div>
  </div>' : '').'
  <div class="input_group">
  <div class="jaklcb_input">
    <label for="start_name">'.$jkl["g4"].'</label>
    <input id="start_name" name="start_name" type="text" class="lcjak_input" placeholder="'.$jkl["g4"].'" value="'.(isset($_REQUEST["start_name"]) ? $_REQUEST["start_name"] : '').'">
  </div>
  <div class="jaklcb_input flex-right">
    <label for="start_email">'.$jkl["g5"].'</label>
    <input id="start_email" name="start_email" type="text" class="lcjak_input" placeholder="'.$jkl["g5"].'" value="'.(isset($_REQUEST["start_email"]) ? $_REQUEST["start_email"] : '').'">
  </div>
  </div>
  <div class="jaklcb_input">
    <label for="start_phone">'.$jkl["g49"].'</label>
    <input id="start_phone" name="start_phone" type="text" class="lcjak_input" placeholder="'.$jkl["g49"].'" value="'.(isset($_REQUEST["start_phone"]) ? $_REQUEST["start_phone"] : '').'">
  </div>
  <div class="jaklcb_input">
    <label for="start_chat_msg">'.$jkl["g71"].'</label>
    <textarea id="start_chat_msg" name="start_chat_msg" placeholder="'.$jkl['g71'].'">'.(isset($_REQUEST["start_chat_msg"]) ? $_REQUEST["start_chat_msg"] : '').'</textarea>
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