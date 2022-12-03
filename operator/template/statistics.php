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
                <h3 class="info-title"><?php echo $sessCtotal;?></h3>
                <h6 class="stats-title"><?php echo $jkl["stat_s25"];?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="card-footer">
        <div class="stats">
          <i class="fa fa-history"></i> <?php echo $jkl["stat_s25"];?>
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
                <h3 class="info-title"><?php echo $commCtotal;?></h3>
                <h6 class="stats-title"><?php echo $jkl["stat_s26"];?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="card-footer">
        <div class="stats">
          <i class="fa fa-history"></i> <?php echo $jkl["stat_s26"];?>
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
                  <i class="fad fa-comments"></i>
                </div>
              </div>
              <div class="col-7 text-right">
                <h3 class="info-title"><?php echo $statsCtotal;?></h3>
                <h6 class="stats-title"><?php echo $jkl["stat_s10"];?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="card-footer">
        <div class="stats">
          <i class="fa fa-history"></i> <?php echo $jkl["stat_s10"];?>
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
                  <i class="fa fa-users"></i>
                </div>
              </div>
              <div class="col-7 text-right">
                <h3 class="info-title"><?php echo $visitCtotal;?></h3>
                <h6 class="stats-title"><?php echo $jkl["stat_s27"];?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="card-footer">
        <div class="stats">
          <i class="fa fa-history"></i> <?php echo $jkl["stat_s27"];?>
        </div>
      </div>
    </div>
  </div><!-- ./col -->
</div><!-- /.row -->

<?php if (JAK_SHOW_IPS) { ?>
<div class="card">
<div class="card-body">
<h3><i class="fa fa-map-marker-alt"></i> <?php echo $jkl["stat_s"];?></h3>
<div id="map_canvas"></div>
</div>
</div>
<?php } ?>

<div class="card">
<div class="card-body">
<h3><i class="fa fa-chart-bar"></i> <?php echo $jkl["stat_s3"];?></h3>
<form id="jak_statform" method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
<div class="row">
  <div class="col-md-6">
    <?php if (isset($JAK_DEPARTMENTS) && !empty($JAK_DEPARTMENTS)) { ?>
    <select name="jak_depid" id="jak_depid" class="selectpicker" data-live-search="true">
    
    <option value="0"<?php if (isset($_REQUEST["jak_depid"]) && $_REQUEST["jak_depid"] == '0') { ?> selected="selected"<?php } ?>><?php echo $jkl["g105"];?></option>
    
    <?php foreach($JAK_DEPARTMENTS as $v) { ?>
    
    <option value="<?php echo $v["id"];?>"<?php if (isset($_REQUEST["jak_depid"]) && $_REQUEST["jak_depid"] == $v["id"]) { ?> selected="selected"<?php } ?>><?php echo $v["title"];?></option>
    
    <?php } ?>
    </select>
    <?php } ?>
    <input type="hidden" name="start_date" id="start_date" value="<?php if (isset($_SESSION["stat_start_date"])) echo $_SESSION["stat_start_date"];?>" />
    <input type="hidden" name="end_date" id="end_date" value="<?php if (isset($_SESSION["stat_end_date"])) echo $_SESSION["stat_end_date"];?>" />
  </div>
  <div class="col-md-6">
    <div id="reportrange" class="pull-right">
        <i class="fa fa-calendar fa-lg"></i>
        <span><?php echo date("F j, Y", strtotime($_SESSION["stat_start_date"]));?> - <?php echo date("F j, Y", strtotime($_SESSION["stat_end_date"]));?></span> <b class="caret"></b>
    </div>
  </div>
</div>
</form>
<hr>
  
<div class="row">
  <div class="col-md-6">
    
    <div id="chart_operator" style="width: 100%; height: 300px; margin-right: 20px;"></div>
    
  </div>
  
  <div class="col-md-6">
    
    <div id="chart_feedback" style=";width: 100%; height: 300px;"></div>
    
  </div>
    
</div>

<h3 class="mt-3"><i class="fa fa-chart-pie"></i> <?php echo $jkl["m10"];?></h3>
<div class="row">
  <div class="col-md-6">
    
    <div id="chart" style="width: 100%; height: 300px; margin-right: 20px;"></div>
    
  </div>
  
  <div class="col-md-6">
    
    <div id="chart2" style=";width: 100%; height: 300px;"></div>
    
  </div>
    
</div>
</div>
</div>

</div>

<?php include_once 'footer.php';?>