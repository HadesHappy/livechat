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
                  <i class="fa fa-cogs"></i>
                </div>
              </div>
              <div class="col-7 text-right">
                <h3 class="info-title"><?php echo $totalAll;?></h3>
                <h6 class="stats-title"><?php echo $jkl["stat_s48"];?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="card-footer">
        <div class="stats">
          <i class="fa fa-cogs"></i> <?php echo $jkl["stat_s48"];?>
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
                  <i class="fad fa-users-cog"></i>
                </div>
              </div>
              <div class="col-7 text-right">
                <h3 class="info-title"><?php echo $totalChange;?></h3>
                <h6 class="stats-title"><?php echo $jkl["stat_s49"];?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="card-footer">
        <div class="stats">
          <i class="fa fa-users-cog"></i> <?php echo $jkl["stat_s49"];?>
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
                  <i class="fal fa-paper-plane"></i>
                </div>
              </div>
              <div class="col-7 text-right">
                <h3 class="info-title"><?php echo $totalSMTP;?></h3>
                <h6 class="stats-title"><?php echo $jkl["stat_s51"];?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="card-footer">
        <div class="stats">
          <i class="fal fa-paper-plane"></i> <?php echo $jkl["stat_s51"];?>
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
                <div class="icon icon-warning icon-circle">
                  <i class="fa fa-paper-plane"></i>
                </div>
              </div>
              <div class="col-7 text-right">
                <h3 class="info-title"><?php echo $totalMAIL;?></h3>
                <h6 class="stats-title"><?php echo $jkl["stat_s52"];?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="card-footer">
        <div class="stats">
          <i class="fa fa-paper-plane"></i> <?php echo $jkl["stat_s52"];?>
        </div>
      </div>
    </div>
  </div><!-- ./col -->
</div><!-- /.row -->

<?php if ($errors) { ?>
<div class="alert alert-danger">
<?php if (isset($errors["e"])) echo $errors["e"];
	  if (isset($errors["e1"])) echo $errors["e1"];
	  if (isset($errors["e2"])) echo $errors["e2"];
	  if (isset($errors["e3"])) echo $errors["e3"];
	  if (isset($errors["e4"])) echo $errors["e4"];
	  if (isset($errors["e5"])) echo $errors["e5"];
	  if (isset($errors["e6"])) echo $errors["e6"];
	  if (isset($errors["e7"])) echo $errors["e7"];?>
</div>
<?php } if ($success) { ?>
<div class="alert alert-success">
	<?php if (isset($success["e"])) echo $success["e"];?>
</div>
<?php } ?>

<p>
<ul class="nav nav-pills nav-pills-primary">
  <li class="nav-item">
    <a class="nav-link" href="<?php echo JAK_rewrite::jakParseurl('settings');?>"><?php echo $jkl["m32"];?></a>
  </li>
  <li class="nav-item">
    <a class="nav-link active" href="<?php echo JAK_rewrite::jakParseurl('settings', 'email');?>"><?php echo $jkl["m35"];?></a>
  </li>
</ul>
</p>

<form method="post" class="jak_form" action="<?php echo $_SERVER['REQUEST_URI'];?>">

<div class="card">
	<div class="card-header">
		<h3 class="card-title"><i class="fa fa-envelope"></i> <?php echo $jkl["g212"];?></h3>
	</div><!-- /.box-header -->
	<div class="card-body">

		<label><?php echo $jkl["g212"];?></label>
		<div class="form-check form-check-radio">
        	<label class="form-check-label">
    	      	<input class="form-check-input" type="radio" name="jak_smpt" value="0"<?php if (JAK_SMTP_MAIL == 0) echo ' checked="checked"';?>>
                <span class="form-check-sign"></span>
                <?php echo $jkl["g204"];?>
            </label>
        </div>
        <div class="form-check form-check-radio">
 	        <label class="form-check-label">
                <input class="form-check-input" type="radio" name="jak_smpt" value="1"<?php if (JAK_SMTP_MAIL == 1) echo ' checked="checked"';?>>
                <span class="form-check-sign"></span>
                <?php echo $jkl["g205"];?>
            </label>
        </div>

        <div class="form-group<?php if (isset($errors["e"])) echo " has-danger";?>">
            <label class="control-label" for="jak_smtpsender"><?php echo $jkl["g68"];?></label>
            <input type="text" name="jak_smtpsender" class="form-control" value="<?php echo JAK_SMTP_SENDER;?>" autocomplete="off">
          </div>

        <div id="smtp_options"<?php if (JAK_SMTP_MAIL == 0) echo ' style="display: none"';?>>

        <div class="form-group">
			<label for="host"><?php echo $jkl["g206"];?></label>
			<input type="text" name="jak_host" id="host" class="form-control" value="<?php echo JAK_SMTPHOST;?>">
		</div>

		<div class="form-group">
			<label for="port"><?php echo $jkl["g207"];?></label>
			<input type="number" name="jak_port" id="port" class="form-control" value="<?php echo JAK_SMTPPORT;?>" placeholder="25">
		</div>

		<label><?php echo $jkl["g208"];?></label>
		<div class="form-check form-check-radio">
        	<label class="form-check-label">
    	      	<input class="form-check-input" type="radio" name="jak_alive" value="1"<?php if (JAK_SMTP_ALIVE == 1) echo ' checked="checked"';?>>
                <span class="form-check-sign"></span>
                <?php echo $jkl["g19"];?>
            </label>
        </div>
        <div class="form-check form-check-radio">
 	        <label class="form-check-label">
                <input class="form-check-input" type="radio" name="jak_alive" value="0"<?php if (JAK_SMTP_ALIVE == 0) echo ' checked="checked"';?>>
                <span class="form-check-sign"></span>
                <?php echo $jkl["g18"];?>
            </label>
        </div>

        <label><?php echo $jkl["g209"];?></label>
		<div class="form-check form-check-radio">
        	<label class="form-check-label">
    	      	<input class="form-check-input" type="radio" name="jak_auth" value="1"<?php if (JAK_SMTP_AUTH == 1) echo ' checked="checked"';?>>
                <span class="form-check-sign"></span>
                <?php echo $jkl["g19"];?>
            </label>
        </div>
        <div class="form-check form-check-radio">
 	        <label class="form-check-label">
                <input class="form-check-input" type="radio" name="jak_auth" value="0"<?php if (JAK_SMTP_AUTH == 0) echo ' checked="checked"';?>>
                <span class="form-check-sign"></span>
                <?php echo $jkl["g18"];?>
            </label>
        </div>

        <div class="form-group">
			<label for="prefix"><?php echo $jkl["g219"];?></label>
			<input type="text" name="jak_prefix" id="prefix" class="form-control" value="<?php echo JAK_SMTP_PREFIX;?>" placeholder="ssl/tls/true/false">
		</div>
		
		<div class="form-group">
			<label for="smtpuser"><?php echo $jkl["g210"];?></label>
			<input type="text" name="jak_smtpusername" id="smtpuser" class="form-control" value="<?php echo JAK_SMTPUSERNAME;?>" autocomplete="off">
		</div>

		<div class="form-group">
			<label for="smtppass"><?php echo $jkl["g211"];?></label>
			<input type="password" name="jak_smtppassword" id="smtppass" class="form-control" value="<?php echo JAK_SMTPPASSWORD;?>" autocomplete="off">
		</div>

    </div>
		
		<button type="submit" name="testMail" class="btn btn-success" id="sendTM"><i id="loader" class="fa fa-spinner fa-pulse"></i> <?php echo $jkl["g216"];?></button>

	</div>
	<div class="card-footer">
		<button type="submit" name="save" class="btn btn-primary"><?php echo $jkl["g38"];?></button>
	</div>
</div>

</form>

</div>

<?php include_once 'footer.php';?>