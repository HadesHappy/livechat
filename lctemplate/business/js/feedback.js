/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0                   # ||
|| # ----------------------------------------- # ||
|| # Copyright 2022 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

// Standard vars
var debugme = working = false;
var loadbtn = document.getElementById("end_save");

/* modern browsers - will execute code once all elements are loaded onto the client */
window.addEventListener('DOMContentLoaded', (event) => {
    // content is loaded, load the value from storage
    lcjak_loadprofile(); // call the function to perform
});

// We send the value from the text area
loadbtn.addEventListener("click", function(event){
  event.preventDefault();
  lcjak_endchat();
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

function lcjak_endchat() {

  if (working) return false;
  working = true;

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
  xhrc.open('POST', base_url+'include/chatdata.php?id='+lcjakwidgetid+'&run=sendfeedback&lang='+lcjak_lang, true);

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

          // Finally remove the customer storage
          if (localStorage.getItem('lcjak_customer')) localStorage.removeItem('lcjak_customer');

          // We also need to send the customer data to the other domain if
          if (cross_url && parent.postMessage) {
            parent.postMessage('removedata::0', cross_url);
          }

          var lcform = document.getElementById('lcjak_formfields');

          lcform.innerHTML = '';

          lcform.insertAdjacentHTML('beforeend', data.successdiv);

          loadbtn.classList.remove("fa-spinner","fa-pulse");
          loadbtn.classList.add("fa-paper-plane");

          var sfb = document.getElementById('send_f_button').style;
          sfb.opacity = 1;
          (function fade(){(sfb.opacity-=.1)<0?sfb.display="none":setTimeout(fade,40)})();

          var bfb = document.getElementById('back_f_button').style;
          bfb.opacity = 1;
          (function fade(){(bfb.opacity-=.1)<0?bfb.display="none":setTimeout(fade,40)})();

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

function lcjak_closechat(anim, fstatus, tstatus) {

  const element = document.querySelector('.lcb_close');
  element.classList.add('animate__animated', 'animate__'+anim);

  element.addEventListener('animationend', () => {

    // Replace the current url with the new one /prevent ajax request
    let old_url = window.location.href;

    // Let us check if we have rewrite enabled
    var searchurl = "lc&sp="+fstatus;
    var repurl = "lc&sp="+tstatus;
    
    if (base_rewrite == 1) {
      searchurl = "lc/"+fstatus;
      repurl = "lc/"+tstatus;
    }
    let new_url = old_url.replace(searchurl, repurl);
    if (debugme) console.log(new_url);

    // Set the local storage to closed
    localStorage.setItem('lcjak_chatstatus', tstatus);

    // We also need to send the customer data to the other domain if
    if (cross_url && parent.postMessage) {
      parent.postMessage('chatstatus::'+tstatus, cross_url);
    }

    // Redirect to close the chat
    window.location = new_url;

  });

}

function lcjak_windowchange(anim, fstatus, tstatus) {

  const element = document.querySelector('.lcb_change');
  element.classList.add('animate__animated', 'animate__'+anim);

  element.addEventListener('animationend', () => {

    // Replace the current url with the new one /prevent ajax request
    let old_url = window.location.href;

    // Let us check if we have rewrite enabled
    var searchurl = "lc&sp="+fstatus;
    var repurl = "lc&sp="+tstatus;
    
    if (base_rewrite == 1) {
      searchurl = "lc/"+fstatus;
      repurl = "lc/"+tstatus;
    }
    let new_url = old_url.replace(searchurl, repurl);
    if (debugme) console.log(new_url);

    // Set the local storage to closed
    localStorage.setItem('lcjak_chatstatus', tstatus);

    // We also need to send the customer data to the other domain if
    if (cross_url && parent.postMessage) {
      parent.postMessage('chatstatus::'+tstatus, cross_url);
    }

    // Redirect to close the chat
    window.location = new_url;

  });

}

function lcjak_backtochat(anim, fstatus, tstatus) {

  // Replace the current url with the new one /prevent ajax request
  let old_url = window.location.href;

  const element = document.querySelector('.lcb_back');
  element.classList.add('animate__animated', 'animate__'+anim);

  element.addEventListener('animationend', () => {

    // Get the session
    var lcjak_session = "0";
    if (localStorage.getItem('lcjak_session')) lcjak_session = localStorage.getItem('lcjak_session');

    // Get the customer
    var lcjak_customer = "0";
    if (localStorage.getItem('lcjak_customer')) lcjak_customer = localStorage.getItem('lcjak_customer');

    // We set the chat to open
  var xhrc = new XMLHttpRequest();
  xhrc.open('POST', base_url+'include/chatcontrol.php?id='+lcjakwidgetid+'&run=backtochat&lang='+lcjak_lang, true);

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

          // Let us check if we have rewrite enabled
          var searchurl = "lc&sp="+fstatus;
          var repurl = "lc&sp="+tstatus;
          
          if (base_rewrite == 1) {
            searchurl = "lc/"+fstatus;
            repurl = "lc/"+tstatus;
          }
          let new_url = old_url.replace(searchurl, repurl);
          if (debugme) console.log(new_url);

          // Set the local storage to closed
          localStorage.setItem('lcjak_chatstatus', tstatus);

          // We also need to send the customer data to the other domain if
          if (cross_url && parent.postMessage) {
            parent.postMessage('chatstatus::'+tstatus, cross_url);
          }

          // Redirect to close the chat
          window.location = new_url;
          return true;
        }

      } else {
          if (debugme) console.log(data.error);
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
  formData.append("rlbid", lcjak_session);
  formData.append("customer", lcjak_customer);

  xhrc.send(formData);
  });

}