<?php include_once 'header.php';?>

<?php if ($success) { ?>
<div class="alert alert-success">
	<?php if (isset($success["e"])) echo $success["e"];?>
</div>
<?php } ?>
<form method="post" class="jak_form" action="<?php echo $_SERVER['REQUEST_URI']; ?>">

<div class="box box-danger">
	<div class="box-header with-border">
		<h3 class="box-title"><?php echo $jkl["g97"];?></h3>
	</div><!-- /.box-header -->
	<div class="box-body">
		<div class="table-responsive">
			<table class="table table-striped">
				<tr>
					<td><?php echo $jkl["g95"];?> <a href="javascript:void(0)" class="jakweb-help" data-content="<?php echo $jkl["h3"];?>" data-original-title="<?php echo $jkl["t"];?>"><i class="fa fa-question-circle"></i></a></td>
					<td><textarea name="ip_block" rows="5" class="form-control"><?php echo JAK_IP_BLOCK;?></textarea></td>
				</tr>
				<tr>
					<td><?php echo $jkl["g96"];?> <a href="javascript:void(0)" class="jakweb-help" data-content="<?php echo $jkl["h4"];?>" data-original-title="<?php echo $jkl["t"];?>"><i class="fa fa-question-circle"></i></a></td>
					<td><textarea name="email_block" rows="5" class="form-control"><?php echo JAK_EMAIL_BLOCK;?></textarea></td>
				</tr>
			</table>
		</div>
	</div>
	<div class="box-footer">
		<button type="submit" name="save" class="btn btn-primary pull-right"><?php echo $jkl["g38"];?></button>
	</div>
</div>

</form>

<?php include_once 'footer.php';?>