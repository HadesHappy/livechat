<?php include_once 'header.php';?>

<div class="content">

	<div class="card">
		<div class="card-header">
			<h3 class="card-title"><i class="fa fa-users-class"></i> <?php echo $JAK_FORM_DATA["title"];?></h3>
		</div><!-- /.box-header -->
		<div class="card-body">

			<div class="list-group">
				<!-- chat output -->
				<?php echo $chatmsg;?>
			</div>
		</div>
		<div class="card-footer">
			<a href="<?php echo JAK_rewrite::jakParseurl('groupchat', 'edit', $datagc["groupchatid"]);?>" class="btn btn-default"><?php echo $jkl["g103"];?></a>
		</div>
	</div>

</div>

<?php include_once 'footer.php';?>