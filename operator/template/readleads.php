<div class="modal-header">
    <h4 class="modal-title"><i class="fa fa-comments"></i> <?php echo $jkl["m1"];?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
</div>
    <div class="modal-body">
    	
    	<div class="card">
    		<div class="card-header">
    			<h5 class="card-title"><i class="fa fa-history"></i> <?php echo $jkl["g57"];?> <strong><?php echo $rowi['operatorname'];?></strong> <?php echo $jkl["g58"];?> <strong><?php echo $rowi['name'];?></strong></h5>
    	  	</div><!-- /.box-header -->
    	  	<div class="card-body">

    	  		<?php if ($page3 == 1) { ?>
				
				<div class="jak-thankyou"></div>
				
				<form class="jak-ajaxform" method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
					
					 <div class="form-group">
					    <label for="email"><?php echo $jkl["g59"];?></label>
						<input type="text" name="email" id="email" class="form-control" placeholder="<?php echo $jkl["g68"];?>" />
					</div>
					
					<button type="submit" class="btn btn-primary ls-submit"><?php echo $jkl["g4"];?></button>
					
					<input type="hidden" name="email_conv" value="1" />
					<input type="hidden" name="convid" value="<?php echo $page2;?>">
					<input type="hidden" name="cagent" value="<?php echo $rowi['operatorname'];?>">
					<input type="hidden" name="cuser" value="<?php echo $rowi['name'];?>">
				</form>

			</div>
		</div>

		<div class="card">
			<div class="card-body">
				
				<?php } ?>

    	  		<h6><?php echo $jkl['g40'];?></h6>
    	  		<div class="table-responsive">
    	  		<table class="table">
    	  			<tr>
    	  				<td><i class="fa fa-user"></i> <?php echo $rowi['name'];?></td>
    	  				<td><i class="fa fa-envelope"></i> <?php echo $rowi['email'];?></td>
    	  				<td><i class="fa fa-phone"></i> <?php echo (!empty($rowi['phone']) ? $rowi['phone'] : '-');?></td>
    	  			</tr>
    			</table>
    			</div>
    			<hr>
    			<h6><?php echo $jkl['stat_s2'];?></h6>
    			<div class="table-responsive">
    			<table class="table">
    	  			<tr>
    	  				<td><i class="fa fa-user-md"></i> <?php echo $rowi['operatorname'];?></td>
    	  				<td><i class="fa fa-clock"></i> <?php echo (!empty($rowi['ended']) ? gmdate('H:i:s', ($rowi['ended'] - $rowi['initiated'])) : '-');?></td>
    	  			</tr>
    			</table>
    			</div>
				<div class="padded-box">
					<ul class="list-group">
						<?php if (isset($CONVERSATION_LS) && is_array($CONVERSATION_LS)) foreach($CONVERSATION_LS as $v) {
						
							if ($v['class'] == "notice") {
						    	echo '<li class="list-group-item '. $v['class'] .'"><div class="d-flex w-100 justify-content-between"><h5 class="mb-1">'.$v['name'] .' '.$jkl['g66'].':</h5></div><p class="mb-1">'.$v['message'].'</p></li>';
						    } else {
						        echo '<li class="list-group-item '. $v['class'] .'"><div class="d-flex w-100 justify-content-between"><h5 class="mb-1">'.$v['name'].' '.$jkl['g66'].' <span class="badge badge-dark">'.$v['time'].'</span></h5></div><p class="mb-1">'.$v['message'].'</p></li>';
						    }
						    
						} ?>
					</ul>
		
				</div>
				
			</div>
			<div class="card-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $jkl["g180"];?></button>
			</div>
		</div>

<script>var jsurl = "../js/contact.js";$.getScript(jsurl);</script>

<script>
	ls.main_url = "<?php echo BASE_URL;?>";
	ls.lsrequest_uri = "<?php echo JAK_PARSE_REQUEST;?>";
	ls.ls_submit = "<?php echo $jkl['g4'];?>";
	ls.ls_submitwait = "<?php echo $jkl['g67'];?>";
</script>