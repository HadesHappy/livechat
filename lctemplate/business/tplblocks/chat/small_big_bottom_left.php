<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0.2                 # ||
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
$wtplsett["customjs"] = "js/chat.js";
$wtplsett["customcss"] = "";

// We only load when we need to receive messages
if (isset($lcdrm) && $lcdrm === true) {
  $wtplsett["operatormsg"] = '<div class="lc_item lc_operator" id="postid_'.$row['id'].'">
            <div class="lc_avatar"><img src="'.$avaimg.'" alt="'.$row['name'].'"></div>
            <div class="lc_message" id="msg'.$row['id'].'">'.($row['quoted'] ? '<blockquote class="blockquote"><i class="fa fa-reply"></i> '.$quotemsg.'</blockquote>' : '').stripcslashes($message).'</div>
            <div class="lc_status"><i class="far fa-clock"></i> '.JAK_base::jakTimesince($row['time'], "", JAK_TIMEFORMAT).'<span id="edited_'.$row['id'].'">'.($row['editoid'] ? ' | <i class="fa fa-edit"></i> '.JAK_base::jakTimesince($row['edited'], "", JAK_TIMEFORMAT) : '').'</span></div>
            </div>';
  $wtplsett["clientmsg"] = '<div class="lc_item lc_user" id="postid_'.$row['id'].'">
            <div class="lc_message" id="msg'.$row['id'].'">'.($row['quoted'] ? '<blockquote class="blockquote"><i class="fa fa-reply"></i> '.$quotemsg.'</blockquote>' : '').stripcslashes($message).'</div>
            <div class="lc_status"><i class="far fa-clock"></i> '.JAK_base::jakTimesince($row['time'], "", JAK_TIMEFORMAT).'<span id="edited_'.$row['id'].'">'.($row['editoid'] ? ' | <i class="fa fa-edit"></i> '.JAK_base::jakTimesince($row['edited'], "", JAK_TIMEFORMAT) : '').'</span></div>
            </div>';
  $wtplsett["infomsg"] = '<div class="lc_item lc_system" id="postid_'.$row['id'].'">
            <div class="lc_message" id="msg'.$row['id'].'">'.stripcslashes($message).'</div>
            <div class="lc_status"><i class="far fa-clock"></i> '.JAK_base::jakTimesince($row['time'], "", JAK_TIMEFORMAT).'</div>
            </div>';
  $wtplsett["download"] = '<div class="lc_item lc_download" id="postid_'.$row['id'].'">
            <div class="lc_message" id="msg'.$row['id'].'">'.stripcslashes($message).'</div>
            <div class="lc_status"><i class="far fa-clock"></i> '.JAK_base::jakTimesince($row['time'], "", JAK_TIMEFORMAT).'</div>
            </div>';
  $wtplsett["chatbot"] = '<div class="lc_item lc_bot" id="postid_'.(isset($lastid) ? $lastid : 0).'">
            <div class="lc_message" id="msg'.(isset($lastid) ? $lastid : 0).'">'.(isset($message) ? stripcslashes($message) : '').'</div>
            <div class="lc_status"><i class="far fa-clock"></i> '.JAK_base::jakTimesince(date('Y-m-d H:i:s'), "", JAK_TIMEFORMAT).'</div>
            </div>';
}

// We only load when we have new messages 
if (isset($lcdnm) && $lcdnm === true) {
  /* Chat Design bot */
  $wtplsett["chatbotinsert"] = '<div class="lc_item lc_bot" id="postid_'.(isset($lastidbot) ? $lastidbot : 0).'">
            <div class="lc_message" id="msg'.(isset($lastidbot) ? $lastidbot : 0).'">'.(isset($botdisp) ? stripcslashes($botdisp) : '').'</div>
            <div class="lc_status"><i class="far fa-clock"></i> '.JAK_base::jakTimesince(date('Y-m-d H:i:s'), "", JAK_TIMEFORMAT).'</div>
            </div>';

  /* Chat Design Insert */
  $wtplsett["chatinsert"] = '<div class="lc_item lc_user" id="postid_'.(isset($lastid) ? $lastid : 0).'">
            <div class="lc_message" id="groupmsg'.(isset($lastid) ? $lastid : 0).'"><span id="msg'.(isset($lastid) ? $lastid : 0).'">'.(isset($messagedisp) ? stripcslashes($messagedisp) : '').'</span></div>
            <div class="lc_status"><i class="far fa-clock"></i> '.JAK_base::jakTimesince(date('Y-m-d H:i:s'), "", JAK_TIMEFORMAT).'</div>
            </div>';

  /* Chat Design Insert ended */
  $wtplsett["chatinsertended"] = '<div class="lc_item lc_system" id="postid_'.(isset($lastid) ? $lastid : 0).'">
            <div class="lc_message" id="msg'.(isset($lastid) ? $lastid : 0).'">'.(isset($message) ? stripcslashes($message) : '').'</div>
            <div class="lc_status"><i class="far fa-clock"></i> '.JAK_base::jakTimesince(date('Y-m-d H:i:s'), "", JAK_TIMEFORMAT).'</div>
            </div>';
}

// Only for the edit page in the operator panel
if (isset($page) && $page == "widget") {

  /* Leave a var empty if not in use or set to false */
  $chatsett = array();

  $chatsett["previewchat"] = "preview/chat.jpg";

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

    $chatsett["formoptions"] = array("1" => "5:#:".$jkl['cw22'].":#:blue,green,orange,red,pink,grey:#:0:#:chat_colour_theme", "2" => "5:#:".$jkl['cw23'].":#:animate__fadeIn,animate__flash,animate__pulse,animate__headShake,animate__slideInUp,animate__slideInRight:#:0:#:chat_animation");

} else {

// Check the vars for this start
$chat_animate = "animate__fadeIn";
$chat_custom = "";
$chat_logo = '<i class="fa fa fa-user-circle '.(isset($ismobile) && !empty($ismobile) ? 'fa-3x' : 'fa-4x').' jaklcb_popup_avatar"></i>';

// We can have custom online icon
if (isset($widgetsettings['chat_animation']) && !empty($widgetsettings['chat_animation'])) {
  $chat_animate = $widgetsettings['chat_animation'];
}

// We can have custom online icon
if (isset($widgetsettings['chat_colour_theme']) && !empty($widgetsettings['chat_colour_theme']) && $widgetsettings['chat_colour_theme'] != "blue") {
  $chat_custom = " ".$widgetsettings['chat_colour_theme'];
}

// Let's get the header connecting message
$headermsg = $jkl['g59'];

if (isset($page1) && $page1 == "big") {

  $livecode = '<div id="lccontainersize" class="jak_chatopen_panel'.(isset($ismobile) ? $ismobile : '').' animate__animated '.$chat_animate.'">
  <form id="lcjak_ajaxform" method="post" action="'.$_SERVER['REQUEST_URI'].'">
  <section class="jaklcb_panel left'.$chat_custom.'">
  <header class="jaklcb_panel_header">
  <aside style="flex:3" id="jaklcb_oimage">'.$chat_logo.'
  </aside>
  <aside style="flex:5">
  <h1 id="jaklcb_oname">'.$jakwidget['title'].'</h1>
  <h5 id="jaklcb_oabout">'.$headermsg.'</h5>
  </aside>
  <aside style="flex:4;text-align:right;">
  <button class="jaklcb_minimize" onclick="lcjak_smallchat()" type="button"><i class="fa fa-window-restore"></i></button>
  <button class="jaklcb_profilesm lcb_profile" onclick="lcjak_profilebig()" type="button"><i class="far fa-id-card-alt"></i></button>
  <button class="jaklcb_endsm lcb_end" onclick="lcjak_endchatbig()" type="button"><i class="fas fa-power-off"></i></button>
  <button class="jaklcb_panelclose lcb_close" onclick="lcjak_closechat()" type="button"><i class="fa fa-times"></i></button>
  </aside>
  </header>
  <main class="jaklcb_panel_main" id="lc_messages">
  </main>
  <div id="lc_typing" class="lc_typing"><div class="dot-pulse"></div></div>
  <footer class="jaklcb_panel_footer">
  <aside class="jaklcb_start_extra">
  <i class="fa fa-camera dropzone animate__animated" id="cUploadDrop"></i>
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
  <textarea type="text" name="lc_chat_msg" id="lc_chat_msg" placeholder="'.$jkl["g60"].'"></textarea>
  </aside>
  <aside class="jaklcb_start_chat">
  <button id="lc_send_msg" type="submit"><i class="fa fa-paper-plane" id="lc_msg_load"></i></button>
  </aside>
  </footer>
  </section>
  <input type="hidden" name="allowedFiles" id="allowedFiles" value="'.JAK_ALLOWED_FILES.'">
  </form>
  </div>';

} else {

  $livecode = '<div id="lccontainersize" class="jak_chatopen_sm'.(isset($ismobile) ? $ismobile : '').' animate__animated '.$chat_animate.'">
  <button class="jaklcb_profile left lcb_profile" onclick="lcjak_profile()">
  <i class="far fa-id-card-alt"></i>
  </button>
  <button class="jaklcb_end left lcb_end" onclick="lcjak_endchat()">
  <i class="fas fa-power-off"></i>
  </button>
  <button class="jaklcb_close left lcb_close" onclick="lcjak_closechat()">
  <i class="fa fa-times"></i>
  </button>
  <form id="lcjak_ajaxform" method="post" action="'.$_SERVER['REQUEST_URI'].'">
  <section class="jaklcb_popup'.$chat_custom.'">
  <header class="jaklcb_popup_header">
  <aside style="flex:3" id="jaklcb_oimage">'.$chat_logo.'
  </aside>
  <aside style="flex:8">
  <h1 id="jaklcb_oname">'.$jakwidget['title'].'</h1>
  <h5 id="jaklcb_oabout">'.$headermsg.'</h5>
  </aside>
  <aside style="flex:1">
  <button class="jaklcb_maximise" onclick="lcjak_bigchat()" type="button"><i class="fa fa-window-maximize"></i></button>
  </aside>
  </header>
  <main class="jaklcb_popup_main" id="lc_messages">  
  </main>
  <div id="lc_typing" class="lc_typing"><div class="dot-pulse"></div></div>
  <footer class="jaklcb_popup_footer">
  <aside class="jaklcb_start_extra">
  <i class="fa fa-camera dropzone animate__animated" id="cUploadDrop"></i>
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
  <textarea type="text" name="lc_chat_msg" id="lc_chat_msg" placeholder="'.$jkl["g60"].'"></textarea>
  </aside>
  <aside class="jaklcb_start_chat">
  <button id="lc_send_msg" type="submit"><i class="fa fa-paper-plane" id="lc_msg_load"></i></button>
  </aside>
  </footer>
  </section>
  <input type="hidden" name="allowedFiles" id="allowedFiles" value="'.JAK_ALLOWED_FILES.'">
  </form>
  </div>';

}

}

?>