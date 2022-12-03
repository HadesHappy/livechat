/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0.3                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2022 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

// Null
var rllinput = null;
var rlsbint = null;
//fields
var title = document.title;
var usrname = $('#userName').val();
var oname = $('#operatorName').val();
var message = '';
// false
var otyping = "false";
var answerconv = "false";
var activeConversation = false;
var jakSSEuo = false;
var activeConvID = false;
// true
var show_notifiy = true;
var scrollchat = true;
var loadchat = true;
// numeric
var clatency = 3000;
var ring_count = 1;
var cchatid = 0;
var lastmsgid = 0;

$(document).ready(function() {

	$('#response').change(function() {
		standardResponse(encodeURI($(this).val()));
	});
	$('#sendFile').click(function() {
		sharedFiles($('#files option:selected').val());
	});
	$('#user-info-hide').click(function() {
		$('#user-info').fadeToggle();
	});
	$("#message").on("keyup", function(event) {
		if ($(this).val().length > 0 && event.which != 13 && otyping == "false") {
			operatorTyping();
		}
		if ($(this).val().length == 0 && otyping == "true") {
			if (!$.trim($(this).val())) operatorNotTyping();
		}
	});
	$("#message").on("keypress", function(event) {
		if (event.keyCode == 13 && !event.shiftKey) {
			event.preventDefault();
			sendInput(activeConvID);
		}
	});

	$("#message_btn").on("click", function (event) {
		event.preventDefault();
		sendInput(activeConvID);
	});

});

function sseJAK(oid, olist, uonline) {
	if (ls.usrAvailable) {
		userOnline(oid, olist, uonline);
		rlsbint = setInterval(function() {
			userOnline(oid, olist, uonline);
		}, clatency);
	}
}

function toggleAvailable(opstatus, uonline) {
	if (opstatus == 1) {
		var newstatusclass = "text-success";
		ls.usrAvailable = 1;
	} else if (opstatus == 2) {
		var newstatusclass = "text-warning";
		ls.usrAvailable = 2;
	} else {
		var newstatusclass = "text-danger";
		ls.usrAvailable = 0;
		if (rlsbint) {
			clearInterval(rlsbint);
			rlsbint = null;
		}
		if (jakSSEuo) {
			jakSSEuo.close();
			jakSSEuo = null;
		}
	}
	$('#operator-status-colour').removeClass(function (index, css) {
		return (css.match(/(^|\s)text-\S+/g) || []).join(' ')
	}).addClass(newstatusclass);
	$.ajax({
		async: true,
		type: "POST",
		url: ls.main_url + 'ajax/oprequests.php',
		data: "oprq=status&available=" + opstatus + "&uid=" + ls.opid,
		dataType: 'json',
		success: function (msg) {
			if (msg.status == 1) {
				if (!jakSSEuo) sseJAK(ls.opid, msg.olist, uonline);
			} else if (msg.status == 2) {
				if (!jakSSEuo) sseJAK(ls.opid, msg.olist, uonline);
			}
			$('#collapseOP').collapse('hide');
			return false;
		}
	});
}

function usrFiles(cid) {
	$.ajax({
		async: true,
		type: "POST",
		url: ls.main_url + 'ajax/userfiles.php',
		data: "id=" + cid,
		dataType: 'json',
		success: function(msg) {
			if (!msg.status) {
				$('#user_files i').removeClass('fa-lock').addClass('fa-check');
			} else {
				$('#user_files i').removeClass('fa-check').addClass('fa-lock');
			}
			return false;
		}
	});
}

function usrBan(id, ip) {

	var request = $.ajax({
		async: true,
		url: ls.main_url + 'ajax/oprequests.php',
		type: "POST",
		data: "oprq=ban&id=" + id + "&conv=" + activeConvID + "&uid=" + ls.opid + "&ip=" + ip + "&oname=" + usrname,
		dataType: "json",
		cache: false
	});
	request.done(function(msg) {

		if (msg.status) {

			$.notify({
				icon: 'fa fa-check-square',
				message: msg.html
			}, {
				type: 'success',
				animate: {
					enter: 'animate__animated animate__fadeInDown',
					exit: 'animate__animated animate__fadeOutUp'
				}
			});

		} else {

			$.notify({
				icon: 'fa fa-exclamation-triangle',
				message: msg.html
			}, {
				type: 'danger',
				animate: {
					enter: 'animate__animated animate__fadeInDown',
					exit: 'animate__animated animate__fadeOutUp'
				}
			});

		}
	});
}

function acceptTransfer(accept, userid, convid) {
	var request = $.ajax({
		async: true,
		url: ls.main_url + 'ajax/oprequests.php',
		type: "POST",
		data: "oprq=transfer&accept=" + accept + "&uid=" + userid + "&convid=" + convid,
		dataType: "json",
		cache: false
	});
	request.done(function(msg) {
		document.title = title;
		ring_count = 1;
		show_notifiy = true;
		if (msg.status) {
			window.location = msg.href;
		} else {
			if (activeConvID) {
				$("#transfer").hide().html("");
			} else {
				$("#transfer").html(msg.noconv);
			}
		}
	});
}

function sharedFiles(id) {
	var request = $.ajax({
		async: true,
		url: ls.main_url + 'ajax/oprequests.php',
		type: "POST",
		data: "oprq=sendfile&id=" + id + "&conv=" + activeConvID + "&uid=" + ls.opid + "&uname=" + usrname + "&oname=" + oname,
		dataType: "json",
		cache: false
	});
	request.done(function(msg) {
		$('#files').val(0);
		loadInput(activeConvID);
	});
}

function standardResponse(msg) {
	$("#message").val(decodeURI(msg)).focus();
	$('#response').val(0);
	sendInput(activeConvID);
}

function operatorTyping() {
	otyping = "true";
	var request = $.ajax({
		async: true,
		url: ls.main_url + 'ajax/typing.php',
		type: "POST",
		data: "conv=" + activeConvID + "&status=1",
		dataType: "json",
		cache: false
	});
}

function operatorNotTyping() {
	otyping = "false";
	var request = $.ajax({
		async: true,
		url: ls.main_url + 'ajax/typing.php',
		type: "POST",
		data: "conv=" + activeConvID + "&status=0",
		dataType: "json",
		cache: false
	});
}

function sendInvitation() {
	$('#proActiveModal').modal('hide');
	var msg = $('#proactivemsg').val();
	var id = $('#proactiveuid').val();
	if (msg) {
		var request = $.ajax({
			async: true,
			url: ls.main_url + 'ajax/invitation.php',
			type: "POST",
			data: "id=" + id + "&uid=" + ls.opid + "&msg=" + msg,
			dataType: "json",
			cache: false
		});
		request.done(function(msg) {
			if (msg.status) {
				$("#usero-" + id).closest("tr").removeClass('error').removeClass('success').addClass("warning");
			}
		});
	}
}

function sendInput(id) {
	var working = false;
	if (working) return false;
	working = true;
	$("#message").addClass("loadingbg");
	var message = $('#message').val();
	var messageURL = $('#sendurl').val();
	var msgeditid = $('#msgeditid').val();
	var msgquoteid = $('#msgquoteid').val();
	var request = $.ajax({
		async: true,
		url: ls.main_url + 'ajax/insertadmin.php',
		type: "POST",
		data: "id=" + id + "&userid=" + ls.opid + "&uname=" + encodeURIComponent(usrname) + "&oname=" + encodeURIComponent(oname) + "&msg=" + encodeURIComponent(message) + "&url=" + encodeURIComponent(messageURL) + "&msgedit=" + encodeURIComponent(msgeditid) + "&msgquote=" + encodeURIComponent(msgquoteid) + "&conv=" + activeConvID,
		dataType: "json",
		cache: false
	});
	request.done(function(msg) {
		if (msg.status) {
			if (msg.edit) {
				$('#msg' + msg.editid).html(msg.edit);
				$('#postid_' + msg.editid + ' a.edit-msg').data("msg", msg.editblank);
				$('#edited_' + msg.editid).html(msg.showedit);
				$('.edit-msg').removeClass('active');
			} else {
				loadInput(id);
			}
			$('.media-text').removeClass('highlight');
			$('.edit-quote').removeClass('active');
			$('#message, #sendurl, #msgquoteid, #msgeditid').val("");
			answerconv = "false";
			otyping = "false";
			$('#message').focus();
			show_notifiy = true;
		} else {
			$.notify({
				icon: 'fa fa-exclamation-triangle',
				message: msg.html
			}, {
				type: 'danger',
				animate: {
					enter: 'animate__animated animate__fadeInDown',
					exit: 'animate__animated animate__fadeOutUp'
				}
			});
			$("#message").focus();
		}
		$("#message").removeClass("loadingbg");
		working = false;
	});
}

function userOnline(id, olist, uonline) {
	var request;
	if (typeof request !== 'undefined') request.abort();
	$('.jakweb-oponline').tooltip('dispose');
	request = new XMLHttpRequest();
	request.open('GET', ls.main_url + 'ajax/status.php?uid=' + ls.opid + '&olist=' + olist + '&uonline=' + uonline + '&activconv=' + activeConvID + '&convlist=1', true);
	request.timeout = clatency;
	request.onload = function() {
		if (request.status >= 200 && request.status < 400) {
			var data = JSON.parse(request.responseText);
			handleuOnline(data);
		} else {

		}
	};
	request.onerror = function() {};
	request.ontimeout = function(e) {};
	request.send();
}

function handleuOnline(msg) {

	// We reset the sound alert, because we have a new customer
	if (msg.soundalert == 1) ring_count = 1;

	// We have no active conversation set to expired
	if (!activeConversation) {
		$("#MessageInput").hide();
		$("#client-left").show();
	}

	if (msg.conversation) {
		$('#chatRequests').fadeIn();
		$('#currentConv').html(msg.conversation);
		$('#totalchats').html(msg.totalchats).fadeIn();
		$('.a-confirm-chat').on("click", function (e) {
			e.preventDefault();
			var convid = $(this).data("convid");
			var opid = $(this).data("opid");
			var href = $(this).attr("href");
			var title = $(this).data("title");
			var okbtn = '<button class="alertable-ok" type="submit">' + $(this).data("okbtn") + '</button>';
			var cbtn = '<button class="alertable-cancel" type="button">' + $(this).data("cbtn") + '</button>';
			$.alertable.confirm(title, {
				okButton: okbtn,
				cancelButton: cbtn
			}).then(function () {
				activeConvID = convid;
				takeChat(convid, opid, href);
			},
			function () {
				denyChat(convid, opid);
			})
		});
		$("#MessageInput").show();
		$("#client-left").hide();
	} else {
		$("#client-left").show();
		$("#MessageInput, #chatRequests, #totalchats").hide();
		$('#currentConv').html("");
	}

	if (msg.oponline) {
		$('#opRequests').fadeIn();
		$('#operatorOnline').html(msg.oponline);
		if (msg.totalops != 0) {
			$('#totalops').html(msg.totalops).fadeIn();
		} else {
			$("#totalops").hide();
		}
		$('.jakweb-oponline').tooltip({placement: 'top',html: true});
	} else {
		$('#opRequests').fadeOut();
		$('#operatorOnline').html("");
		$("#totalops").hide();
	}

	if (msg.totalchats && msg.totalchats != 0) {
		$('#totalchats').html(msg.totalchats);
	} else {
		$('#totalchats').html("0");
	}
	if (msg.useronline) {
		$('#userOnline').html("");
		$('#userOnline').append(msg.useronline);
		$('.currentlyonline').html(msg.totalonline);
		$('.jakweb-online-user').click(function() {
			var param = $(this).attr('id');
			var param = param.replace("usero-", "");
			$('#proActiveModal').modal('show');
			$('#proactiveuid').val(param);
		});
		if (msg.useronlinemap.length != 0) {
			var markers = L.layerGroup();
			var cmarkerid = [];
			loadlayers = false;
			for (var i = 0; i < msg.useronlinemap.length; ++i) {
				cmarkerid.push(msg.useronlinemap[i].id);
				if (markerid.indexOf(msg.useronlinemap[i].id) == -1) {
					markerid.push(msg.useronlinemap[i].id);
					marker[msg.useronlinemap[i].id] = L.marker([msg.useronlinemap[i].lat, msg.useronlinemap[i].lng]).bindPopup(msg.useronlinemap[i].popup);
					markers.addLayer(marker[msg.useronlinemap[i].id]);
					loadlayers = true;
				}
			}
			var removemarker = markerid.filter(function(val) {
				return cmarkerid.indexOf(val) == -1
			});
			for (var u = 0; u < removemarker.length; ++u) {
				statmap.removeLayer(marker[removemarker[u]]);
				var o = markerid.indexOf(removemarker[u]);
				if (o != -1) {
					markerid.splice(o, 1);
				}
			}
			if (loadlayers) {
				statmap.addLayer(markers);
			}
		} else {
			var cmarkerid = [];
			markerid = [];
		}
	} else {
		$('#userOnline').html("");
		$('.currentlyonline').html("0");
		if (typeof markerid !== 'undefined') {
			for (var u = 0; u < markerid.length; ++u) {
				statmap.removeLayer(marker[markerid[u]]);
			}
			markerid = [];
		}
	}
	if (msg.newc == 1) {
		if (ls.muted && ring_count <= ls.ls_ringing) {
			playSound(msg.soundjs + '.webm', msg.soundjs + '.mp3');
			ring_count++;
		}
		document.title = msg.newclient + ' ('+msg.totalchats+')';
		if (show_notifiy) dNotifyNew(title, msg.newclient);
	} else if (msg.newc == 2) {
		loadInput(activeConvID);
		document.title = msg.newmsg + ' (1)';
		if (ls.muted && answerconv == "false") {
			playSound(msg.soundmsgjs + '.webm', msg.soundmsgjs + '.mp3');
		}
		if (show_notifiy) dNotifyNew(title, msg.newmsg);
	} else {
		document.title = title
	}
	if (msg.tid) {
		$("#topAlerts").fadeIn();
		$("#transfer").html("").show();
		$("#transfer").append(msg.tmsg).fadeIn(200).delay(30000);
		document.title = msg.jsmsg;
		if (ls.muted) {
			playSound(msg.soundjs + '.webm', msg.soundjs + '.mp3');
		}
		if (show_notifiy) dNotifyNew(title, msg.jsmsg);
	}
	if (activeConvID && msg.previewmsg) {
		if ($("#prevcontainer_" + activeConvID).length == 0) {
			$('#chatOutput').append(msg.previewmsg);
			scrollBottom();
		} else {
			$('#prevmsg_' + activeConvID).html(msg.prevmsgonly)
		}
	} else if (activeConvID) {
		if ($("#prevcontainer_" + activeConvID).length != 0) {
			$("#prevcontainer_" + activeConvID).remove();
		}
	}
}

function takeChat(id, userid, href) {
	var request = $.ajax({
		async: true,
		url: ls.main_url + 'ajax/takechat.php',
		type: "POST",
		data: "id=" + id + "&userid=" + userid + "&pusho=" + ls.pushnotify,
		dataType: "json",
		cache: false
	});
	request.done(function(msg) {
		if (msg.cid) {
			window.location = href;
		}
	});
}

function denyChat(id, userid) {
	var request = $.ajax({
		async: true,
		url: ls.main_url + 'ajax/oprequests.php',
		type: "POST",
		data: "oprq=deny&id=" + id + "&uid=" + userid,
		dataType: "json",
		cache: false
	});
	request.done(function(msg) {
		ring_count = 1;
		show_notifiy = true;
	});
}

function getInfo(id) {
	if (id != cchatid) {
		lastmsgid = 0;
		activeConversation = true;
		var request = $.ajax({
			async: true,
			url: ls.main_url + 'ajax/userinfo.php',
			type: "POST",
			data: "id=" + id,
			dataType: "json",
			cache: false
		});
		request.done(function(msg) {
			if (msg.status) {
				if (activeConvID) {
					$('#chatOutput').html("");
					$('.chat-inactive-container').hide();
					$('.chat-active-container, .flex-sidebar-chat').show();
					$('#content-header-title').html('<i class="fa fa-comments-o"></i> ' + msg.name);
					$('#message').focus();
					$("#response").html(msg.responses).selectpicker("refresh");
					$("#emoji").emojioneArea();
					loadInput(activeConvID);
				}
				cchatid = id;
			}
		});
	}
}

function loadInput(id) {
	loadchat = true;
	getInput(id);
}

function getInput(id) {
	if (loadchat && activeConversation) {
		if (rllinput) {
			clearInterval(rllinput);
			rllinput = null;
		}
		var request = $.ajax({
			async: true,
			url: ls.main_url + 'ajax/retrieveadmin.php',
			type: "POST",
			data: "id=" + id + "&lastid=" + lastmsgid,
			dataType: "json",
			cache: false
		});
		request.done(function(msg) {
			if (msg.status) {
				if ($("#prevcontainer_" + activeConvID).length != 0) {
					$("#prevcontainer_" + activeConvID).remove();
				}
				$('#chatOutput').append(msg.chat);
				if (ls.usrAvailable) {
					loadchat = false;
					lastmsgid = msg.lastid;
					if (!rllinput) rllinput = setInterval("loadInput(activeConvID);", 30000);
					scrollBottom();
					if (msg.chatended) activeConversation = false;
				} else {
					$("#client-left").show();
					$("#MessageInput").hide();
				}
			} else {
				if (msg.chatended) activeConversation = false;
			}
		});
	}
}

function setTimer(id) {
	var request = $.ajax({
		async: true,
		url: ls.main_url + 'ajax/timer.php',
		type: "POST",
		data: "id=" + id + "&ops=" + ls.usrAvailable,
		dataType: "json",
		cache: false
	});
	request.done(function(msg) {

		var newstatusclass = false;

		if (msg.status) {
        	// All good nothing to do!
            $("#connection-error").hide();

        } else {

            $("#connection-error").fadeIn();

            newstatusclass = "status-danger";
            ls.usrAvailable = 0;

            if (rlsbint) {
             	clearInterval(rlsbint);
             	rlsbint = null;
            }
            if (jakSSEuo) {
             	jakSSEuo.close();
             	jakSSEuo = null;
            }
        }

        if (msg.usrstatus == 1) {
            newstatusclass = "status-success";
            ls.usrAvailable = 1;
        } else if (msg.usrstatus == 2) {
            newstatusclass = "status-warning";
            ls.usrAvailable = 2;
        }

        // Only if we have a change
        if (newstatusclass) {
	        $('.toggle-available').removeClass(function(index, css) {
	            return (css.match(/(^|\s)status-\S+/g) || []).join(' ')
	        }).addClass(newstatusclass);
	    }
    });
}

function dNotifyNew(title, msg) {
  // Let's check if the browser supports notifications
  if (!("Notification" in window)) {
    alert("This browser does not support desktop notification");
  }

  // Let's check whether notification permissions have alredy been granted
  else if (Notification.permission === "granted") {
    // If it's okay let's create a notification
    var notification = new Notification(title, {icon: ls.orig_main_url + "img/logo.png"}, {body: msg});
  }

  // Otherwise, we need to ask the user for permission
  else if (Notification.permission !== 'denied') {
    Notification.requestPermission(function (permission) {
      // If the user accepts, let's create a notification
      if (permission === "granted") {
        var notification = new Notification(title, {icon: ls.orig_main_url + "img/logo.png"}, {body: msg});
      }
    });
  }

  	show_notifiy = false;
	return true;

  // At last, if the user has denied notifications, and you
  // want to be respectful there is no need to bother them any more.
}

function scrollBottom() {
	$('#chatOutput').animate({
		scrollTop: $('#chatOutput')[0].scrollHeight
	}, 300);
	scrollchat = false;
}

function playSound(soundfile, soundfile2) {
	var sound = new Howl({
		src: [ls.orig_main_url + soundfile2, ls.orig_main_url + soundfile]
	});
	sound.play();
	answerconv = "true";
}