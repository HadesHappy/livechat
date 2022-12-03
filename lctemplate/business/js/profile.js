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

/* modern browsers - will execute code once all elements are loaded onto the client */
window.addEventListener('DOMContentLoaded', (event) => {
    // content is loaded, load the value from storage
    lcjak_loadprofile(); // call the function to perform
});

// We send the value from the text area
document.getElementById("profile_save").addEventListener("click", function(event){
  event.preventDefault();
  lcjak_saveprofile();
});

function lcjak_loadprofile() {
   if (working) return false;
  working = true;

  // Get the session
  var lcjak_session = "0";
  if (localStorage.getItem('lcjak_session')) lcjak_session = localStorage.getItem('lcjak_session');
  
  // Get the customer
  var lcjak_customer = "0";
  if (localStorage.getItem('lcjak_customer')) lcjak_customer = localStorage.getItem('lcjak_customer');

  var xhrc = new XMLHttpRequest();

  // Bind the FormData object and the form element
  const lcjakform = document.getElementById("lcjak_ajaxform");
  const FD = new FormData(lcjakform);

  // Call the file to verify and start the process
  xhrc.open('POST', base_url+'include/chatdata.php?id='+lcjakwidgetid+'&run=loadprofile&lang='+lcjak_lang, true);

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

          // Set the avatar if we have any
          if (data.avatar) {

            let radios = document.getElementsByName("avatar");
            let value = data.avatar;//value you want to compare radio with

            for (let i = 0, length = radios.length; i < length; i++) {
              if (radios[i].value == value) {
                radios[i].checked = true;
                // only one radio can be logically checked, don't check the rest
                break;
              }
            }

          }

          // Set the name
          if (data.name) document.querySelector('input[name="name"]').value = data.name;

          // Set the email
          if (data.email) document.querySelector('input[name="email"]').value = data.email;

          // Set the phone
          if (data.phone) document.querySelector('input[name="phone"]').value = data.phone;

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
  FD.append("customer", lcjak_customer);

  // Finally send the data
  xhrc.send(FD);

}

function lcjak_saveprofile() {

  if (working) return false;
  working = true;

  // Replace the current url with the new one /prevent ajax request
  let old_url = window.location.href;

  var loadbtn = document.getElementById("profile_save");
  loadbtn.classList.remove("fa-save");
  loadbtn.classList.add("fa-spinner","fa-pulse");
  document.querySelector('.lcjak_input').classList.remove("error");

  // Get the session
  var lcjak_session = "0";
  if (localStorage.getItem('lcjak_session')) lcjak_session = localStorage.getItem('lcjak_session');
  
  // Get the customer
  var lcjak_customer = "0";
  if (localStorage.getItem('lcjak_customer')) lcjak_customer = localStorage.getItem('lcjak_customer');

  var xhrc = new XMLHttpRequest();

  // Bind the FormData object and the form element
  const lcjakform = document.getElementById("lcjak_ajaxform");
  const FD = new FormData(lcjakform);

  // Call the file to verify and start the process
  xhrc.open('POST', base_url+'include/chatdata.php?id='+lcjakwidgetid+'&run=changeprofile&lang='+lcjak_lang, true);

  // time in milliseconds
  xhrc.timeout = 3000; 

  // Some sort of an error, let's redo the send button
  xhrc.addEventListener( "error", function( event ) {
      loadbtn.classList.remove("fa-spinner","fa-pulse");
      loadbtn.classList.add("fa-save");
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

          // Finally update the customer storage
          if (localStorage.getItem('lcjak_customer')) localStorage.setItem('lcjak_customer', data.customer);

          // We also need to send the customer data to the other domain if
          if (cross_url && parent.postMessage) {
              parent.postMessage('customerdata::'+data.customer, cross_url);
          }

          // the new status
          var lstor = "open";
          // Let us check if we have rewrite enabled
          var searchurl = "lc&sp=profile";
          var repurl = "lc&sp=big";
          if (localStorage.getItem('lcjak_chatstatus') == "bigprofile") {
            searchurl = "lc&sp=bigprofile";
            repurl = "lc&sp=big";
            lstor = "big";
          }
          
          if (base_rewrite == 1) {
            searchurl = "lc/profile";
            repurl = "lc/open";
            if (localStorage.getItem('lcjak_chatstatus') == "bigprofile") {
              searchurl = "lc/bigprofile";
              repurl = "lc/big";
              lstor = "big";
            }
          }
          let new_url = old_url.replace(searchurl, repurl);
          if (debugme) console.log(new_url);

          // Set the local storage to closed
          localStorage.setItem('lcjak_chatstatus', lstor);

          // We also need to send the customer data to the other domain if
          if (cross_url && parent.postMessage) {
            parent.postMessage('chatstatus::'+lstor, cross_url);
          }

          // Redirect to close the chat
          window.location = new_url;
          return true;

        }

      } else {

          if (debugme) console.log(data.error);
          loadbtn.classList.remove("fa-spinner","fa-pulse");
          loadbtn.classList.add("fa-save");

          for (var k in data.error) {
              console.log(data.error[k]);
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
  FD.append("customer", lcjak_customer);

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
    if (localStorage.getItem('lcjak_chatstatus') == "bigprofile") {
      searchurl = "lc&sp=bigprofile";
    }
    var repurl = "lc&sp=closed";
    
    if (base_rewrite == 1) {
      searchurl = "lc/open";
      if (localStorage.getItem('lcjak_chatstatus') == "bigprofile") {
        searchurl = "lc/bigprofile";
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

  const element = document.querySelector('.lcb_max');
  element.classList.add('animate__animated', 'animate__'+anim);

  element.addEventListener('animationend', () => {

    // Set the local storage to bigprofile
    localStorage.setItem('lcjak_chatstatus', "bigprofile");

    // We also need to send the customer data to the other domain if
    if (cross_url && parent.postMessage) {
      parent.postMessage('chatstatus::bigprofile', cross_url);
    }

    // Replace the current url with the new one /prevent ajax request
    let old_url = window.location.href;

    // Let us check if we have rewrite enabled
    var searchurl = "lc&sp=profile";
    var repurl = "lc&sp=bigprofile";
    if (base_rewrite == 1) {
      searchurl = "lc/profile";
      repurl = "lc/bigprofile";
    }
    let new_url = old_url.replace(searchurl, repurl);
    if (debugme) console.log(new_url);

    // Redirect to big chat
    window.location = new_url;

  });

}

function lcjak_bigchatback(anim) {

  const element = document.querySelector('.lcb_max');
  element.classList.add('animate__animated', 'animate__'+anim);

  element.addEventListener('animationend', () => {

    // Set the local storage to big
    localStorage.setItem('lcjak_chatstatus', "big");

    // We also need to send the customer data to the other domain if
    if (cross_url && parent.postMessage) {
      parent.postMessage('chatstatus::big', cross_url);
    }

    // Replace the current url with the new one /prevent ajax request
    let old_url = window.location.href;

    // Let us check if we have rewrite enabled
    var searchurl = "lc&sp=bigprofile";
    var repurl = "lc&sp=big";
    if (base_rewrite == 1) {
      searchurl = "lc/bigprofile";
      repurl = "lc/big";
    }
    let new_url = old_url.replace(searchurl, repurl);
    if (debugme) console.log(new_url);

    // Redirect to big chat
    window.location = new_url;

  });

}

function lcjak_smallchat(anim) {

  const element = document.querySelector('.lcb_backtochat');
  element.classList.add('animate__animated', 'animate__'+anim);

  element.addEventListener('animationend', () => {

    // Replace the current url with the new one /prevent ajax request
    let old_url = window.location.href;

    // Let us check if we have rewrite enabled
    var searchurl = "lc&sp=profile";
    if (localStorage.getItem('lcjak_chatstatus') == "big") {
      searchurl = "lc&sp=bigprofile";
    }
    var repurl = "lc&sp=open";
    if (base_rewrite == 1) {
      searchurl = "lc/profile";
      if (localStorage.getItem('lcjak_chatstatus') == "big") {
        searchurl = "lc/bigprofile";
      }
      repurl = "lc/open";
    }
    let new_url = old_url.replace(searchurl, repurl);
    if (debugme) console.log(new_url);

    // Set the local storage to closed
    localStorage.setItem('lcjak_chatstatus', "open");

    // We also need to send the customer data to the other domain if
    if (cross_url && parent.postMessage) {
      parent.postMessage('chatstatus::open', cross_url);
    }

    // Redirect to big chat
    window.location = new_url;

  });

}