<script type="text/javascript" src="<?php echo BASE_URL;?>js/jvectormap.js"></script>

<!-- JavaScript for select all -->
<script type="text/javascript">
$(document).ready(function() {
    loadmap = {
    initVectorMap: function() {
    var mapData = {
    <?php if (isset($ctlres) && !empty($ctlres)) foreach ($ctlres as $uj) { ?>
    	"<?php echo strtoupper($uj['countrycode']);?>": <?php echo $uj["total_country"];?>,
    <?php } ?>
    };

    $('#worldMap').vectorMap({
      map: 'world_mill_en',
      backgroundColor: "transparent",
      zoomOnScroll: false,
      regionStyle: {
        initial: {
          fill: '#e4e4e4',
          "fill-opacity": 0.9,
          stroke: 'none',
          "stroke-width": 0,
          "stroke-opacity": 0
        }
      },

    series: {
        regions: [{
          values: mapData,
          scale: ["#AAAAAA", "#444444"],
          normalizeFunction: 'polynomial'
        }]
      },
    });
  }

};

  loadmap.initVectorMap();
		
});
ls.main_url = "<?php echo BASE_URL_ADMIN;?>";
ls.orig_main_url = "<?php echo BASE_URL_ORIG;?>";
ls.main_lang = "<?php echo JAK_LANG;?>";
</script>