<script type="text/javascript">
$(document).ready(function(){
    $("#loader").hide();

    $('input[type=radio][name=jak_smpt]').change(function() {
	    if (this.value == '1') {
	        $('#smtp_options').fadeIn();
	    }
	    else if (this.value == '0') {
	        $('#smtp_options').fadeOut();
	    }
	});
    
    <!-- JavaScript to disable send button and show loading.gif image -->
    $("#sendTM").click(function() {
    	$("#loader").show();
    	$('#sendTM').attr("disabled", "disabled");
    	$('.jak_form').submit();
    });
                
});
</script>