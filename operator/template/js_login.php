<script type="text/javascript">
$(document).ready(function() {
	$(".forgotP").hide();
	
	// Switch buttons from "Log In | Register" to "Close Panel" on click
	$(".lost-pwd").click(function(e) {
		e.preventDefault();
		$(".loginF").slideToggle();
		$(".forgotP").slideToggle();
	});
	
	<?php if (isset($errorfp)) { ?>
		$(".loginF").hide();
		$(".forgotP").show();
		$(".forgotP").addClass("shake");
	<?php } if (isset($ErrLogin)) { ?>
		$(".loginF").addClass("shake");
	<?php } ?>
});
// Simple Framebuster
if (self != top) {
	var theBody = document.getElementsByTagName('body')[0];
	theBody.style.display = "none";
}
</script>