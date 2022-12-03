<?php include_once 'header.php';?>

<div class="content">

<?php if (JAK_SHOW_IPS) { ?>
	<div class="card">
		<div class="card-header">
			<h3 class="card-title"><i class="fa fa-map-marker-smile"></i> <?php echo $jkl["g177"];?> (<span class="currentlyonline">0</span>)</h3>
		</div><!-- /.box-header -->
		<div class="card-body">
			<div id="map_canvas"></div>
		</div>
	</div>
<?php } ?>

<div class="card">
	<div class="card-header">
		<h3 class="card-title"><i class="fa fa-users"></i> <?php echo $jkl["g177"];?> (<span class="currentlyonline">0</span>)</h3>
	</div><!-- /.box-header -->
	<div class="card-body">
		<div id="userOnline"></div>
	</div>
</div>

<?php if (isset($UONLINE_ALL) && is_array($UONLINE_ALL)) { ?>

	<div class="card">
		<div class="card-header">
			<h3 class="card-title"><i class="fa fa-user-chart"></i> <?php echo $jkl["g105"].' ('.$totalAll.')';?></h3>
		</div><!-- /.box-header -->
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped w-100 d-block d-md-table">
					<tr>
						<th>#</th>
						<th><?php echo $jkl["g224"];?></th>
						<th><?php echo $jkl["g169"];?></th>
						<th><?php echo $jkl["g171"];?></th>
						<th><?php echo $jkl["g172"];?></th>
						<th><?php echo $jkl["g11"];?></th>
						<th><?php echo $jkl["g173"];?></th>
						<th><?php echo $jkl["g174"];?></th>
						<th><?php if (JAK_SUPERADMINACCESS) { ?><a class="btn btn-warning btn-sm btn-confirm" href="<?php echo JAK_rewrite::jakParseurl('uonline', 'truncate');?>" data-title="<?php echo addslashes($jkl["g48"]);?>" data-text="<?php echo addslashes($jkl["e41"]);?>" data-type="warning" data-okbtn="<?php echo addslashes($jkl["g279"]);?>" data-cbtn="<?php echo addslashes($jkl["g280"]);?>"><i class="fa fa-exclamation-triangle"></i></a><?php } ?></th>
					</tr>
				</thead>
				<?php foreach($UONLINE_ALL as $v) { ?>
					<tr>
						<td><?php echo $v["id"];?></td>
						<td><img src="<?php echo BASE_URL;?>img/blank.png" class="flag-big flag-<?php echo $v['countrycode'];?>" title="<?php echo $v['country'];?>" alt="<?php echo $v['country'];?>"></td>
						<td><strong><?php echo $jkl["g169"].':</strong> '.$v["referrer"];?></strong><br><?php echo $jkl["g170"].':</strong> '.$v["firstreferrer"];?></td>
						<td><?php echo $v["agent"];?></td>
						<td><?php echo $v["hits"];?></td>
						<td><?php echo $v["ip"];?></a></td>
						<td><?php echo JAK_base::jakTimesince($v['time'], JAK_DATEFORMAT, JAK_TIMEFORMAT);?></td>
						<td><?php echo JAK_base::jakTimesince($v['lasttime'], JAK_DATEFORMAT, JAK_TIMEFORMAT);?></td>
						<td><?php if (JAK_SUPERADMINACCESS) { ?><a href="<?php echo JAK_rewrite::jakParseurl('uonline', 'delete', $v["id"]);?>" class="btn btn-secondary btn-sm btn-confirm" data-title="<?php echo addslashes($jkl["g48"]);?>" data-text="<?php echo addslashes($jkl["e30"]);?>" data-type="warning" data-okbtn="<?php echo addslashes($jkl["g279"]);?>" data-cbtn="<?php echo addslashes($jkl["g280"]);?>"><i class="fa fa-trash-alt"></i></a><?php } ?></td>
					</tr>
				<?php } ?>
			</table>
		</div>
	</div>
</div>

<?php if ($JAK_PAGINATE) { ?>
	<div class="pagination">
		<?php echo $JAK_PAGINATE;?>
	</div>
<?php } } ?>

</div>

<?php include_once 'footer.php';?>