<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0.4                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2022 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

// Filter widget settings input
function jak_widget_settings($value) {
  return filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}

// Get the data per array for page,newsletter with limit
function jak_get_page_info($table,$limit = "") 
{
	global $jakdb;
	if (!empty($limit)) {
    	$datatable = $jakdb->select($table, "*", ["ORDER" => ["id" => "DESC"], "LIMIT" => $limit]);
    } else {
    	$datatable = $jakdb->select($table, "*", ["ORDER" => ["id" => "DESC"]]);
    }
        
    if (!empty($datatable)) return $datatable;
}

// Search for lang files in the admin folder, only choose .ini files.
function jak_get_lang_files() {

	// Get the language folder
	$langdir = '../lang/';
	
	if ($handle = opendir($langdir)) {
	
	    /* This is the correct way to loop over the directory. */
	    while (false !== ($file = readdir($handle))) {
	    $showlang = substr($file, strrpos($file, '.'));
	    if ($file != '.' && $file != '..' && $showlang == '.php') {
	    
	    	$getlang[] = substr($file, 0, -4);
	    
	    }
	    }
		return $getlang;
	    closedir($handle);
	}
}

// Search for lang files in the admin folder, only choose .ini files.
function jak_get_chat_templates() {

	// Get the chat template folder
	$packdir = '../'.'lctemplate/';

	return array_diff(scandir($packdir), array('..', '.', 'index.html', '.DS_Store'));
}

// Make the filename beautiful
function jak_tpl_name($tpl) {

	// First we remove the .php
	$tpl = substr($tpl, 0, -4);

	// Then we remove the _
	$tpl = str_replace("_", " ", $tpl);

	// Finally we make each first letter uppercase
	$tpl = ucwords($tpl);

	return $tpl;
}

// Get files
function jak_get_templates_files($directory) {

	foreach (glob($directory."*.php") as $file) {
	    $getTPL[] = basename($file);
	}

	if (!empty($getTPL)) return $getTPL; 
}

// Get avatar sets
function jak_get_avatar_sets($template) {

	// Get the avatar folder
	$packdir = '../'.'lctemplate/'.$template.'/avatar/';

	return array_diff(scandir($packdir), array('..', '.', 'index.html', '.DS_Store'));
}

// Get avatar images
function jak_get_avatar_images($template, $avatarset) {

	// Get the avatar folder
	$packdir = '../lctemplate/'.$template.'/avatar/'.$avatarset.'/';

	foreach (glob($packdir."*.jpg") as $file) {
	    $getAva[] = $file;
	}

	if (!empty($getAva)) return $getAva; 
}

// Search for lang files in the admin folder, only choose .ini files.
function jak_get_sound_files() {

	$getsound = array();
	
	// Get the general sounds
	$soundir = '../sound/';

	if ($handle = opendir($soundir)) {
	
	    /* This is the correct way to loop over the directory. */
	    while (false !== ($file = readdir($handle))) {
		    $showsound = substr($file, strrpos($file, '.'));
		    if ($file != '.' && $file != '..' && $showsound == '.mp3') {
		    
		    	$getsound[] = 'sound/'.substr($file, 0, -4);
		    
		    }
	    }
	    closedir($handle);
		return $getsound;
	    
	}
}

// Get all user out the database limited with the paginator
function jak_get_user_all($table, $userid, $supero) {

	global $jakdb;
	if ($userid && $supero) {
		$datausr = $jakdb->select($table, "*", ["OR" => ["id" => $userid, "id[!]" => $supero]]);
	} elseif ($userid) {
		$datausr = $jakdb->select($table, "*", ["id" => $userid]);
	} elseif ($supero) {
		$datausr = $jakdb->select($table, "*", ["id[!]" => $supero]);
	} else {
		$datausr = $jakdb->select($table, "*");
	}
	
    return $datausr;
}

// Check if user exist and it is possible to delete ## (config.php)
function jak_user_exist_deletable($id) {
	$useridarray = explode(',', JAK_SUPERADMIN);
	// check if userid is protected in the config.php
	if (in_array($id, $useridarray)) {
	    return false;
	} else {
		global $jakdb;
	    if ($jakdb->has("user", ["id" => $id])) return true;
	}
	return false;
}

// Check if row exist with id
function jak_field_not_exist_id($lsvar, $id, $table, $lsvar3)
{
	global $jakdb;
    if ($jakdb->has($table, ["AND" => ["id[!]" => $id, $lsvar3 => $lsvar]])) {
        return true;
	}
}

// Remove chat options because of the template
function jak_remove_chat_options($formtype, $widgetid)
{
	global $jakdb;
    if ($jakdb->delete("chatsettings", ["AND" => ["formtype" => $formtype, "widgetid" => $widgetid]])) {
        return true;
	}
}

// Get files
function jak_get_files($directory, $exempt = array('.','..','.ds_store','.svn','js','css','img','_cache','index.html'), &$files = array()) { 
	
	if ($handle = opendir($directory)) {
		$getlang = array();
	    /* This is the correct way to loop over the directory. */
	    while (false !== ($file = readdir($handle))) {
	    if (!in_array($file, $exempt)) {
	    
	    	$getlang[] = $file;
	    
	    }
	    }
		if (!empty($getlang)) return $getlang;
	    closedir($handle);
	}
}

function secondsToTime($seconds, $time) {
	$singletime = explode(",", $time);
	if (is_numeric($seconds)) {
    	$dtF = new DateTime("@0");
    	$dtT = new DateTime("@$seconds");
    	return $dtF->diff($dtT)->format('%a '.$singletime[0].', %h '.$singletime[1].', %i '.$singletime[2].' '.$singletime[4].' %s '.$singletime[3]);
    } else {
    	return '0 '.$singletime[0].', 0 '.$singletime[1].', 0 '.$singletime[2].' '.$singletime[4].' 0 '.$singletime[3];
    }
}

// Get the custom fields ready to serve
function jak_get_custom_fields($widgetid, $template, $formtype, $formfields, $buttons, $slideimg) {

    global $jakdb;
    $fields = $translations = '';

	if (isset($formfields) && !empty($formfields)) {

	    foreach ($formfields as $v) {

	    	// We explode each form
	    	$fvalue = explode(":#:", $v);

	    	// Get the correct fields
    		$formdata = $jakdb->get('chatsettings', ["lang", "settname", "settvalue"], ["AND" => ["widgetid" => $widgetid, "template" => $template, "formtype" => $formtype, "settname" => jak_clean_string($fvalue[4])]]);

	        if ($fvalue[0] == 3 || $fvalue[0] == 4 || $fvalue[0] == 5) {
	            $fieldoptions = explode(",", $fvalue[2]);
	            // Set translation to false because it does not exist
	            $tl = false;
	        }

	        // We will need a hidden input fields to find out what we need to save
	        $fields .= '<input type="hidden" name="chatsettings[]" value="'.jak_clean_string($fvalue[4]).':#:'.$formtype.'">';

            if ($fvalue[0] == 1) {
                // INPUT
                $fields .= '<div class="form-group">
                <label class="control-label" for="'.jak_clean_string($fvalue[4]).'">'.$fvalue[1].'</label>
                '.(isset($fvalue[2]) && $fvalue[2] == "icon" ? '<div class="input-group">' : '').'
                <input type="text" name="'.jak_clean_string($fvalue[4]).'" id="'.jak_clean_string($fvalue[4]).'" class="form-control'.(isset($fvalue[2]) && $fvalue[2] == "colour" ? ' colour_changer' : '').'" value="'.(isset($formdata["settname"]) && $formdata["settname"] == jak_clean_string($fvalue[4]) ? $formdata["settvalue"] : '').'">
            	'.(isset($fvalue[2]) && $fvalue[2] == "icon" ? '<div class="input-group-append"><span class="input-group-text"><a href="https://fontawesome.com/v5/search" target="_blank"><i class="fa fa-link"></i></a></span></div></div>' : '').'</div>';
            } elseif ($fvalue[0] == 2) {
                // TEXTAREA
                $fields .= '<div class="form-group">
                <label class="control-label" for="'.jak_clean_string($fvalue[4]).'">'.$fvalue[1].'</label>
                <textarea class="form-control" name="'.jak_clean_string($fvalue[4]).'">'.(isset($formdata["settname"]) && $formdata["settname"] == jak_clean_string($fvalue[4]) ? $formdata["settvalue"] : '').'</textarea></div>';
            } elseif ($fvalue[0] == 3) {
                // RADIO
                $fields .= '<div class="form-group"><label class="control-label" for="'.jak_clean_string($fvalue[4]).'">'.$fvalue[1].'</label>';
                if (isset($fieldoptions) && !empty($fieldoptions)) foreach ($fieldoptions as $k => $z) {
                    $value = ($tl ? $k : $z);
                    $fields .= '<div class="form-check">
                    <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="'.jak_clean_string($fvalue[4]).'" value="'.$value.'"'.(isset($formdata["settname"]) && $formdata["settname"] == jak_clean_string($fvalue[4]) && $formdata["settvalue"] == $value ? ' checked' : '').'> '.$z.'
                    <span class="circle">
                    <span class="check"></span>
                    </span>
                    </label>
                    </div>';
                }
                $fields .= '</div>';
            } elseif ($fvalue[0] == 4) {
                // CHECKBOX
                $fields .= '<div class="form-group"><label class="control-label" for="'.jak_clean_string($fvalue[4]).'">'.$fvalue[1].'</label>';
                if (isset($fieldoptions) && !empty($fieldoptions)) foreach ($fieldoptions as $k => $z) {
                    $value = ($tl ? $k : $z);
                    $fields .= '<div class="form-check">
                    <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" name="'.jak_clean_string($fvalue[4]).'[]" value="'.$value.'"'.(isset($formdata["settname"]) && $formdata["settname"] == jak_clean_string($fvalue[4]) && in_array($value, explode(',', $formdata["settvalue"])) ? ' checked' : '').'> '.$z.'
                    <span class="form-check-sign">
                    <span class="check"></span>
                    </span>
                    </label>
                    </div>';
                }
                $fields .= '</div>';
            } elseif ($fvalue[0] == 5) {
                // SELECT
                $fields .= '<div class="form-group"><label class="control-label" for="'.jak_clean_string($fvalue[4]).'">'.$fvalue[1].'</label><select name="'.jak_clean_string($fvalue[4]).'" class="selectpicker" data-style="select-with-transition" data-live-search="true">';
                if (isset($fieldoptions) && !empty($fieldoptions)) foreach ($fieldoptions as $k => $z) {
                	if ($z == "btn") {

                		if (isset($buttons) && is_array($buttons)) foreach($buttons as $k) { if (getimagesize(APP_PATH.JAK_FILES_DIRECTORY.'/buttons/'.$k) && strpos($k,"_on")) {
							$fields .= '<option value="'.$k.'"'.(isset($formdata["settname"]) && $formdata["settname"] == jak_clean_string($fvalue[4]) && $formdata["settvalue"] == $k ? ' selected="selected"' : '').'>'.$k.'</option>';
						} }

                	} elseif ($z == "slideimg") {

                		if (isset($slideimg) && is_array($slideimg)) foreach($slideimg as $k) { if (getimagesize(APP_PATH.JAK_FILES_DIRECTORY.'/slideimg/'.$k) && strpos($k,"_on")) {
							$fields .= '<option value="'.$k.'"'.(isset($formdata["settname"]) && $formdata["settname"] == jak_clean_string($fvalue[4]) && $formdata["settvalue"] == $k ? ' selected="selected"' : '').'>'.$k.'</option>';
						} }

                	} else {
                    	$value = ($tl ? $k : $z);
                    	$fields .= '<option value="'.$value.'"'.(isset($formdata["settname"]) && $formdata["settname"] == jak_clean_string($fvalue[4]) && $formdata["settvalue"] == $value ? ' selected' : '').'>'.$z.'</option>';
                    }
                }
                $fields .= '</select></div>';
            }
	        
	    }

	}

	return $fields;
}
?>