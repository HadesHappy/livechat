<?php include_once 'header.php';?>

<div class="content">

<!-- Small boxes (Stat box) -->
<div class="row">
  <div class="col-md-3">
    <div class="card card-stats">
      <div class="card-body">
        <div class="statistics statistics-horizontal">
          <div class="info info-horizontal">
            <div class="row">
              <div class="col-3">
                <div class="icon icon-primary icon-circle">
                  <i class="fa fa-spider-black-widow"></i>
                </div>
              </div>
              <div class="col-9 text-right">
                <h3 class="info-title"><?php echo ($BLACKLIST_ALL ? count($BLACKLIST_ALL) : 0);?></h3>
                <h6 class="stats-title"><?php echo $jkl["stat_s58"];?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="card-footer">
        <div class="stats">
          <i class="fa fa-spider-black-widow"></i> <?php echo $jkl["stat_s58"];?>
        </div>
      </div>
    </div>
  </div><!-- ./col -->
  <div class="col-md-3">
    <div class="card card-stats">
      <div class="card-body">
        <div class="statistics statistics-horizontal">
          <div class="info info-horizontal">
            <div class="row">
              <div class="col-3">
                <div class="icon icon-info icon-circle">
                  <i class="fal fa-language"></i>
                </div>
              </div>
              <div class="col-9 text-right">
                <h3 class="info-title"><?php echo strtoupper(JAK_LANG);?></h3>
                <h6 class="stats-title"><?php echo $jkl["u11"];?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="card-footer">
        <div class="stats">
          <i class="fa fa-language"></i> <?php echo $jkl["u11"];?>
        </div>
      </div>
    </div>
  </div><!-- ./col -->
  <div class="col-md-3">
    <div class="card card-stats">
      <div class="card-body">
        <div class="statistics statistics-horizontal">
          <div class="info info-horizontal">
            <div class="row">
              <div class="col-5">
                <div class="icon icon-success icon-circle">
                  <i class="fas fa-users-cog"></i>
                </div>
              </div>
              <div class="col-7 text-right">
                <h3 class="info-title"><?php echo $totalChange;?></h3>
                <h6 class="stats-title"><?php echo $jkl["stat_s54"];?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="card-footer">
        <div class="stats">
          <i class="fa fa-users-cog"></i> <?php echo $jkl["stat_s54"];?>
        </div>
      </div>
    </div>
  </div><!-- ./col -->
  <div class="col-md-3">
    <div class="card card-stats">
      <div class="card-body">
        <div class="statistics statistics-horizontal">
          <div class="info info-horizontal">
            <div class="row">
              <div class="col-3">
                <div class="icon icon-warning icon-circle">
                  <i class="fa fa-clock"></i>
                </div>
              </div>
              <div class="col-9 text-right">
                <h3 class="info-title"><?php echo ($lastChange ? JAK_base::jakTimesince($lastChange, JAK_DATEFORMAT, "") : "-");?></h3>
                <h6 class="stats-title"><?php echo $jkl["stat_s55"];?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="card-footer">
        <div class="stats">
          <i class="fa fa-clock"></i> <?php echo $jkl["stat_s55"];?>
        </div>
      </div>
    </div>
  </div><!-- ./col -->
</div><!-- /.row -->

<div class="row">
<div class="col-md-4">
<div class="card">
	<div class="card-header">
		<h3 class="card-title"><i class="fal fa-spider-black-widow"></i> <?php echo $jkl["g184"];?></h3>
  	</div><!-- /.box-header -->
  	<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
  	<div class="card-body">
				
	<?php if ($errors) { ?>
		<div class="alert alert-danger"><?php if (isset($errors["e"])) echo $errors["e"];
		if (isset($errors["e1"])) echo $errors["e1"];?></div>
	<?php } ?>	
		<div class="form-group">
			<label for="path"><?php echo $jkl["g167"];?></label>
			<input type="text" name="path" id="path" class="form-control<?php if (isset($errors["e"])) echo " is-invalid";?>" value="<?php if (isset($_REQUEST["path"])) echo $_REQUEST["path"];?>">
		</div>
					
		<div class="form-group">
			<label for="title"><?php echo $jkl["g16"];?></label>
			<input type="text" name="title" id="title" class="form-control<?php if (isset($errors["e1"])) echo " is-invalid";?>" value="<?php if (isset($_REQUEST["title"])) echo $_REQUEST["title"];?>">
		</div>

	</div>
						
	<div class="card-footer">
		<button type="submit" name="insert_blacklist" class="btn btn-primary"><?php echo $jkl["g38"];?></button>
	</div>

	</form>
			
	</div>
</div>
<div class="col-md-8">
	<div class="card">
		<div class="card-header">
			<h3 class="card-title"><i class="fa fa-spider-black-widow"></i> <?php echo $jkl["m27"];?></h3>
	  	</div><!-- /.box-header -->
	  	<div class="card-body">

	  		<?php if (isset($BLACKLIST_ALL) && is_array($BLACKLIST_ALL) && !empty($BLACKLIST_ALL)) { ?>
			<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th><?php echo $jkl["g16"];?></th>
						<th><?php echo $jkl["g167"];?></th>
						<th><?php echo $jkl["g47"];?></th>
						<th><?php echo $jkl["g48"];?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>#</th>
						<th><?php echo $jkl["g16"];?></th>
						<th><?php echo $jkl["g167"];?></th>
						<th><?php echo $jkl["g47"];?></th>
						<th><?php echo $jkl["g48"];?></th>
					</tr>
				</tfoot>
				<tbody>
					<?php foreach($BLACKLIST_ALL as $v) { ?>
					<tr>
					<td><?php echo $v["id"];?></td>
					<td><a href="<?php echo JAK_rewrite::jakParseurl('blacklist', 'edit', $v["id"]);?>"><?php echo $v["title"];?></a></td>
					<td><?php echo $v["path"];?></td>
					<td><a class="btn btn-default btn-sm" href="<?php echo JAK_rewrite::jakParseurl('blacklist', 'edit', $v["id"]);?>"><i class="fa fa-pencil"></i></a></td>
					<td><a class="btn btn-danger btn-sm btn-confirm" href="<?php echo JAK_rewrite::jakParseurl('blacklist', 'delete', $v["id"]);?>" data-title="<?php echo addslashes($jkl["g48"]);?>" data-text="<?php echo addslashes($jkl["e30"]);?>" data-type="warning" data-okbtn="<?php echo addslashes($jkl["g279"]);?>" data-cbtn="<?php echo addslashes($jkl["g280"]);?>"><i class="fa fa-trash-alt"></i></a></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			</div>
			<?php } else { ?>

			<div class="alert alert-info">
				<?php echo $jkl['i3'];?>
			</div>

			<?php } ?>

			</div>
		</div>
	</div>		
</div>

</div>

<?php include_once 'footer.php';?>