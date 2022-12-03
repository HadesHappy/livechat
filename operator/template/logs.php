<?php include_once 'header.php';?>

<div class="content">

<!-- Small boxes (Stat box) -->
<div class="row">
  <div class="col-md-5">
    <div class="card card-stats">
      <div class="card-body">
        <div class="statistics statistics-horizontal">
          <div class="info info-horizontal">
            <div class="row">
              <div class="col-3">
                <div class="icon icon-primary icon-circle">
                  <i class="fa fa-clipboard-list"></i>
                </div>
              </div>
              <div class="col-9 text-right">
                <h3 class="info-title"><?php echo $totalAll;?></h3>
                <h6 class="stats-title"><?php echo $jkl["stat_s43"];?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="card-footer">
        <div class="stats">
          <i class="fa fa-clipboard-list"></i> <?php echo $jkl["stat_s43"];?>
        </div>
      </div>
    </div>
  </div><!-- ./col -->
  <div class="col-md-7">
    <div class="card card-stats">
      <div class="card-body">
        <div class="statistics statistics-horizontal">
          <div class="info info-horizontal">
            <div class="row">
              <div class="col-3">
                <div class="icon icon-info icon-circle">
                  <i class="fa fa-user-md-chat"></i>
                </div>
              </div>
              <div class="col-9 text-right">
                <h3 class="info-title"><?php echo $busy_operator["username"];?></h3>
                <h6 class="stats-title"><?php echo $jkl["stat_s41"];?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="card-footer">
        <div class="stats">
          <i class="fa fa-user-md-chat"></i> <?php echo $jkl["stat_s41"];?>
        </div>
      </div>
    </div>
  </div><!-- ./col -->
</div><!-- /.row -->

<?php if (isset($totalAll) && $totalAll != 0) { ?>

<div class="row">
  <div class="col-md-12">
<form method="post" class="jak_form" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
<div class="card">
<div class="card-body">


<?php if (JAK_SUPERADMINACCESS) { ?>
<p class="pull-right">
<a class="btn btn-warning btn-sm btn-confirm" href="<?php echo JAK_rewrite::jakParseurl('logs', 'truncate');?>" data-title="<?php echo addslashes($jkl["g48"]);?>" data-text="<?php echo addslashes($jkl["e40"]);?>" data-type="warning" data-okbtn="<?php echo addslashes($jkl["g279"]);?>" data-cbtn="<?php echo addslashes($jkl["g280"]);?>"><i class="fa fa-exclamation-triangle"></i></a> <button class="btn btn-danger btn-sm btn-confirm" data-action="delete" data-title="<?php echo addslashes($jkl["g48"]);?>" data-text="<?php echo addslashes($jkl["e30"]);?>" data-type="warning" data-okbtn="<?php echo addslashes($jkl["g279"]);?>" data-cbtn="<?php echo addslashes($jkl["g280"]);?>"><i class="fa fa-trash-alt"></i></button>
</p>
<div class="clearfix"></div>
<?php } ?>

<table id="dynamic-data" class="table table-striped" cellspacing="0" width="100%">
<thead>
  <tr>
    <th style="width: 3%">#</th>
    <th style="width: 3%"><input type="checkbox" id="jak_delete_all"></th>
    <th style="width: 50%"><i class="fal fa-sticky-note" title="<?php echo $jkl["g146"];?>"></i></th>
    <th style="width: 25%"><i class="fal fa-file-user" title="<?php echo $jkl["g136"];?>"></i></th>
    <th style="width: 19%"><i class="fal fa-clock" title="<?php echo $jkl["g13"];?>"></i></th>
  </tr>
</thead>
<tfoot>
  <tr>
    <th style="width: 3%">#</th>
    <th style="width: 3%"><input type="checkbox" id="jak_delete_all"></th>
    <th style="width: 50%"><i class="fal fa-sticky-note" title="<?php echo $jkl["g146"];?>"></i></th>
    <th style="width: 25%"><i class="fal fa-file-user" title="<?php echo $jkl["g136"];?>"></i></th>
    <th style="width: 19%"><i class="fal fa-clock" title="<?php echo $jkl["g13"];?>"></i></th>
  </tr>
</tfoot>
<tbody></tbody>
</table>

</div>
</div>
<input type="hidden" name="action" id="action">
</form>
</div>
</div>

<?php } else { ?>

<div class="alert alert-info">
<?php echo $jkl['i3'];?>
</div>

<?php } ?>

</div>
		
<?php include_once 'footer.php';?>