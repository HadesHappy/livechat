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
                  <i class="fa fa-id-badge"></i>
                </div>
              </div>
              <div class="col-7 text-right">
                <h3 class="info-title"><?php echo ($verify_response['status'] == true ? $jkl['g337'] : $jkl['g338']);?></h3>
                <h6 class="stats-title"><?php echo $jkl["stat_s37"];?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="card-footer">
        <div class="stats">
          <i class="fa fa-id-badge"></i> <?php echo $jkl["stat_s37"];?>
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
                  <i class="fa fa-history"></i>
                </div>
              </div>
              <div class="col-7 text-right">
                <h3 class="info-title"><?php echo JAK_VERSION;?></h3>
                <h6 class="stats-title"><?php echo $jkl["g118"];?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="card-footer">
        <div class="stats">
          <i class="fa fa-history"></i> <?php echo $jkl["g118"];?>
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
                  <i class="fad fa-mobile"></i>
                </div>
              </div>
              <div class="col-9 text-right">
                <h3 class="info-title"><?php echo $totalPND;?></h3>
                <h6 class="stats-title"><?php echo $jkl["g313"];?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="card-footer">
        <div class="stats">
          <i class="fa fa-mobile"></i> <?php echo $jkl["g313"];?>
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
                  <i class="fa fa-file"></i>
                </div>
              </div>
              <div class="col-9 text-right">
                <h3 class="info-title"><?php echo iterator_count($totalFiles);?></h3>
                <h6 class="stats-title"><?php echo $jkl["stat_s38"];?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="card-footer">
        <div class="stats">
          <i class="fa fa-file"></i> <?php echo $jkl["stat_s38"];?>
        </div>
      </div>
    </div>
  </div><!-- ./col -->
</div><!-- /.row -->

<?php if (isset($errors)) { ?>
<div class="alert alert-danger">
<?php if (isset($errors["e"])) echo $errors["e"];
	  if (isset($errors["e1"])) echo $errors["e1"];?>
</div>
<?php } ?>

<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
<div class="content">
	<div class="row">
    	<div class="col-md-6">
        	<div class="card">
        		<div class="card-header">
        			<h3 class="card-title"><i class="fa fa-id-badge"></i> <?php echo $jkl["g332"];?></h3>
        		</div>
            	<div class="card-body">
            		<div class="alert alert-info">
                  <span><?php echo $licmsg;?></span>
                </div>
                <?php if ($verify_response['status'] != true) { ?>
	              <div class="form-group">
							   <label for="jaklic"><?php echo $jkl["g333"];?></label>
							   <input type="text" name="jak_lic" id="jaklic" class="form-control<?php if (isset($errors["e1"])) echo " is-invalid";?>" autocomplete="off">
						    </div>

						<div class="form-group">
							<label for="jaklicusr"><?php echo $jkl["g334"];?></label>
							<input type="text" name="jak_licusr" id="jaklicusr" class="form-control<?php if (isset($errors["e2"])) echo " is-invalid";?>" autocomplete="off">
						</div>
						<button type="submit" name="regLicense" class="btn btn-primary"><?php echo $jkl["g4"];?></button>
                	<?php } else { ?>
                		<button type="submit" name="deregLicense" class="btn btn-danger"><?php echo $jkl["g335"];?></button>
                	<?php } ?>
                </div>
            </div>
            <div class="card">
        		<div class="card-header">
        			<h3 class="card-title"><i class="fa fa-file-certificate"></i> <?php echo $jkl["g236"];?></h3>
        		</div>
        		<div class="card-body">
        			<?php if ($verify_response['status']) { $update_data = $jaklic->check_update();?>
        			<div class="alert alert-info">
        				<span><?php echo $update_data['message'];?></span>
        			</div>
        			<?php if ($update_data['status']) { ?>
					<p><?php echo $update_data['changelog'];?></p><?php 
                	$update_id = null;
                	$has_sql = null;
                	$version = null;
                	if (!empty($_POST['update_id'])) {
                  	$update_id = strip_tags(trim($_POST["update_id"]));
                  	$has_sql = strip_tags(trim($_POST["has_sql"]));
                  	$version = strip_tags(trim($_POST["version"]));
                  	?>
                  	<div class="progress-container progress-primary mb-3">
                      <span class="progress-badge"><?php echo $jkl['g336'];?></span>
                      <div class="progress">
                        <div class="progress-bar progress-bar-success" role="progressbar" id="updprog" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                          <span class="progress-value" id="updprogval">0%</span>
                        </div>
                      </div>
                    </div>
                    <?php
                  	$jaklic->download_update($_POST['update_id'], $_POST['has_sql'], $_POST['version']);
        			} else { ?>
        			<input type="hidden" value="<?php echo $update_data['update_id'];?>" name="update_id">
                    <input type="hidden" value="<?php echo $update_data['has_sql'];?>" name="has_sql">
                    <input type="hidden" value="<?php echo $update_data['version'];?>" name="version">
                    <button type="submit" name="updSoftware" class="btn btn-success"><?php echo $jkl["g235"];?></button>
                	<?php } } } ?>
        		</div>
        	</div>
        </div>
        <div class="col-md-6">
			<div class="card">
				<div class="card-header">
        			<h3 class="card-title"><i class="fa fa-tools"></i> <?php echo $jkl["m19"];?></h3>
        		</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped">
						<tr>
							<td><?php echo $jkl["g182"];?></td>
							<td><button type="submit" name="delCache" class="btn btn-danger"><?php echo $jkl["g48"];?></button></td>
						</tr>
						<tr>
							<td><?php echo $jkl["g313"];?></td>
							<td><button type="submit" name="delTokens" class="btn btn-danger"><?php echo $jkl["g48"];?></button></td>
						</tr>
						<tr>
							<td><?php echo $jkl["g185"];?></td>
							<td><button type="submit" name="optimize" class="btn btn-success"><?php echo $jkl["g185"];?></button></td>
						</tr>
						</table>
					</div>
				</div>
			</div>
        </div>
    </div>
</div>
</form>

</div>

<?php include_once 'footer.php';?>