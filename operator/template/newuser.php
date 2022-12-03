<?php include_once 'header.php';?>

<div class="content">

<?php if ($errors) { ?>
<div class="alert alert-danger"><?php if (isset($errors["e"])) echo $errors["e"];
	  if (isset($errors["e1"])) echo $errors["e1"];
	  if (isset($errors["e2"])) echo $errors["e2"];
	  if (isset($errors["e3"])) echo $errors["e3"];
	  if (isset($errors["e4"])) echo $errors["e4"];
	  if (isset($errors["e5"])) echo $errors["e5"];
	  if (isset($errors["e6"])) echo $errors["e6"];?></div>
<?php } ?>
<form class="jak_form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
<div class="row">
	<div class="col-md-6">

	<div class="card">
	<div class="card-header">
	  <h3 class="card-title"><i class="fa fa-user"></i> <?php echo $jkl["g40"];?></h3>
	</div><!-- /.box-header -->
	<div class="card-body">

		<div class="form-group">
			<label for="name"><?php echo $jkl["u"];?></label>
			<input type="text" name="jak_name" id="name" class="form-control<?php if (isset($errors["e1"])) echo " is-invalid";?>" value="<?php if (isset($_REQUEST["jak_name"])) echo $_REQUEST["jak_name"];?>">
		</div>

		<div class="form-group">
			<label for="email"><?php echo $jkl["u1"];?></label>
			<input type="text" name="jak_email" id="email" class="form-control<?php if (isset($errors["e2"])) echo " is-invalid";?>" value="<?php if (isset($_REQUEST["jak_email"])) echo $_REQUEST["jak_email"];?>">
		</div>

		<div class="form-group">
			<label for="username"><?php echo $jkl["u2"];?></label>
			<input type="text" name="jak_username" id="username" class="form-control<?php if (isset($errors["e3"]) || isset($errors["e4"])) echo " is-invalid";?>" value="<?php if (isset($_REQUEST["jak_username"])) echo $_REQUEST["jak_username"];?>">
		</div>

		<div class="form-group">
			<label for="inv"><?php echo $jkl["u12"];?></label>
			<input type="text" name="jak_inv" id="inv" class="form-control<?php if (isset($errors["e1"])) echo " is-invalid";?>" value="<?php if (isset($_REQUEST["jak_inv"])) echo $_REQUEST["jak_inv"]; ?>">
		</div>

		<div class="form-group">
            <p><label><?php echo $jkl["g22"];?></label></p>
            <select name="jak_lang" class="selectpicker" title="<?php echo $jkl["g22"];?>" data-size="4" data-live-search="true">
				<option value=""><?php echo $jkl["u11"];?></option>
				<?php if (isset($lang_files) && is_array($lang_files)) foreach($lang_files as $lf) { ?><option value="<?php echo $lf;?>"><?php echo ucwords($lf);?></option><?php } ?>
			</select>
		</div>

		<label><?php echo $jkl["u3"];?></label>
		<div class="form-check form-check-radio">
           	<label class="form-check-label">
              	<input class="form-check-input" type="radio" name="jak_access" value="1" checked>
                <span class="form-check-sign"></span>
                <?php echo $jkl["g19"];?>
            </label>
        </div>
        <div class="form-check form-check-radio">
       	    <label class="form-check-label">
               	<input class="form-check-input" type="radio" name="jak_access" value="0">
                <span class="form-check-sign"></span>
                <?php echo $jkl["g18"];?>
            </label>
        </div>

		<label><?php echo $jkl["u43"];?></label>
		<div class="form-check form-check-radio">
           	<label class="form-check-label">
              	<input class="form-check-input" type="radio" name="jak_uolist" value="1" checked>
                <span class="form-check-sign"></span>
                <?php echo $jkl["g19"];?>
            </label>
        </div>
        <div class="form-check form-check-radio">
       	    <label class="form-check-label">
               	<input class="form-check-input" type="radio" name="jak_uolist" value="0">
                <span class="form-check-sign"></span>
                <?php echo $jkl["g18"];?>
            </label>
        </div>

        <label><?php echo $jkl["u6"];?></label>
		<div class="form-check form-check-radio">
           	<label class="form-check-label">
              	<input class="form-check-input" type="radio" name="jak_responses" value="1" checked>
                <span class="form-check-sign"></span>
                <?php echo $jkl["g19"];?>
            </label>
        </div>
        <div class="form-check form-check-radio">
       	    <label class="form-check-label">
               	<input class="form-check-input" type="radio" name="jak_responses" value="0">
                <span class="form-check-sign"></span>
                <?php echo $jkl["g18"];?>
            </label>
        </div>

	<?php if (!$jakhs['hostactive']) { ?>

		<label><?php echo $jkl["u7"];?></label>
		<div class="form-check form-check-radio">
           	<label class="form-check-label">
              	<input class="form-check-input" type="radio" name="jak_files" value="1" checked>
                <span class="form-check-sign"></span>
                <?php echo $jkl["g19"];?>
            </label>
        </div>
        <div class="form-check form-check-radio">
       	    <label class="form-check-label">
               	<input class="form-check-input" type="radio" name="jak_files" value="0">
                <span class="form-check-sign"></span>
                <?php echo $jkl["g18"];?>
            </label>
        </div>

        <label><?php echo $jkl["u13"];?></label>
		<div class="form-check form-check-radio">
           	<label class="form-check-label">
              	<input class="form-check-input" type="radio" name="jak_chat" value="1" checked>
                <span class="form-check-sign"></span>
                <?php echo $jkl["g19"];?>
            </label>
        </div>
        <div class="form-check form-check-radio">
       	    <label class="form-check-label">
               	<input class="form-check-input" type="radio" name="jak_chat" value="0">
                <span class="form-check-sign"></span>
                <?php echo $jkl["g18"];?>
            </label>
        </div>

        <label><?php echo $jkl["u41"];?></label>
		<div class="form-check form-check-radio">
           	<label class="form-check-label">
              	<input class="form-check-input" type="radio" name="jak_chatpublic" value="1" checked>
                <span class="form-check-sign"></span>
                <?php echo $jkl["g19"];?>
            </label>
        </div>
        <div class="form-check form-check-radio">
       	    <label class="form-check-label">
               	<input class="form-check-input" type="radio" name="jak_chatpublic" value="0">
                <span class="form-check-sign"></span>
                <?php echo $jkl["g18"];?>
            </label>
        </div>

	<?php } ?>

		<label><?php echo $jkl["g137"];?></label>
		<div class="form-check form-check-radio">
           	<label class="form-check-label">
              	<input class="form-check-input" type="radio" name="jak_chatlist" value="1" checked>
                <span class="form-check-sign"></span>
                <?php echo $jkl["g19"];?>
            </label>
        </div>
        <div class="form-check form-check-radio">
       	    <label class="form-check-label">
               	<input class="form-check-input" type="radio" name="jak_chatlist" value="0">
                <span class="form-check-sign"></span>
                <?php echo $jkl["g18"];?>
            </label>
        </div>

        <label><?php echo $jkl["u45"];?></label>
		<div class="form-check form-check-radio">
           	<label class="form-check-label">
              	<input class="form-check-input" type="radio" name="jak_transfer" value="1" checked>
                <span class="form-check-sign"></span>
                <?php echo $jkl["g19"];?>
            </label>
        </div>
        <div class="form-check form-check-radio">
       	    <label class="form-check-label">
               	<input class="form-check-input" type="radio" name="jak_transfer" value="0">
                <span class="form-check-sign"></span>
                <?php echo $jkl["g18"];?>
            </label>
        </div>

        <label><?php echo $jkl["u45"];?></label>
		<div class="form-check form-check-radio">
           	<label class="form-check-label">
              	<input class="form-check-input" type="radio" name="jak_latency" value="3000" checked>
                <span class="form-check-sign"></span>
                <?php echo $jkl["g240"];?>
            </label>
        </div>
        <div class="form-check form-check-radio">
       	    <label class="form-check-label">
               	<input class="form-check-input" type="radio" name="jak_latency" value="5000">
                <span class="form-check-sign"></span>
                <?php echo $jkl["g241"];?>
            </label>
        </div>

        <label><?php echo $jkl["g2"];?></label>
		<div class="form-check form-check-radio">
           	<label class="form-check-label">
              	<input class="form-check-input" type="radio" name="jak_sound" value="1" checked>
                <span class="form-check-sign"></span>
                <?php echo $jkl["g19"];?>
            </label>
        </div>
        <div class="form-check form-check-radio">
       	    <label class="form-check-label">
               	<input class="form-check-input" type="radio" name="jak_sound" value="0">
                <span class="form-check-sign"></span>
                <?php echo $jkl["g18"];?>
            </label>
        </div>

		<div class="form-group">
            <p><label><?php echo $jkl["g200"];?></label></p>
    		<select name="jak_ringing" class="selectpicker" title="<?php echo $jkl["g200"];?>" data-size="4">
    			<option disabled><?php echo $jkl["g200"];?></option>
    			<?php for ($i = 1; $i < 30; $i++) {
				echo '<option value="'.$i.'">'.$i.'</option>';
				} ?>
    		</select>
		</div>

	</div>
	<div class="card-footer">
		<button type="submit" name="save" class="btn btn-primary"><?php echo $jkl["g38"];?></button>
	</div>
	</div>
	
	</div>
	<div class="col-md-6">
			<div class="card">
				<div class="card-header">
				  <h3 class="card-title"><i class="fa fa-city"></i> <?php echo $jkl["u36"];?></h3>
				</div><!-- /.box-header -->
				<div class="card-body">

					<div class="form-group">
			            <p><label><?php echo $jkl["u36"];?></label> <a href="javascript:void(0)" class="jakweb-help" data-content="<?php echo $jkl["h6"];?>" data-original-title="<?php echo $jkl["t"];?>"><i class="fa fa-question-circle"></i></a></p>
						
						<select name="jak_depid[]" multiple="multiple" class="selectpicker" data-size="4" data-live-search="true">
						
							<option value="0"<?php if (isset($_REQUEST["jak_depid"]) && $_REQUEST["jak_depid"] == '0') { ?> selected="selected"<?php } ?>><?php echo $jkl["g105"];?></option>
						
							<?php if (isset($LC_DEPARTMENTS) && is_array($LC_DEPARTMENTS)) foreach($LC_DEPARTMENTS as $v) { ?>
								
							<option value="<?php echo $v["id"];?>"><?php echo $v["title"];?></option>
								
							<?php } ?>
						
						</select>
					</div>
				
				</div>
				<div class="card-footer">
					<button type="submit" name="save" class="btn btn-primary"><?php echo $jkl["g38"];?></button>
				</div>
			</div>
			
			<div class="card">
				<div class="card-header">
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
					<button type="submit" name="save" class="btn btn-primary"><?php echo $jkl["g38"];?></button>
				</div>
			</div>
			
			<div class="card">
				<div class="card-header">
					<h3 class="card-title"><i class="fa fa-user-tag"></i> <?php echo $jkl["u29"];?></h3>
				</div><!-- /.box-header -->
				<div class="card-body">
					
					<div class="form-check form-check-inline">
						<label class="form-check-label">
							<input class="form-check-input" type="checkbox" name="jak_roles[]" value="leads"<?php if (isset($_REQUEST["jak_roles"]) && in_array("leads", $_REQUEST["jak_roles"])) { ?> checked="checked"<?php } ?>>
					    	<span class="form-check-sign"></span> <?php echo $jkl["u30"];?>
						</label>
					</div>

					<div class="form-check form-check-inline">
				  		<label class="form-check-label">
				    		<input class="form-check-input" type="checkbox" name="jak_roles[]" value="leads_all"<?php if (isset($_REQUEST["jak_roles"]) && in_array("leads_all", $_REQUEST["jak_roles"])) { ?> checked="checked"<?php } ?>>
				    		<span class="form-check-sign"></span> <?php echo $jkl["u30"].' ('.$jkl["g105"].')';?>
						</label>
					</div>

					<div class="form-check form-check-inline">
	  					<label class="form-check-label">
	    					<input class="form-check-input" type="checkbox" name="jak_roles[]" value="off_all"<?php if (isset($_REQUEST["jak_roles"]) && in_array("off_all", $_REQUEST["jak_roles"])) { ?> checked="checked"<?php } ?>>
	    					<span class="form-check-sign"></span> <?php echo $jkl["g33"].' ('.$jkl["g105"].')';?>
						</label>
					</div>

					<div class="form-check form-check-inline">
	  					<label class="form-check-label">
	    				<input class="form-check-input" type="checkbox" name="jak_roles[]" value="ochat"<?php if (isset($_REQUEST["jak_roles"]) && in_array("ochat", $_REQUEST["jak_roles"])) { ?> checked="checked"<?php } ?>>
	    				<span class="form-check-sign"></span> <?php echo $jkl["u31"];?>
	    			</label>
	    		</div>

					<div class="form-check form-check-inline">
	  					<label class="form-check-label">
	    				<input class="form-check-input" type="checkbox" name="jak_roles[]" value="ochat_all"<?php if (isset($_REQUEST["jak_roles"]) && in_array("ochat_all", $_REQUEST["jak_roles"])) { ?> checked="checked"<?php } ?>>
	    				<span class="form-check-sign"></span> <?php echo $jkl["u31"].' ('.$jkl["g105"].')';?>
	    			</label>
	    		</div>

					<div class="form-check form-check-inline">
	  					<label class="form-check-label">
	    				<input class="form-check-input" type="checkbox" name="jak_roles[]" value="statistic"<?php if (isset($_REQUEST["jak_roles"]) && in_array("statistic", $_REQUEST["jak_roles"])) { ?> checked="checked"<?php } ?>>
	    				<span class="form-check-sign"></span> <?php echo $jkl["u32"];?>
	    			</label>
	    		</div>

					<div class="form-check form-check-inline">
	  					<label class="form-check-label">
	    					<input class="form-check-input" type="checkbox" name="jak_roles[]" value="statistic_all"<?php if (isset($_REQUEST["jak_roles"]) && in_array("statistic_all", $_REQUEST["jak_roles"])) { ?> checked="checked"<?php } ?>>
	    					<span class="form-check-sign"></span> <?php echo $jkl["u32"].' ('.$jkl["g105"].')';?>
	    				</label>
	    			</div>

					<div class="form-check form-check-inline">
	  					<label class="form-check-label">
	    					<input class="form-check-input" type="checkbox" name="jak_roles[]" value="files"<?php if (isset($_REQUEST["jak_roles"]) && in_array("files", $_REQUEST["jak_roles"])) { ?> checked="checked"<?php } ?>>
	    					<span class="form-check-sign"></span> <?php echo $jkl["u33"];?>
	    			</label>
	    		</div>

					<div class="form-check form-check-inline">
	  					<label class="form-check-label">
	    					<input class="form-check-input" type="checkbox" name="jak_roles[]" value="proactive"<?php if (isset($_REQUEST["jak_roles"]) && in_array("proactive", $_REQUEST["jak_roles"])) { ?> checked="checked"<?php } ?>>
	    					<span class="form-check-sign"></span> <?php echo $jkl["u34"];?>
	    				</label>
	    			</div>

					<div class="form-check form-check-inline">
	  					<label class="form-check-label">
	    					<input class="form-check-input" type="checkbox" name="jak_roles[]" value="usrmanage"<?php if (isset($_REQUEST["jak_roles"]) && in_array("usrmanage", $_REQUEST["jak_roles"])) { ?> checked="checked"<?php } ?>>
	    					<span class="form-check-sign"></span> <?php echo $jkl["u42"];?>
	    				</label>
	    			</div>

					<div class="form-check form-check-inline">
	  					<label class="form-check-label">
	    					<input class="form-check-input" type="checkbox" name="jak_roles[]" value="responses"<?php if (isset($_REQUEST["jak_roles"]) && in_array("responses", $_REQUEST["jak_roles"])) { ?> checked="checked"<?php } ?>>
	    					<span class="form-check-sign"></span> <?php echo $jkl["u35"];?>
	    				</label>
	    			</div>

					<div class="form-check form-check-inline">
	  					<label class="form-check-label">
	   		 				<input class="form-check-input" type="checkbox" name="jak_roles[]" value="departments"<?php if (isset($_REQUEST["jak_roles"]) && in_array("departments", $_REQUEST["jak_roles"])) { ?> checked="checked"<?php } ?>>
	   		 				<span class="form-check-sign"></span> <?php echo $jkl["u36"];?>
	   		 			</label>
	   		 		</div>

					<div class="form-check form-check-inline">
	  					<label class="form-check-label">
	    					<input class="form-check-input" type="checkbox" name="jak_roles[]" value="settings"<?php if (isset($_REQUEST["jak_roles"]) && in_array("settings", $_REQUEST["jak_roles"])) { ?> checked="checked"<?php } ?>>
	    					<span class="form-check-sign"></span> <?php echo $jkl["u37"];?>
	    				</label>
	    			</div>

					<div class="form-check form-check-inline">
	  					<label class="form-check-label">
	    					<input class="form-check-input" type="checkbox" name="jak_roles[]" value="maintenance"<?php if (isset($_REQUEST["jak_roles"]) && in_array("maintenance", $_REQUEST["jak_roles"])) { ?> checked="checked"<?php } ?>>
	    					<span class="form-check-sign"></span> <?php echo $jkl["u38"];?>
	    				</label>
	    			</div>

					<div class="form-check form-check-inline">
	  					<label class="form-check-label">
	   		 				<input class="form-check-input" type="checkbox" name="jak_roles[]" value="logs"<?php if (isset($_REQUEST["jak_roles"]) && in_array("logs", $_REQUEST["jak_roles"])) { ?> checked="checked"<?php } ?>>
	   		 				<span class="form-check-sign"></span> <?php echo $jkl["u39"];?>
	   		 			</label>
	   		 		</div>

					<div class="form-check form-check-inline">
	  					<label class="form-check-label">
	    					<input class="form-check-input" type="checkbox" name="jak_roles[]" value="answers"<?php if (isset($_REQUEST["jak_roles"]) && in_array("answers", $_REQUEST["jak_roles"])) { ?> checked="checked"<?php } ?>>
	    					<span class="form-check-sign"></span> <?php echo $jkl["u44"];?>
	    				</label>
	    			</div>

					<div class="form-check form-check-inline">
	  					<label class="form-check-label">
	    				<input class="form-check-input" type="checkbox" name="jak_roles[]" value="widget"<?php if (isset($_REQUEST["jak_roles"]) && in_array("widget", $_REQUEST["jak_roles"])) { ?> checked="checked"<?php } ?>>
	    				<span class="form-check-sign"></span> <?php echo $jkl["m26"];?>
	    			</label>
	    		</div>

					<div class="form-check form-check-inline">
	  					<label class="form-check-label">
	    					<input class="form-check-input" type="checkbox" name="jak_roles[]" value="groupchat"<?php if (isset($_REQUEST["jak_roles"]) && in_array("groupchat", $_REQUEST["jak_roles"])) { ?> checked="checked"<?php } ?>>
	    					<span class="form-check-sign"></span> <?php echo $jkl["m29"];?>
	    				</label>
	    			</div>

					<div class="form-check form-check-inline">
	  					<label class="form-check-label">
	    					<input class="form-check-input" type="checkbox" name="jak_roles[]" value="blacklist"<?php if (isset($_REQUEST["jak_roles"]) && in_array("blacklist", $_REQUEST["jak_roles"])) { ?> checked="checked"<?php } ?>>
	    					<span class="form-check-sign"></span> <?php echo $jkl["m27"];?>
	    				</label>
	    			</div>

					<div class="form-check form-check-inline">
	  					<label class="form-check-label">
	    					<input class="form-check-input" type="checkbox" name="jak_roles[]" value="blocklist"<?php if (isset($_REQUEST["jak_roles"]) && in_array("blocklist", $_REQUEST["jak_roles"])) { ?> checked="checked"<?php } ?>>
	    					<span class="form-check-sign"></span> <?php echo $jkl["g97"];?>
	    				</label>
	    			</div>
					
				</div>
				<div class="card-footer">
					<button type="submit" name="save" class="btn btn-primary"><?php echo $jkl["g38"];?></button>
				</div>
			</div>
			
	</div>
</div>
</form>

</div>
		
<?php include_once 'footer.php';?>