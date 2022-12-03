<!-- JavaScript for select all -->
<script type="text/javascript">
$(document).ready(function() {

	$("#jak_delock_all").click(function() {
		var checked_status = this.checked;
		$(".highlight").each(function()
		{
			this.checked = checked_status;
		});
	});
				
});

ls.main_url = "<?php echo BASE_URL_ADMIN;?>";
ls.orig_main_url = "<?php echo BASE_URL_ORIG;?>";
ls.main_lang = "<?php echo JAK_LANG;?>";
</script>