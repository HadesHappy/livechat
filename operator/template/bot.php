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
                  <i class="fa fa-robot"></i>
                </div>
              </div>
              <div class="col-9 text-right">
                <h3 class="info-title"><?php echo ($BOT_ALL ? count($BOT_ALL) : 0);?></h3>
                <h6 class="stats-title"><?php echo $jkl["stat_s45"];?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="card-footer">
        <div class="stats">
          <i class="fa fa-robot"></i> <?php echo $jkl["stat_s45"];?>
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
<div class="col-md-3">

	<div class="card">
		<div class="card-header">
			<h3 class="card-title"><i class="fal fa-robot"></i> <?php echo $jkl["g270"];?></h3>
	  	</div><!-- /.box-header -->
	  	<div class="card-body">

				<?php if ($errors) { ?>
				<div class="alert alert-danger"><?php if (isset($errors["e"])) echo $errors["e"];
					  if (isset($errors["e1"])) echo $errors["e1"];?></div>
				<?php } ?>
				
				<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
					
					<div class="form-group">
					    <label for="question"><?php echo $jkl["g269"];?> <a href="javascript:void(0)" class="jakweb-help" data-content="<?php echo $jkl["h22"];?>" data-original-title="<?php echo $jkl["t"];?>"><i class="fa fa-question-circle"></i></a></label>
						<input type="text" name="question" class="form-control<?php if (isset($errors["e"])) echo " is-invalid";?>" value="<?php if (isset($_REQUEST["question"])) echo $_REQUEST["question"];?>" />
					</div>

					<div class="form-group">
						<p><label for="widgetid"><?php echo $jkl['g291'];?> <a href="javascript:void(0)" class="jakweb-help" data-content="<?php echo $jkl["h6"];?>" data-original-title="<?php echo $jkl["t"];?>"><i class="fa fa-question-circle"></i></a></label></p>
						<select name="jak_widgetid[]" id="widgetid" class="selectpicker" data-size="4" data-live-search="true" multiple>
					
						<option value="0"><?php echo $jkl["g105"];?></option>
						<?php if (isset($JAK_WIDGETS) && is_array($JAK_WIDGETS)) foreach($JAK_WIDGETS as $w) { ?>
					
						<option value="<?php echo $w["id"];?>"><?php echo $w["title"];?></option>
					
						<?php } ?>
					
						</select>
					</div>
					
					<div class="form-group">
						<p><label for="department"><?php echo $jkl["g131"];?></label></p>
						<select name="jak_depid" id="department" class="selectpicker" data-size="4" data-live-search="true">
					
						<option value="0"><?php echo $jkl["g105"];?></option>
						<?php if (isset($JAK_DEPARTMENTS) && is_array($JAK_DEPARTMENTS)) foreach($JAK_DEPARTMENTS as $z) { ?>
					
						<option value="<?php echo $z["id"];?>"><?php echo $z["title"];?></option>
					
						<?php } ?>
					
						</select>
					</div>

					<div class="form-group">
						<p><label for="jak_lang"><?php echo $jkl["g22"];?></label></p>
						<select name="jak_lang" class="selectpicker" data-size="4" data-live-search="true">
						<?php if (isset($lang_files) && is_array($lang_files)) foreach($lang_files as $lf) { ?><option value="<?php echo $lf;?>"<?php if (JAK_LANG == $lf) { ?> selected="selected"<?php } ?>><?php echo ucwords($lf);?></option><?php } ?>
						</select>
					</div>
					
					<div class="form-group">
					    <label for="answer"><?php echo $jkl["g273"];?> <a href="javascript:void(0)" class="jakweb-help" data-content="<?php echo $jkl["h21"];?>" data-original-title="<?php echo $jkl["t"];?>"><i class="fa fa-question-circle"></i></a></label>
						<textarea name="answer" class="form-control<?php if (isset($errors["e1"])) echo " is-invalid";?>" rows="3"><?php if (isset($_REQUEST["answer"])) echo $_REQUEST["answer"];?></textarea>
					</div>

				</div>
					
				<div class="card-footer">
					<button type="submit" name="insert_bot" class="btn btn-primary"><?php echo $jkl["g38"];?></button>
				</div>

			</form>
	</div>
</div>
<div class="col-md-9">

	<div class="card">
		<div class="card-header">
			<h3 class="card-title"><i class="fa fa-robot"></i> <?php echo $jkl["g271"];?></h3>
	  	</div><!-- /.box-header -->
	  	<div class="card-body">

	  		<?php if (isset($BOT_ALL) && is_array($BOT_ALL) && !empty($BOT_ALL)) { ?>
	  		<div class="table-responsive">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th><?php echo $jkl["g269"];?></th>
						<th><?php echo $jkl["g273"];?></th>
						<th><?php echo $jkl["g47"];?></th>
						<th><?php echo $jkl["g48"];?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>#</th>
						<th><?php echo $jkl["g269"];?></th>
						<th><?php echo $jkl["g273"];?></th>
						<th><?php echo $jkl["g47"];?></th>
						<th><?php echo $jkl["g48"];?></th>
					</tr>
				</tfoot>
				<tbody>
					<?php foreach($BOT_ALL as $v) { ?>
					<tr>
					<td><?php echo $v["id"];?></td>
					<td class="title"><a href="<?php echo JAK_rewrite::jakParseurl('bot', 'edit', $v["id"]);?>"><?php echo $v["question"];?></a></td>
					<td class="desc"><?php echo $v["answer"];?></td>
					<td><a class="btn btn-default btn-sm" href="<?php echo JAK_rewrite::jakParseurl('bot', 'edit', $v["id"]);?>"><i class="fa fa-pencil"></i></a></td>
					<td><a class="btn btn-danger btn-sm btn-confirm" href="<?php echo JAK_rewrite::jakParseurl('bot', 'delete', $v["id"]);?>" data-title="<?php echo addslashes($jkl["g48"]);?>" data-text="<?php echo addslashes($jkl["e30"]);?>" data-type="warning" data-okbtn="<?php echo addslashes($jkl["g279"]);?>" data-cbtn="<?php echo addslashes($jkl["g280"]);?>"><i class="fa fa-trash"></i></a></td>
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