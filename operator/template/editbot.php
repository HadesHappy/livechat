<?php include_once 'header.php';?>

<div class="content">

<?php if ($errors) { ?>
<div class="alert alert-danger"><?php if (isset($errors["e"])) echo $errors["e"];
	if (isset($errors["e1"])) echo $errors["e1"];?></div>
<?php } ?>

<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data">
<div class="card">
<div class="card-header">
  <h3 class="card-title"><i class="fa fa-edit"></i> <?php echo $jkl["m24"];?></h3>
</div><!-- /.box-header -->
<div class="card-body">

	<div class="form-group">
		<label for="question"><?php echo $jkl["g269"];?> <a href="javascript:void(0)" class="jakweb-help" data-content="<?php echo $jkl["h22"];?>" data-original-title="<?php echo $jkl["t"];?>"><i class="fa fa-question-circle"></i></a></label>
		<input type="text" name="question" id="question" class="form-control<?php if (isset($errors["e"])) echo " is-invalid";?>" value="<?php echo $JAK_FORM_DATA["question"];?>" />
	</div>

	<div class="row">
		<div class="col-md-4">

			<div class="form-group">
				<p><label for="jak_widgetid"><?php echo $jkl['g291'];?> <a href="javascript:void(0)" class="jakweb-help" data-content="<?php echo $jkl["h6"];?>" data-original-title="<?php echo $jkl["t"];?>"><i class="fa fa-question-circle"></i></a></label></p>
				<select name="jak_widgetid[]" multiple="multiple" class="selectpicker" data-size="4" data-live-search="true">
			
					<option value="0"<?php if ($JAK_FORM_DATA["widgetids"] == 0) { ?> selected="selected"<?php } ?>><?php echo $jkl["g105"];?></option>
					<?php if (isset($JAK_WIDGETS) && is_array($JAK_WIDGETS)) foreach($JAK_WIDGETS as $w) { ?>
					<option value="<?php echo $w["id"];?>"<?php if (in_array($w["id"], explode(',', $JAK_FORM_DATA["widgetids"]))) echo ' selected';?>><?php echo $w["title"];?></option>
					
					<?php } ?>
			
				</select>
			</div>

		</div>
		<div class="col-md-4">

			<div class="form-group">
				<p><label for="department"><?php echo $jkl['g131'];?></label></p>
				<select name="jak_depid" id="department" class="selectpicker" data-size="4" data-live-search="true">
			
					<option value="0"<?php if ($JAK_FORM_DATA["depid"] == 0) echo ' selected';?>><?php echo $jkl["g105"];?></option>
					<?php if (isset($JAK_DEPARTMENTS_CHAT) && is_array($JAK_DEPARTMENTS_CHAT)) foreach($JAK_DEPARTMENTS_CHAT as $z) { ?>
				
					<option value="<?php echo $z["id"];?>"<?php if ($JAK_FORM_DATA["depid"] == $z["id"]) echo ' selected';?>><?php echo $z["title"];?></option>
				
					<?php } ?>
			
				</select>
			</div>

		</div>
		<div class="col-md-4">

			<div class="form-group">
				<p><label for="jak_lang"><?php echo $jkl['g22'];?></label></p>
				<select name="jak_lang" id="jak_lang" class="selectpicker" data-size="4" data-live-search="true">
			
					<?php if (isset($lang_files) && is_array($lang_files)) foreach($lang_files as $lf) { ?><option value="<?php echo $lf;?>"<?php if ($JAK_FORM_DATA["lang"] == $lf) echo ' selected';?>><?php echo ucwords($lf);?></option><?php } ?>
			
				</select>
			</div>

		</div>
	</div>

	<div class="form-group">
		<label for="answer"><?php echo $jkl["g273"];?> <a href="javascript:void(0)" class="jakweb-help" data-content="<?php echo $jkl["h21"];?>" data-original-title="<?php echo $jkl["t"];?>"><i class="fa fa-question-circle"></i></a></label>
		<textarea name="answer" class="form-control<?php if ($errors["e1"]) echo " is-invalid";?>" rows="5"><?php if (isset($JAK_FORM_DATA["answer"])) echo $JAK_FORM_DATA["answer"];?></textarea>
	</div>

</div>
<div class="card-footer">
	<a href="<?php echo JAK_rewrite::jakParseurl('bot');?>" class="btn btn-default"><?php echo $jkl["g103"];?></a>
	<button type="submit" name="save" class="btn btn-primary"><?php echo $jkl["g38"];?></button>
</div>
</div>
</form>

</div>
		
<?php include_once 'footer.php';?>