<?php include_once 'header.php';?>

<div class="content">

<div class="row">
<div class="col-md-6">

	<div class="card card-primary">
		<div class="card-header">
			<h3 class="card-title"><i class="fa fa-ticket-alt"></i> <?php echo $FORM_TITLE;?></h3>
	  	</div><!-- /.card-header -->
	  	<div class="card-body">

	  		<?php if ($page1 == "r") { ?>

	  		<p><?php echo nl2br($ticket["content"], false);?></p>


	  		<?php } else { ?>
				
				<?php if ($errors) { ?>
				<div class="alert alert-danger"><?php if (isset($errors["e"])) echo $errors["e"];
					  if (isset($errors["e1"])) echo $errors["e1"];?></div>
				<?php } ?>
				
				<form role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
					
					<div class="form-group">
					    <label class="control-label" for="subject"><?php echo $jkl["g221"];?></label>
						<input type="text" name="subject" class="form-control<?php if (isset($errors["e"])) echo " is-invalid";?>" value="<?php if (isset($_REQUEST["subject"])) echo $_REQUEST["subject"]; if (isset($ticket["subject"])) echo 'Re: '.$ticket["subject"];?>">
					</div>
					
					<div class="form-group">
					    <label class="control-label" for="content"><?php echo $jkl["g321"];?></label>
						<textarea name="content" id="content" class="form-control<?php if (isset($errors["e1"])) echo " is-invalid";?>" rows="10"><?php if (isset($_REQUEST["content"])) echo $_REQUEST["content"]; if (isset($ticket["content"])) echo "\n\n------------------------ ".JAK_base::jakTimesince($ticket["sent"], "d.m.Y", " g:i a")." ------------------------\n\n".$ticket["content"];?></textarea>
					</div>
					
					<div class="form-actions">
						<button type="submit" name="create_ticket" class="btn btn-primary btn-block"><?php echo $jkl["g4"];?></button>
					</div>

				</form>

			<?php } ?>
		</div>
		
	</div>
</div>
<div class="col-md-6">
	<div class="card card-primary">
		<div class="card-header">
			<h3 class="card-title"><i class="fa fa-list-alt"></i> <?php echo $jkl["g324"];?></h3>
	  	</div><!-- /.card-header -->
	  	<div class="card-body">
	  		<?php if (isset($TICKETS_ALL) && is_array($TICKETS_ALL)) { ?>
			<table class="table table-striped table-responsive">
			<thead>
			<tr>
			<th>#</th>
			<th><?php echo $jkl["g221"];?></th>
			<th><?php echo $jkl["g321"];?></th>
			<th><?php echo $jkl["g65"];?></th>
			<th><?php echo $jkl["g273"];?></th>
			</tr>
			</thead>
			<?php foreach($TICKETS_ALL as $v) { ?>
			<tr<?php if ($v["readtime"] == 0) echo ' class="table-danger"';?>>
			<td><?php echo $v["id"];?></td>
			<td class="title"><a href="<?php echo JAK_rewrite::jakParseurl('tickets', 'a', $v["id"]);?>"><?php echo $v["subject"];?></a></td>
			<td class="desc"><?php echo ls_cut_text($v["content"], 60, "...");?></td>
			<td><a class="btn btn-default btn-sm" href="<?php echo JAK_rewrite::jakParseurl('tickets', 'r', $v["id"]);?>"><i class="fa fa-file-text-o"></i></a></td>
			<td><a class="btn btn-default btn-sm" href="<?php echo JAK_rewrite::jakParseurl('tickets', 'a', $v["id"]);?>"><i class="fa fa-pencil"></i></a></td>
			</tr>
			<?php } ?>
			</table>
			<?php } else { ?>
				<div class="alert alert-info"><?php echo $jkl['i3'];?></div>
			<?php } ?>
		</div>
	</div>
</div>		
</div>

<?php if (isset($JAK_PAGINATE)) echo $JAK_PAGINATE;?>

</div>

<?php include_once 'footer.php';?>