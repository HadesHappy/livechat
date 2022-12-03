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
              <div class="col-5">
                <div class="icon icon-primary icon-circle">
                  <i class="fa fa-file"></i>
                </div>
              </div>
              <div class="col-7 text-right">
                <h3 class="info-title"><?php echo count($ANSWERS_ALL);?></h3>
                <h6 class="stats-title"><?php echo $jkl["stat_s60"];?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="card-footer">
        <div class="stats">
          <i class="fa fa-history"></i> <?php echo $jkl["stat_s60"];?>
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

	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
	<div class="card">
		<div class="card-header">
			<h3 class="card-title"><i class="fa fa-language"></i> <?php echo $jkl["g326"];?></h3>
		</div><!-- /.box-header -->
		<div class="card-body">
			
			<div class="form-group">
				<select name="jak_lang_pack" class="selectpicker" data-size="4" data-live-search="true">
				<?php if (isset($unique_lang) && is_array($unique_lang)) foreach($unique_lang as $lfu) { ?><option value="<?php echo $lfu;?>"><?php echo ucwords($lfu);?></option><?php } ?>
				</select>
			</div>

			<button type="submit" name="create_language_pack" class="btn btn-primary"><?php echo $jkl["g4"];?></button>
		</div>
	</div>
	</form>

	<div class="card">
		<div class="card-header">
			<h3 class="card-title"><i class="fa fa-pencil"></i> <?php echo $jkl["g244"];?></h3>
	  	</div><!-- /.box-header -->
	  	<div class="card-body">
				
				<?php if ($errors) { ?>
				<div class="alert alert-danger"><?php if (isset($errors["e"])) echo $errors["e"];
					  if (isset($errors["e1"])) echo $errors["e1"];
					  if (isset($errors["e2"])) echo $errors["e2"];?></div>
				<?php } ?>
				
				<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
					
					<div class="form-group">
					    <label for="title"><?php echo $jkl["g16"];?></label>
						<input type="text" name="title" class="form-control<?php if (isset($errors["e"])) echo " is-invalid";?>" value="<?php if (isset($_REQUEST["title"])) echo $_REQUEST["title"];?>">
					</div>
					
					<div class="form-group">
						<p><label for="department"><?php echo $jkl["g131"];?></label></p>
						<select name="jak_depid" id="department" class="selectpicker">
					
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
						<p><label for="jak_msgtype"><?php echo $jkl["g250"];?></label></p>
						<select name="jak_msgtype" id="jak_msgtype" class="selectpicker" data-size="4" data-live-search="true">
							<option value="1" checked> <?php echo $jkl["g246"];?></option>
							<option value="2"> <?php echo $jkl["g247"];?></option>
							<option value="3"> <?php echo $jkl["g248"];?></option>
							<option value="4"> <?php echo $jkl["g249"];?></option>
							<option value="5"> <?php echo $jkl["g255"];?></option>
							<option value="6"> <?php echo $jkl["g259"];?></option>
							<option value="7"> <?php echo $jkl["g260"];?></option>
							<option value="8"> <?php echo $jkl["g261"];?></option>
							<option value="9"> <?php echo $jkl["g262"];?></option>
							<option value="10"> <?php echo $jkl["g263"];?></option>
							<option value="11"> <?php echo $jkl["g307"];?></option>
							<option value="12"> <?php echo $jkl["g308"];?></option>
							<option value="13"> <?php echo $jkl["g309"];?></option>
							<option value="14"> <?php echo $jkl["g350"];?></option>
							<option value="15"> <?php echo $jkl["g351"];?></option>
							<option value="16"> <?php echo $jkl["g352"];?></option>
							<option value="26"> <?php echo $jkl["g328"];?></option>
							<option value="27"> <?php echo $jkl["g329"];?></option>
						</select>
					</div>
					
					<div class="form-group">
					   <p><label for="fireup"><?php echo $jkl["g251"];?> <a href="javascript:void(0)" class="jakweb-help" data-content="<?php echo $jkl["h17"];?>" data-original-title="<?php echo $jkl["t"];?>"><i class="fa fa-question-circle"></i></a></label></p>
					    <select name="jak_fireup" id="fireup" class="selectpicker" data-size="4">
					    <option value="15">15 <?php echo $jkl["g196"];?></option>
					    <option value="30">30 <?php echo $jkl["g196"];?></option>
					    <option value="60">1 <?php echo $jkl["g197"];?></option>
					    <option value="120">2 <?php echo $jkl["g197"];?></option>
					    <option value="180">3 <?php echo $jkl["g197"];?></option>
					    <option value="240">4 <?php echo $jkl["g197"];?></option>
					    <option value="300">5 <?php echo $jkl["g197"];?></option>
					    <option value="360">6 <?php echo $jkl["g197"];?></option>
					    <option value="420">7 <?php echo $jkl["g197"];?></option>
					    <option value="480">8 <?php echo $jkl["g197"];?></option>
					    <option value="540">9 <?php echo $jkl["g197"];?></option>
					    <option value="600">10 <?php echo $jkl["g197"];?></option>
					    <option value="900">15 <?php echo $jkl["g197"];?></option>
					    <option value="1200">20 <?php echo $jkl["g197"];?></option>
					    </select>
					</div>
					
					<div class="form-group">
					    <label for="answer"><?php echo $jkl["g245"];?> <a href="javascript:void(0)" class="jakweb-help" data-content="<?php echo $jkl["h13"];?>" data-original-title="<?php echo $jkl["t"];?>"><i class="fa fa-question-circle"></i></a></label>
						<textarea name="answer" id="answer" class="form-control<?php if (isset($errors["e1"])) echo " is-invalid";?>" rows="5"><?php if (isset($_REQUEST["answer"])) echo $_REQUEST["answer"];?></textarea>
					</div>
					
					<div class="form-actions">
						<button type="submit" name="insert_answer" class="btn btn-primary"><?php echo $jkl["g38"];?></button>
					</div>

				</form>
		</div>
		
	</div>
</div>
<div class="col-md-8">
	<div class="card">
		<div class="card-header">
			<h3 class="card-title"><i class="fa fa-sticky-note"></i> <?php echo $jkl["g243"];?></h3>
	  	</div><!-- /.box-header -->
	  	<div class="card-body">

	  		<?php if (isset($only_used_lang) && count($only_used_lang) > 1) { ?>

						<p>
							<ul class="nav nav-pills nav-pills-primary">
								<?php foreach ($only_used_lang as $ul) { ?>
									<li class="nav-item">
										<a class="nav-link<?php echo ($ul == $page1 ? ' active' : (empty($page1) && $ul == JAK_LANG ? ' active' : ''));?>" href="<?php echo JAK_rewrite::jakParseurl('answers', ($ul == JAK_LANG ? '' : $ul));?>"><?php echo $ul;?></a>
									</li>
								<?php } ?>
							</ul>
						</p>

					<?php } ?>

			<div class="table-responsive">
			<table class="table table-striped">
			<thead>
			<tr>
			<th>#</th>
			<th><?php echo $jkl["g16"];?></th>
			<th><?php echo $jkl["g250"];?></th>
			<th><?php echo $jkl["g22"];?></th>
			<th><?php echo $jkl["g47"];?></th>
			<th><?php echo $jkl["g48"];?></th>
			</tr>
			</thead>
			<?php if (isset($ANSWERS_ALL) && is_array($ANSWERS_ALL)) foreach($ANSWERS_ALL as $v) { ?>
			<tr>
			<td><?php echo $v["id"];?></td>
			<td class="title"><a href="<?php echo JAK_rewrite::jakParseurl('answers', 'edit', $v["id"]);?>"><?php echo $v["title"];?></a></td>
			<td><?php echo ($v["msgtype"] == 1 ? $jkl["g246"] : '-');?></td>
			<td class="desc"><?php echo $v["lang"];?></td>
			<td><a class="btn btn-default btn-sm" href="<?php echo JAK_rewrite::jakParseurl('answers', 'edit', $v["id"]);?>"><i class="fa fa-pencil"></i></a></td>
			<td><a class="btn btn-danger btn-sm btn-confirm" href="<?php echo JAK_rewrite::jakParseurl('answers', 'delete', $v["id"]);?>" data-title="<?php echo addslashes($jkl["g48"]);?>" data-text="<?php echo addslashes($jkl["e31"]);?>" data-type="warning" data-okbtn="<?php echo addslashes($jkl["g279"]);?>" data-cbtn="<?php echo addslashes($jkl["g280"]);?>"><i class="fa fa-trash-alt"></i></a></td>
			</tr>
			<?php } ?>
			</table>
			</div>

		</div>
	</div>
</div>		
</div>

</div>

<?php include_once 'footer.php';?>