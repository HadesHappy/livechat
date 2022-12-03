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
                  <i class="fa fa-code"></i>
                </div>
              </div>
              <div class="col-9 text-right">
                <h3 class="info-title"><?php echo count($CHATWIDGET_ALL);?></h3>
                <h6 class="stats-title"><?php echo $jkl["stat_s39"];?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="card-footer">
        <div class="stats">
          <i class="fa fa-code"></i> <?php echo $jkl["stat_s39"];?>
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
                <div class="icon icon-success icon-circle">
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
                <div class="icon icon-info icon-circle">
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

<?php if ($newwidg) { ?>
	<div class="card">
		<div class="card-header">
			<h3 class="card-title"><i class="fal fa-comment"></i> <?php echo $jkl["g289"];?></h3>
	  	</div><!-- /.box-header -->
	  	<div class="card-body">
				
				<?php if ($errors) { ?>
				<div class="alert alert-danger"><?php if (isset($errors["e"])) echo $errors["e"];
					  if (isset($errors["e1"])) echo $errors["e1"];?></div>
				<?php } ?>
				
				<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
					
					<div class="form-group">
					    <label for="title"><?php echo $jkl["g16"];?></label>
						<input type="text" name="title" class="form-control<?php if (isset($errors["e"])) echo " is-invalid";?>" value="<?php if (isset($_REQUEST["title"])) echo $_REQUEST["title"];?>" />
					</div>
					
					<div class="form-group">
						<p><label for="department"><?php echo $jkl["g131"];?></label></p>
						<select name="jak_depid[]" id="department" class="selectpicker" multiple="multiple" data-size="4" data-live-search="true">
					
						<option value="0" selected><?php echo $jkl["g105"];?></option>
						<?php if (isset($JAK_DEPARTMENTS) && is_array($JAK_DEPARTMENTS)) foreach($JAK_DEPARTMENTS as $z) { ?>
					
						<option value="<?php echo $z["id"];?>"><?php echo $z["title"];?></option>
					
						<?php } ?>
					
						</select>
					</div>

					<div class="form-group">
						<p><label for="operator"><?php echo $jkl["g130"];?></label></p>
						<select name="jak_opid" id="operator" class="selectpicker" data-size="4" data-live-search="true">
					
						<option value="0"><?php echo $jkl["g105"];?></option>
						<?php if (isset($JAK_OPERATORS) && is_array($JAK_OPERATORS)) foreach($JAK_OPERATORS as $o) { ?>
					
						<option value="<?php echo $o["id"];?>"><?php echo $o["username"];?></option>
					
						<?php } ?>
					
						</select>
					</div>

					<div class="form-group">
						<p><label for="jak_lang"><?php echo $jkl["g22"];?></label></p>
						<select name="jak_lang" class="selectpicker" data-size="4" data-live-search="true">
						<?php if (isset($lang_files) && is_array($lang_files)) foreach($lang_files as $lf) { ?><option value="<?php echo $lf;?>"<?php if (JAK_LANG == $lf) { ?> selected="selected"<?php } ?>><?php echo ucwords($lf);?></option><?php } ?>
						</select>
					</div>
					
					<div class="form-actions">
						<button type="submit" name="create_widget" class="btn btn-primary"><?php echo $jkl["g38"];?></button>
					</div>

				</form>
		</div>
	</div>
<?php } else { ?>
	<div class="alert alert-danger"><?php echo $jkl['i6'];?></div>
<?php } ?>
</div>
<div class="col-md-8">
	<div class="card">
		<div class="card-header">
			<h3 class="card-title"><i class="fa fa-comment"></i> <?php echo $jkl["g291"];?></h3>
	  	</div><!-- /.box-header -->
	  	<div class="card-body">

	  		<?php if (isset($CHATWIDGET_ALL) && is_array($CHATWIDGET_ALL) && !empty($CHATWIDGET_ALL)) { ?>
			<div class="table-responsive">
			<table class="table table-striped">
			<thead>
			<tr>
			<th>#</th>
			<th><?php echo $jkl["g16"];?></th>
			<th><?php echo $jkl["g47"];?></th>
			<th><?php echo $jkl["g48"];?></th>
			</tr>
			</thead>
			<?php foreach($CHATWIDGET_ALL as $v) { ?>
			<tr>
			<td><?php echo $v["id"];?></td>
			<td><a href="<?php echo JAK_rewrite::jakParseurl('widget', 'edit', $v["id"]);?>"><?php echo $v["title"];?></a></td>
			<td><a class="btn btn-secondary btn-sm" href="<?php echo JAK_rewrite::jakParseurl('widget', 'edit', $v["id"]);?>"><i class="fa fa-pencil"></i></a></td>
			<td><?php if ($v['id'] != 1) { ?><a class="btn btn-danger btn-sm btn-confirm" href="<?php echo JAK_rewrite::jakParseurl('widget', 'delete', $v["id"]);?>" data-title="<?php echo addslashes($jkl["g48"]);?>" data-text="<?php echo addslashes($jkl["e44"]);?>" data-type="warning" data-okbtn="<?php echo addslashes($jkl["g279"]);?>" data-cbtn="<?php echo addslashes($jkl["g280"]);?>"><i class="fa fa-trash-alt"></i></a><?php } ?></td>
			</tr>
			<?php } ?>
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