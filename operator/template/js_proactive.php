<!-- JavaScript for select all -->
<script type="text/javascript">
$(document).ready(function() {

	$(".play-tone").change(function() {
    	var playtone = $(this).val();
    	var sound = new Howl({
    	  src: ['<?php echo BASE_URL_ORIG;?>/'+playtone+'.mp3', '<?php echo BASE_URL_ORIG;?>/'+playtone+'.webm']
    	});
    	
    	// Finally play the sound, also on mobiles
    	sound.play();
    });
					
});

ls.main_url = "<?php echo BASE_URL_ADMIN;?>";
ls.orig_main_url = "<?php echo BASE_URL_ORIG;?>";
ls.main_lang = "<?php echo JAK_LANG;?>";
</script>