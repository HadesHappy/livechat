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
                  <i class="fa fa-comments"></i>
                </div>
              </div>
              <div class="col-7 text-right">
                <h3 class="info-title"><?php echo $totalAll;?></h3>
                <h6 class="stats-title"><?php echo $jkl["stat_s45"];?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="card-footer">
        <div class="stats">
          <i class="fa fa-comments"></i> <?php echo $jkl["stat_s45"];?>
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
                  <i class="fa fa-comment-lines"></i>
                </div>
              </div>
              <div class="col-7 text-right">
                <h3 class="info-title"><?php echo $totalSeven;?></h3>
                <h6 class="stats-title"><?php echo $jkl["stat_s17"];?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="card-footer">
        <div class="stats">
          <i class="fa fa-comment-lines"></i> <?php echo $jkl["stat_s17"];?>
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
                  <i class="fad fa-comment-dots"></i>
                </div>
              </div>
              <div class="col-7 text-right">
                <h3 class="info-title"><?php echo $totalMonth;?></h3>
                <h6 class="stats-title"><?php echo $jkl["stat_s18"];?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="card-footer">
        <div class="stats">
          <i class="fa fa-comment-dots"></i> <?php echo $jkl["stat_s18"];?>
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
                  <i class="fa fa-user-md-chat"></i>
                </div>
              </div>
              <div class="col-7 text-right">
                <h3 class="info-title"><?php echo (isset($busy_operator["username"]) ? $busy_operator["username"] : '-');?></h3>
                <h6 class="stats-title"><?php echo $jkl["stat_s44"];?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="card-footer">
        <div class="stats">
          <i class="fa fa-user-md-chat"></i> <?php echo $jkl["stat_s44"];?>
        </div>
      </div>
    </div>
  </div><!-- ./col -->
</div><!-- /.row -->

<?php if (isset($CHATS_ALLC) && is_array($CHATS_ALLC) && !empty($CHATS_ALLC)) { ?>

<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
<div class="card">
<div class="card-body">
<div class="table-responsive">
<table class="table table-striped">
<thead>
<tr>
<th>#</th>
<th><input type="checkbox" id="jak_delete_all" /></th>
<th><?php echo $jkl["g12"];?></th>
<th><?php echo $jkl["g145"];?></th>
<th><?php echo $jkl["g146"];?></th>
<th><?php echo $jkl["g13"];?></th>
<?php if (JAK_SUPERADMINACCESS) { ?><th class="content-go"><a class="btn btn-warning btn-sm btn-confirm" href="<?php echo JAK_rewrite::jakParseurl('chats', 'truncate');?>" data-title="<?php echo addslashes($jkl["g48"]);?>" data-text="<?php echo addslashes($jkl["e39"]);?>" data-type="warning" data-okbtn="<?php echo addslashes($jkl["g279"]);?>" data-cbtn="<?php echo addslashes($jkl["g280"]);?>"><i class="fa fa-exclamation-triangle"></i></a></th>
<th><button class="btn btn-danger btn-sm btn-confirm" data-action="delete" data-title="<?php echo addslashes($jkl["g48"]);?>" data-text="<?php echo addslashes($jkl["e30"]);?>" data-type="warning" data-okbtn="<?php echo addslashes($jkl["g279"]);?>" data-cbtn="<?php echo addslashes($jkl["g280"]);?>"><i class="fa fa-trash-alt"></i></button></th><?php } ?>

</tr>
</thead>
<?php foreach($CHATS_ALLC as $vc) { ?>
<tr>
<td><?php echo $vc["id"];?></td>
<td><input type="checkbox" name="ls_delete_chats[]" class="highlight" value="<?php echo $vc["id"];?>" /></td>
<td><?php echo $vc["username"];?></td>
<td><?php echo $vc["touser"];?></td>
<td class="span8"><?php echo $vc["message"];?></td>
<td><?php echo JAK_base::jakTimesince($vc['sent'], JAK_DATEFORMAT, JAK_TIMEFORMAT);?></td>
<?php if (JAK_SUPERADMINACCESS) { ?><td></td>
<td><a class="btn btn-default btn-sm btn-confirm" href="<?php echo JAK_rewrite::jakParseurl('chats', 'delete', $vc["id"]);?>" data-title="<?php echo addslashes($jkl["g48"]);?>" data-text="<?php echo addslashes($jkl["e33"]);?>" data-type="warning" data-okbtn="<?php echo addslashes($jkl["g279"]);?>" data-cbtn="<?php echo addslashes($jkl["g280"]);?>"><i class="fa fa-trash-alt"></i></a></td><?php } ?>
</tr>
<?php } ?>
</table>
</div>
</div>
</div>
</form>

<?php } else { ?>

<div class="alert alert-info">
 	<?php echo $jkl['i3'];?>
</div>

<?php } if (isset($JAK_PAGINATE)) echo $JAK_PAGINATE;?>

</div>
		
<?php include_once 'footer.php';?>