<script type="text/javascript" src="<?php echo BASE_URL;?>js/hours.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	
	jQuery("#pass").keyup(function() {
		passwordStrength(jQuery(this).val());
	});
	
	var bHoursM = $("#bHoursM").businessHours({
	    postInit:function(){
	        $("#bHoursM").find('.operationTimeFrom, .operationTimeTill').timepicker({
	            'timeFormat': 'H:i',
	            'noneOption': true
	        });
	    },
	    'weekdays': <?php echo $jkl["u48"];?>,
	    <?php if (isset($JAK_FORM_DATA["hours_array"]) && !empty($JAK_FORM_DATA["hours_array"])) { ?>
	    operationTime: <?php echo $JAK_FORM_DATA["hours_array"];?>,
	    <?php } ?>
	    dayTmpl: '<div class="dayContainer">' +
	            '<div data-original-title="" class="colorBox"><input type="checkbox" class="invisible operationState"/></div>' +
	            '<div class="weekday"></div>' +
	            '<div class="operationDayTimeContainer">' +
	            '<div class="operationTime"><input type="text" name="startTime" class="mini-time form-control operationTimeFrom"></div>' +
	            '<div class="operationTime"><input type="text" name="endTime" class="mini-time form-control operationTimeTill"></div>' +
	            '<div class="operationTime"><input type="text" name="startTimea" class="mini-time form-control operationTimeFrom"></div>' +
	            '<div class="operationTime"><input type="text" name="endTimea" class="mini-time form-control operationTimeTill"></div>' +
	            '</div></div>'
	});
	
	$(document).on("click", ".form-submit", function(e) {
		e.preventDefault();
		$("#bhours").val(JSON.stringify(bHoursM.serialize()));
		// finally send form
		$(".jak_form").submit();
	});				
});
</script>