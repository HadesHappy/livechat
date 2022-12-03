<div class="modal-header">
    <h4 class="modal-title"><?php echo $jkl["g224"];?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
</div>
<div class="modal-body">
    	<div class="table-responsive">
    	  <table class="table table-condensed">
    	  <thead>
    	  <tr>
    	  <th><?php echo $jkl["g54"];?></th>
    	  <th><?php echo $jkl["g12"];?></th>
    	  </tr>
    	    <tr>
    	    	<td><?php echo $row['name'];?></td>
    	    	<td><?php echo $row['country'];?> / <?php echo $row['city'];?></td>
    	    </tr>
    	  </table>
    	</div>
    
    	<h3><?php echo $jkl["g224"];?></h3>
		
		<div id="clientmap-canvas" style="height: 300px;margin: 0px;padding: 0px"></div>
		
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $jkl["g180"];?></button>
	</div>

<script src="js/leaflet.js" type="text/javascript"></script>
<script type="text/javascript">
function loadmap() {
<?php if ($row['latitude']) { ?>

    var map = L.map('map_canvas', {zoomControl:true}).setView([<?php echo $row['latitude'];?>,<?php echo $row['longitude'];?>], 8);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
            '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'
    }).addTo(statmap);

    // Disable drag and zoom handlers.
    statmap.dragging.disable();
    statmap.touchZoom.disable();
    statmap.doubleClickZoom.disable();
    statmap.scrollWheelZoom.disable();

    var marker = L.marker([<?php echo $row['latitude'];?>,<?php echo $row['longitude'];?>]).addTo(map);
    marker.bindPopup("<?php echo $row['name'];?>").openPopup();

<?php } else { ?>
	$('#clientmap-canvas').addClass("text-danger").html("<?php echo $jkl['i3'];?>");
<?php } ?>
  
}

 $('#jakModal').on('shown.bs.modal', function () { 
	loadmap();
});

</script>