/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 3.2                   # ||
|| # ----------------------------------------- # ||
|| # Copyright 2018 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

document.getElementById("uploadavatar").onchange = function () {
    var reader = new FileReader();

    reader.onload = function(e) {
        // get loaded data and render thumbnail.
        document.getElementById("customavatar").src = e.target.result;
    };

    // read the image file as a data URL.
    reader.readAsDataURL(this.files[0]);
};