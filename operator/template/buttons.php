<?php include_once 'header.php';?>

<div class="content">

<?php if ($errors) { ?>
<div class="alert alert-danger"><?php echo $errors["e"];?></div>
<?php } ?>

<form class="jak_form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">

<div class="card">
<div class="card-header">
  <h3 class="card-title"><i class="fal fa-images"></i> <?php echo $jkl["g164"];?> <a href="javascript:void(0)" class="jakweb-help" data-content="<?php echo $jkl["h15"];?>" data-original-title="<?php echo $jkl["t"];?>"><i class="fa fa-question-circle"></i></a></h3>
</div><!-- /.box-header -->
<div class="card-body">

  <div class="row">
    <div class="col-md-6">
      <div class="form-group form-file-upload form-file-simple">
        <input type="text" class="form-control inputFileVisible" placeholder="<?php echo $jkl['g165'];?>">
        <input type="file" class="inputFileHidden" name="uploadpp" accept="image/*">
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group form-file-upload form-file-simple">
        <input type="text" class="form-control inputFileVisible" placeholder="<?php echo $jkl['g166'];?>">
        <input type="file" class="inputFileHidden" name="uploadpp1" accept="image/*">
      </div>
    </div>
  </div>

</div>
<div class="card-footer">
  <button type="submit" name="upload" class="btn btn-primary"><?php echo $jkl["g127"];?></button>
</div>
</div>

<div class="card">
<div class="card-header">
  <h3 class="card-title"><i class="fa fa-images"></i> <?php echo $jkl["g71"];?></h3>
</div><!-- /.box-header -->
<div class="card-body">

  <div class="card-columns">
  <?php if (isset($BUTTONS_ALL) && !empty($BUTTONS_ALL)) foreach($BUTTONS_ALL as $k) { if (getimagesize(APP_PATH.JAK_FILES_DIRECTORY.'/buttons/'.$k) && strpos($k,"_on")) { ?>
    <div class="card card-body text-center">
     <img src="../<?php echo JAK_FILES_DIRECTORY;?>/buttons/<?php echo $k;?>" alt="<?php echo $k;?>" class="img-fluid center hover-button">
      <div class="card-body">
        <h4 class="card-title"><?php echo $k;?></h4>
        <a href="<?php echo JAK_rewrite::jakParseurl('buttons', 'delete', $k);?>" class="btn btn-danger btn-sm btn-confirm" data-title="<?php echo addslashes($jkl["g48"]);?>" data-text="<?php echo addslashes($jkl["e43"]);?>" data-type="warning" data-okbtn="<?php echo addslashes($jkl["g279"]);?>" data-cbtn="<?php echo addslashes($jkl["g280"]);?>"><i class="fa fa-trash-alt"></i></a>
      </div>
    </div>
  <?php } } ?>
  </div>

</div>
</div>

<div class="card">
<div class="card-header with-border">
  <h3 class="card-title"><i class="fal fa-image"></i> <?php echo $jkl["g298"];?> <a href="javascript:void(0)" class="jakweb-help" data-content="<?php echo $jkl["h15"];?>" data-original-title="<?php echo $jkl["t"];?>"><i class="fa fa-question-circle"></i></a></h3>
</div><!-- /.box-header -->
<div class="card-body">

  <div class="row">
    <div class="col-md-6">
      <div class="form-group form-file-upload form-file-simple">
        <input type="text" class="form-control inputFileVisible" placeholder="<?php echo $jkl['g165'];?>">
        <input type="file" class="inputFileHidden" name="uploadppsi" accept="image/*">
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group form-file-upload form-file-simple">
        <input type="text" class="form-control inputFileVisible" placeholder="<?php echo $jkl['g166'];?>">
        <input type="file" class="inputFileHidden" name="uploadppsi1" accept="image/*">
      </div>
    </div>
  </div>

</div>
<div class="card-footer">
  <button type="submit" name="upload" class="btn btn-primary"><?php echo $jkl["g127"];?></button>
</div>
</div>

<div class="card">
<div class="card-header">
  <h3 class="card-title"><i class="fa fa-image"></i> <?php echo $jkl["g292"];?></h3>
</div><!-- /.box-header -->
<div class="card-body">

  <div class="card-columns">
  <?php if (isset($SLIDEIMG_ALL) && !empty($SLIDEIMG_ALL)) foreach($SLIDEIMG_ALL as $k) { if (getimagesize(APP_PATH.JAK_FILES_DIRECTORY.'/slideimg/'.$k) && strpos($k,"_on")) { ?>
    <div class="card card-body text-center">
     <img src="../<?php echo JAK_FILES_DIRECTORY;?>/slideimg/<?php echo $k;?>" alt="<?php echo $k;?>" class="img-fluid center hover-button">
      <div class="card-body">
        <h4 class="card-title"><?php echo $k;?></h4>
        <a href="<?php echo JAK_rewrite::jakParseurl('buttons', 'deletef', $k);?>" class="btn btn-danger btn-sm btn-confirm" data-title="<?php echo addslashes($jkl["g48"]);?>" data-text="<?php echo addslashes($jkl["e43"]);?>" data-type="warning" data-okbtn="<?php echo addslashes($jkl["g279"]);?>" data-cbtn="<?php echo addslashes($jkl["g280"]);?>"><i class="fa fa-trash-alt"></i></a>
      </div>
    </div>
  <?php } } ?>
  </div>

</div>
</div>

</form>

</div>

<?php include_once 'footer.php';?>