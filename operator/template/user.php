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
                  <i class="fa fa-users"></i>
                </div>
              </div>
              <div class="col-7 text-right">
                <h3 class="info-title"><?php echo $totalAll;?></h3>
                <h6 class="stats-title"><?php echo $jkl["stat_s56"];?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="card-footer">
        <div class="stats">
          <i class="fa fa-users"></i> <?php echo $jkl["stat_s56"];?>
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
                  <i class="fa fa-user-clock"></i>
                </div>
              </div>
              <div class="col-7 text-right">
                <h3 class="info-title"><?php echo $totalSeven;?></h3>
                <h6 class="stats-title"><?php echo $jkl["stat_s46"];?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="card-footer">
        <div class="stats">
          <i class="fa fa-user-clock"></i> <?php echo $jkl["stat_s46"];?>
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
                  <i class="fad fa-sign-in"></i>
                </div>
              </div>
              <div class="col-7 text-right">
                <h3 class="info-title"><?php echo $total_logins;?></h3>
                <h6 class="stats-title"><?php echo $jkl["stat_s47"];?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="card-footer">
        <div class="stats">
          <i class="fa fa-sign-in"></i> <?php echo $jkl["stat_s47"];?>
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
                  <i class="fa fa-star"></i>
                </div>
              </div>
              <div class="col-7 text-right">
                <h3 class="info-title"><?php echo ($total_voted ? round($total_vote / $total_voted, 1).'/5' : '-');?></h3>
                <h6 class="stats-title"><?php echo $jkl["stat_s7"];?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="card-footer">
        <div class="stats">
          <i class="fa fa-star"></i> <?php echo $jkl["stat_s7"];?>
        </div>
      </div>
    </div>
  </div><!-- ./col -->
</div><!-- /.row -->

<div class="row">
	<div class="col-md-12">
<div class="card">
<div class="card-body">

<?php if ($newop && jak_get_access("usrmanage", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)) { ?>
<p><a class="btn btn-primary" href="<?php echo JAK_rewrite::jakParseurl('users', 'new');?>"><?php echo $jkl["m7"];?></a></p>
<?php } ?>

<form method="post" class="jak_form" action="<?php echo $_SERVER['REQUEST_URI']; ?>">

<div class="table-responsive">
<table class="table table-striped">
<thead>
<tr>
<th>#</th>
<th><input type="checkbox" id="jak_delock_all" /></th>
<th><?php echo $jkl["u"];?></th>
<th><?php echo $jkl["u1"];?></th>
<th><?php echo $jkl["u2"];?></th>
<th></th>
<th><?php if (JAK_SUPERADMINACCESS) { ?><button class="btn btn-default btn-sm btn-confirm" data-action="lock" data-title="<?php echo addslashes($jkl["g101"]);?>" data-text="<?php echo addslashes($jkl["all"]);?>" data-type="warning" data-okbtn="<?php echo addslashes($jkl["g279"]);?>" data-cbtn="<?php echo addslashes($jkl["g280"]);?>"><i class="fa fa-lock"></i></button><?php } ?></th>
<th></th>
<th><?php if (JAK_SUPERADMINACCESS) { ?><button class="btn btn-danger btn-sm btn-confirm" data-action="delete" data-title="<?php echo addslashes($jkl["g48"]);?>" data-text="<?php echo addslashes($jkl["al"]);?>" data-type="warning" data-okbtn="<?php echo addslashes($jkl["g279"]);?>" data-cbtn="<?php echo addslashes($jkl["g280"]);?>"><i class="fa fa-trash-alt"></i></button><?php } ?></th>
</tr>
</thead>
<?php if (isset($JAK_USER_ALL) && is_array($JAK_USER_ALL)) foreach($JAK_USER_ALL as $v) { ?>
<tr>
<td><?php echo $v["id"];?></td>
<td><input type="checkbox" name="jak_delock_all[]" class="highlight" value="<?php echo $v["id"].':#:'.$v["access"];?>"></td>
<td><?php echo ($v["available"] == 1 ? '<i class="fa fa-signal" title="'.$jkl["g177"].'"></i>' : '<i class="far fa-signal-slash"></i>');?> <?php echo (!empty($v["hours_array"]) ? '<i class="fa fa-clock" title="'.$jkl["g343"].'"></i>' : '');?> <a href="<?php echo JAK_rewrite::jakParseurl('users', 'edit', $v["id"]);?>"><?php echo $v["name"];?></a></td>
<td><?php echo $v["email"];?></td>
<td><a href="<?php echo JAK_rewrite::jakParseurl('users', 'edit', $v["id"]);?>"><?php echo $v["username"];?></a></td>
<td><a class="btn btn-default btn-sm" data-toggle="modal" href="<?php echo JAK_rewrite::jakParseurl('users', 'stats', $v["id"], $v["username"]);?>" data-target="#jakModal"><i class="fa fa-chart-bar"></i></a></td>
<td><?php if (JAK_SUPERADMINACCESS) { ?><a class="btn btn-default btn-sm" href="<?php echo JAK_rewrite::jakParseurl('users', 'lock', $v["id"]);?>"><i class="fa fa-<?php if ($v["access"] == '1') { ?>check<?php } else { ?>lock<?php } ?>"></i></a><?php } ?></td>
<td><a class="btn btn-default btn-sm" href="<?php echo JAK_rewrite::jakParseurl('users', 'edit', $v["id"]);?>"><i class="fa fa-pencil"></i></a></td>
<td><?php if (JAK_SUPERADMINACCESS) { ?><a class="btn btn-default btn-sm btn-confirm" href="<?php echo JAK_rewrite::jakParseurl('users', 'delete', $v["id"]);?>" data-title="<?php echo addslashes($jkl["g48"]);?>" data-text="<?php echo addslashes($jkl["al"]);?>" data-type="warning" data-okbtn="<?php echo addslashes($jkl["g279"]);?>" data-cbtn="<?php echo addslashes($jkl["g280"]);?>"><i class="fa fa-trash-alt"></i></a><?php } ?></td>
</tr>
<?php } ?>
</table>
</div>

<input type="hidden" name="action" id="action">
</form>

</div>
</div>
</div>
</div>

</div>
		
<?php include_once 'footer.php';?>