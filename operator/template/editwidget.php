<?php include_once 'header.php';?>

<div class="content">

<?php if ($errors) { ?>
<div class="alert alert-danger">
<?php if (isset($errors["e"])) echo $errors["e"];
	  if (isset($errors["e1"])) echo $errors["e1"];?>
</div>
<?php } ?>

<form class="jak_form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
<div class="row">
	<div class="col-md-6">
		<div class="card">
			<div class="card-header">
		  		<h3 class="card-title"><i class="fa fa-palette"></i> <?php echo $jkl['m32'];?></h3>
			</div><!-- /.box-header -->
			<div class="card-body">
				<div class="form-group">
					<label for="title"><?php echo $jkl["g16"];?></label>
					<input type="text" name="title" id="title" class="form-control<?php if (isset($errors["e"])) echo " is-invalid";?>" value="<?php echo $JAK_FORM_DATA["title"];?>">
				</div>

				<div class="form-group">
					<p><label for="jak_lang"><?php echo $jkl["g22"];?></label></p>
					<select name="jak_lang" class="selectpicker" data-size="4" data-live-search="true">
						<?php if (isset($lang_files) && is_array($lang_files)) foreach($lang_files as $lf) { ?>
						<option value="<?php echo $lf;?>"<?php if ($JAK_FORM_DATA["lang"] == $lf) { ?> selected="selected"<?php } ?>><?php echo ucwords($lf);?></option><?php } ?>
					</select>
				</div>

				<div class="form-group">
					<p><label><?php echo $jkl["cw7"];?></label></p>
					<select name="chatSty" class="selectpicker" onchange="this.form.submit()">
						<?php if (isset($chat_templates) && is_array($chat_templates)) foreach($chat_templates as $c) { ?>
						<option value="<?php echo $c;?>"<?php if ($JAK_FORM_DATA["template"] == $c) { ?> selected="selected"<?php } ?>><?php echo $c;?></option>
						<?php } ?>
					</select>
				</div>

				<div class="form-group">
					<p><label for="department"><?php echo $jkl["g131"];?> <a href="javascript:void(0)" class="jakweb-help" data-content="<?php echo $jkl["h6"];?>" data-original-title="<?php echo $jkl["t"];?>"><i class="fa fa-question-circle"></i></a></label></p>
					<select name="jak_depid[]" id="department" class="selectpicker" multiple="multiple" data-size="4" data-live-search="true">

						<option value="0"<?php if ($JAK_FORM_DATA["depid"] == 0) echo ' selected="selected"';?>><?php echo $jkl["g105"];?></option>
						<?php if (isset($JAK_DEPARTMENTS) && is_array($JAK_DEPARTMENTS)) foreach($JAK_DEPARTMENTS as $z) { ?>

						<option value="<?php echo $z["id"];?>"<?php if (in_array($z["id"], explode(',', $JAK_FORM_DATA["depid"]))) echo ' selected';?>><?php echo $z["title"];?></option>

						<?php } ?>

					</select>
				</div>

				<div class="form-group">
					<p><label for="operator"><?php echo $jkl["g130"];?></label></p>
					<select name="jak_opid" id="operator" class="selectpicker" data-size="4" data-live-search="true">

						<option value="0"<?php if ($JAK_FORM_DATA["opid"] == 0) echo ' selected="selected"';?>><?php echo $jkl["g105"];?></option>
						<?php if (isset($JAK_OPERATORS) && is_array($JAK_OPERATORS)) foreach($JAK_OPERATORS as $o) { ?>

						<option value="<?php echo $o["id"];?>"<?php if ($JAK_FORM_DATA["opid"] == $o["id"]) echo ' selected="selected"';?>><?php echo $o["username"];?></option>

						<?php } ?>

					</select>
				</div>

				<label><?php echo $jkl["stat_s12"];?></label>
				<div class="form-check form-check-radio">
				    <label class="form-check-label">
						<input class="form-check-input" type="radio" name="jak_feedback" value="1"<?php if ($JAK_FORM_DATA["feedback"] == 1) echo ' checked';?>>
						<span class="form-check-sign"></span>
						<?php echo $jkl["g19"];?>
					</label>
				</div>
				<div class="form-check form-check-radio">
				    <label class="form-check-label">
						<input class="form-check-input" type="radio" name="jak_feedback" value="0"<?php if ($JAK_FORM_DATA["feedback"] == 0) echo ' checked';?>>
						<span class="form-check-sign"></span>
						<?php echo $jkl["g18"];?>
					</label>
				</div>

				<label><?php echo $jkl["chato"];?></label>
				<div class="form-check form-check-radio">
				    <label class="form-check-label">
						<input class="form-check-input" type="radio" name="jak_hidewhenoff" value="1"<?php if ($JAK_FORM_DATA["hidewhenoff"] == 1) echo ' checked';?>>
						<span class="form-check-sign"></span>
						<?php echo $jkl["g19"];?>
					</label>
				</div>
				<div class="form-check form-check-radio">
				    <label class="form-check-label">
						<input class="form-check-input" type="radio" name="jak_hidewhenoff" value="0"<?php if ($JAK_FORM_DATA["hidewhenoff"] == 0) echo ' checked';?>>
						<span class="form-check-sign"></span>
						<?php echo $jkl["g18"];?>
					</label>
				</div>

				<label><?php echo $jkl["g346"];?></label>
				<div class="form-check form-check-radio">
				    <label class="form-check-label">
						<input class="form-check-input" type="radio" name="jak_onlymembers" value="1"<?php if ($JAK_FORM_DATA["onlymembers"] == 1) echo ' checked';?>>
						<span class="form-check-sign"></span>
						<?php echo $jkl["g19"];?>
					</label>
				</div>
				<div class="form-check form-check-radio">
				    <label class="form-check-label">
						<input class="form-check-input" type="radio" name="jak_onlymembers" value="0"<?php if ($JAK_FORM_DATA["onlymembers"] == 0) echo ' checked';?>>
						<span class="form-check-sign"></span>
						<?php echo $jkl["g18"];?>
					</label>
				</div>

				<label><?php echo $jkl["g190"];?></label>
				<div class="form-check form-check-radio">
				    <label class="form-check-label">
						<input class="form-check-input" type="radio" name="redirect_active" id="redirect_active" value="1"<?php if ($JAK_FORM_DATA["redirect_active"] == 1) echo ' checked';?>>
						<span class="form-check-sign"></span>
						<?php echo $jkl["g19"];?>
					</label>
				</div>
				<div class="form-check form-check-radio">
				    <label class="form-check-label">
						<input class="form-check-input" type="radio" name="redirect_active" value="0"<?php if ($JAK_FORM_DATA["redirect_active"] == 0) echo ' checked';?>>
						<span class="form-check-sign"></span>
						<?php echo $jkl["g18"];?>
					</label>
				</div>
				
				<div class="form-group">
					<label for="url_red"><?php echo $jkl["g238"];?></label>
					<input type="text" name="url_red" id="url_red" class="form-control" value="<?php echo $JAK_FORM_DATA["redirect_url"];?>" placeholder="https://www.yourdomain.com/contactform">
				</div>

				<div class="form-group">
					<label for="jak_redi_contact"><?php echo $jkl["g252"];?></label>
					<input type="number" name="jak_redi_contact" id="jak_redi_contact" class="form-control" min="1" max="30" step="1" value="<?php echo $JAK_FORM_DATA["redirect_after"];?>">
				</div>

				<div class="form-group">
					<label for="jak_dsgvo"><?php echo $jkl["g342"];?></label>
					<textarea name="jak_dsgvo" id="jak_dsgvo" class="form-control" rows="3"><?php echo $JAK_FORM_DATA["dsgvo"];?></textarea>
				</div>

			</div>
			<div class="card-footer">
				<a href="<?php echo JAK_rewrite::jakParseurl('widget');?>" class="btn btn-default"><?php echo $jkl["g103"];?></a>
				<button type="submit" name="save" class="btn btn-primary"><?php echo $jkl["g38"];?></button>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="card">
			<div class="card-header">
		  		<h3 class="card-title"><i class="fa fa-code"></i> <?php echo $jkl["m34"];?></h3>
			</div><!-- /.box-header -->
		  	<div class="card-body">
		  	<p><?php echo $jkl['cw8'];?> <a href="javascript:void(0)" data-clipboard-target="#widget-code" class="btn btn-primary btn-sm clipboard"><i class="fa fa-clipboard"></i></a></p>
			<textarea rows="11" class="form-control" id="widget-code" readonly="readonly"><?php echo htmlentities('<!-- Live Chat 3 widget -->
<script type="text/javascript">
	(function(w, d, s, u) {
		w.id = '.$page2.'; w.lang = \'\'; w.cName = \'\'; w.cEmail = \'\'; w.cMessage = \'\'; w.lcjUrl = u;
		var h = d.getElementsByTagName(s)[0], j = d.createElement(s);
		j.async = true; j.src = \''.BASE_URL_ORIG.'js/jaklcpchat.js\';
		h.parentNode.insertBefore(j, h);
	})(window, document, \'script\', \''.BASE_URL_ORIG.'\');
</script>
<div id="jaklcp-chat-container"></div>
<!-- end Live Chat 3 widget -->');?></textarea>
<p><?php echo $jkl['g301'];?> <a href="javascript:void(0)" data-clipboard-target="#marketing-code" class="btn btn-primary btn-sm clipboard"><i class="fa fa-clipboard"></i></a></p>
<div class="alert alert-info" id="marketing-code">
				<?php echo htmlentities('<a href="'.str_replace(JAK_OPERATOR_LOC."/", "", JAK_rewrite::jakParseurl('link', $page2, $JAK_FORM_DATA["lang"])).'">'.$jkl['g302'].'</a>');?>
			</div>
			<div class="alert alert-primary">
				<?php echo htmlentities(str_replace(JAK_OPERATOR_LOC."/", "", JAK_rewrite::jakParseurl('link', $page2, $JAK_FORM_DATA["lang"])));?>
			</div>
			</div>
		</div>

		<div class="card">
			<div class="card-header">
		  		<h3 class="card-title"><i class="far fa-user-circle"></i> <?php echo $jkl['cw26'];?></h3>
			</div><!-- /.box-header -->
			<div class="card-body">

				<div class="form-group">
					<p><label><?php echo $jkl["cw25"];?></label></p>
					<select name="avatarset" class="selectpicker" onchange="this.form.submit()">
						<?php if (isset($avatar_sets) && is_array($avatar_sets)) foreach($avatar_sets as $c) { ?>
						<option value="<?php echo $c;?>"<?php if ($JAK_FORM_DATA["avatarset"] == $c) { ?> selected="selected"<?php } ?>><?php echo $c;?></option>
						<?php } ?>
					</select>
				</div>

				<hr>

				<?php if (isset($avatar_images) && is_array($avatar_images)) foreach($avatar_images as $i) { ?>
					<img src="<?php echo BASE_URL_ORIG.str_replace("../", "", $i);?>" class="img-thumbnail img-responsive" alt="avatarset" title="<?php echo basename($i);?>" width="135px">
				<?php } ?>

			</div>
		</div>

	</div>
</div>

<div class="row">
	<div class="col-md-4">
		<div class="card">
		<div class="card-header with-border">
		  <h3 class="card-title"><i class="fa fa-play"></i> <?php echo $jkl['cw'];?></h3>
		</div><!-- /.box-header -->
		<div class="card-body">

			<?php if (isset($btn_tpl) && is_array($btn_tpl)) { ?>

				<div class="form-group">
					<p><label for="jak_btn_tpl"><?php echo $jkl['cw5'];?></label></p>
					<select name="jak_btn_tpl" class="selectpicker" onchange="this.form.submit()">
						<?php foreach($btn_tpl as $v) { ?>
							<option value="<?php echo $v;?>"<?php if ($JAK_FORM_DATA["btn_tpl"] == $v) echo " selected";?>><?php echo jak_tpl_name($v);?></option>
						<?php } ?>
					</select>
				</div>
				<input type="hidden" name="jak_btn_tpl_old" value="<?php echo $JAK_FORM_DATA["btn_tpl"];?>">

			<?php } else { ?>
				<input type="hidden" name="jak_btn_tpl" value="">
			<?php } ?>

			<hr>

			<div class="row">
				<div class="col-md-6">

					<h4><?php echo $jkl['cw13'];?></h4>

					<?php if (isset($btn_form) && !empty($btn_form)) echo $btn_form;?>

				</div>
				<div class="col-md-6">

					<h4><?php echo $jkl['cw14'];?></h4>

					<?php if (isset($btnsett["previewbtn"]) && !empty($btnsett["previewbtn"])) echo $btnsett["previewbtn"];?>
					<?php if (isset($btnsett["previewbtnmobile"]) && !empty($btnsett["previewbtnmobile"])) echo '<hr>'.$btnsett["previewbtnmobile"];?>

				</div>
			</div>

		</div>
		<div class="card-footer">
			<a href="<?php echo JAK_rewrite::jakParseurl('widget');?>" class="btn btn-default"><?php echo $jkl["g103"];?></a>
			<button type="submit" name="save" class="btn btn-primary"><?php echo $jkl["g38"];?></button>
		</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="card">
		<div class="card-header with-border">
		  <h3 class="card-title"><i class="fa fa-sign-in-alt"></i> <?php echo $jkl["cw1"];?></h3>
		</div><!-- /.box-header -->
		<div class="card-body">

			<?php if (isset($start_tpl) && is_array($start_tpl)) { ?>

				<div class="form-group">
					<p><label for="jak_start_tpl"><?php echo $jkl['cw5'];?></label></p>
					<select name="jak_start_tpl" class="selectpicker" onchange="this.form.submit()">
						<?php foreach($start_tpl as $v) { ?>
							<option value="<?php echo $v;?>"<?php if ($JAK_FORM_DATA["start_tpl"] == $v) echo " selected";?>><?php echo jak_tpl_name($v);?></option>
						<?php } ?>
					</select>
				</div>
				<input type="hidden" name="jak_start_tpl_old" value="<?php echo $JAK_FORM_DATA["start_tpl"];?>">

			<?php } else { ?>
				<input type="hidden" name="jak_start_tpl" value="">
			<?php } ?>

			<hr>

			<div class="row">
				<div class="col-md-6">

					<h4><?php echo $jkl['cw13'];?></h4>

					<?php if (isset($start_form) && !empty($start_form)) echo $start_form;?>

				</div>
				<div class="col-md-6">

					<h4><?php echo $jkl['cw14'];?></h4>

					<?php if (isset($startsett["previewchat"]) && !empty($startsett["previewchat"])) { ?>
					<p><img src="<?php echo BASE_URL_ORIG;?>/lctemplate/<?php echo $JAK_FORM_DATA["template"]."/".$startsett["previewchat"];?>" class="img-thumbnail img-responsive" alt="button preview"></p>
					<?php } ?>

				</div>
			</div>

		</div>
		<div class="card-footer">
			<a href="<?php echo JAK_rewrite::jakParseurl('widget');?>" class="btn btn-default"><?php echo $jkl["g103"];?></a>
			<button type="submit" name="save" class="btn btn-primary"><?php echo $jkl["g38"];?></button>
		</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="card">
		<div class="card-header with-border">
		  <h3 class="card-title"><i class="fa fa-comments-alt"></i> <?php echo $jkl["cw2"];?></h3>
		</div><!-- /.box-header -->
		<div class="card-body">

			<?php if (isset($chat_tpl) && is_array($chat_tpl)) { ?>

				<div class="form-group">
					<p><label for="jak_chat_tpl"><?php echo $jkl['cw5'];?></label></p>
					<select name="jak_chat_tpl" class="selectpicker" onchange="this.form.submit()">
						<?php foreach($chat_tpl as $v) { ?>
							<option value="<?php echo $v;?>"<?php if ($JAK_FORM_DATA["chat_tpl"] == $v) echo " selected";?>><?php echo jak_tpl_name($v);?></option>
						<?php } ?>
					</select>
				</div>
				<input type="hidden" name="jak_chat_tpl_old" value="<?php echo $JAK_FORM_DATA["chat_tpl"];?>">

			<?php } else { ?>
				<input type="hidden" name="jak_chat_tpl" value="">
			<?php } ?>

			<hr>

			<div class="row">
				<div class="col-md-6">

					<h4><?php echo $jkl['cw13'];?></h4>

					<?php if (isset($chat_form) && !empty($chat_form)) echo $chat_form;?>

				</div>
				<div class="col-md-6">

					<h4><?php echo $jkl['cw14'];?></h4>

					<?php if (isset($chatsett["previewchat"]) && !empty($chatsett["previewchat"])) { ?>
					<p><img src="<?php echo BASE_URL_ORIG;?>/lctemplate/<?php echo $JAK_FORM_DATA["template"]."/".$chatsett["previewchat"];?>" class="img-thumbnail img-responsive" alt="button preview"></p>
					<?php } ?>

				</div>
			</div>

		</div>
		<div class="card-footer">
			<a href="<?php echo JAK_rewrite::jakParseurl('widget');?>" class="btn btn-default"><?php echo $jkl["g103"];?></a>
			<button type="submit" name="save" class="btn btn-primary"><?php echo $jkl["g38"];?></button>
		</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-4">
		<div class="card">
		<div class="card-header">
		  <h3 class="card-title"><i class="fab fa-wpforms"></i> <?php echo $jkl["cw6"];?></h3>
		</div><!-- /.box-header -->
		<div class="card-body">

			<?php if (isset($contact_tpl) && is_array($contact_tpl)) { ?>

				<div class="form-group">
					<p><label for="jak_contact_tpl"><?php echo $jkl['cw5'];?></label></p>
					<select name="jak_contact_tpl" class="selectpicker" onchange="this.form.submit()">
						<?php foreach($contact_tpl as $v) { ?>
							<option value="<?php echo $v;?>"<?php if ($JAK_FORM_DATA["contact_tpl"] == $v) echo " selected";?>><?php echo jak_tpl_name($v);?></option>
						<?php } ?>
					</select>
				</div>
				<input type="hidden" name="jak_contact_tpl_old" value="<?php echo $JAK_FORM_DATA["contact_tpl"];?>">

			<?php } else { ?>
				<input type="hidden" name="jak_contact_tpl" value="">
			<?php } ?>

			<hr>

			<div class="row">
				<div class="col-md-6">

					<h4><?php echo $jkl['cw13'];?></h4>

					<?php if (isset($contact_form) && !empty($contact_form)) echo $contact_form;?>

				</div>
				<div class="col-md-6">

					<h4><?php echo $jkl['cw14'];?></h4>

					<?php if (isset($contactsett["previewchat"]) && !empty($contactsett["previewchat"])) { ?>
					<p><img src="<?php echo BASE_URL_ORIG;?>/lctemplate/<?php echo $JAK_FORM_DATA["template"]."/".$contactsett["previewchat"];?>" class="img-thumbnail img-responsive" alt="button preview"></p>
					<?php } ?>

				</div>
			</div>

		</div>
		<div class="card-footer">
			<a href="<?php echo JAK_rewrite::jakParseurl('widget');?>" class="btn btn-default"><?php echo $jkl["g103"];?></a>
			<button type="submit" name="save" class="btn btn-primary"><?php echo $jkl["g38"];?></button>
		</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="card">
		<div class="card-header">
		  <h3 class="card-title"><i class="fa fa-id-card"></i> <?php echo $jkl["cw3"];?></h3>
		</div><!-- /.box-header -->
		<div class="card-body">

			<?php if (isset($profile_tpl) && is_array($profile_tpl)) { ?>

				<div class="form-group">
					<p><label for="jak_profile_tpl"><?php echo $jkl['cw5'];?></label></p>
					<select name="jak_profile_tpl" class="selectpicker" onchange="this.form.submit()">
						<?php foreach($profile_tpl as $v) { ?>
							<option value="<?php echo $v;?>"<?php if ($JAK_FORM_DATA["profile_tpl"] == $v) echo " selected";?>><?php echo jak_tpl_name($v);?></option>
						<?php } ?>
					</select>
				</div>
				<input type="hidden" name="jak_profile_tpl_old" value="<?php echo $JAK_FORM_DATA["profile_tpl"];?>">

			<?php } else { ?>
				<input type="hidden" name="jak_profile_tpl" value="">
			<?php } ?>

			<hr>

			<div class="row">
				<div class="col-md-6">

					<h4><?php echo $jkl['cw13'];?></h4>

					<?php if (isset($profile_form) && !empty($profile_form)) echo $profile_form;?>

				</div>
				<div class="col-md-6">

					<h4><?php echo $jkl['cw14'];?></h4>

					<?php if (isset($profilesett["previewchat"]) && !empty($profilesett["previewchat"])) { ?>
					<p><img src="<?php echo BASE_URL_ORIG;?>/lctemplate/<?php echo $JAK_FORM_DATA["template"]."/".$profilesett["previewchat"];?>" class="img-thumbnail img-responsive" alt="button preview"></p>
					<?php } ?>

				</div>
			</div>

		</div>
		<div class="card-footer">
			<a href="<?php echo JAK_rewrite::jakParseurl('widget');?>" class="btn btn-default"><?php echo $jkl["g103"];?></a>
			<button type="submit" name="save" class="btn btn-primary"><?php echo $jkl["g38"];?></button>
		</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="card">
		<div class="card-header with-border">
		  <h3 class="card-title"><i class="far fa-star-half-alt"></i> <?php echo $jkl["cw4"];?></h3>
		</div><!-- /.box-header -->
		<div class="card-body">

			<?php if (isset($feedback_tpl) && is_array($feedback_tpl)) { ?>

				<div class="form-group">
					<p><label for="jak_feedback_tpl"><?php echo $jkl['cw5'];?></label></p>
					<select name="jak_feedback_tpl" class="selectpicker" onchange="this.form.submit()">
						<?php foreach($feedback_tpl as $v) { ?>
							<option value="<?php echo $v;?>"<?php if ($JAK_FORM_DATA["feedback_tpl"] == $v) echo " selected";?>><?php echo jak_tpl_name($v);?></option>
						<?php } ?>
					</select>
				</div>
				<input type="hidden" name="jak_feedback_tpl_old" value="<?php echo $JAK_FORM_DATA["feedback_tpl"];?>">

			<?php } else { ?>
				<input type="hidden" name="jak_feedback_tpl" value="">
			<?php } ?>

			<hr>

			<div class="row">
				<div class="col-md-6">

					<h4><?php echo $jkl['cw13'];?></h4>

					<?php if (isset($feedback_form) && !empty($feedback_form)) echo $feedback_form;?>

				</div>
				<div class="col-md-6">

					<h4><?php echo $jkl['cw14'];?></h4>

					<?php if (isset($feedbacksett["previewchat"]) && !empty($feedbacksett["previewchat"])) { ?>
					<p><img src="<?php echo BASE_URL_ORIG;?>/lctemplate/<?php echo $JAK_FORM_DATA["template"]."/".$feedbacksett["previewchat"];?>" class="img-thumbnail img-responsive" alt="button preview"></p>
					<?php } ?>

				</div>
			</div>

		</div>
		<div class="card-footer">
			<a href="<?php echo JAK_rewrite::jakParseurl('widget');?>" class="btn btn-default"><?php echo $jkl["g103"];?></a>
			<button type="submit" name="save" class="btn btn-primary"><?php echo $jkl["g38"];?></button>
		</div>
		</div>
	</div>
</div>

</form>
</div>
		
<?php include_once 'footer.php';?>