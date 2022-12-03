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

	<?php if (getimagesize(APP_PATH.JAK_FILES_DIRECTORY.$JAK_FORM_DATA['path'])) { ?>
	<p><img src="<?php echo BASE_URL_ORIG.JAK_FILES_DIRECTORY.$JAK_FORM_DATA['path'];?>" class="img-fluid" alt="img"></p>
	<?php } else { ?>
	<p>../<?php echo $JAK_FORM_DATA['path'];?></p>
	<?php } ?>

	<div class="alert alert-info"><?php echo BASE_URL_ORIG.JAK_FILES_DIRECTORY.$JAK_FORM_DATA['path'];?></div>

	<div class="form-group">
		<label for="name"><?php echo $jkl["g53"];?></label>
		<input type="text" name="name" id="name" class="form-control<?php if (isset($errors["e"])) echo " is-invalid";?>" value="<?php echo $JAK_FORM_DATA["name"];?>">
	</div>

	<div class="form-group">
		<label for="desc"><?php echo $jkl["g52"];?></label>
		<textarea name="description" id="desc" rows="5" class="form-control"><?php echo $JAK_FORM_DATA["description"];?></textarea>
	</div>

</div>
<div class="card-footer">
	<a href="<?php echo JAK_rewrite::jakParseurl('files');?>" class="btn btn-default"><?php echo $jkl["g103"];?></a>
	<button type="submit" name="save" class="btn btn-primary"><?php echo $jkl["g38"];?></button>
</div>
</div>

</form>

</div>
		
<?php include_once 'footer.php';?>