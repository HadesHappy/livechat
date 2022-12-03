<!DOCTYPE html>
<html lang="<?php echo $BT_LANGUAGE;?>">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="Live Chat 3 - JAKWEB">
	<title><?php echo $jkl["g2"];?> - <?php echo JAK_TITLE;?></title>
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<?php include_once('lctemplate/'.$jakwidget['template'].'/style.php');?>
  	<?php if (isset($wtplsett["customcss"]) && !empty($wtplsett["customcss"])) echo '<link rel="stylesheet" href="'.BASE_URL.'lctemplate/'.$jakwidget['template'].'/'.$wtplsett["customcss"].'" type="text/css">';?>
	 
	 <!-- Le fav and touch icons -->
	 <link rel="shortcut icon" href="<?php echo BASE_URL;?>img/ico/favicon.ico">
	 
</head>
<body>
 
<div class="navbar navbar-default">
	<div class="container">
    	<div class="navbar-header">
        	<a class="navbar-brand" href="<?php echo $_SERVER['REQUEST_URI'];?>"><?php echo $jkl["g2"];?> - <?php echo JAK_TITLE;?></a>
    	</div>
	</div>
</div>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<p><?php echo $jkl["e5"];?>
			<ul>
				<li><?php echo $jkl["e6"];?></li>
				<li><?php echo $jkl["e7"];?></li>
				<li><?php echo $jkl["e8"];?></li>
			</ul>
			</p>
		</div>
	</div>
	
</div>

</body>
</html>