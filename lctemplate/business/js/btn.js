/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0.3                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2022 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

// Standard vars
var debugme = false;
var openchat = document.getElementById("lcjak_openchat");

document.addEventListener("DOMContentLoaded", function() { 
	lcjak_engageChat();
	if (!localStorage.getItem('lcjak_engage')) lcjakint = setInterval(function(){lcjak_engageChat()}, 3000);
});

if (openchat) openchat.addEventListener("click", function(event){
	event.preventDefault();
	lcjak_openchat(openchat);
	
});

function lcjak_engageChat() {

  var xhrc = new XMLHttpRequest();

  // Let's get the current status of the local storage
	lcjak_chatstatus = localStorage.getItem('lcjak_chatstatus');
  // Get the customer
	var lcjak_customer = "0";
	if (localStorage.getItem('lcjak_customer')) lcjak_customer = localStorage.getItem('lcjak_customer');
	// Get the session
	var lcjak_session = "0";
	if (localStorage.getItem('lcjak_session')) lcjak_session = localStorage.getItem('lcjak_session');
	// Get the first visit
	var lcjak_firstvisit = "0";
	if (localStorage.getItem('lcjak_firstvisit')) lcjak_firstvisit = localStorage.getItem('lcjak_firstvisit');
	// Get the online status
	if (localStorage.getItem('lcjak_onlinestatus')) lcjak_onlinestatus = localStorage.getItem('lcjak_onlinestatus');
	// Last time online
	var lcjak_lastvisit = localStorage.getItem('lcjak_lastvisit');
	// Engage Status
	var lcjak_engage = "0";
	if (localStorage.getItem('lcjak_engage')) lcjak_engage = localStorage.getItem('lcjak_engage');

  // Call the file to verify and start the process
  xhrc.open('POST', base_url+'include/chatdata.php?id='+lcjakwidgetid+'&run=engage&lang='+lcjak_lang, true);

  // time in milliseconds
  xhrc.timeout = 3000; 

  // Some sort of an error, let's redo the send button
  xhrc.addEventListener( "error", function( event ) {
      if (debugme) console.log(event);
  });

  // Request
  xhrc.onload = function() {
    if (xhrc.status >= 200 && xhrc.status < 400) {
     	// Success!
      	var data = JSON.parse(xhrc.responseText);

      	// We have data
		if (data.status) {

			if (debugme) {

          console.log(data);

        } else {

        	// We will need to refresh the last status
					if (data.lastvisit) localStorage.setItem('lcjak_lastvisit', data.lastvisit);

					// We will need to current status of the chat
					if (data.onlinestatus) localStorage.setItem('lcjak_onlinestatus', data.onlinestatus);

          // We reload the location
          lcjak_handleEngage(data);
          return true;
        }

		} else {

			// We will need to refresh the last status
			if (data.lastvisit) localStorage.setItem('lcjak_lastvisit', data.lastvisit);

			// Debug
		  if (debugme) console.log(data);
		  return true;
		}

    } else {
      // We reached our target server, but it returned an error

    }
  };

  xhrc.onerror = function() {
    // There was a connection error of some sort
  };

  xhrc.ontimeout = function (e) {
      // XMLHttpRequest timed out. Do something here.
  };

  // Attach Data
  var formData = new FormData();
  formData.append("chatstatus", lcjak_chatstatus);
  formData.append("rlbid", lcjak_session);
  formData.append("firstvisit", lcjak_firstvisit);
  formData.append("customer", lcjak_customer);
  formData.append("onlinestatus", lcjak_onlinestatus);
  formData.append("lastvisit", lcjak_lastvisit);
  formData.append("engage", lcjak_engage);

  // Finally send the data
  xhrc.send(formData);

}

function lcjak_handleEngage(data) {

	// We have a widget change
	if (data.widget) {
		location.reload();
		return true;
	}

	// Get the knock knock
	if (data.knockknock) {

		if (data.soundalert) {
			var lcjsound = new Howl({
				src: [base_url+'/'+data.soundalert+'.webm', base_url+'/'+data.soundalert+'.mp3']
			});
			lcjsound.play();
		}
		if (parent.postMessage) {
        	parent.postMessage('knockknock::'+data.knockknock, cross_url);
    	} else {
			alert(msg.knockknock);
		}
		
		return true;
	}

	// New Message Play the sound
	if (data.newmessage) {

		if (data.soundalert) {
			var lcjsound = new Howl({
				src: [base_url+'/'+data.soundalert+'.webm', base_url+'/'+data.soundalert+'.mp3']
			});
			lcjsound.play();
		}

		if (localStorage.getItem('lcjak_chatstatus') == "closed") {

			// Replace the current url with the new one /prevent ajax request
			let old_url = window.location.href;
			// Let us check if we have rewrite enabled
	    var searchurl = "lc&sp=closed";
	    var repurl = "lc&sp=open";
	    if (base_rewrite == 1) {
	      searchurl = "lc/closed";
	      repurl = "lc/open";
	    }
	    let new_url = old_url.replace(searchurl, repurl);
			if (debugme) console.log(new_url);

			// Set the local storage to open
			localStorage.setItem('lcjak_chatstatus', "open");

			// We also need to send the customer data to the other domain if
	    if (cross_url && parent.postMessage) {
	    	parent.postMessage('chatstatus::open', cross_url);
	    }

			// Redirect to open the chat
			window.location = new_url;

		}

	}

	// Fire engage
	if (data.engage) {

		// Replace the current url with the new one /prevent ajax request
		let old_url = window.location.href;

		if (data.showalert == 1 && data.engagediv) {

			// We load the div dynamically to show the engage
			var lccont = document.getElementById('lccontainersize');
			lccont.classList.remove('jak_roundbtn');
			lccont.removeAttribute("style");
			lccont.classList.add('jak_roundbtn_engage');
			var lcsize = lccont.getBoundingClientRect();
		  var cswidth = lcsize.width;
		  var csheight = lcsize.height;
		  iframe_resize(cswidth, csheight, data.cposition, cross_url);
			
			lccont.insertAdjacentHTML('beforeend', data.engagediv);

			if (data.sound) {
				var lcjsound = new Howl({
					src: [base_url+'/'+data.sound+'.webm', base_url+'/'+data.sound+'.mp3']
				});
				lcjsound.play();
			}

			clearInterval(lcjakint);
      lcjakint = null;
			
			return true;

		} else {

			// Let us check if we have rewrite enabled
	    var searchurl = "lc&sp=closed";
	    var repurl = "lc&sp=open";
	    if (base_rewrite == 1) {
	      searchurl = "lc/closed";
	      repurl = "lc/open";
	    }

	    // Set the local storage to open
			localStorage.setItem('lcjak_chatstatus', "open");

			// We also need to send the customer data to the other domain if
	    if (cross_url && parent.postMessage) {
	    	parent.postMessage('chatstatus::open', cross_url);
	    }

			let new_url = old_url.replace(searchurl, repurl);

    	// Redirect to open the chat
			window.location = new_url;

		}
		
	}

}

function lcjak_openchat(elm) {
	
	elm.classList.add('animate__animated', 'animate__zoomOut');

	elm.addEventListener('animationend', () => {

		// Set the local storage to open
		localStorage.setItem('lcjak_chatstatus', "open");

		// Replace the current url with the new one /prevent ajax request
		let old_url = window.location.href;
		// Let us check if we have rewrite enabled
    var searchurl = "lc&sp=closed";
    // special url for engage
  	if (localStorage.getItem('lcjak_engage')) {
  		searchurl = "lc&sp=engage";
  	}
    var repurl = "lc&sp=open";
    if (base_rewrite == 1) {
      searchurl = "lc/closed";
      // special url for engage
	  	if (localStorage.getItem('lcjak_engage')) {
	  		searchurl = "lc/engage";
	  		
	  	}
      repurl = "lc/open";
    }
    let new_url = old_url.replace(searchurl, repurl);
		if (debugme) console.log(new_url);

		// Finally remove the engage storage
		if (localStorage.getItem('lcjak_engage')) localStorage.removeItem('lcjak_engage');

		// We also need to send the customer data to the other domain if
    if (cross_url && parent.postMessage) {
    	parent.postMessage('chatstatus::open', cross_url);
    }

		// Redirect to open the chat
		window.location = new_url;

	});

}

function lcjak_closechat(elm) {

  elm.classList.add('animate__animated', 'animate__rotateOut');

  elm.addEventListener('animationend', () => {

  	// Set the local storage to closed
  	localStorage.setItem('lcjak_chatstatus', "closed");

  	// We also need to send the customer data to the other domain if
    if (cross_url && parent.postMessage) {
    	parent.postMessage('chatstatus::closed', cross_url);
    }

		// Replace the current url with the new one /prevent ajax request
		let old_url = window.location.href;
		// Let us check if we have rewrite enabled
    var searchurl = "lc&sp=open";
    // special url for engage
  	if (localStorage.getItem('lcjak_engage')) {
  		searchurl = "lc&sp=engage";
  	}
    var repurl = "lc&sp=closed";
    
    if (base_rewrite == 1) {
      searchurl = "lc/open";
      // special url for engage
	  	if (localStorage.getItem('lcjak_engage')) {
	  		searchurl = "lc/engage";
	  		
	  	}
      repurl = "lc/closed";
    }
    let new_url = old_url.replace(searchurl, repurl);
		if (debugme) console.log(new_url);

		// Finally remove the engage storage
		if (localStorage.getItem('lcjak_engage')) localStorage.removeItem('lcjak_engage');

		// Redirect to close the chat
		window.location = new_url;

  });

}