<form id="cNotes" method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
<div class="modal-header">
	<h4 class="modal-title"><?php echo $jkl['g181'];?> - <?php echo $getnote["name"];?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
</div>
<div class="modal-body">
<div class="padded-box">

<div id="contact-container">

	 <div class="form-group">
	    <label for="note"><?php echo $jkl["g339"];?></label>
		<textarea name="note" id="note" rows="5" class="form-control"><?php echo $getnote["notes"];?></textarea>
	</div>
	
	<input type="hidden" name="convid" value="<?php if (is_numeric($page1)) { echo $page1; } else { echo $page2;}?>">

</div>

</div>

</div>
	<div class="modal-footer">
		<button type="submit" id="formsubmit" class="btn btn-primary"><?php echo $jkl["g38"];?></button>
		<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $jkl["g180"];?></button>
	</div>
</form>

<script type="text/javascript" src="<?php echo BASE_URL;?>js/notes.js"></script>