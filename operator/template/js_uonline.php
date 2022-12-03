<?php if (JAK_SHOW_IPS) { ?>
<script src="<?php echo BASE_URL;?>js/leaflet.js" type="text/javascript"></script>
<script type="text/javascript">
var marker = new Array();
var markerid = [];
var statmap = L.map('map_canvas', {zoomControl:true}).setView([10,4], 2);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
	attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
				'<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'
}).addTo(statmap);

// Disable drag and zoom handlers.
statmap.dragging.enable();
statmap.touchZoom.enable();
statmap.doubleClickZoom.disable();
statmap.scrollWheelZoom.disable();
  
</script>
<?php } ?>