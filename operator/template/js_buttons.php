<!-- JavaScript for select all -->
<script type="text/javascript">
$(document).ready(function() {
        
	$(".hover-button").hover(function() {
		$(this).attr("src", function(index, attr){
	        return attr.replace("_on", "_off");
	    });
	}, function(){
	    $(this).attr("src", function(index, attr){
	        return attr.replace("_off", "_on");
	    });
	});
					
});

ls.main_url = "<?php echo BASE_URL_ADMIN;?>";
ls.orig_main_url = "<?php echo BASE_URL_ORIG;?>";
ls.main_lang = "<?php echo JAK_LANG;?>";
</script>