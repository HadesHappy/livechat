<div class="modal-header">
	<h4 class="modal-title"><?php echo $jkl["stat_s12"];?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
</div>
<div class="modal-body">

<?php if (isset($USER_FEEDBACK) && !empty($USER_FEEDBACK) && is_array($USER_FEEDBACK)) { ?>
	
	<div class="card">
		<div class="card-header">
		    <h5 class="card-title"><i class="fa fa-comments"></i> <?php echo sprintf($jkl['hd302'], $USER_NAME);?></h5>
		    <p><?php echo '<strong>'.$jkl["g90"].':</strong> '.gmdate('H:i:s', $USER_SUPPORT).'<br><strong>'.$jkl["g91"].':</strong> '.round(($USER_VOTES / count($USER_FEEDBACK)), 2);?>/5</p>
		</div><!-- /.box-header -->
		<div class="card-body">
						
			<div class="jak-thankyou"></div>
						
			<form class="jak-ajaxform" method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
						
				<div class="form-group">
					<label for="email"><?php echo $jkl["g93"];?></label>
					<input type="text" name="email" id="email" class="form-control" placeholder="<?php echo $jkl["g68"];?>" />
				</div>
							
				<button type="submit" id="formsubmit" class="btn btn-primary ls-submit"><?php echo $jkl["g4"];?></button>
							
				<input type="hidden" name="email_feedback" value="1" />
				<input type="hidden" name="convid" value="<?php echo $page2;?>">
			</form>

<?php } else { ?>

			<div class="alert alert-info"><?php echo $jkl['i3'];?></div>

<?php } ?>

		</div>
	</div>

<?php if (isset($USER_FEEDBACK) && !empty($USER_FEEDBACK) && is_array($USER_FEEDBACK)) { ?>
		    	
	<div class="card">
		<div class="card-header">
		    <h3 class="card-title"><?php echo $jkl["g81"].' '.$page3;?></h3>
		</div><!-- /.box-header -->
		<div class="card-body">
						
			<ul class="list-group">
			<?php foreach($USER_FEEDBACK as $v) { if (JAK_SUPERADMINACCESS) {
							
				echo '<div class="card" id="stat'.$v['id'].'">
								  <div class="card-header">
								    <h5 class="card-title">'.$v['time'].' - '.$jkl['g86'].' <button class="btn btn-box-tool btn-danger btn-sm delete-stat" id="'.$v['id'].'" onclick="if(!confirm(\''.$jkl["e30"].'\'))return false;"><i class="fa fa-trash"></i></button></h5>
								  </div>
								  <div class="card-body">
								    <span class="usr_rate">'.$jkl['g85'].': </span>'.$v['vote'].'/5<br />'.$jkl['g54'].': '.$v['name'].'<br />'.$jkl['l5'].': '.$v['email'].'<br />'.$jkl['stat_s12'].': '.$v['comment'].'<br />'.$jkl['g87'].': '.gmdate('H:i:s', $v['support_time']).'
								  </div>
								</div>';
							
			} else {
							
				echo '<div class="card" id="stat'.$v['id'].'">
								  <div class="card-header">
								    <h5 class="card-title">'.$v['time'].' - '.$jkl['g86'].'</h5>
								  </div>
								  <div class="card-body">
								    <span class="usr_rate">'.$jkl['g85'].': </span>'.$v['vote'].'/5<br />'.$jkl['g54'].': '.$v['name'].'<br />'.$jkl['l5'].': '.$v['email'].'<br />'.$jkl['stat_s12'].': '.$v['comment'].'<br />'.$jkl['g87'].': '.gmdate('H:i:s', $v['support_time']).'
								  </div>
								</div>';
							
			} }?>
			</ul>

<?php } ?>

		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $jkl["g180"];?></button>
</div>


<?php if (isset($USER_FEEDBACK) && !empty($USER_FEEDBACK) && is_array($USER_FEEDBACK)) { ?>

<script>var jsurl = "../js/contact.js";$.getScript(jsurl);</script>

<script type="text/javascript">
	ls.main_url = "<?php echo BASE_URL;?>";
	ls.lsrequest_uri = "<?php echo JAK_PARSE_REQUEST;?>";
	ls.ls_submit = "<?php echo $jkl['g4'];?>";
	ls.ls_submitwait = "<?php echo $jkl['g67'];?>";
	
	
	<?php if (JAK_SUPERADMINACCESS) { ?>
	
		$('.delete-stat').click(function() {
		
			var sid = $(this).attr('id');
			
			var request = $.ajax({
			  url:  'ajax/oprequests.php',
			  type: "POST",
			  data: "oprq=delstat&sid="+sid+"&uid="+ls.opid,
			  dataType: "json",
			  cache: false
			});
			
			request.done(function(msg) {
				
				if (msg.status) {
					$("#stat"+sid).fadeOut();
				} else {
					alert("<?php echo $jkl["not"];?>");
				}
			});
			
		});
	
	<?php } ?>
	
</script>
<?php } ?>