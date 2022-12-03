<?php include_once 'header.php';?>

<div class="content">

<?php if ($errors) { ?>
<div class="alert alert-danger">
<?php if (isset($errors["e"])) echo $errors["e"];
	  if (isset($errors["e1"])) echo $errors["e1"];
	  if (isset($errors["e2"])) echo $errors["e2"];
	  if (isset($errors["e3"])) echo $errors["e3"];
	  if (isset($errors["e4"])) echo $errors["e4"];
	  if (isset($errors["e5"])) echo $errors["e5"];
	  if (isset($errors["e6"])) echo $errors["e6"];
	  if (isset($errors["e7"])) echo $errors["e7"];?>
</div>
<?php } ?>

<form class="jak_form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">

<div class="row">
	<div class="col-md-6">

		<div class="card">
              <div class="card-header">
                <h3 class="card-title"><i class="fa fa-user"></i> <?php echo $jkl["g40"];?></h3>
              </div>
              <div class="card-body">

			<div class="form-group">
				<label for="name"><?php echo $jkl["u"];?></label>
				<input type="text" name="jak_name" id="name" class="form-control<?php if (isset($errors["e1"])) echo " is-invalid";?>" value="<?php echo $JAK_FORM_DATA["name"];?>">
			</div>

			<div class="form-group">
				<label for="email"><?php echo $jkl["u1"];?></label>
				<input type="text" name="jak_email" id="email" class="form-control<?php if (isset($errors["e2"])) echo " is-invalid";?>" value="<?php echo $JAK_FORM_DATA["email"];?>">
			</div>

			<div class="form-group">
				<label for="username"><?php echo $jkl["u2"];?></label>
				<input type="text" name="jak_username" id="username" class="form-control<?php if (isset($errors["e3"]) || isset($errors["e4"])) echo " is-invalid";?>" value="<?php echo $JAK_FORM_DATA["username"];?>"><input type="hidden" name="jak_username_old" value="<?php echo $JAK_FORM_DATA["username"];?>">
			</div>

			<div class="form-group">
				<label for="aboutme"><?php echo $jkl["u57"];?></label>
				<input type="text" name="jak_aboutme" id="aboutme" class="form-control" value="<?php echo $JAK_FORM_DATA["aboutme"];?>">
			</div>

			<div class="form-group">
				<label for="inv"><?php echo $jkl["u12"];?></label>
				<input type="text" name="jak_inv" id="inv" class="form-control" value="<?php echo $JAK_FORM_DATA["invitationmsg"];?>">
			</div>

			<div class="form-group">
            	<p><label><?php echo $jkl["g22"];?></label></p>
            	<select name="jak_lang" class="selectpicker" title="<?php echo $jkl["g22"];?>" data-size="4" data-live-search="true">
					<option value=""><?php echo $jkl["u11"];?></option>
					<?php if (isset($lang_files) && is_array($lang_files)) foreach($lang_files as $lf) { ?><option value="<?php echo $lf;?>"<?php if ($JAK_FORM_DATA["language"] == $lf) { ?> selected="selected"<?php } ?>><?php echo ucwords($lf);?></option><?php } ?>
				</select>
			</div>

			<h4 class="card-title"><?php echo $jkl["u10"];?></h4>
            <div class="form-group form-file-upload form-file-simple">
                <input type="text" class="form-control inputFileVisible" placeholder="<?php echo $jkl['g8'];?>">
                <input type="file" class="inputFileHidden" name="uploadpp" accept="image/*">
            </div>

            <div class="form-group">
                <div class="form-check">
                    <label class="form-check-label">
                    	<input class="form-check-input" type="checkbox" name="jak_delete_avatar">
                       	<span class="form-check-sign"></span> <?php echo $jkl["u46"];?>
                    </label>
                </div>
            </div>

            <h4 class="card-title"><?php echo $jkl["m32"];?></h4>
		<?php if (jak_get_access("usrmanage", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)) { ?>
			<label><?php echo $jkl["u43"];?></label>
			<div class="form-check form-check-radio">
            	<label class="form-check-label">
                	<input class="form-check-input" type="radio" name="jak_uolist" value="1"<?php if ($JAK_FORM_DATA["useronlinelist"] == 1) echo ' checked';?>>
                    <span class="form-check-sign"></span>
                    <?php echo $jkl["g19"];?>
                </label>
            </div>
            <div class="form-check form-check-radio">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="jak_uolist" value="0"<?php if ($JAK_FORM_DATA["useronlinelist"] == 0) echo ' checked';?>>
                    <span class="form-check-sign"></span>
                    <?php echo $jkl["g18"];?>
                </label>
            </div>
            <label><?php echo $jkl["u3"];?></label>
			<div class="form-check form-check-radio">
            	<label class="form-check-label">
                	<input class="form-check-input" type="radio" name="jak_access" value="1"<?php if ($JAK_FORM_DATA["access"] == 1) echo ' checked';?>>
                    <span class="form-check-sign"></span>
                    <?php echo $jkl["g19"];?>
                </label>
            </div>
            <div class="form-check form-check-radio">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="jak_access" value="0"<?php if ($JAK_FORM_DATA["access"] == 0) echo ' checked';?>>
                    <span class="form-check-sign"></span>
                    <?php echo $jkl["g18"];?>
                </label>
            </div>
            <label><?php echo $jkl["u6"];?></label>
			<div class="form-check form-check-radio">
            	<label class="form-check-label">
                	<input class="form-check-input" type="radio" name="jak_responses" value="1"<?php if ($JAK_FORM_DATA["responses"] == 1) echo ' checked';?>>
                    <span class="form-check-sign"></span>
                    <?php echo $jkl["g19"];?>
                </label>
            </div>
            <div class="form-check form-check-radio">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="jak_responses" value="0"<?php if ($JAK_FORM_DATA["responses"] == 0) echo ' checked';?>>
                    <span class="form-check-sign"></span>
                    <?php echo $jkl["g18"];?>
                </label>
            </div>

		<?php if (!$jakhs['hostactive']) { ?>
			<label><?php echo $jkl["u7"];?></label>
			<div class="form-check form-check-radio">
            	<label class="form-check-label">
                	<input class="form-check-input" type="radio" name="jak_files" value="1"<?php if ($JAK_FORM_DATA["files"] == 1) echo ' checked';?>>
                    <span class="form-check-sign"></span>
                    <?php echo $jkl["g19"];?>
                </label>
            </div>
            <div class="form-check form-check-radio">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="jak_files" value="0"<?php if ($JAK_FORM_DATA["files"] == 0) echo ' checked';?>>
                    <span class="form-check-sign"></span>
                    <?php echo $jkl["g18"];?>
                </label>
            </div>
            <label><?php echo $jkl["u13"];?></label>
			<div class="form-check form-check-radio">
            	<label class="form-check-label">
                	<input class="form-check-input" type="radio" name="jak_chat" value="1"<?php if ($JAK_FORM_DATA["operatorchat"] == 1) echo ' checked';?>>
                    <span class="form-check-sign"></span>
                    <?php echo $jkl["g19"];?>
                </label>
            </div>
            <div class="form-check form-check-radio">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="jak_chat" value="0"<?php if ($JAK_FORM_DATA["operatorchat"] == 0) echo ' checked';?>>
                    <span class="form-check-sign"></span>
                    <?php echo $jkl["g18"];?>
                </label>
            </div>
            <label><?php echo $jkl["u41"];?></label>
			<div class="form-check form-check-radio">
            	<label class="form-check-label">
                	<input class="form-check-input" type="radio" name="jak_chatpublic" value="1"<?php if ($JAK_FORM_DATA["operatorchatpublic"] == 1) echo ' checked';?>>
                    <span class="form-check-sign"></span>
                    <?php echo $jkl["g19"];?>
                </label>
            </div>
            <div class="form-check form-check-radio">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="jak_chatpublic" value="0"<?php if ($JAK_FORM_DATA["operatorchatpublic"] == 0) echo ' checked';?>>
                    <span class="form-check-sign"></span>
                    <?php echo $jkl["g18"];?>
                </label>
            </div>
		<?php } ?>
			<label><?php echo $jkl["g137"];?></label>
			<div class="form-check form-check-radio">
            	<label class="form-check-label">
                	<input class="form-check-input" type="radio" name="jak_chatlist" value="1"<?php if ($JAK_FORM_DATA["operatorlist"] == 1) echo ' checked';?>>
                    <span class="form-check-sign"></span>
                    <?php echo $jkl["g19"];?>
                </label>
            </div>
            <div class="form-check form-check-radio">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="jak_chatlist" value="0"<?php if ($JAK_FORM_DATA["operatorlist"] == 0) echo ' checked';?>>
                    <span class="form-check-sign"></span>
                    <?php echo $jkl["g18"];?>
                </label>
            </div>
            <label><?php echo $jkl["u45"];?></label>
			<div class="form-check form-check-radio">
            	<label class="form-check-label">
                	<input class="form-check-input" type="radio" name="jak_transfer" value="1"<?php if ($JAK_FORM_DATA["transferc"] == 1) echo ' checked';?>>
                    <span class="form-check-sign"></span>
                    <?php echo $jkl["g19"];?>
                </label>
            </div>
            <div class="form-check form-check-radio">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="jak_transfer" value="0"<?php if ($JAK_FORM_DATA["transferc"] == 0) echo ' checked';?>>
                    <span class="form-check-sign"></span>
                    <?php echo $jkl["g18"];?>
                </label>
            </div>
		<?php } ?>
			<label><?php echo $jkl["g239"];?></label>
			<div class="form-check form-check-radio">
            	<label class="form-check-label">
                	<input class="form-check-input" type="radio" name="jak_latency" value="3000"<?php if ($JAK_FORM_DATA["chat_latency"] == 3000) echo ' checked';?>>
                    <span class="form-check-sign"></span>
                    <?php echo $jkl["g240"];?>
                </label>
            </div>
            <div class="form-check form-check-radio">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="jak_latency" value="5000"<?php if ($JAK_FORM_DATA["chat_latency"] == 5000) echo ' checked';?>>
                    <span class="form-check-sign"></span>
                    <?php echo $jkl["g241"];?>
                </label>
            </div>

            <label><?php echo $jkl["u64"];?></label>
            <div class="form-check form-check-radio">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="jak_navsidebar" value="1"<?php if ($JAK_FORM_DATA["navsidebar"] == 1) echo ' checked';?>>
                    <span class="form-check-sign"></span>
                    <?php echo $jkl["g19"];?>
                </label>
            </div>
            <div class="form-check form-check-radio">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="jak_navsidebar" value="0"<?php if ($JAK_FORM_DATA["navsidebar"] == 0) echo ' checked';?>>
                    <span class="form-check-sign"></span>
                    <?php echo $jkl["g18"];?>
                </label>
            </div>

            <div class="form-group">
                <p><label><?php echo $jkl["u63"];?></label></p>
                <select name="jak_themecolour" class="selectpicker" title="<?php echo $jkl["u63"];?>" data-size="4" data-live-search="true">
                    <option value="blue"<?php if ($JAK_FORM_DATA["themecolour"] == 'blue') echo ' selected';?>><?php echo $jkl["u58"];?></option>
                    <option value="green"<?php if ($JAK_FORM_DATA["themecolour"] == 'green') echo ' selected';?>><?php echo $jkl["u59"];?></option>
                    <option value="orange"<?php if ($JAK_FORM_DATA["themecolour"] == 'orange') echo ' selected';?>><?php echo $jkl["u60"];?></option>
                    <option value="red"<?php if ($JAK_FORM_DATA["themecolour"] == 'red') echo ' selected';?>><?php echo $jkl["u61"];?></option>
                    <option value="yellow"<?php if ($JAK_FORM_DATA["themecolour"] == 'yellow') echo ' selected';?>><?php echo $jkl["u62"];?></option>
                </select>
            </div>

            <label><?php echo $jkl["g2"];?></label>
			<div class="form-check form-check-radio">
            	<label class="form-check-label">
                	<input class="form-check-input" type="radio" name="jak_sound" value="1"<?php if ($JAK_FORM_DATA["sound"] == 1) echo ' checked';?>>
                    <span class="form-check-sign"></span>
                    <?php echo $jkl["g19"];?>
                </label>
            </div>
            <div class="form-check form-check-radio">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="jak_sound" value="0"<?php if ($JAK_FORM_DATA["sound"] == 0) echo ' checked';?>>
                    <span class="form-check-sign"></span>
                    <?php echo $jkl["g18"];?>
                </label>
            </div>

            <div class="form-group">
                <p><label><?php echo $jkl["g200"];?></label></p>
    			<select name="jak_ringing" class="selectpicker" title="<?php echo $jkl["g200"];?>" data-size="4" data-live-search="true">
    				<option disabled><?php echo $jkl["g200"];?></option>
    				<?php for ($i = 1; $i < 30; $i++) {
    					if ($i == $JAK_FORM_DATA["ringing"]) {
    						echo '<option value="'.$i.'" selected>'.$i.'</option>';
    					} else {
    						echo '<option value="'.$i.'">'.$i.'</option>';
    					}
    				} ?>
    			</select>
			</div>

		</div>
		<div class="card-footer">
			<button type="submit" name="save" class="btn btn-primary form-submit"><?php echo $jkl["g38"];?></button>
		</div>
		</div>
		
	</div>
	<div class="col-md-6">

		<?php if (jak_get_access("usrmanage", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)) { ?>
		<div class="card">
		<div class="card-header">
		  <h3 class="card-title"><i class="fa fa-city"></i> <?php echo $jkl["u36"];?></h3>
		</div><!-- /.box-header -->
		<div class="card-body">

			<div class="form-group">
            <p><label><?php echo $jkl["u36"];?></label> <a href="javascript:void(0)" class="jakweb-help" data-content="<?php echo $jkl["h6"];?>" data-original-title="<?php echo $jkl["t"];?>"><i class="fa fa-question-circle"></i></a></p>
			
			<select name="jak_depid[]" multiple="multiple" class="selectpicker" data-size="4" data-live-search="true">
			
			<option value="0"<?php if ($JAK_FORM_DATA["departments"] == 0) { ?> selected="selected"<?php } ?>><?php echo $jkl["g105"];?></option>
			<?php if (isset($JAK_DEPARTMENTS) && is_array($JAK_DEPARTMENTS)) foreach($JAK_DEPARTMENTS as $z) { ?>
			
			<option value="<?php echo $z["id"];?>"<?php if (in_array($z["id"], explode(',', $JAK_FORM_DATA["departments"]))) echo ' selected';?>><?php echo $z["title"];?></option>
			
			<?php } ?>
			
			</select>
			</div>
			
		</div>
		<div class="card-footer">
			<button type="submit" name="save" class="btn btn-primary form-submit"><?php echo $jkl["g38"];?></button>
		</div>
		</div>
		<?php } ?>
		
		<div class="card">
		<div class="card-header with-border">
		  <h3 class="card-title"><i class="fa fa-key"></i> <?php echo $jkl["g39"];?></h3>
		</div><!-- /.box-header -->
		<div class="card-body">

			<div class="form-group">
				<label for="pass"><?php echo $jkl["u4"];?></label>
				<input type="password" name="jak_password" id="pass" class="form-control<?php if (isset($errors["e5"]) || isset($errors["e6"])) echo " is-invalid";?>">
			</div>

			<div class="form-group">
				<label for="passwc"><?php echo $jkl["u5"];?></label>
				<input type="password" name="jak_confirm_password" id="passwc" class="form-control<?php if (isset($errors["e5"]) || isset($errors["e6"])) echo " is-invalid";?>">
			</div>

			<div class="progress">
				<div id="jak_pstrength" class="progress-bar progress-bar-striped progress-bar-animated" style="width:0" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
			</div>

		</div>
		<div class="card-footer">
			<button type="submit" name="save" class="btn btn-primary form-submit"><?php echo $jkl["g38"];?></button>
		</div>
		</div>
		
		<?php if (jak_get_access("usrmanage", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)) { ?>
		<div class="card">
		<div class="card-header">
		  <h3 class="card-title"><i class="fa fa-user-tag"></i> <?php echo $jkl["u29"];?></h3>
		</div><!-- /.box-header -->
		<div class="card-body">
			
			<div class="form-check form-check-inline">
  				<label class="form-check-label">
    			<input class="form-check-input" type="checkbox" name="jak_roles[]" value="leads"<?php if (in_array("leads", explode(',', $JAK_FORM_DATA["permissions"]))) { ?> checked="checked"<?php } ?>>
    			<span class="form-check-sign"></span> <?php echo $jkl["u30"];?>
    			</label>
			</div>

			<div class="form-check form-check-inline">
  				<label class="form-check-label">
    			<input class="form-check-input" type="checkbox" name="jak_roles[]" value="leads_all"<?php if (in_array("leads_all", explode(',', $JAK_FORM_DATA["permissions"]))) { ?> checked="checked"<?php } ?>>
    			<span class="form-check-sign"></span> <?php echo $jkl["u30"].' ('.$jkl["g105"].')';?>
    			</label>
    		</div>

			<div class="form-check form-check-inline">
  				<label class="form-check-label">
    			<input class="form-check-input" type="checkbox" name="jak_roles[]" value="off_all"<?php if (in_array("off_all", explode(',', $JAK_FORM_DATA["permissions"]))) { ?> checked="checked"<?php } ?>>
    			<span class="form-check-sign"></span> <?php echo $jkl["g33"].' ('.$jkl["g105"].')';?>
    			</label>
    		</div>

			<div class="form-check form-check-inline">
  				<label class="form-check-label">
    			<input class="form-check-input" type="checkbox" name="jak_roles[]" value="ochat"<?php if (in_array("ochat", explode(',', $JAK_FORM_DATA["permissions"]))) { ?> checked="checked"<?php } ?>>
    			<span class="form-check-sign"></span> <?php echo $jkl["u31"];?>
    			</label>
    		</div>

			<div class="form-check form-check-inline">
  				<label class="form-check-label">
    			<input class="form-check-input" type="checkbox" name="jak_roles[]" value="ochat_all"<?php if (in_array("ochat_all", explode(',', $JAK_FORM_DATA["permissions"]))) { ?> checked="checked"<?php } ?>>
    			<span class="form-check-sign"></span> <?php echo $jkl["u31"].' ('.$jkl["g105"].')';?>
    			</label>
    		</div>

			<div class="form-check form-check-inline">
  				<label class="form-check-label">
    			<input class="form-check-input" type="checkbox" name="jak_roles[]" value="statistic"<?php if (in_array("statistic", explode(',', $JAK_FORM_DATA["permissions"]))) { ?> checked="checked"<?php } ?>>
    			<span class="form-check-sign"></span> <?php echo $jkl["u32"];?>
    			</label>
    		</div>

			<div class="form-check form-check-inline">
  				<label class="form-check-label">
    			<input class="form-check-input" type="checkbox" name="jak_roles[]" value="statistic_all"<?php if (in_array("statistic_all", explode(',', $JAK_FORM_DATA["permissions"]))) { ?> checked="checked"<?php } ?>>
    			<span class="form-check-sign"></span> <?php echo $jkl["u32"].' ('.$jkl["g105"].')';?>
    			</label>
    		</div>

			<div class="form-check form-check-inline">
  				<label class="form-check-label">
    			<input class="form-check-input" type="checkbox" name="jak_roles[]" value="files"<?php if (in_array("files", explode(',', $JAK_FORM_DATA["permissions"]))) { ?> checked="checked"<?php } ?>>
    			<span class="form-check-sign"></span> <?php echo $jkl["u33"];?>
    			</label>
    		</div>

			<div class="form-check form-check-inline">
  				<label class="form-check-label">
    			<input class="form-check-input" type="checkbox" name="jak_roles[]" value="proactive"<?php if (in_array("proactive", explode(',', $JAK_FORM_DATA["permissions"]))) { ?> checked="checked"<?php } ?>>
    			<span class="form-check-sign"></span> <?php echo $jkl["u34"];?>
    			</label>
    		</div>

			<div class="form-check form-check-inline">
  				<label class="form-check-label">
    			<input class="form-check-input" type="checkbox" name="jak_roles[]" value="usrmanage"<?php if (in_array("usrmanage", explode(',', $JAK_FORM_DATA["permissions"]))) { ?> checked="checked"<?php } ?>>
    			<span class="form-check-sign"></span> <?php echo $jkl["u42"];?>
    			</label>
    		</div>

			<div class="form-check form-check-inline">
  				<label class="form-check-label">
    			<input class="form-check-input" type="checkbox" name="jak_roles[]" value="responses"<?php if (in_array("responses", explode(',', $JAK_FORM_DATA["permissions"]))) { ?> checked="checked"<?php } ?>>
    			<span class="form-check-sign"></span> <?php echo $jkl["u35"];?>
    			</label>
    		</div>

			<div class="form-check form-check-inline">
  				<label class="form-check-label">
    			<input class="form-check-input" type="checkbox" name="jak_roles[]" value="departments"<?php if (in_array("departments", explode(',', $JAK_FORM_DATA["permissions"]))) { ?> checked="checked"<?php } ?>>
    			<span class="form-check-sign"></span> <?php echo $jkl["u36"];?>
    			</label>
    		</div>

			<div class="form-check form-check-inline">
  				<label class="form-check-label">
    			<input class="form-check-input" type="checkbox" name="jak_roles[]" value="settings"<?php if (in_array("settings", explode(',', $JAK_FORM_DATA["permissions"]))) { ?> checked="checked"<?php } ?>>
    			<span class="form-check-sign"></span> <?php echo $jkl["u37"];?>
    			</label>
    		</div>

			<div class="form-check form-check-inline">
  				<label class="form-check-label">
    			<input class="form-check-input" type="checkbox" name="jak_roles[]" value="maintenance"<?php if (in_array("maintenance", explode(',', $JAK_FORM_DATA["permissions"]))) { ?> checked="checked"<?php } ?>>
    			<span class="form-check-sign"></span> <?php echo $jkl["u38"];?>
    			</label>
    		</div>

			<div class="form-check form-check-inline">
  				<label class="form-check-label">
    			<input class="form-check-input" type="checkbox" name="jak_roles[]" value="logs"<?php if (in_array("logs", explode(',', $JAK_FORM_DATA["permissions"]))) { ?> checked="checked"<?php } ?>>
    			<span class="form-check-sign"></span> <?php echo $jkl["u39"];?>
    			</label>
    		</div>

			<div class="form-check form-check-inline">
  				<label class="form-check-label">
    			<input class="form-check-input" type="checkbox" name="jak_roles[]" value="answers"<?php if (in_array("answers", explode(',', $JAK_FORM_DATA["permissions"]))) { ?> checked="checked"<?php } ?>>
    			<span class="form-check-sign"></span> <?php echo $jkl["u44"];?>
    			</label>
    		</div>

			<div class="form-check form-check-inline">
  				<label class="form-check-label">
    			<input class="form-check-input" type="checkbox" name="jak_roles[]" value="widget"<?php if (in_array("widget", explode(',', $JAK_FORM_DATA["permissions"]))) { ?> checked="checked"<?php } ?>>
    			<span class="form-check-sign"></span> <?php echo $jkl["m26"];?>
    			</label>
    		</div>

			<div class="form-check form-check-inline">
  				<label class="form-check-label">
    			<input class="form-check-input" type="checkbox" name="jak_roles[]" value="groupchat"<?php if (in_array("groupchat", explode(',', $JAK_FORM_DATA["permissions"]))) { ?> checked="checked"<?php } ?>>
    			<span class="form-check-sign"></span> <?php echo $jkl["m29"];?>
    			</label>
    		</div>

			<div class="form-check form-check-inline">
  				<label class="form-check-label">
    			<input class="form-check-input" type="checkbox" name="jak_roles[]" value="blacklist"<?php if (in_array("blacklist", explode(',', $JAK_FORM_DATA["permissions"]))) { ?> checked="checked"<?php } ?>>
    			<span class="form-check-sign"></span> <?php echo $jkl["m27"];?>
    			</label>
    		</div>

			<div class="form-check form-check-inline">
  				<label class="form-check-label">
    			<input class="form-check-input" type="checkbox" name="jak_roles[]" value="blocklist"<?php if (in_array("blocklist", explode(',', $JAK_FORM_DATA["permissions"]))) { ?> checked="checked"<?php } ?>>
    			<span class="form-check-sign"></span> <?php echo $jkl["g97"];?>
    			</label>
    		</div>
			
		</div>
		<div class="card-footer">
			<button type="submit" name="save" class="btn btn-primary form-submit"><?php echo $jkl["g38"];?></button>
		</div>
		</div>
		<?php } ?>
		
		<div class="card">
		<div class="card-header">
		  <h3 class="card-title"><i class="fa fa-bells"></i> <?php echo $jkl["u17"];?></h3>
		</div><!-- /.box-header -->
		<div class="card-body">

			<label><?php echo $jkl["u25"];?></label>
			<div class="form-check form-check-radio">
            	<label class="form-check-label">
                	<input class="form-check-input" type="radio" name="jak_alwaysnot" value="1"<?php if ($JAK_FORM_DATA["alwaysnot"] == 1) echo ' checked';?>>
                    <span class="form-check-sign"></span>
                    <?php echo $jkl["g19"];?>
                </label>
            </div>
            <div class="form-check form-check-radio">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="jak_alwaysnot" value="0"<?php if ($JAK_FORM_DATA["alwaysnot"] == 0) echo ' checked';?>>
                    <span class="form-check-sign"></span>
                    <?php echo $jkl["g18"];?>
                </label>
            </div>

            <label><?php echo $jkl["g214"];?></label>
			<div class="form-check form-check-radio">
            	<label class="form-check-label">
                	<input class="form-check-input" type="radio" name="jak_emailnot" value="1"<?php if ($JAK_FORM_DATA["emailnot"] == 1) echo ' checked';?>>
                    <span class="form-check-sign"></span>
                    <?php echo $jkl["g19"];?>
                </label>
            </div>
            <div class="form-check form-check-radio">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="jak_emailnot" value="0"<?php if ($JAK_FORM_DATA["emailnot"] == 0) echo ' checked';?>>
                    <span class="form-check-sign"></span>
                    <?php echo $jkl["g18"];?>
                </label>
            </div>


				<div class="form-group">
					<label for="whatsphone"><?php echo $jkl["u53"];?></label>
					<input type="text" name="jak_whatsphone" id="whatsphone" class="form-control" value="<?php echo $JAK_FORM_DATA["whatsappnumber"];?>">
				</div>

		<?php if ((JAK_TW_SID && JAK_TW_TOKEN) || JAK_TWILIO_NEXMO == 3) { ?>

				<div class="form-group">
					<label for="phone"><?php echo $jkl["u14"];?></label>
					<input type="text" name="jak_phone" id="phone" class="form-control" value="<?php echo $JAK_FORM_DATA["phonenumber"];?>">
				</div>

		<?php } ?>
	
				<div class="form-group">
					<label for="pushot"><?php echo $jkl["u49"];?></label>
					<input type="text" name="jak_pushot" id="pushot" class="form-control" value="<?php echo $JAK_FORM_DATA["pusho_tok"];?>">
				</div>

				<div class="form-group">
					<label for="pushok"><?php echo $jkl["u50"];?></label>
					<input type="text" name="jak_pushok" id="pushok" class="form-control" value="<?php echo $JAK_FORM_DATA["pusho_key"];?>">
				</div>

                <p><a class="btn btn-mini btn-info" href="javascript:void(0)" onclick="dNotifyNew('<?php echo addslashes(JAK_TITLE);?>', '<?php echo addslashes($jkl['u26']);?>')"><?php echo $jkl["u26"];?></a></p>

                <label><?php echo $jkl["u65"];?></label>
                <div class="form-check form-check-radio">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="jak_alwaysonline" value="1"<?php if ($JAK_FORM_DATA["alwaysonline"] == 1) echo ' checked';?>>
                        <span class="form-check-sign"></span>
                        <?php echo $jkl["g19"];?>
                    </label>
                </div>
                <div class="form-check form-check-radio">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="jak_alwaysonline" value="0"<?php if ($JAK_FORM_DATA["alwaysonline"] == 0) echo ' checked';?>>
                        <span class="form-check-sign"></span>
                        <?php echo $jkl["g18"];?>
                    </label>
                </div>
                <span class="help-block"><?php echo $jkl["u66"];?></span>

                <hr>

				<h3><i class="fa fa-clock"></i> <?php echo $jkl["u15"];?></h3>
				<div id="bHoursM"></div>
				<div class="clearfix"></div>
                <hr>
				<span class="help-block"><?php echo $jkl["u47"];?></span>
				<input type="hidden" name="bhours" id="bhours" value="<?php echo $JAK_FORM_DATA["hours_array"];?>">
				<p><a href="<?php echo JAK_rewrite::jakParseurl('users', 'resethours', $page2);?>" class="btn btn-sm btn-danger"><?php echo $jkl['g229'];?></a></p>

		</div>
		<div class="card-footer">
			<button type="submit" name="save" class="btn btn-primary form-submit"><?php echo $jkl["g38"];?></button>
		</div>
		</div>

	</div>
</div>
</form>

</div>

<?php include_once 'footer.php';?>