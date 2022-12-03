/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0                   # ||
|| # ----------------------------------------- # ||
|| # Copyright 2021 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

// Standard vars
var debugme = false;
var working = false;
var loadbtn = document.getElementById("send_contact");

// We send the value from the text area
if (loadbtn) loadbtn.addEventListener("click", function(event){
  event.preventDefault();
  lcjak_contactchat();
});

function lcjak_contactchat() {

  if (working) return false;
  working = true;

  loadbtn.classList.remove("fa-paper-plane");
  loadbtn.classList.add("fa-spinner","fa-pulse");
  document.querySelector('.lcjak_input').classList.remove("error");

  // Get the session
  var lcjak_session = "0";
  if (localStorage.getItem('lcjak_session')) lcjak_session = localStorage.getItem('lcjak_session');

  // Get the geo data
  var lcjak_geodata = "0";
  if (localStorage.getItem('lcjak_geodata')) lcjak_geodata = localStorage.getItem('lcjak_geodata');

  var xhrc = new XMLHttpRequest();

  // Bind the FormData object and the form element
  const lcjakform = document.getElementById("lcjak_ajaxform");
  const FD = new FormData(lcjakform);

  // Call the file to verify and start the process
  xhrc.open('POST', base_url+'include/chatdata.php?id='+lcjakwidgetid+'&run=sendcontact&lang='+lcjak_lang, true);

  // time in milliseconds
  xhrc.timeout = 3000; 

  // Some sort of an error, let's redo the send button
  xhrc.addEventListener( "error", function( event ) {
      loadbtn.classList.remove("fa-spinner","fa-pulse");
      loadbtn.classList.add("fa-paper-plane");
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

          var lcform = document.getElementById('lcjak_formfields');

          lcform.innerHTML = '';

          lcform.insertAdjacentHTML('beforeend', data.successdiv);

          loadbtn.classList.remove("fa-spinner","fa-pulse");
          loadbtn.classList.add("fa-paper-plane");

          var s = document.getElementById('send_c_button').style;
          s.opacity = 1;
          (function fade(){(s.opacity-=.1)<0?s.display="none":setTimeout(fade,40)})();

          return true;

        }

      } else {

          if (debugme) console.log(data.error);
          loadbtn.classList.remove("fa-spinner","fa-pulse");
          loadbtn.classList.add("fa-paper-plane");

          for (var k in data.error) {
              if (debugme) console.log(data.error[k]);
              document.getElementById(k).classList.add("error");
          } 

      }

      // We will make the form again
      working = false;
      return true;

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

  FD.append("rlbid", lcjak_session);
  FD.append("geo", lcjak_geodata);

  // Finally send the data
  xhrc.send(FD);

}

function lcjak_closechat(anim) {

  const element = document.querySelector('.lcb_close');
  element.classList.add('animate__animated', 'animate__rotateOut');

  element.addEventListener('animationend', () => {

    // Replace the current url with the new one /prevent ajax request
    let old_url = window.location.href;

    // Let us check if we have rewrite enabled
    var searchurl = "lc&sp=open";
    if (localStorage.getItem('lcjak_chatstatus') == "big") {
      searchurl = "lc&sp=big";
    }
    if (localStorage.getItem('lcjak_chatstatus') == "contactform") {
      searchurl = "lc&sp=contactform";
    }
    var repurl = "lc&sp=closed";
    
    if (base_rewrite == 1) {
      searchurl = "lc/open";
      if (localStorage.getItem('lcjak_chatstatus') == "big") {
        searchurl = "lc/big";
      }
      if (localStorage.getItem('lcjak_chatstatus') == "contactform") {
        searchurl = "lc/contactform";
      }
      repurl = "lc/closed";
    }
    let new_url = old_url.replace(searchurl, repurl);
    if (debugme) console.log(new_url);

    // Set the local storage to closed
    localStorage.setItem('lcjak_chatstatus', "closed");

    // We also need to send the customer data to the other domain if
    if (cross_url && parent.postMessage) {
      parent.postMessage('chatstatus::closed', cross_url);
    }

    // Redirect to close the chat
    window.location = new_url;

  });

}

function lcjak_bigchat(anim) {

  const element = document.querySelector('.jaklcb_maximise');
  element.classList.add('animate__animated', 'animate__slideOutUp');

  element.addEventListener('animationend', () => {

    // Set the local storage to closed
    localStorage.setItem('lcjak_chatstatus', "big");

    // We also need to send the customer data to the other domain if
    if (cross_url && parent.postMessage) {
      parent.postMessage('chatstatus::big', cross_url);
    }

    // Replace the current url with the new one /prevent ajax request
    let old_url = window.location.href;

    // Let us check if we have rewrite enabled
    var searchurl = "lc&sp=open";
    var repurl = "lc&sp=big";
    if (base_rewrite == 1) {
      searchurl = "lc/open";
      repurl = "lc/big";
    }
    let new_url = old_url.replace(searchurl, repurl);
    if (debugme) console.log(new_url);

    // Redirect to big chat
    window.location = new_url;

  });

}

function lcjak_smallchat(anim) {

  const element = document.querySelector('.jaklcb_minimize');
  element.classList.add('animate__animated', 'animate__slideOutDown');

  element.addEventListener('animationend', () => {

    // Set the local storage to closed
    localStorage.setItem('lcjak_chatstatus', "open");

    // We also need to send the customer data to the other domain if
    if (cross_url && parent.postMessage) {
      parent.postMessage('chatstatus::open', cross_url);
    }

    // Replace the current url with the new one /prevent ajax request
    let old_url = window.location.href;

    // Let us check if we have rewrite enabled
    var searchurl = "lc&sp=big";
    var repurl = "lc&sp=open";
    if (base_rewrite == 1) {
      searchurl = "lc/big";
      repurl = "lc/open";
    }
    let new_url = old_url.replace(searchurl, repurl);
    if (debugme) console.log(new_url);

    // Redirect to big chat
    window.location = new_url;

  });

}