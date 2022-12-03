<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0.4                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2022 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

// Check if the file is accessed only via index.php if not stop the script from running
if (!defined('JAK_PREVENT_ACCESS')) die('You cannot access this file directly.');

// Standard Vars
$sessLogged = false;
$ismobile = "";
$errors = $op_phones = array();
$depdirect = 0;
$opdirect = 0;
$customjs = "";

// Get the client browser
$ua = new Browser();
if ($ua->isMobile()) $ismobile = " ismobile";

// Now we check all the necessary stuff in one file and load the correct templates depend on sessions

// First we will need to know if the chat window is closed or open
if (isset($page1) && in_array($page1, array("open", "big", "profile", "bigprofile", "feedback", "bigfeedback", "contactform"))) {

  // Let's make sure to remove all unwanted sessions
  if (isset($page4) && is_numeric($page4) && isset($page5) && !empty($page5) && JAK_base::jakCheckSession($page4, $page5)) {

    // It looks like we are having a session
    $sessLogged = true;

  }

  // Now let's find out if we have a session to close the chat
  if ($sessLogged) {

    // Do we wont to stop the chat and with feedback
    if ($page1 == "feedback" || $page1 == "bigfeedback") {

      // Yes, but do we want to show the feedback
      if (isset($jakwidget["feedback_tpl"]) && !empty($jakwidget["feedback_tpl"])) {
        include_once APP_PATH.'lctemplate/'.$jakwidget['template'].'/tplblocks/feedback/'.$jakwidget["feedback_tpl"];

      // Ok there is no template we load the standard one
      } else {
        include_once APP_PATH.'lctemplate/'.$jakwidget['template'].'/tplblocks/feedback/small_big_bottom_right.php.php';
      }

    // Nope, we want to show the chat
    } else {

      if ($page1 == "profile" || $page1 == "bigprofile") {

        if (isset($jakwidget["profile_tpl"]) && !empty($jakwidget["profile_tpl"])) {
          include_once APP_PATH.'lctemplate/'.$jakwidget['template'].'/tplblocks/profile/'.$jakwidget["profile_tpl"];

        // Ok there is no template we load the standard one
        } else {
          include_once APP_PATH.'lctemplate/'.$jakwidget['template'].'/tplblocks/chat/small_big_bottom_right.php.php';
        }

      } else {

        if (isset($jakwidget["chat_tpl"]) && !empty($jakwidget["chat_tpl"])) {
          include_once APP_PATH.'lctemplate/'.$jakwidget['template'].'/tplblocks/chat/'.$jakwidget["chat_tpl"];

        // Ok there is no template we load the standard one
        } else {
          include_once APP_PATH.'lctemplate/'.$jakwidget['template'].'/tplblocks/chat/small_big_bottom_right.php';
        }

      }

    }

  } else {

    if ($online_op && $page1 != "contactform") {

      if (isset($jakwidget['start_tpl']) && !empty($jakwidget["start_tpl"])) {
        include_once APP_PATH.'lctemplate/'.$jakwidget['template'].'/tplblocks/start/'.$jakwidget["start_tpl"];

      // Ok there is no template we load the standard one
      } else {
        include_once APP_PATH.'lctemplate/'.$jakwidget['template'].'/tplblocks/start/small_big_bottom_right.php.php';
      }

      // Load the javascript file for the start
      if (!empty($wtplsett["startjs"])) $customjs = $wtplsett["startjs"];

    } else {

      if (isset($jakwidget["contact_tpl"]) && !empty($jakwidget["contact_tpl"])) {
        include_once APP_PATH.'lctemplate/'.$jakwidget['template'].'/tplblocks/contact/'.$jakwidget["contact_tpl"];

      // Ok there is no template we load the standard one
      } else {
        include_once APP_PATH.'lctemplate/'.$jakwidget['template'].'/tplblocks/contact/small_big_bottom_right.php.php';
      }

    }

  }

// Chat is closed we need to show for sure the opening button
} else {

  // We load the template chosen for this widget
  if (isset($jakwidget["btn_tpl"]) && !empty($jakwidget["btn_tpl"])) {
    include_once APP_PATH.'lctemplate/'.$jakwidget['template'].'/tplblocks/btn/'.$jakwidget["btn_tpl"];

  // Ok there is no template we load the standard one
  } else {
    include_once APP_PATH.'lctemplate/'.$jakwidget['template'].'/tplblocks/btn/icon_button_right.php';
  }

  // Are we online of offline
  if ($online_op) {
    $livecode = $livecode_online;
  } elseif ($jakwidget['hidewhenoff']) {
    $livecode = $livecode_hide;
  } else {
    $livecode = $livecode_offline;
  }

}

// start buffer
ob_start();

?>

<!DOCTYPE html>
<html lang="<?php echo $BT_LANGUAGE;?>">
<head>
	<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="Live Chat 3 - JAKWEB">
	<title><?php echo $jakwidget['title'].' - '.JAK_TITLE;?></title>
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<!-- The template Styles -->
	<?php include_once('lctemplate/'.$jakwidget['template'].'/style.php');?>
  <?php if (isset($wtplsett["customcss"]) && !empty($wtplsett["customcss"])) echo '<link rel="stylesheet" href="'.BASE_URL.'lctemplate/'.$jakwidget['template'].'/'.$wtplsett["customcss"].'" type="text/css">';?>
	
	<!-- Le fav and touch icons -->
	<link rel="shortcut icon" href="<?php echo BASE_URL;?>img/ico/favicon.ico">
	 
</head>
<body>

<?php 

// We have a linked chat
if (isset($_SESSION['islinked']) && $_SESSION['islinked'] == true) { ?>
  <div id="jaklcp-chat-container" style="position:fixed;z-index:9999;<?php echo $wtplsett["chatposition"];?>top:0;">
<?php }

echo $livecode;

if (isset($_SESSION['islinked']) && $_SESSION['islinked'] == true) {

?>

</div>

<?php } ?>

<script src="<?php echo BASE_URL;?>js/resizer.js?=<?php echo JAK_UPDATED;?>"></script>
<!-- The template Styles -->
<?php include_once('lctemplate/'.$jakwidget['template'].'/javascript.php');?>
<script>
  // The Widget ID
  var lcjakwidgetid = <?php echo $widgetid;?>;
  // The Base Url
  var base_url = '<?php echo BASE_URL;?>';
  var ls_sound = "<?php echo JAK_CLIENT_SOUND;?>";
  var cross_url = '*';
  var base_rewrite = '<?php echo JAK_USE_APACHE;?>';
  var onoff = '<?php echo ($online_op ? 'online' : 'offline');?>';
  // We will need to current status of the chat
  var lcjak_chatstatus = localStorage.setItem('lcjak_chatstatus', '<?php echo $page1;?>');
  var lcjak_onlinestatus = localStorage.setItem('lcjak_onlinestatus', onoff);
  var lcjak_lang = '<?php echo $BT_LANGUAGE;?>';
  // The Iframe Height/Width
  var contsize = document.getElementById('lccontainersize').getBoundingClientRect();
  // console.log(contsize);
  var cswidth = contsize.width;
  var csheight = contsize.height;
  iframe_resize(cswidth, csheight, "<?php echo jak_html_widget_css($wtplsett["chatposition"]);?>", cross_url);
  // We will need to refresh the last status
  if (!localStorage.getItem('lcjak_firstvisit')) localStorage.setItem('lcjak_firstvisit', <?php echo time();?>);
  if (!localStorage.getItem('lcjak_lastvisit')) localStorage.setItem('lcjak_lastvisit', <?php echo time();?>);
</script>
<?php // Load custom javascript
  if (isset($wtplsett["customjs"]) && !empty($wtplsett["customjs"])) echo '<script src="'.BASE_URL.'lctemplate/'.$jakwidget['template'].'/'.$wtplsett["customjs"].'"></script>';
?>
</body>
</html>

<?php
// flush buffer
ob_flush();
?>