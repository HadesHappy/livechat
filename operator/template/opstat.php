<div class="modal-header">
	<h4 class="modal-title"><?php echo $jkl["g137"];?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
</div>
<div class="modal-body">

	<strong><?php echo $jkl["u"].':</strong> '.$row["name"];?><br />
	<strong><?php echo $jkl["u2"].':</strong> '.$row["username"];?>
	
	<hr>
	
	<h4><?php echo $jkl["g5"];?></h4>
	<div class="alert alert-success">
	<?php echo ($totalAll ? $totalAll : 0);?>
	</div>
	
	<hr>

	<h4><?php echo $jkl["g89"];?></h4>
	<?php if ($total_support) { ?>
	<p><?php echo '<strong>'.$jkl["g90"].':</strong> '.gmdate('H:i:s', $total_support).'<br /><strong>'.$jkl["g91"].':</strong> '.round(($total_vote / $totalAllu), 2);?>/5</p>
	<?php } else { ?>
	<div class="alert alert-info">
	<?php echo $jkl['i5'];?>
	</div>
	<?php } ?>
	    
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $jkl["g180"];?></button>
</div>