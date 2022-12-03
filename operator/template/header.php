<!DOCTYPE html>
<html lang="<?php echo $USER_LANGUAGE;?>">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no">
  <meta name="description" content="Live Chat 3, the professional and easy way to help your webiste visitors">
  <meta name="keywords" content="Your premium Live Support/Chat application from JAKWEB">
  <meta name="author" content="Live Chat 3">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <title><?php echo $SECTION_TITLE;?> - <?php echo JAK_TITLE;?></title>

  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo BASE_URL_ORIG;?>css/stylesheet.css?=<?php echo JAK_UPDATED;?>" type="text/css" media="screen">

  <?php if ($jkl["rtlsupport"]) { ?>
  <!-- RTL Support -->
  <link rel="stylesheet" href="https://cdn.rtlcss.com/bootstrap/v4.5.3/css/bootstrap.min.css" integrity="sha384-P4uhUIGk/q1gaD/NdgkBIl3a6QywJjlsFJFk7SPRdruoGddvRVSwv5qFnvZ73cpz" crossorigin="anonymous">
  <!-- End RTL Support -->
  <?php } ?>

  <link rel="stylesheet" href="<?php echo BASE_URL;?>css/screen.css?=<?php echo JAK_UPDATED;?>" type="text/css" media="screen">
  
  <!-- Le fav and touch icons -->
  <link rel="shortcut icon" href="<?php echo BASE_URL_ORIG;?>img/ico/favicon.ico">
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo BASE_URL_ORIG;?>img/ico/144.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo BASE_URL_ORIG;?>img/ico/114.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo BASE_URL_ORIG;?>img/ico/72.png">
  <link rel="apple-touch-icon-precomposed" href="<?php echo BASE_URL_ORIG;?>img/ico/57.png">
   
</head>
<body<?php echo (!JAK_USERID ? ' class="login-page"' : (($jakuser->getVar("navsidebar") && $jkl["rtlsupport"]) ? ' class="rtl sidebar-mini rtl-active"' : (($jakuser->getVar("navsidebar") && !$jkl["rtlsupport"]) ? ' class="sidebar-mini"' : '')));?>>

<?php if (JAK_USERID) { ?>
  <div class="wrapper">
    <div class="sidebar" data-color="<?php echo $jakuser->getVar("themecolour");?>">
      <!--
        Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red | yellow"
    -->
      <div class="logo">
        <a href="<?php echo BASE_URL_ADMIN;?>" class="simple-text logo-mini">
          <?php echo $short_title;?>
        </a>
        <a href="<?php echo BASE_URL_ADMIN;?>" class="simple-text logo-normal">
          <?php echo JAK_TITLE;?>
        </a>
        <div class="navbar-minimize">
          <button id="minimizeSidebar" class="btn btn-simple btn-icon btn-neutral btn-round">
            <i class="fas fa-ellipsis-h text_align-center visible-on-sidebar-regular"></i>
            <i class="fas fa-ellipsis-v visible-on-sidebar-mini"></i>
          </button>
        </div>
      </div>
      <div class="sidebar-wrapper" id="sidebar-wrapper">
        <div class="user">
          <div class="photo">
            <img src="<?php echo BASE_URL_ORIG.basename(JAK_FILES_DIRECTORY).$jakuser->getVar("picture");?>" alt="operator image">
          </div>
          <div class="info">
            <a href="<?php echo JAK_rewrite::jakParseurl('users','edit',JAK_USERID);?>">
              <span>
                <?php echo $jakuser->getVar("name");?>
              </span>
            </a>
          </div>
        </div>
        <?php include_once 'navbar.php';?>
      </div>
    </div>
    <div class="main-panel" id="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-transparent bg-primary navbar-absolute">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <div class="navbar-toggle">
              <button type="button" class="navbar-toggler">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </button>
            </div>
            <a class="navbar-brand d-none d-sm-none d-md-block" href="javascript:void(0)"><?php echo $SECTION_TITLE;?></a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end" id="navigation">
            
            <ul class="navbar-nav">
              <li class="nav-item dropdown" id="chatRequests" style="display: none">
                <a class="nav-link dropdown-toggle" id="navbarDropdownLiveChat" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="<?php echo $jkl['g5'];?>">
                  <i class="fa fa-comments-alt"></i>
                  <span class="notification" id="totalchats" style="display:none"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownLiveChat" id="currentConv">
                </div>
              </li>

              <?php if ($jakuser->getVar("operatorlist") || $jakuser->getVar("operatorchat")){?>
                <li class="nav-item dropdown" id="opRequests" style="display: none">
                <a class="nav-link dropdown-toggle" id="navbarDropdownOperators" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="<?php echo $jkl['g134'];?>">
                  <i class="fad fa-user-headset"></i>
                  <span class="notification" id="totalops" style="display:none"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownOperators" id="operatorOnline">
                </div>
              </li>
              <?php } ?>

              <li class="nav-item">
                <a href="javascript:void(0)" id="sound_alert" onclick="toggleAlert()" class="nav-link" title="<?php echo $jkl["h8"];?>"><i class="fas fa-volume<?php echo ($jakuser->getVar("sound") ? '' : '-mute');?>"></i></a>
              </li>
              <li class="nav-item">
                <a href="javascript:void(0)" id="push_alert" onclick="togglePush()" class="nav-link text-<?php echo ($jakuser->getVar("push_notifications") ? 'success' : 'danger');?>" title="<?php echo $jkl["u16"];?>"><i class="fas fa-mobile-alt"></i></a>
              </li>
              <?php if ($jakuser->getVar("operatorchatpublic") == 1){;?>
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link" id="operator_chat" title="<?php echo $jkl["h9"];?>" onclick="if(navigator.userAgent.toLowerCase().indexOf('opera') != -1 && window.event.preventDefault) window.event.preventDefault();this.newWindow = window.open('<?php echo JAK_rewrite::jakParseurl('chat');?>', 'lsr', 'toolbar=0,scrollbars=1,location=0,status=1,menubar=0,width=800,height=520,resizable=1');this.newWindow.focus();this.newWindow.opener=window;return false;"><i class="fas fa-comment-dots"></i></a>
              </li>
              <?php } ?>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="<?php echo $jkl['g10'];?>">
                  <i id="operator-status-colour" class="fas fa-user-clock<?php if ($jakuser->getVar("available") == 0) { echo ' text-danger'; } elseif ($jakuser->getVar("available") == 2) { echo ' text-warning'; } else { echo ' text-success';}?>"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                  <a class="dropdown-item" href="javascript:void(0)" onclick="toggleAvailable(1,<?php echo $JAK_UONLINE;?>)"><i class="fas fa-clock text-success"></i> <?php echo $jkl["g"];?></a>
                  <a class="dropdown-item" href="javascript:void(0)" onclick="toggleAvailable(2,<?php echo $JAK_UONLINE;?>)"><i class="fas fa-clock text-warning"></i> <?php echo $jkl["g202"];?></a>
                  <a class="dropdown-item" href="javascript:void(0)" onclick="toggleAvailable(0,0)"><i class="fas fa-clock text-danger"></i> <?php echo $jkl["g1"];?></a>
                </div>
              </li>
              <li class="nav-item">
                <a href="<?php echo JAK_rewrite::jakParseurl('logout');?>" class="nav-link btn-confirm" data-title="<?php echo addslashes($jkl["l18"]);?>" data-text="<?php echo addslashes($jkl["l20"]);?>" data-type="warning" data-okbtn="<?php echo addslashes($jkl["g279"]);?>" data-cbtn="<?php echo addslashes($jkl["g280"]);?>" title="<?php echo $jkl["l18"];?>"><i class="fas fa-power-off"></i></a>
              </li>
            </ul>
          </div>
        </div>
      </nav><!-- End Navbar -->
    <div class="panel-header panel-header-sm">
    </div><!-- End Header -->
    <div class="content" id="topAlerts" style="display:none">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-stats">
            <div class="card-body">

              <!-- Show the user that the connection to the server has been interrupted -->
              <div class="alert alert-danger" id="connection-error" style="display:none"><i class="fa fa-exclamation-triangle"></i> <?php echo $jkl["g331"];?></div>

              <!-- Transfer Message -->
              <div id="transfer"></div>

            </div>
          </div>
        </div>
    </div>
  </div>

<?php } ?>