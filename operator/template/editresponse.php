<?php include_once 'header.php';?>

<div class="content">

<?php if ($errors) { ?>
<div class="alert alert-danger">
<?php if (isset($errors["e"])) echo $errors["e"];
	  if (isset($errors["e1"])) echo $errors["e1"];?>
</div>
<?php } ?>

<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
<div class="card">
<div class="card-header">
  <h3 class="card-title"><i class="fa fa-edit"></i> <?php echo $jkl["g47"];?></h3>
</div><!-- /.box-header -->
<div class="card-body">

	<div class="form-group">
		<label for="title"><?php echo $jkl["g16"];?></label>
		<input type="text" name="title" id="title" class="form-control<?php if (isset($errors["e"])) echo " is-invalid";?>" value="<?php echo $JAK_FORM_DATA["title"];?>" />
	</div>

	<div class="form-group">
		<label for="short_code"><?php echo $jkl["g349"];?></label>
		<input type="text" name="short_code" id="short_code" class="form-control" value="<?php echo $JAK_FORM_DATA["short_code"];?>">
	</div>

	<div class="form-group">
		<p><label for="department"><?php echo $jkl["g131"];?></label></p>
		<select name="jak_depid" id="department" class="selectpicker" data-size="4" data-live-search="true">
	
		<option value="0"<?php if ($JAK_FORM_DATA["department"] == 0) echo ' selected="selected"';?>><?php echo $jkl["g105"];?></option>
		<?php if (isset($JAK_DEPARTMENTS) && is_array($JAK_DEPARTMENTS)) foreach($JAK_DEPARTMENTS as $z) { ?>
	
		<option value="<?php echo $z["id"];?>"<?php if ($JAK_FORM_DATA["department"] == $z["id"]) echo ' selected="selected"';?>><?php echo $z["title"];?></option>
	
		<?php } ?>
	
		</select>
	</div>

	<div class="form-group">
		<label for="resp"><?php echo $jkl["g49"];?> <a href="javascript:void(0)" class="jakweb-help" data-content="<?php echo $jkl["h13"];?>" data-original-title="<?php echo $jkl["t"];?>"><i class="fa fa-question-circle"></i></a></label>
		<textarea name="response" id="content-editor" rows="5" class="form-control<?php if (isset($errors["e1"])) echo " is-invalid";?>"><?php echo $JAK_FORM_DATA["message"];?></textarea>
	</div>

</div>
<div class="card-footer">
	<a href="<?php echo JAK_rewrite::jakParseurl('response');?>" class="btn btn-default"><?php echo $jkl["g103"];?></a>
	<button type="submit" name="save" class="btn btn-primary"><?php echo $jkl["g38"];?></button>
</div>
</div>
</form>

</div>
		
<?php include_once 'footer.php';?>