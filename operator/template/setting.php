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
                <div class="col-4">
                  <div class="icon icon-info icon-circle">
                    <i class="fa fa-building"></i>
                  </div>
                </div>
                <div class="col-8 text-right">
                  <h3 class="info-title"><?php echo $totalAllD;?></h3>
                  <h6 class="stats-title"><?php echo $jkl["stat_s50"];?></h6>
                </div>
              </div>
            </div>
          </div>
        </div>
        <hr>
        <div class="card-footer">
          <div class="stats">
            <i class="fa fa-building"></i> <?php echo $jkl["stat_s50"];?>
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
                    <i class="fa fa-file"></i>
                  </div>
                </div>
                <div class="col-7 text-right">
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
      <a class="nav-link active" href="<?php echo JAK_rewrite::jakParseurl('settings');?>"><?php echo $jkl["m32"];?></a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="<?php echo JAK_rewrite::jakParseurl('settings', 'email');?>"><?php echo $jkl["m35"];?></a>
    </li>
  </ul>
</p>

<form method="post" class="jak_form" action="<?php echo $_SERVER['REQUEST_URI'];?>">

  <div class="row">
   <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="fa fa-cogs"></i> <?php echo $jkl["m32"];?></h3>
      </div><!-- /.box-header -->
      <div class="card-body">

       <div class="form-group">
        <label for="title"><?php echo $jkl["g16"];?></label>
        <input type="text" name="jak_title" id="title" class="form-control" value="<?php echo JAK_TITLE;?>" placeholder="<?php echo $jkl["g16"];?>">
      </div>

      <div class="form-group">
        <label for="email"><?php echo $jkl["l5"];?> <a href="javascript:void(0)" class="jakweb-help" data-content="<?php echo $jkl["h"];?>" data-original-title="<?php echo $jkl["t"];?>"><i class="fa fa-question-circle"></i></a></label>
        <input type="text" name="jak_email" id="email" class="form-control<?php if (isset($errors["e1"])) echo " is-invalid";?>" value="<?php echo JAK_EMAIL;?>" placeholder="<?php echo $jkl["l5"];?>">
      </div>

      <div class="form-group">
        <label for="emailcc"><?php echo $jkl["g201"];?> <a href="javascript:void(0)" class="jakweb-help" data-content="<?php echo $jkl["h16"];?>" data-original-title="<?php echo $jkl["t"];?>"><i class="fa fa-question-circle"></i></a></label>
        <input type="text" name="jak_emailcc" id="emailcc" class="form-control" value="<?php echo JAK_EMAILCC;?>" placeholder="<?php echo $jkl["g201"];?>">
      </div>

      <label><?php echo $jkl["g303"];?></label>
      <div class="form-check form-check-radio">
        <label class="form-check-label">
          <input class="form-check-input" type="radio" name="jak_holidaym" value="0"<?php if (JAK_HOLIDAY_MODE == 0) echo " checked";?>>
          <span class="form-check-sign"></span>
          <?php echo $jkl["g304"];?>
        </label>
      </div>
      <div class="form-check form-check-radio">
        <label class="form-check-label">
          <input class="form-check-input" type="radio" name="jak_holidaym" value="1"<?php if (JAK_HOLIDAY_MODE == 1) echo " checked";?>>
          <span class="form-check-sign"></span>
          <?php echo $jkl["g1"];?>
        </label>
      </div>
      <div class="form-check form-check-radio">
        <label class="form-check-label">
          <input class="form-check-input" type="radio" name="jak_holidaym" value="2"<?php if (JAK_HOLIDAY_MODE == 2) echo " checked";?>>
          <span class="form-check-sign"></span>
          <?php echo $jkl["g305"];?>
        </label>
      </div>

      <label><?php echo $jkl["g242"];?></label>
      <div class="form-check form-check-radio">
       <label class="form-check-label">
         <input class="form-check-input" type="radio" name="jak_showip" value="1"<?php if (JAK_SHOW_IPS == 1) echo " checked";?>>
         <span class="form-check-sign"></span>
         <?php echo $jkl["g19"];?>
       </label>
     </div>
     <div class="form-check form-check-radio">
      <label class="form-check-label">
        <input class="form-check-input" type="radio" name="jak_showip" value="0"<?php if (JAK_SHOW_IPS == 0) echo " checked";?>>
        <span class="form-check-sign"></span>
        <?php echo $jkl["g18"];?>
      </label>
    </div>

    <label><?php echo $jkl["g92"];?></label>
    <div class="form-check form-check-radio">
     <label class="form-check-label">
       <input class="form-check-input" type="radio" name="jak_rating" value="1"<?php if (JAK_CRATING == 1) echo " checked";?>>
       <span class="form-check-sign"></span>
       <?php echo $jkl["g19"];?>
     </label>
   </div>
   <div class="form-check form-check-radio">
    <label class="form-check-label">
      <input class="form-check-input" type="radio" name="jak_rating" value="0"<?php if (JAK_CRATING == 0) echo " checked";?>>
      <span class="form-check-sign"></span>
      <?php echo $jkl["g18"];?>
    </label>
  </div>

  <label><?php echo $jkl["g234"];?></label>
  <div class="form-check form-check-radio">
   <label class="form-check-label">
     <input class="form-check-input" type="radio" name="jak_trans" value="1"<?php if (JAK_SEND_TSCRIPT == 1) echo " checked";?>>
     <span class="form-check-sign"></span>
     <?php echo $jkl["g19"];?>
   </label>
 </div>
 <div class="form-check form-check-radio">
  <label class="form-check-label">
    <input class="form-check-input" type="radio" name="jak_trans" value="0"<?php if (JAK_SEND_TSCRIPT == 0) echo " checked";?>>
    <span class="form-check-sign"></span>
    <?php echo $jkl["g18"];?>
  </label>
</div>

<label><?php echo $jkl["g266"];?></label>
<div class="form-check form-check-radio">
 <label class="form-check-label">
   <input class="form-check-input" type="radio" name="jak_openop" value="1"<?php if (JAK_OPENOP == 1) echo " checked";?>>
   <span class="form-check-sign"></span>
   <?php echo $jkl["g19"];?>
 </label>
</div>
<div class="form-check form-check-radio">
  <label class="form-check-label">
    <input class="form-check-input" type="radio" name="jak_openop" value="0"<?php if (JAK_OPENOP == 0) echo " checked";?>>
    <span class="form-check-sign"></span>
    <?php echo $jkl["g18"];?>
  </label>
</div>

<div class="form-group">
  <label for="proactive_time"><?php echo $jkl["g327"];?></label>
  <input type="number" name="jak_proactive_time" id="proactive_time" class="form-control" min="1" max="30" step="1" value="<?php echo JAK_PROACTIVE_TIME;?>">
</div>

<div class="form-group">
  <label for="user_left"><?php echo $jkl["g253"];?> <a href="javascript:void(0)" class="jakweb-help" data-content="<?php echo $jkl["h18"];?>" data-original-title="<?php echo $jkl["t"];?>"><i class="fa fa-question-circle"></i></a></label>
  <input type="number" name="jak_user_left" id="user_left" class="form-control" min="30" max="1800" step="1" value="<?php echo JAK_CLIENT_LEFT;?>">
</div>

<div class="form-group">
  <label for="user_expire"><?php echo $jkl["g254"];?> <a href="javascript:void(0)" class="jakweb-help" data-content="<?php echo $jkl["h18"];?>" data-original-title="<?php echo $jkl["t"];?>"><i class="fa fa-question-circle"></i></a></label>
  <input type="number" name="jak_user_expired" id="user_expire" class="form-control" min="120" max="2000" step="1" value="<?php echo JAK_CLIENT_EXPIRED;?>">
</div>

<div class="form-group">
  <label for="pushrem"><?php echo $jkl["g311"];?> <a href="javascript:void(0)" class="jakweb-help" data-content="<?php echo $jkl["h12"];?>" data-original-title="<?php echo $jkl["t"];?>"><i class="fa fa-question-circle"></i></a></label>
  <input type="number" name="jak_pushrem" id="pushrem" class="form-control" min="15" max="300" step="1" value="<?php echo JAK_PUSH_REMINDER;?>">
</div>

<div class="form-group">
  <p><label><?php echo $jkl["g22"];?></label></p>
  <select name="jak_lang" class="selectpicker" title="<?php echo $jkl["g22"];?>" data-size="4" data-live-search="true">
    <option disabled><?php echo $jkl["g22"];?></option>
    <?php if (isset($lang_files) && is_array($lang_files)) foreach($lang_files as $lf) { ?><option value="<?php echo $lf;?>"<?php if (JAK_LANG == $lf) { ?> selected="selected"<?php } ?>><?php echo ucwords($lf);?></option><?php } ?>
  </select>
</div>

<div class="form-group">
  <label for="dateformat"><?php echo $jkl["g23"];?></label>
  <input type="text" name="jak_date" id="dateformat" class="form-control<?php if (isset($errors["e2"])) echo " is-invalid";?>" value="<?php echo JAK_DATEFORMAT;?>" placeholder="d.m.Y">
  <span class="form-text"><?php echo JAK_base::jakTimesince(time(), JAK_DATEFORMAT, "");?></span>
</div>

<div class="form-group">
  <label for="timeformat"><?php echo $jkl["g24"];?></label>
  <input type="text" name="jak_time" id="timeformat" class="form-control<?php if (isset($errors["e3"])) echo " is-invalid";?>" value="<?php echo JAK_TIMEFORMAT;?>" placeholder=" g:i a">
  <span class="form-text"><?php echo JAK_base::jakTimesince(time(), "", JAK_TIMEFORMAT);?></span>
</div>

<div class="form-group">
  <p><label><?php echo $jkl["g25"];?></label></p>
  <select name="jak_timezone_server" class="selectpicker" title="<?php echo $jkl["g25"];?>" data-size="8" data-live-search="true">
    <option disabled><?php echo $jkl["g25"];?></option>
    <?php include_once "timezoneserver.php";?>
  </select>
</div>

<div class="form-group">
  <label for="clientfiles"><?php echo $jkl["g109"];?></label>
  <input type="text" name="allowed_files" id="clientfiles" class="form-control<?php if (isset($errors["e3"])) echo " is-invalid";?>" value="<?php echo ($jakhs['hostactive'] ? $jakhs['filetype'] : JAK_ALLOWED_FILES);?>" placeholder=".zip,.rar,.jpg,.jpeg,.png,.gif"<?php if ($jakhs['hostactive']) echo " readonly";?>>
</div>

<div class="form-group">
  <label for="opfiles"><?php echo $jkl["g128"];?></label>
  <input type="text" name="allowedo_files" id="opfiles" class="form-control<?php if (isset($errors["e3"])) echo " is-invalid";?>" value="<?php echo ($jakhs['hostactive'] ? $jakhs['filetypeo'] : JAK_ALLOWEDO_FILES);?>" placeholder=".zip,.rar,.jpg,.jpeg,.png,.gif"<?php if ($jakhs['hostactive']) echo " readonly";?>>
</div>

<div class="row">
  <div class="col-md-6">
   <div class="form-group">
    <label for="avatwidth"><?php echo $jkl["g44"];?></label>
    <input type="number" name="jak_avatwidth" id="avatwidth" class="form-control" value="<?php echo JAK_USERAVATWIDTH;?>" placeholder="<?php echo $jkl["g42"];?>">
  </div>
</div>
<div class="col-md-6">
 <div class="form-group">
  <label for="avaheight"><?php echo $jkl["g44"];?></label>
  <input type="number" name="jak_avatheight" id="avaheight" class="form-control" value="<?php echo JAK_USERAVATHEIGHT;?>" placeholder="<?php echo $jkl["g43"];?>">
</div>
</div>
</div>

</div>
<div class="card-footer">
 <button type="submit" name="save" class="btn btn-primary"><?php echo $jkl["g38"];?></button>
</div>
</div>

<div class="card">
 <div class="card-header">
  <h3 class="card-title"><i class="fa fa-mobile"></i> <?php echo $jkl["g312"];?></h3>
</div><!-- /.box-header -->
<div class="card-body">

  <div class="form-group">
   <label for="nativtok"><?php echo $jkl["u51"];?></label>
   <input type="text" name="jak_nativtok" id="nativtok" class="form-control" value="<?php echo JAK_NATIVE_APP_TOKEN;?>">
 </div>

 <div class="form-group">
   <label for="nativkey"><?php echo $jkl["u52"];?></label>
   <input type="text" name="jak_nativkey" id="nativkey" class="form-control" value="<?php echo JAK_NATIVE_APP_KEY;?>">
 </div>

</div>
<div class="card-footer">
  <button type="submit" name="save" class="btn btn-primary"><?php echo $jkl["g38"];?></button>
</div>
</div>

</div>
<div class="col-md-6">
	
  <div class="card box-info">
    <div class="card-header">
      <h3 class="card-title"><i class="fa fa-music"></i> <?php echo $jkl["g258"];?></h3>
    </div><!-- /.box-header -->
    <div class="card-body">

     <div class="form-group">
      <p><label><?php echo $jkl["g256"];?></label></p>
      <select name="jak_ringtone" class="selectpicker play-tone" title="<?php echo $jkl["g256"];?>" data-size="4" data-live-search="true">
        <?php if (isset($sound_files) && is_array($sound_files)) foreach($sound_files as $rt) { ?><option value="<?php echo $rt;?>"<?php if (JAK_RING_TONE == $rt) echo ' selected';?>><?php echo $rt;?></option><?php } ?>
      </select>
    </div>

    <div class="form-group">
      <p><label><?php echo $jkl["g257"];?></label></p>
      <select name="jak_msgtone" class="selectpicker play-tone" title="<?php echo $jkl["g257"];?>" data-size="4" data-live-search="true">
        <?php if (isset($sound_files) && is_array($sound_files)) foreach($sound_files as $sfn) { ?><option value="<?php echo $sfn;?>"<?php if (JAK_MSG_TONE == $sfn) echo ' selected';?>><?php echo $sfn;?></option><?php } ?>
      </select>
    </div>

  </div>
  <div class="card-footer">
   <button type="submit" name="save" class="btn btn-primary"><?php echo $jkl["g38"];?></button>
 </div>
</div>

<div class="card box-info">
  <div class="card-header">
    <h3 class="card-title"><i class="fa fa-user"></i> <?php echo $jkl["g318"];?></h3>
  </div><!-- /.box-header -->
  <div class="card-body">

   <label><?php echo $jkl["g191"];?></label>
   <div class="form-check form-check-radio">
    <label class="form-check-label">
     <input class="form-check-input" type="radio" name="showalert" value="1"<?php if (JAK_PRO_ALERT == 1) echo " checked";?>>
     <span class="form-check-sign"></span>
     <?php echo $jkl["g19"];?>
   </label>
 </div>
 <div class="form-check form-check-radio">
  <label class="form-check-label">
    <input class="form-check-input" type="radio" name="showalert" value="0"<?php if (JAK_PRO_ALERT == 0) echo " checked";?>>
    <span class="form-check-sign"></span>
    <?php echo $jkl["g18"];?>
  </label>
</div>

<div class="form-group">
  <label for="jak_icon"><?php echo $jkl["g344"];?></label>
  <div class="input-group">
    <input type="text" name="jak_engage_icon" class="form-control" value="<?php echo JAK_ENGAGE_ICON;?>">
    <div class="input-group-append">
      <span class="input-group-text">
        <i class="fa <?php echo JAK_ENGAGE_ICON;?>"></i>&nbsp;<a href="https://fontawesome.com/v5/search" target="_blank"><i class="fa fa-link"></i></a>
      </span>
    </div>
  </div>
</div>

<div class="form-group">
  <p><label><?php echo $jkl["g257"];?></label></p>
  <select name="jak_client_sound" class="selectpicker play-tone" title="<?php echo $jkl["g257"];?>" data-size="4" data-live-search="true">
    <?php if (isset($sound_files) && is_array($sound_files)) foreach($sound_files as $cs) { ?><option value="<?php echo $cs;?>"<?php if (JAK_CLIENT_SOUND == $cs) echo ' selected';?>><?php echo $cs;?></option><?php } ?>
  </select>
</div>

<div class="form-group">
  <p><label><?php echo $jkl["g316"];?></label></p>
  <select name="jak_engage_sound" class="selectpicker play-tone" title="<?php echo $jkl["g316"];?>" data-size="4" data-live-search="true">
    <?php if (isset($sound_files) && is_array($sound_files)) foreach($sound_files as $sfc) { ?><option value="<?php echo $sfc;?>"<?php if (JAK_ENGAGE_SOUND == $sfc) echo ' selected';?>><?php echo $sfc;?></option><?php } ?>
  </select>
</div>

<label><?php echo $jkl["g317"];?></label>
<div class="form-check form-check-radio">
  <label class="form-check-label">
   <input class="form-check-input" type="radio" name="jak_client_push_not" value="1"<?php if (JAK_CLIENT_PUSH_NOT == 1) echo " checked";?>>
   <span class="form-check-sign"></span>
   <?php echo $jkl["g19"];?>
 </label>
</div>
<div class="form-check form-check-radio">
  <label class="form-check-label">
    <input class="form-check-input" type="radio" name="jak_client_push_not" value="0"<?php if (JAK_CLIENT_PUSH_NOT == 0) echo " checked";?>>
    <span class="form-check-sign"></span>
    <?php echo $jkl["g18"];?>
  </label>
</div>

<label><?php echo $jkl["g319"];?></label>
<div class="form-check form-check-radio">
  <label class="form-check-label">
   <input class="form-check-input" type="radio" name="jak_live_online_status" value="1"<?php if (JAK_LIVE_ONLINE_STATUS == 1) echo " checked";?>>
   <span class="form-check-sign"></span>
   <?php echo $jkl["g19"];?>
 </label>
</div>
<div class="form-check form-check-radio">
  <label class="form-check-label">
    <input class="form-check-input" type="radio" name="jak_live_online_status" value="0"<?php if (JAK_LIVE_ONLINE_STATUS == 0) echo " checked";?>>
    <span class="form-check-sign"></span>
    <?php echo $jkl["g18"];?>
  </label>
</div>

<label><?php echo $jkl["g331"];?></label>
<div class="form-check form-check-radio">
  <label class="form-check-label">
   <input class="form-check-input" type="radio" name="jak_chat_upload_standard" value="1"<?php if (JAK_CHAT_UPLOAD_STANDARD == 1) echo " checked";?>>
   <span class="form-check-sign"></span>
   <?php echo $jkl["g19"];?>
 </label>
</div>
<div class="form-check form-check-radio">
  <label class="form-check-label">
    <input class="form-check-input" type="radio" name="jak_chat_upload_standard" value="0"<?php if (JAK_CHAT_UPLOAD_STANDARD == 0) echo " checked";?>>
    <span class="form-check-sign"></span>
    <?php echo $jkl["g18"];?>
  </label>
</div>

</div>
<div class="card-footer">
 <button type="submit" name="save" class="btn btn-primary"><?php echo $jkl["g38"];?></button>
</div>
</div>

<div class="card box-danger">
  <div class="card-header">
    <h3 class="card-title"><i class="fa fa-ban"></i> <?php echo $jkl["g97"];?></h3>
  </div><!-- /.box-header -->
  <div class="card-body">

   <div class="form-group">
    <label for="twoco"><?php echo $jkl["g95"];?> <a href="javascript:void(0)" class="jakweb-help" data-content="<?php echo $jkl["h3"];?>" data-original-title="<?php echo $jkl["t"];?>"><i class="fa fa-question-circle"></i></a></label>
    <textarea name="ip_block" rows="5" class="form-control"><?php echo JAK_IP_BLOCK;?></textarea>
  </div>

  <div class="form-group">
    <label for="twoco"><?php echo $jkl["g96"];?> <a href="javascript:void(0)" class="jakweb-help" data-content="<?php echo $jkl["h4"];?>" data-original-title="<?php echo $jkl["t"];?>"><i class="fa fa-question-circle"></i></a></label>
    <textarea name="email_block" rows="5" class="form-control"><?php echo JAK_EMAIL_BLOCK;?></textarea>
  </div>

</div>
<div class="card-footer">
 <button type="submit" name="save" class="btn btn-primary"><?php echo $jkl["g38"];?></button>
</div>
</div>

<div class="card">
  <div class="card-header">
    <h3 class="card-title"><i class="fa fa-sms"></i> <?php echo $jkl["g155"];?></h3>
  </div><!-- /.box-header -->
  <div class="card-body">

   <label><a href="http://www.twilio.com">Twilio</a>, <a href="http://www.plivo.com">Plivo</a> <?php echo $jkl["g157"];?> <a href="http://www.nexmo.com">Nexmo</a></label>
   <div class="form-check form-check-radio">
    <label class="form-check-label">
     <input class="form-check-input" type="radio" name="jak_twilio_nexmo" value="1"<?php if (JAK_TWILIO_NEXMO == 1) echo " checked";?>>
     <span class="form-check-sign"></span>
     Twilio
   </label>
 </div>
 <div class="form-check form-check-radio">
  <label class="form-check-label">
    <input class="form-check-input" type="radio" name="jak_twilio_nexmo" value="0"<?php if (JAK_TWILIO_NEXMO == 0) echo " checked";?>>
    <span class="form-check-sign"></span>
    Nexmo
  </label>
</div>
<div class="form-check form-check-radio">
  <label class="form-check-label">
    <input class="form-check-input" type="radio" name="jak_twilio_nexmo" value="2"<?php if (JAK_TWILIO_NEXMO == 2) echo " checked";?>>
    <span class="form-check-sign"></span>
    Plivo
  </label>
</div>
<div class="form-check form-check-radio">
  <label class="form-check-label">
    <input class="form-check-input" type="radio" name="jak_twilio_nexmo" value="3"<?php if (JAK_TWILIO_NEXMO == 3) echo " checked";?>>
    <span class="form-check-sign"></span>
    CM.COM
  </label>
</div>

<div class="form-group">
  <label for="tw_msg"><?php echo $jkl["g151"];?></label>
  <input type="text" name="jak_tw_msg" id="tw_msg" class="form-control" value="<?php echo JAK_TW_MSG;?>" maxlength="160">
</div>

<div class="form-group">
  <label for="tw_phone"><?php echo $jkl["g152"];?></label>
  <input type="text" name="jak_tw_phone" id="tw_phone" class="form-control" value="<?php echo JAK_TW_PHONE;?>">
</div>

<div class="form-group">
  <label for="tw_sid"><?php echo $jkl["g153"];?></label>
  <input type="text" name="jak_tw_sid" id="tw_sid" class="form-control" value="<?php echo JAK_TW_SID;?>">
</div>

<div class="form-group">
  <label for="tw_token"><?php echo $jkl["g154"];?></label>
  <input type="text" name="jak_tw_token" id="tw_token" class="form-control" value="<?php echo JAK_TW_TOKEN;?>">
</div>

</div>
<div class="card-footer">
 <button type="submit" name="save" class="btn btn-primary"><?php echo $jkl["g38"];?></button>
</div>
</div>

</div>
</div>

</form>

</div>

<?php include_once 'footer.php';?>