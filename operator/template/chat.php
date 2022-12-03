<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
  	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
  	<meta name="description" content="Live Chat PHP">
  	<meta name="keywords" content="Your premium Live Support/Chat application from JAKWEB">
  	<meta name="author" content="Live Chat PHP">
	<title><?php if ($page) { ?><?php echo ucwords($page);?> - <?php } echo JAK_TITLE;?></title>
	<link rel="shortcut icon" href="<?php echo BASE_URL_ORIG;?>favicon.ico" type="image/x-icon">
	<link rel="stylesheet" href="<?php echo BASE_URL_ORIG;?>css/stylesheet.css?=<?php echo JAK_UPDATED;?>" type="text/css" media="screen">
	<link rel="stylesheet" href="<?php echo BASE_URL;?>css/screen.css?=<?php echo JAK_UPDATED;?>" type="text/css" media="screen">

	<?php if ($jkl["rtlsupport"]) { ?>
	<!-- RTL Support -->
	<link rel="stylesheet" href="<?php echo BASE_URL_ORIG;?>css/style-rtl.css?=<?php echo JAK_UPDATED;?>" type="text/css" media="screen">
	<!-- End RTL Support -->
	<?php } ?>
	
	<!--[if lt IE 9]>
		<script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	 
</head>
<body>

<div class="container-fluid">
      <div class="row">
      	 	<div class="col-md-12">
      	 	
	      	 	<div id="operator-chat"></div>
	      	 	
	      	 	<!-- Error MSG -->
	      	 	<div id="msgErrorOC"></div>
	      	 	
	      	 	<form role="form" name="messageInputOC" id="MessageInputOC" action="javascript:sendInputOC();">
	      	 	
		      	 	<div class="form-group">
		      	 	<label class="control-label" for="messageOC"><?php echo $jkl["g135"];?></label>
		      	 	<textarea name="messageOC" id="messageOC" class="form-control" rows="2"></textarea>
		      	 	</div>
		      	 	
		      	 	<input type="hidden" name="userIDOC" id="userIDOC" value="<?php echo $jakuser->getVar("id");?>">
		      	 	<input type="hidden" name="userIDOCOP" id="userIDOCOP" value="<?php echo $page2;?>">
		      	 	<input type="hidden" name="userName" id="userName" value="<?php echo $jakuser->getVar("username");?>">
	      	 				
	      	 	</form>

			</div><!--/coll-->
		</div><!--/row-->
</div>

<script type="text/javascript" src="<?php echo BASE_URL_ORIG;?>js/jquery.js?=<?php echo JAK_UPDATED;?>"></script>

<?php if (is_numeric($page1) && is_numeric($page2) && JAK_USERID == $page1) { ?>

<script type="text/javascript" src="<?php echo BASE_URL;?>js/popupop.chat.js"></script>

<script type="text/javascript">
	ls.main_url = "<?php echo BASE_URL_ADMIN;?>";
	$(document).ready(function(){
		getInputOCP();
		// set up auto refresh to pull new entries into chat window
		ls.intervalID = setInterval("getInputOCP();", 5000);
	});
</script>

<?php } else { 

	// We have to check if we have public chat access
	if ($jakuser->getVar("operatorchatpublic") != 1) jak_redirect(BASE_URL);

?>

<script type="text/javascript" src="<?php echo BASE_URL;?>js/public.chat.js"></script>

<script type="text/javascript">
	ls.main_url = "<?php echo BASE_URL_ADMIN;?>";
	$(document).ready(function(){
		getInputOC();
		// set up auto refresh to pull new entries into chat window
		ls.intervalID = setInterval("getInputOC();", 5000);
	});

</script>

<?php } ?>

</body>
</html>