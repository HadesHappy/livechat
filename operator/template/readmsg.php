<div class="modal-header">
	<h4 class="modal-title"><?php echo $jkl["g33"];?> - <?php echo $rowi['name'];?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
</div>
<div class="modal-body">
    
    	<div class="table-responsive">
    		<table class="table">
    			<tr>
    				<td><i class="fa fa-envelope"></i> <?php echo $rowi['email'];?></td>
    				<td><i class="fa fa-phone"></i> <?php echo $rowi['phone'];?></td>
    				<td><i class="fa fa-link"></i> <?php echo $rowi['referrer'];?></td>
    				<td><i class="fa fa-globe"></i> <?php echo $rowi['ip'];?></td>
    			</tr>
    		</table>
    	</div>

    	<?php echo $customfields;?>
    	
    	<h4><?php echo $jkl['g146'];?></h4>
    	<div class="card border-info text-center">
  			<div class="card-body"><?php echo $rowi['message'];?></div>
  		</div>
    	
    	<h4><?php echo $jkl['g135'];?></h4>
    	
    	<div class="jak-thankyou"></div>
    	
		<form class="jak-ajaxform" method="post" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']);?>">
			
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
					    <label for="name"><?php echo $jkl["g54"];?></label>
						<input type="text" name="name" id="name" class="form-control" placeholder="<?php echo $jkl["g54"];?>" value="<?php echo $rowi['name'];?>">
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
					    <label for="email"><?php echo $jkl["g220"];?></label>
						<input type="text" name="email" id="email" class="form-control" placeholder="<?php echo $jkl["g220"];?>" value="<?php echo $rowi['email'];?>">
					</div>
				</div>
			</div>

			<div class="form-group">
			    <label for="subject"><?php echo $jkl["g221"];?></label>
				<input type="text" name="subject" id="subject" class="form-control" placeholder="<?php echo $jkl["g221"];?>">
			</div>
			<div class="form-group">
			    <label for="message"><?php echo $jkl["g146"];?></label>
			    <textarea name="message" id="message" rows="5" class="form-control"></textarea>
			</div>
				
			<button type="submit" class="btn btn-primary btn-block ls-submit"><?php echo $jkl["g4"];?></button>
			
			<input type="hidden" name="send_email" value="1">
			
		</form>
		
		<?php if (isset($MESSAGES_ALL) && !empty($MESSAGES_ALL)) { ?>
		<hr>
		<h3><?php echo $jkl["g222"];?></h3>
		<div class="card">
        	<div class="card-body">
            	<div id="accordion" role="tablist" aria-multiselectable="true" class="card-collapse">
		<?php foreach($MESSAGES_ALL as $v) { ?>
					<div class="card card-plain">
                    	<div class="card-header" role="tab" id="heading<?php echo $v["id"];?>">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $v["id"];?>" aria-expanded="true" aria-controls="collapse<?php echo $v["id"];?>">
                        <?php echo $v["subject"];?> - <?php echo $v["operatorname"];?> <span class="badge badge-secondary"><?php echo JAK_base::jakTimesince($v['sent'], JAK_DATEFORMAT, JAK_TIMEFORMAT);?></span>
                        <i class="fa fa-chevron-up"></i>
                      </a>
                    </div>
                    <div id="collapse<?php echo $v["id"];?>" class="collapse" role="tabpanel" aria-labelledby="heading<?php echo $v["id"];?>">
                      <div class="card-body">
                      	<?php echo $v["message"];?>
                      </div>
                    </div>
                  </div>
		<?php } ?>
				</div>
            </div>
        </div>
		<?php } ?>
		
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $jkl["g180"];?></button>
	</div>

<script>var jsurl = "../js/contact.js";$.getScript(jsurl);</script>

<script>
	ls.main_url = "<?php echo BASE_URL;?>";
	ls.lsrequest_uri = "<?php echo JAK_PARSE_REQUEST;?>";
	ls.ls_submit = "<?php echo $jkl['g4'];?>";
	ls.ls_submitwait = "<?php echo $jkl['g67'];?>";
</script>