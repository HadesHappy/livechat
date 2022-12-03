<?php include_once 'header.php';?>

<div class="content">

<?php if ($errors) { ?>
<div class="alert alert-danger">
<?php if (isset($errors["e"])) echo $errors["e"];
	  if (isset($errors["e1"])) echo $errors["e1"];?>
</div>
<?php } ?>

<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">
<div class="card">
<div class="card-header">
  <h3 class="card-title"><i class="fa fa-edit"></i> <?php echo $jkl["g47"];?></h3>
</div><!-- /.box-header -->
<div class="card-body">

	<div class="form-group">
		<label for="title"><?php echo $jkl["g16"];?></label>
		<input type="text" name="title" id="title" class="form-control<?php if (isset($errors["e"])) echo " is-invalid";?>" value="<?php echo $JAK_FORM_DATA["title"];?>">
	</div>

	<div class="row">
		<div class="col-md-6">

	<div class="form-group">
		<p><label for="department"><?php echo $jkl["g131"];?></label></p>
		<select name="jak_depid" id="department" class="selectpicker">
	
		<option value="0"<?php if ($JAK_FORM_DATA["department"] == 0) echo ' selected="selected"';?>><?php echo $jkl["g105"];?></option>
		<?php if (isset($JAK_DEPARTMENTS) && is_array($JAK_DEPARTMENTS)) foreach($JAK_DEPARTMENTS as $z) { ?>
	
		<option value="<?php echo $z["id"];?>"<?php if ($JAK_FORM_DATA["department"] == $z["id"]) echo ' selected="selected"';?>><?php echo $z["title"];?></option>
	
		<?php } ?>
	
		</select>
	</div>

	<div class="form-group">
		<p><label for="jak_lang"><?php echo $jkl["g22"];?></label></p>
		<select name="jak_lang" class="selectpicker">
		<?php if (isset($lang_files) && is_array($lang_files)) foreach($lang_files as $lf) { ?><option value="<?php echo $lf;?>"<?php if ($JAK_FORM_DATA["lang"] == $lf) { ?> selected="selected"<?php } ?>><?php echo ucwords($lf);?></option><?php } ?>
		</select>
	</div>

</div>
<div class="col-md-6">

	<div class="form-group">
		<p><label for="jak_msgtype"><?php echo $jkl["g250"];?></label></p>
		<select name="jak_msgtype" id="jak_msgtype" class="selectpicker">
			<option value="1"<?php if ($JAK_FORM_DATA["msgtype"] == 1) echo " selected";?>> <?php echo $jkl["g246"];?></option>
			<option value="2"<?php if ($JAK_FORM_DATA["msgtype"] == 2) echo " selected";?>> <?php echo $jkl["g247"];?></option>
			<option value="3"<?php if ($JAK_FORM_DATA["msgtype"] == 3) echo " selected";?>> <?php echo $jkl["g248"];?></option>
			<option value="4"<?php if ($JAK_FORM_DATA["msgtype"] == 4) echo " selected";?>> <?php echo $jkl["g249"];?></option>
			<option value="5"<?php if ($JAK_FORM_DATA["msgtype"] == 5) echo " selected";?>> <?php echo $jkl["g255"];?></option>
			<option value="6"<?php if ($JAK_FORM_DATA["msgtype"] == 6) echo " selected";?>> <?php echo $jkl["g259"];?></option>
			<option value="7"<?php if ($JAK_FORM_DATA["msgtype"] == 7) echo " selected";?>> <?php echo $jkl["g260"];?></option>
			<option value="8"<?php if ($JAK_FORM_DATA["msgtype"] == 8) echo " selected";?>> <?php echo $jkl["g261"];?></option>
			<option value="9"<?php if ($JAK_FORM_DATA["msgtype"] == 9) echo " selected";?>> <?php echo $jkl["g262"];?></option>
			<option value="10"<?php if ($JAK_FORM_DATA["msgtype"] == 10) echo " selected";?>> <?php echo $jkl["g263"];?></option>
			<option value="11"<?php if ($JAK_FORM_DATA["msgtype"] == 11) echo " selected";?>> <?php echo $jkl["g307"];?></option>
			<option value="12"<?php if ($JAK_FORM_DATA["msgtype"] == 12) echo " selected";?>> <?php echo $jkl["g308"];?></option>
			<option value="13"<?php if ($JAK_FORM_DATA["msgtype"] == 13) echo " selected";?>> <?php echo $jkl["g309"];?></option>
			<option value="14"<?php if ($JAK_FORM_DATA["msgtype"] == 14) echo " selected";?>> <?php echo $jkl["g350"];?></option>
			<option value="15"<?php if ($JAK_FORM_DATA["msgtype"] == 15) echo " selected";?>> <?php echo $jkl["g351"];?></option>
			<option value="16"<?php if ($JAK_FORM_DATA["msgtype"] == 16) echo " selected";?>> <?php echo $jkl["g352"];?></option>
			<option value="26"<?php if ($JAK_FORM_DATA["msgtype"] == 26) echo " selected";?>> <?php echo $jkl["g328"];?></option>
			<option value="27"<?php if ($JAK_FORM_DATA["msgtype"] == 27) echo " selected";?>> <?php echo $jkl["g329"];?></option>
		</select>
	</div>

	<div class="form-group">
		<p><label for="fireup"><?php echo $jkl["g251"];?> <a href="javascript:void(0)" class="jakweb-help" data-content="<?php echo $jkl["h17"];?>" data-original-title="<?php echo $jkl["t"];?>"><i class="fa fa-question-circle"></i></a></label></p>
		<select name="jak_fireup" id="fireup" class="selectpicker">
		    <option value="15"<?php if ($JAK_FORM_DATA["fireup"] == 15) echo " selected";?>>15 <?php echo $jkl["g196"];?></option>
		    <option value="30"<?php if ($JAK_FORM_DATA["fireup"] == 30) echo " selected";?>>30 <?php echo $jkl["g196"];?></option>
		    <option value="60"<?php if ($JAK_FORM_DATA["fireup"] == 60) echo " selected";?>>1 <?php echo $jkl["g197"];?></option>
		    <option value="120"<?php if ($JAK_FORM_DATA["fireup"] == 120) echo " selected";?>>2 <?php echo $jkl["g197"];?></option>
		    <option value="180"<?php if ($JAK_FORM_DATA["fireup"] == 180) echo " selected";?>>3 <?php echo $jkl["g197"];?></option>
		    <option value="240"<?php if ($JAK_FORM_DATA["fireup"] == 240) echo " selected";?>>4 <?php echo $jkl["g197"];?></option>
		    <option value="300"<?php if ($JAK_FORM_DATA["fireup"] == 300) echo " selected";?>>5 <?php echo $jkl["g197"];?></option>
		    <option value="360"<?php if ($JAK_FORM_DATA["fireup"] == 360) echo " selected";?>>6 <?php echo $jkl["g197"];?></option>
		    <option value="420"<?php if ($JAK_FORM_DATA["fireup"] == 420) echo " selected";?>>7 <?php echo $jkl["g197"];?></option>
		    <option value="480"<?php if ($JAK_FORM_DATA["fireup"] == 480) echo " selected";?>>8 <?php echo $jkl["g197"];?></option>
		    <option value="540"<?php if ($JAK_FORM_DATA["fireup"] == 540) echo " selected";?>>9 <?php echo $jkl["g197"];?></option>
		    <option value="600"<?php if ($JAK_FORM_DATA["fireup"] == 600) echo " selected";?>>10 <?php echo $jkl["g197"];?></option>
		    <option value="900"<?php if ($JAK_FORM_DATA["fireup"] == 900) echo " selected";?>>15 <?php echo $jkl["g197"];?></option>
		    <option value="1200"<?php if ($JAK_FORM_DATA["fireup"] == 1200) echo " selected";?>>20 <?php echo $jkl["g197"];?></option>
		</select>
	</div>

</div>
</div>

	<div class="form-group">
		<label for="answer"><?php echo $jkl["g146"];?> <a href="javascript:void(0)" class="jakweb-help" data-content="<?php echo $jkl["h13"];?>" data-original-title="<?php echo $jkl["t"];?>"><i class="fa fa-question-circle"></i></a></label>
		<textarea name="answer" id="answer" rows="5" class="form-control<?php if (isset($errors["e1"])) echo " is-invalid";?>"><?php echo $JAK_FORM_DATA["message"];?></textarea>
	</div>

</div>
<div class="card-footer">
	<a href="<?php echo JAK_rewrite::jakParseurl('answers');?>" class="btn btn-default"><?php echo $jkl["g103"];?></a>
	<button type="submit" name="save" class="btn btn-primary"><?php echo $jkl["g38"];?></button>
</div>
</div>
</form>

</div>
		
<?php include_once 'footer.php';?>