/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 3.0                   # ||
|| # ----------------------------------------- # ||
|| # Copyright 2016 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

$(document).ready(function() {

	$(document).on('keypress', '#messageOC', function(e) {
		if (e.keyCode == 13 && !e.shiftKey) {
			e.preventDefault();
			sendInputOC();
		}
	});
			
});

(function(){
	ls = {
		main_lang: "",
		main_url: "",
		intervalID: ""
	}
})();
	
function sendInputOC() {

	/* This flag will prevent multiple comment submits: */
	var working = false;
	
	if(working) return false;
	
	working = true;
	var chat_button_text = $('#chat_s_buttonOC').val();
	$('#chat_s_buttonOC').val("...");

	var messageOC = $('#messageOC').val();
	var messageoc = encodeURIComponent(messageOC);
	
	// Cancel if there is no message
	if (!messageoc) return false;
	
	var uid = $('#userIDOC').val();

	var request = $.ajax({
		async: true,
	  url: ls.main_url+'ajax/publicchat.php',
	  type: "POST",
	  data: "page=send-msg&uid="+uid+"&message="+messageoc,
	  dataType: "html"
	});
	
	request.done(function(msg) {
		if (msg == "success") {
			if (ls.intervalID) {
				clearInterval(ls.intervalID);
				ls.intervalID = null;
			}
			getInputOC();

			$('#operator-chat').animate({scrollTop: $('#operator-chat')[0].scrollHeight});
			$('#messageOC').val("");
			$('#messageOC').focus();
			// Load the message
			ls.intervalID = setInterval("getInputOC();", 5000);
		} else {
			$('#msgErrorOC').fadeIn().addClass("alert alert-block alert-error").html(msg).delay(5000).fadeOut();
		}
		
		working = false;
		$('#chat_s_buttonOC').val(chat_button_text);
	});
	
}

function getInputOC() {

	var uid = $('#userIDOC').val();

	var request = $.ajax({
		async: true,
	  url: ls.main_url+'ajax/publicchat.php',
	  type: "POST",
	  data: "page=load-msg&uid="+uid,
	  dataType: "html"
	});
	
	request.done(function(msg) {
		if (msg) {
			$('#operator-chat').html(msg);
		}
	});	
}