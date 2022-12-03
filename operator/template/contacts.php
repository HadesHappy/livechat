<?php include_once 'header.php';?>

<div class="content">

<!-- Small boxes (Stat box) -->
<div class="row">
  <div class="col-md-4">
    <div class="card card-stats">
      <div class="card-body">
        <div class="statistics statistics-horizontal">
          <div class="info info-horizontal">
            <div class="row">
              <div class="col-5">
                <div class="icon icon-primary icon-circle">
                  <i class="fa fa-inbox-in"></i>
                </div>
              </div>
              <div class="col-7 text-right">
                <h3 class="info-title"><?php echo $totalAll;?></h3>
                <h6 class="stats-title"><?php echo $jkl["stat_s29"];?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="card-footer">
        <div class="stats">
          <i class="fa fa-history"></i> <?php echo $jkl["stat_s29"];?>
        </div>
      </div>
    </div>
  </div><!-- ./col -->
  <div class="col-md-4">
    <div class="card card-stats">
      <div class="card-body">
        <div class="statistics statistics-horizontal">
          <div class="info info-horizontal">
            <div class="row">
              <div class="col-5">
                <div class="icon icon-info icon-circle">
                  <i class="fa fa-inbox-out"></i>
                </div>
              </div>
              <div class="col-7 text-right">
                <h3 class="info-title"><?php echo $totalAllc;?></h3>
                <h6 class="stats-title"><?php echo $jkl["stat_s30"];?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="card-footer">
        <div class="stats">
          <i class="fa fa-history"></i> <?php echo $jkl["stat_s30"];?>
        </div>
      </div>
    </div>
  </div><!-- ./col -->
  <div class="col-md-4">
    <div class="card card-stats">
      <div class="card-body">
        <div class="statistics statistics-horizontal">
          <div class="info info-horizontal">
            <div class="row">
              <div class="col-5">
                <div class="icon icon-success icon-circle">
                  <i class="fa fa-vote-nay"></i>
                </div>
              </div>
              <div class="col-7 text-right">
                <h3 class="info-title"><?php echo $bounce_percentage;?>%</h3>
                <h6 class="stats-title"><?php echo $jkl["stat_s31"];?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="card-footer">
        <div class="stats">
          <i class="fa fa-history"></i> <?php echo $jkl["stat_s31"];?>
        </div>
      </div>
    </div>
  </div><!-- ./col -->
</div><!-- /.row -->

<?php if (isset($totalAll) && !empty($totalAll)) { ?>

<form method="post" class="jak_form" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
<div class="card">
<div class="card-body">

<?php if (JAK_SUPERADMINACCESS) { ?>
<p class="pull-right">
<a class="btn btn-warning btn-sm btn-confirm" href="<?php echo JAK_rewrite::jakParseurl('contacts', 'truncate');?>" data-title="<?php echo addslashes($jkl["g48"]);?>" data-text="<?php echo addslashes($jkl["e40"]);?>" data-type="warning" data-okbtn="<?php echo addslashes($jkl["g279"]);?>" data-cbtn="<?php echo addslashes($jkl["g280"]);?>"><i class="fa fa-exclamation-triangle"></i></a> <button class="btn btn-danger btn-sm btn-confirm" data-action="delete" data-title="<?php echo addslashes($jkl["g48"]);?>" data-text="<?php echo addslashes($jkl["e30"]);?>" data-type="warning" data-okbtn="<?php echo addslashes($jkl["g279"]);?>" data-cbtn="<?php echo addslashes($jkl["g280"]);?>"><i class="fa fa-trash-alt"></i></button>
</p>
<div class="clearfix"></div>
<?php } ?>

<table id="dynamic-data" class="table table-striped" cellspacing="0" width="100%">
<thead>
  <tr>
    <th style="width: 3%">#</th>
    <th style="width: 3%"><input type="checkbox" id="jak_delete_contacts"></th>
    <th><i class="fal fa-user" title="<?php echo $jkl["g54"];?>"></i></th>
    <th><i class="fal fa-envelope" title="<?php echo $jkl["l5"];?>"></i></th>
    <th><i class="fal fa-clock" title="<?php echo $jkl["stat_s30"];?>"></i></th>
    <th><i class="fal fa-calendar-day" title="<?php echo $jkl["g13"];?>"></i></th>
  </tr>
</thead>
<tfoot>
  <tr>
    <th style="width: 3%">#</th>
    <th style="width: 3%"></th>
    <th><i class="fal fa-user" title="<?php echo $jkl["g54"];?>"></i></th>
    <th><i class="fal fa-envelope" title="<?php echo $jkl["l5"];?>"></i></th>
    <th><i class="fal fa-clock" title="<?php echo $jkl["stat_s30"];?>"></i></th>
    <th><i class="fal fa-calendar-day" title="<?php echo $jkl["g13"];?>"></i></th>
  </tr>
</tfoot>
<tbody></tbody>
</table>

</div>
</div>
<input type="hidden" name="action" id="action">
</form>

<?php } else { ?>

<div class="alert alert-info">
<?php echo $jkl['i3'];?>
</div>

<?php } ?>

</div>
    
<?php include_once 'footer.php';?>