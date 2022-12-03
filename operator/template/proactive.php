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
                  <i class="fa fa-rings-wedding"></i>
                </div>
              </div>
              <div class="col-9 text-right">
                <h3 class="info-title"><?php echo ($PROACTIVE_ALL ? count($PROACTIVE_ALL) : 0);?></h3>
                <h6 class="stats-title"><?php echo $jkl["stat_s57"];?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="card-footer">
        <div class="stats">
          <i class="fa fa-rings-wedding"></i> <?php echo $jkl["stat_s57"];?>
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
		<h3 class="card-title"><i class="fal fa-rings-wedding"></i> <?php echo $jkl["g175"];?></h3>
  	</div><!-- /.box-header -->
  	<div class="card-body">
				
				<?php if ($errors) { ?>
				<div class="alert alert-danger"><?php if (isset($errors["e"])) echo $errors["e"];
					  if (isset($errors["e1"])) echo $errors["e1"];
					  if (isset($errors["e3"])) echo $errors["e3"];
	  				  if (isset($errors["e4"])) echo $errors["e4"];?></div>
				<?php } ?>
				
				<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
					
					<div class="form-group">
					    <label for="path"><?php echo $jkl["g167"];?></label>
						<input type="text" name="path" class="form-control<?php if (isset($errors["e"])) echo " is-invalid";?>" value="<?php if (isset($_REQUEST["path"])) echo $_REQUEST["path"];?>">
					</div>

					<label><?php echo $jkl["g191"];?></label>
					<div class="form-check form-check-radio">
						<label class="form-check-label">
							<input class="form-check-input" type="radio" name="showalert" value="1"<?php if (isset($_REQUEST["showalert"]) && $_REQUEST["showalert"] == 1) echo " checked";?>>
							<span class="form-check-sign"></span>
					 		<?php echo $jkl["g19"];?>
					  	</label>
					</div>
					<div class="form-check form-check-radio">
						<label class="form-check-label">
							<input class="form-check-input" type="radio" name="showalert" value="0"<?php if (isset($_REQUEST["showalert"]) && $_REQUEST["showalert"] == 0) echo " checked";?>>
							<span class="form-check-sign"></span>
							<?php echo $jkl["g18"];?>
						</label>
					</div>

					<div class="form-group">
						<p><label for="soundtype"><?php echo $jkl["g314"];?></label></p>
						<select name="jak_ringtone" id="soundtype" class="selectpicker play-tone" data-size="4" data-live-search="true">
						<option value=""<?php if (!isset($_REQUEST["jak_ringtone"])) echo ' selected="selected"';?>><?php echo $jkl['bw4'];?></option>
						<?php if (isset($sound_files) && is_array($sound_files)) foreach($sound_files as $sfc) { ?><option value="<?php echo $sfc;?>"<?php if (isset($_REQUEST["jak_ringtone"]) && $_REQUEST["jak_ringtone"] == $sfc) echo ' selected="selected"';?>><?php echo $sfc;?></option><?php } ?>
						</select>
					</div>
					
					<div class="form-group">
					    <p><label for="response"><?php echo $jkl["g194"];?></label></p>
					    <select name="onsite" class="selectpicker" data-size="4" data-live-search="true">
						    <option value="1">1 <?php echo $jkl["g196"];?></option>
						    <option value="5">5 <?php echo $jkl["g196"];?></option>
						    <option value="15">15 <?php echo $jkl["g196"];?></option>
						    <option value="30">30 <?php echo $jkl["g196"];?></option>
						    <option value="60">1 <?php echo $jkl["g197"];?></option>
						    <option value="120">2 <?php echo $jkl["g197"];?></option>
						    <option value="180">3 <?php echo $jkl["g197"];?></option>
						    <option value="240">4 <?php echo $jkl["g197"];?></option>
						    <option value="300">5 <?php echo $jkl["g197"];?></option>
					    </select>
					</div>
					
					<div class="form-group">
					    <p><label for="response"><?php echo $jkl["g195"];?></label></p>
						<select name="visited" class="selectpicker" data-size="4" data-live-search="true">
							<?php for ($i = 1; $i <= 20; $i++) { ?>
							<option value="<?php echo $i ?>"><?php echo $i; ?> <?php echo $jkl["g198"];?></option>
							<?php } ?>
						</select>
					</div>
					
					<div class="form-group">
						<label for="title"><?php echo $jkl["g16"];?></label>
						<input type="text" name="title" id="title" class="form-control<?php if (isset($errors["e2"])) echo " is-invalid";?>" value="<?php if (isset($_REQUEST["title"])) echo $_REQUEST["title"];?>" />
					</div>
					
					<div class="form-group">
						<label for="imgpath"><?php echo $jkl["g265"];?> <a href="javascript:void(0)" class="jakweb-help" data-content="<?php echo $jkl["h19"];?>" data-original-title="<?php echo $jkl["t"];?>"><i class="fa fa-question-circle"></i></a></label>
						<input type="text" name="imgpath" id="imgpath" class="form-control" value="<?php if (isset($_REQUEST["imgpath"])) echo $_REQUEST["imgpath"];?>" />
					</div>
					
					<div class="form-group">
					    <label for="response"><?php echo $jkl["g146"];?></label>
						<textarea name="message" class="form-control<?php if (isset($errors["e1"])) echo " is-invalid";?>" rows="3"><?php if (isset($_REQUEST["message"])) echo $_REQUEST["message"];?></textarea>
					</div>

					<div class="form-group">
						<label for="btn-s"><?php echo $jkl["g281"];?></label>
						<input type="text" name="btn-s" id="btn-s" class="form-control<?php if (isset($errors["e3"])) echo " is-invalid";?>" value="<?php if (isset($_REQUEST["btn-s"])) echo $_REQUEST["btn-s"];?>" />
					</div>

					<div class="form-group">
						<label for="btn-c"><?php echo $jkl["g282"];?></label>
						<input type="text" name="btn-c" id="btn-c" class="form-control<?php if (isset($errors["e4"])) echo " is-invalid";?>" value="<?php if (isset($_REQUEST["btn-c"])) echo $_REQUEST["btn-c"];?>" />
					</div>
					
					<div class="form-actions">
						<button type="submit" name="insert_proactive" class="btn btn-primary"><?php echo $jkl["g38"];?></button>
					</div>

				</form>
			</div>
		</div>
</div>
<div class="col-md-8">
	<div class="card box-primary">
		<div class="card-header">
			<h3 class="card-title"><i class="fa fa-rings-wedding"></i> <?php echo $jkl["g176"];?></h3>
	  	</div><!-- /.box-header -->
	  	<div class="card-body">

	  		<?php if (isset($PROACTIVE_ALL) && is_array($PROACTIVE_ALL) && !empty($PROACTIVE_ALL)) { ?>
			<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th><?php echo $jkl["g167"];?></th>
						<th><?php echo $jkl["g146"];?></th>
						<th><?php echo $jkl["g47"];?></th>
						<th><?php echo $jkl["g48"];?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>#</th>
						<th><?php echo $jkl["g167"];?></th>
						<th><?php echo $jkl["g146"];?></th>
						<th><?php echo $jkl["g47"];?></th>
						<th><?php echo $jkl["g48"];?></th>
					</tr>
				</tfoot>
				<tbody>
					<?php foreach($PROACTIVE_ALL as $v) { ?>
					<tr>
					<td><?php echo $v["id"];?></td>
					<td class="title"><a href="<?php echo JAK_rewrite::jakParseurl('proactive', 'edit', $v["id"]);?>"><?php echo $v["path"];?></a></td>
					<td class="desc"><?php echo $v["message"];?></td>
					<td><a class="btn btn-default btn-sm" href="<?php echo JAK_rewrite::jakParseurl('proactive', 'edit', $v["id"]);?>"><i class="fa fa-pencil"></i></a></td>
					<td><a class="btn btn-danger btn-sm btn-confirm" href="<?php echo JAK_rewrite::jakParseurl('proactive', 'delete', $v["id"]);?>" data-title="<?php echo addslashes($jkl["g48"]);?>" data-text="<?php echo addslashes($jkl["e30"]);?>" data-type="warning" data-okbtn="<?php echo addslashes($jkl["g279"]);?>" data-cbtn="<?php echo addslashes($jkl["g280"]);?>"><i class="fa fa-trash-alt"></i></a></td>
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