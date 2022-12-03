<!-- Style Changer -->
<script src="<?php echo BASE_URL;?>js/minicolor.js"></script>

<!-- JavaScript for select all -->
<script>
$(document).ready(function() {

    var clipboard = new ClipboardJS('.clipboard');

    clipboard.on('success', function(e) {

        $.notify({icon: 'fa fa-check-square', message: '<?php echo addslashes($jkl["g284"]);?>'}, {type: 'success', animate: {
            enter: 'animate__animated animate__fadeInDown',
            exit: 'animate__animated animate__fadeOutUp'
        }});

        e.clearSelection();
    });

    $('#department').change(function() {
        if (this.value != '0') {
            $('#operator').val(["0"]);
        }
    });
    $('#operator').change(function() {
        if (this.value != '0') {
            $('#department').val(["0"]);
        }
    });

    // Colour Changer
    $('.colour_changer').minicolors({
        theme: "bootstrap"
    });

    // Load the button preview for images
    $('#btn_preview').attr("src",ls.orig_main_url+ls.files_url+'/buttons/'+$('select[name="btn_image"]').val());

    // Load the mobile preview for images
    $('#btn_preview_mobile').attr("src",ls.orig_main_url+ls.files_url+'/buttons/'+$('select[name="btn_image_mobile"]').val());

    $("#btn_preview").hover(function() {
        $(this).attr("src", function(index, attr){
            return attr.replace("_on", "_off");
        });
    }, function(){
        $(this).attr("src", function(index, attr){
            return attr.replace("_off", "_on");
        });
    });

    $("#btn_preview_mobile").hover(function() {
        $(this).attr("src", function(index, attr){
            return attr.replace("_on", "_off");
        });
    }, function(){
        $(this).attr("src", function(index, attr){
            return attr.replace("_off", "_on");
        });
    });

    // Load changed previews if any
    if ($('input[name="btn_icon"]').val()) $('#btn_preview i').attr("class", $('input[name="btn_icon"]').val());
    if ($('input[name="btn_icon_colour"]').val()) $('#btn_preview').css("color", $('input[name="btn_icon_colour"]').val());
    if ($('input[name="btn_background_colour"]').val()) $('#btn_preview').css("background-color", $('input[name="btn_background_colour"]').val());
    if ($('input[name="btn_icon_offline"]').val()) $('#btn_preview_mobile i').attr("class", $('input[name="btn_icon_offline"]').val());
    if ($('input[name="btn_icon_off_colour"]').val()) $('#btn_preview_mobile').css("color", $('input[name="btn_icon_off_colour"]').val());
    if ($('input[name="btn_background_off_colour"]').val()) $('#btn_preview_mobile').css("background-color", $('input[name="btn_background_off_colour"]').val());
                    
});

// Images

$(document).on('change', 'select[name="btn_image"]', function() {
    
    $('#btn_preview').attr("src",ls.orig_main_url+ls.files_url+'/buttons/'+$(this).val());
    
});

$(document).on('change', 'select[name="btn_image_mobile"]', function() {
    
    $('#btn_preview_mobile').attr("src",ls.orig_main_url+ls.files_url+'/buttons/'+$(this).val());
    
});

// Icon

$(document).on('change', 'input[name="btn_icon"]', function() {
    
    $('#btn_preview i').attr("class", $(this).val());
    
});

$(document).on('change', 'input[name="btn_icon_colour"]', function() {

        // Get the colour from the input field
        $('#btn_preview').css("color", $(this).val());
    
});

$(document).on('change', 'input[name="btn_background_colour"]', function() {

        // Get the colour from the input field
        $('#btn_preview').css("background-color", $(this).val());
    
});

$(document).on('change', 'input[name="btn_icon_offline"]', function() {
    
    $('#btn_preview_mobile i').attr("class", $(this).val());
    
});

$(document).on('change', 'input[name="btn_icon_off_colour"]', function() {

        // Get the colour from the input field
        $('#btn_preview_mobile').css("color", $(this).val());
    
});

$(document).on('change', 'input[name="btn_background_off_colour"]', function() {

        // Get the colour from the input field
        $('#btn_preview_mobile').css("background-color", $(this).val());
    
});

ls.files_url = "<?php echo JAK_FILES_DIRECTORY;?>";

</script>