<?php include_once 'header.php';?>

<div class="content">

<?php if ($errors) { ?>
<div class="alert alert-danger">
<?php if (isset($errors["e"])) echo $errors["e"];
	  if (isset($errors["e1"])) echo $errors["e1"];?>
</div>
<?php } ?>

<div class="row">
	<div class="col-md-4">
		<div class="card">
			<div class="card-header">
		  		<h3 class="card-title"><i class="fa fa-history"></i> <?php echo $jkl["g310"];?></h3>
			</div><!-- /.box-header -->
			<div class="card-body">
				<?php if (isset($JAK_GCHISTORY) && is_array($JAK_GCHISTORY) && !empty($JAK_GCHISTORY)) { ?>
				<ul class="list-group">
				<?php foreach($JAK_GCHISTORY as $v) { ?>
					<li class="list-group-item">
						<a href="<?php echo JAK_rewrite::jakParseurl('groupchat', 'view', $v["id"]);?>"><?php echo JAK_base::jakTimesince($v["created"], JAK_DATEFORMAT, JAK_TIMEFORMAT);?></a> <a href="<?php echo JAK_rewrite::jakParseurl('groupchat', 'delete', $v["id"], 'chatlog');?>" class="btn btn-sm btn-danger"><i class="fa fa-trash-alt"></i></a>
					</li>
				<?php } ?>
				</ul>
				<?php } else { ?>
					<div class="alert alert-info"><?php echo $jkl['i3'];?></div>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="col-md-8">
		<div class="card">
			<div class="card-header">
		  		<h3 class="card-title"><i class="fa fa-code"></i> <?php echo $jkl["m34"];?></h3>
			</div><!-- /.box-header -->
		  	<div class="card-body">
				<p><?php echo $jkl['bw1'];?> <a href="javascript:void(0)" data-clipboard-target="#widget-code" class="btn btn-primary btn-sm clipboard"><i class="fa fa-clipboard"></i></a></p>
		<p><textarea class="form-control" id="widget-code" readonly="readonly" rows="11"><?php echo htmlentities('<!-- Live Chat 3 group chat widget -->
<script type="text/javascript">
	(function(w, d, s, u) {
		w.id = '.$page2.'; w.lcjUrl = u;
		var h = d.getElementsByTagName(s)[0], j = d.createElement(s);
		j.async = true; j.src = \''.BASE_URL_ORIG.'js/jaklcgroupchat.js\';
		h.parentNode.insertBefore(j, h);
	})(window, document, \'script\', \''.BASE_URL_ORIG.'\');
</script>
<div id="jakgroup-chat-container"></div>
<!-- end Live Chat 3 group chat widget -->');?></textarea></p>
	
				<p><?php echo $jkl['g301'];?> <a href="javascript:void(0)" data-clipboard-target="#marketing-code" class="btn btn-primary btn-sm clipboard"><i class="fa fa-clipboard"></i></a></p>
				<div class="alert alert-info" id="marketing-code">
				<?php echo htmlentities('<a href="'.str_replace("operator/", "", JAK_rewrite::jakParseurl('groupchat', $page2, $JAK_FORM_DATA["lang"])).'">'.$jkl['g306'].'</a>');?>
				</div>
			</div>
		</div>

	</div>
</div>
<hr>
<form class="jak_form" method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
<div class="row">
	<div class="col-md-4">
		<div class="card">
		<div class="card-header with-border">
		  <h3 class="card-title"><i class="fa fa-edit"></i> <?php echo $jkl["g47"];?></h3>
		</div><!-- /.box-header -->
		<div class="card-body">

			<div class="form-group">
				<label for="title"><?php echo $jkl["g16"];?></label>
				<input type="text" name="title" id="title" class="form-control<?php if (isset($errors["e"])) echo " is-invalid";?>" value="<?php echo $JAK_FORM_DATA["title"];?>">
			</div>

			<div class="form-group">
				<label for="description"><?php echo $jkl["g52"];?></label>
				<textarea name="description" id="description" rows="5" class="form-control"><?php echo $JAK_FORM_DATA["description"];?></textarea>
			</div>

			<div class="form-group">
				<p><label for="operator"><?php echo $jkl["g130"];?></label></p>
				<select name="jak_opid[]" id="operator" class="selectpicker" data-size="4" data-live-search="true" multiple>
							
					<option value="0"<?php if ($JAK_FORM_DATA["opids"] == 0) echo ' selected';?>><?php echo $jkl["g105"];?></option>
					<?php if (isset($JAK_OPERATORS) && is_array($JAK_OPERATORS)) foreach($JAK_OPERATORS as $o) { ?>
							
					<option value="<?php echo $o["id"];?>"<?php if (in_array($o["id"], explode(",", $JAK_FORM_DATA["opids"]))) echo ' selected';?>><?php echo $o["username"];?></option>
							
					<?php } ?>
							
				</select>
			</div>

			<div class="form-group">
				<label for="jak_maxclients"><?php echo $jkl["g21"];?></label>
				<input type="number" name="jak_maxclients" class="form-control" min="2" max="50" step="1" value="<?php echo $JAK_FORM_DATA["maxclients"];?>">
			</div>

			<div class="form-group">
				<label for="jak_password"><?php echo $jkl["l2"];?></label>
				<input type="password" name="jak_password" class="form-control" value="<?php echo $JAK_FORM_DATA["password"];?>">
			</div>

			<div class="form-group">
				<p><label for="jak_lang"><?php echo $jkl["g22"];?></label></p>
				<select name="jak_lang" class="selectpicker" data-size="4" data-live-search="true">
				<?php if (isset($lang_files) && is_array($lang_files)) foreach($lang_files as $lf) { ?>
					<option value="<?php echo $lf;?>"<?php if ($JAK_FORM_DATA["lang"] == $lf) { ?> selected="selected"<?php } ?>><?php echo ucwords($lf);?></option><?php } ?>
				</select>
			</div>

		</div>
		<div class="card-footer">
			<a href="<?php echo JAK_rewrite::jakParseurl('groupchat');?>" class="btn btn-default"><?php echo $jkl["g103"];?></a>
			<button type="submit" name="save" class="btn btn-primary"><?php echo $jkl["g38"];?></button>
		</div>
		</div>
	</div>
	<div class="col-md-8">
		<div class="card">
		<div class="card-header">
		  <h3 class="card-title"><i class="fa fa-palette"></i> <?php echo $jkl["bw"];?></h3>
		</div><!-- /.box-header -->
		<div class="card-body">

			<div class="form-group">
				<label for="jak_floatcss"><?php echo $jkl["bw9"];?></label>
				<input type="text" class="form-control" name="jak_floatcss" value="<?php if (isset($JAK_FORM_DATA["floatcss"])) echo $JAK_FORM_DATA["floatcss"];?>" placeholder="bottom:20px;left:20px">
			</div>

			<div class="form-check">
				<label class="form-check-label">
					<input class="form-check-input" type="checkbox" name="jak_float" id="jak_float" value="1"<?php if ($JAK_FORM_DATA["floatpopup"] == 1) echo ' checked';?>>
					<span class="form-check-sign"></span> <?php echo $jkl["float"];?>
				</label>
			</div>

			<hr>

			<div class="form-group">
				<p><label for="jak_buttonimg"><?php echo $jkl["g71"];?></label></p>
				<select name="jak_buttonimg" id="chatbutton" class="selectpicker" data-size="4" data-live-search="true">
				<?php if (isset($BUTTONS_ALL) && is_array($BUTTONS_ALL)) foreach($BUTTONS_ALL as $k) { if (getimagesize(APP_PATH.JAK_FILES_DIRECTORY.'/buttons/'.$k) && strpos($k,"_on")) { ?>
					<option value="<?php echo $k;?>"<?php if ($JAK_FORM_DATA["buttonimg"] == $k) { ?> selected="selected"<?php } ?>><?php echo ($k);?></option><?php } } ?>
				</select>
			</div>

		<div class="card text-center">
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<h4><?php echo $jkl['g165'];?></h4>
						<div id="chat_preview_button" style="min-height: 100px">
							<img src="<?php echo BASE_URL_ORIG.JAK_FILES_DIRECTORY;?>/buttons/<?php echo $JAK_FORM_DATA["buttonimg"];?>" alt="button">
						</div>

					</div>
					<div class="col-md-6">
						<h4><?php echo $jkl['g166'];?></h4>
						<div id="chat_preview_button_off" style="min-height: 100px">
							<img src="<?php echo BASE_URL_ORIG.JAK_FILES_DIRECTORY;?>/buttons/<?php echo str_replace("_on", "_off", $JAK_FORM_DATA["buttonimg"]);?>" alt="button">
						</div>

					</div>
				</div>

			</div>
		</div>
		<div class="clearfix"></div>
		</div>

		<div class="card-footer">
			<a href="<?php echo JAK_rewrite::jakParseurl('groupchat');?>" class="btn btn-default"><?php echo $jkl["g103"];?></a>
			<button type="submit" name="save" class="btn btn-primary"><?php echo $jkl["g38"];?></button>
		</div>
		</div>
	</div>
</div>

</form>

</div>
		
<?php include_once 'footer.php';?>