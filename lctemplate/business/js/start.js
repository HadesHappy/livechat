/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0.4                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2022 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

// Standard vars
var debugme = false;
var working = false;

/* modern browsers - will execute code once all elements are loaded onto the client */
window.addEventListener('DOMContentLoaded', (event) => {
    // content is loaded, load the value from storage
    if (localStorage.getItem('lcjak_customvars')) lcjak_customvars(); // call the function to perform
});

// We listen for the enter key on the textarea
if (document.getElementById('start_chat_msg')) {
  document.getElementById("start_chat_msg").addEventListener("keyup", function(event) {
      event.preventDefault();
      if (event.keyCode === 13) {
          document.getElementById("start_chat_btn").click();
      }
  });

  document.getElementById("start_chat_btn").addEventListener("click", function(event){
    event.preventDefault();
    lcjak_startChat();
  });
}

function lcjak_customvars() {
   if (working) return false;
  working = true;

  // Get the session
  var lcjak_session = "0";
  if (localStorage.getItem('lcjak_session')) lcjak_session = localStorage.getItem('lcjak_session');
  
  // Get the customer
  var lcjak_customvars = "0";
  if (localStorage.getItem('lcjak_customvars')) lcjak_customvars = localStorage.getItem('lcjak_customvars');

  var xhrc = new XMLHttpRequest();

  // Bind the FormData object and the form element
  const lcjakform = document.getElementById("lcjak_ajaxform");
  const FD = new FormData(lcjakform);

  // Call the file to verify and start the process
  xhrc.open('POST', base_url+'include/chatdata.php?id='+lcjakwidgetid+'&run=loadcustomvars&lang='+lcjak_lang, true);

  // time in milliseconds
  xhrc.timeout = 3000; 

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

          // Set the name
          if (data.name) document.querySelector('input[name="start_name"]').value = data.name;

          // Set the email
          if (data.email) document.querySelector('input[name="start_email"]').value = data.email;

          // Set the chat message
          if (data.msg) document.querySelector('textarea[name="start_chat_msg"]').value = data.msg;

        }

      } else {

        // Something went wrong

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
  FD.append("customvars", lcjak_customvars);

  // Finally send the data
  xhrc.send(FD);

}

function lcjak_startChat() {

  if (working) return false;
  working = true;

  var loadbtn = document.getElementById("start_chat_btn");
  loadbtn.classList.remove("fa-paper-plane");
  loadbtn.classList.add("fa-spinner","fa-pulse");
  document.querySelector('.lcjak_input').classList.remove("error");

  // Let's get the current status of the local storage
  lcjak_chatstatus = localStorage.getItem('lcjak_chatstatus');

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
  xhrc.open('POST', base_url+'include/chatdata.php?id='+lcjakwidgetid+'&run=start&lang='+lcjak_lang, true);

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

          // We have a success we need to fire up the chat

          // Make sure we have the correct client
          localStorage.setItem('lcjak_customer', data.customer);

          // We also need to send the customer data to the other domain if
          if (cross_url && parent.postMessage) {
              parent.postMessage('customerdata::'+data.customer, cross_url);
          }

          // We reload the location
          window.location = data.gotochat;
          
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

  // Additional Form Data
  FD.append("chatstatus", lcjak_chatstatus);
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
    var repurl = "lc&sp=closed";
    
    if (base_rewrite == 1) {
      searchurl = "lc/open";
      if (localStorage.getItem('lcjak_chatstatus') == "big") {
        searchurl = "lc/big";
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