<?php include_once 'header.php';?>

<div class="content">

<div class="row">
<div class="col-md-9">
	
	<div class="card box-primary">
		<div class="card-header">
			<h3 class="card-title"><i class="fa fa-file"></i> <?php echo $jkl["g51"];?></h3>
	  	</div><!-- /.box-header -->
	  	<div class="card-body">

	  		<div class="table-responsive">
	  		
	  		<?php if (isset($FILES_ALL) && is_array($FILES_ALL)) { ?>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th><?php echo $jkl["g53"];?></th>
						<th><?php echo $jkl["g52"];?></th>
						<th><?php echo $jkl["g47"];?></th>
						<th><?php echo $jkl["g48"];?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>#</th>
						<th><?php echo $jkl["g53"];?></th>
						<th><?php echo $jkl["g52"];?></th>
						<th><?php echo $jkl["g47"];?></th>
						<th><?php echo $jkl["g48"];?></th>
					</tr>
				</tfoot>
				<tbody>
					<?php foreach($FILES_ALL as $v) { ?>
					<tr>
					<td><?php echo $v["id"];?></td>
					<td class="title"><a href="<?php echo JAK_rewrite::jakParseurl('files', 'edit', $v["id"]);?>"><?php echo $v["name"];?></a></td>
					<td class="desc"><?php echo $v["description"];?></td>
					<td><a class="btn btn-default btn-sm" href="<?php echo JAK_rewrite::jakParseurl('files', 'edit', $v["id"]);?>"><i class="fa fa-pencil"></i></a></td>
					<td><a class="btn btn-danger btn-sm btn-confirm" href="<?php echo JAK_rewrite::jakParseurl('files', 'delete', $v["id"]);?>" data-title="<?php echo addslashes($jkl["g48"]);?>" data-text="<?php echo addslashes($jkl["e32"]);?>" data-type="warning" data-okbtn="<?php echo addslashes($jkl["g279"]);?>" data-cbtn="<?php echo addslashes($jkl["g280"]);?>"><i class="fa fa-trash-alt"></i></a></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>

			<?php } else { ?>
				<div class="alert alert-info">
					<?php echo $jkl['i3'];?>
				</div>
			<?php } ?>
			
			<?php if (isset($JAK_OPERATOR_FILES) && is_array($JAK_OPERATOR_FILES)) { ?>
			
			<div class="heading_solid">
			<h4><?php echo $jkl["g132"];?></h4>
			</div>
			
			<table class="table table-striped">
			<?php foreach($JAK_OPERATOR_FILES as $l) { ?>
				
				<tr><td>
				<?php if (getimagesize(APP_PATH.JAK_FILES_DIRECTORY.'/operator/'.$l)) { ?>
					<a data-toggle="lightbox" href="<?php echo BASE_URL_ORIG.JAK_FILES_DIRECTORY;?>/operator/<?php echo $l;?>"><img src="<?php echo BASE_URL_ORIG.JAK_FILES_DIRECTORY;?>/operator/<?php echo $l;?>" alt="<?php echo $l;?>" width="40px"></a> <a href="<?php echo JAK_rewrite::jakParseurl('files', 'deletefo', $l);?>" class="btn btn-danger btn-sm btn-confirm" data-title="<?php echo addslashes($jkl["g48"]);?>" data-text="<?php echo addslashes($jkl["e32"]);?>" data-type="warning" data-okbtn="<?php echo addslashes($jkl["g279"]);?>" data-cbtn="<?php echo addslashes($jkl["g280"]);?>"><i class="fa fa-trash-alt"></i></a>
				<?php } else { ?>
					<?php echo $l;?> <a href="<?php echo BASE_URL_ORIG.JAK_FILES_DIRECTORY;?>/operator/<?php echo $l;?>" class="btn btn-info btn-sm"><i class="fa fa-save"></i></a> <a href="<?php echo BASE_URL_ORIG.JAK_rewrite::jakParseurl('files', 'deletefo', $l);?>" class="btn btn-danger btn-sm btn-confirm" data-title="<?php echo addslashes($jkl["g48"]);?>" data-text="<?php echo addslashes($jkl["e32"]);?>" data-type="warning" data-okbtn="<?php echo addslashes($jkl["g279"]);?>" data-cbtn="<?php echo addslashes($jkl["g280"]);?>"><i class="fa fa-trash-alt"></i></a>
				<?php } ?>
				</td></tr>
			<?php } ?>
			</table>
			
			<?php } ?>
			
			<?php if (isset($JAK_USER_FILES) && is_array($JAK_USER_FILES)) { ?>
			
			<h4><?php echo $jkl["g133"];?></h4>
			
			<table class="table table-striped">
			<?php foreach($JAK_USER_FILES as $k) { ?>
				
				<tr><td>
					<?php if (getimagesize(APP_PATH.JAK_FILES_DIRECTORY.'/user/'.$k)) { ?>
						<a data-toggle="lightbox" href="<?php echo BASE_URL_ORIG.JAK_FILES_DIRECTORY;?>/user/<?php echo $k;?>"><img src="<?php echo BASE_URL_ORIG.JAK_FILES_DIRECTORY;?>/user/<?php echo $k;?>" alt="<?php echo $k;?>" width="40px"></a> <a href="<?php echo JAK_rewrite::jakParseurl('files', 'deletef', $k);?>" class="btn btn-danger btn-sm btn-confirm" data-title="<?php echo addslashes($jkl["g48"]);?>" data-text="<?php echo addslashes($jkl["e32"]);?>" data-type="warning" data-okbtn="<?php echo addslashes($jkl["g279"]);?>" data-cbtn="<?php echo addslashes($jkl["g280"]);?>"><i class="fa fa-trash-alt"></i></a>
					<?php } else { ?>
						<?php echo $k;?> <a href="<?php echo BASE_URL_ORIG.JAK_FILES_DIRECTORY;?>/user/<?php echo $k;?>" class="btn btn-info btn-sm"><i class="fa fa-save"></i></a> <a href="<?php echo JAK_rewrite::jakParseurl('files', 'deletef', $k);?>" class="btn btn-danger btn-sm btn-confirm" data-title="<?php echo addslashes($jkl["g48"]);?>" data-text="<?php echo addslashes($jkl["e32"]);?>" data-type="warning" data-okbtn="<?php echo addslashes($jkl["g279"]);?>" data-cbtn="<?php echo addslashes($jkl["g280"]);?>"><i class="fa fa-trash-alt"></i></a>
					<?php } ?>
				</td></tr>
			<?php } ?>
			</table>
			
			<?php } ?>
			
			</div>
			
		</div>
	</div>		
	</div>
	<div class="col-md-3">
		
		<div class="card">
			<div class="card-header">
				<h3 class="card-title"><i class="fal fa-file"></i> <?php echo $jkl["g50"];?></h3>
		  	</div><!-- /.box-header -->
		  	<div class="card-body">
				
				<?php if ($errors) { ?>
				<div class="alert alert-danger"><?php if (isset($errors["e"])) echo $errors["e"];
					  if (isset($errors["e1"])) echo $errors["e1"];
					  if (isset($errors["e2"])) echo $errors["e2"];?></div>
				<?php } ?>
				
				<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">

		            <div class="form-group form-file-upload form-file-simple">
		                <input type="text" class="form-control inputFileVisible" placeholder="<?php echo JAK_ALLOWEDO_FILES;?>">
		                <input type="file" class="inputFileHidden" name="uploadedfile">
		            </div>
					
					<div class="form-group">
					    <label for="name"><?php echo $jkl["g53"];?></label>
						<input type="text" name="name" class="form-control<?php if (isset($errors["e1"])) echo " is-invalid";?>" value="<?php if (isset($_REQUEST["name"])) echo $_REQUEST["name"];?>">
					</div>
					<div class="form-group">
					    <label for="description"><?php echo $jkl["g52"];?></label>
						<textarea name="description" class="form-control" rows="3"><?php if (isset($_REQUEST["description"])) echo $_REQUEST["description"];?></textarea>
					</div>
						
						<div class="form-actions">
							<button type="submit" name="insert_response" class="btn btn-primary"><?php echo $jkl["g38"];?></button>
						</div>
						
					<input type="hidden" name="max_file_size" value="10000000">

				</form>
			</div>
		</div>
	</div>		
</div>

</div>

<?php include_once 'footer.php';?>