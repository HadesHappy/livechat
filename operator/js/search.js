/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 4.0                   # ||
|| # ----------------------------------------- # ||
|| # Copyright 2020 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

function showResult(str,convid) {
  	if (str.length == 0) { 
    	document.getElementById("livesearch").innerHTML = "";
    	return;
  	}
  	if (window.XMLHttpRequest) {
    	// code for IE7+, Firefox, Chrome, Opera, Safari
   		xmlhttp = new XMLHttpRequest();
  	} else {  // code for IE6, IE5
    	xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  	}
  	xmlhttp.onreadystatechange=function() {
	    if (this.readyState == 4 && this.status == 200) {
	      	document.getElementById("livesearch").innerHTML = this.responseText;
	    }
  	}
  	if (str.length == 2) {
		xmlhttp.open("GET", ls.main_url + "ajax/search.php?q=" + str + "&convid=" + convid, true);
		xmlhttp.send();
	}
}