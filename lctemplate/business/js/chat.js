/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0.2                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2022 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

// Standard vars
var debugme = false;
var myDropzone = false;
var answerconv = "false";
var rllinput = rlsbint = livechat3_popup_window = null;
var utyping = "false";
var title = document.title;
var message = '';
var attSource = '';
var working = livetype = false;
var scrollchat = loadchat = muted = show_notifiy = true;
var jrc_lang = "en";
var ulastmsgid = 0;
var ulastmsg = "";
var opname = ""
var chat_container = document.getElementById("lc_messages");
var uploadbtn = document.getElementById("cUploadDrop");
// We listen for the enter key on the textarea
var msgfield = document.getElementById("lc_chat_msg");
lcjak_loadInput();
lcjak_sseJAK(5000);

// The status on the page
document.addEventListener('visibilitychange', handleVisibilityChange, false);

// We listen for the textarea input
msgfield.addEventListener("keyup", function(event) {
    event.preventDefault();
    if (event.keyCode === 13) {
      document.getElementById("lc_send_msg").click();
    }

    if (event.which != 13 && utyping == "false") {
      lcjak_userTyping();
    }

    if (msgfield.value.length == 0) {
      if (!msgfield.value.trim()) lcjak_userNotTyping();
    }
    // We call the live text preview
    if(event.keyCode != 13) lcjak_livepreview(msgfield.value.trim());
});

// We send the value from the text area
document.getElementById("lc_send_msg").addEventListener("click", function(event){
  event.preventDefault();
  // Finally we send the message
  lcjak_sendMSG(msgfield.value.trim());
});

// Emoticons
document.getElementById("emoticons").addEventListener("click", function() {
  // get the button bubble
  likeBox = document.getElementById("emoticons_btn");
        
        if (likeBox.style.display === '') {
          addCSS(likeBox);
        } else {
          removeCSS(likeBox);
        }
      
      });

function sendEmo(shortcode) {

  // get the button bubble
  likeBox = document.getElementById("emoticons_btn");

  // Add the text to the textarea
  var txta = document.getElementById("lc_chat_msg");
  txta.value += shortcode+' ';
  txta.focus();

  // remove the emoticons box
  removeCSS(likeBox);

}

function addCSS(lb) {
  lb.style.display = 'block';
  // add class
  if (lb.classList) {
    lb.classList.add("animate__fadeInUp");
  } else {
    lb.className = lb.className.replace(new RegExp('(^|\\b)' + className.split(' ').join('|') + '(\\b|$)', 'gi'), ' ');
  }
}

function removeCSS(lb) {
  // remove class
  if (lb.classList) {
    lb.classList.remove("animate__fadeInUp");
  } else {
    lb.className = lb.className.replace(new RegExp('(^|\\b)' + className.split(' ').join('|') + '(\\b|$)', 'gi'), ' ');
  }
  lb.style.display = '';
}

// Let's check if the browser or tab is not active
function handleVisibilityChange() {
  if (document.visibilityState == "hidden") {

    // Store the latest message ID
    sessionStorage.umsgid = ulastmsgid;
    // Close the message connection
    if (rlsbint) {
      clearInterval(rlsbint);
      rlsbint = null;
    }
  } else {

    // Bring back the latest message ID
    ulastmsgid = sessionStorage.umsgid;
    // Bring back the chat messages this time faster
    scrollchat = true;
    // lcjak_loadInput();
    lcjak_sseJAK(5000);
  }
}

function lcjak_sseJAK(timer) {

  lcjak_setChecker();
  if (!rlsbint) rlsbint = setInterval(function(){lcjak_setChecker()}, timer);   

}

function lcjak_sendMSG(msg) {

  if (working) return false;
  working = true;

  // Close the message connection
  if (rlsbint) {
    clearInterval(rlsbint);
    rlsbint = null;
  }

  var loadbtn = document.getElementById("lc_msg_load");
  loadbtn.classList.remove("fa-paper-plane");
  loadbtn.classList.add("fa-spinner","fa-pulse");
  msgfield.classList.remove("error");

  // Get the session
  var lcjak_session = "0";
  if (localStorage.getItem('lcjak_session')) lcjak_session = localStorage.getItem('lcjak_session');

  // Get the customer
  var lcjak_customer = "0";
  if (localStorage.getItem('lcjak_customer')) lcjak_customer = localStorage.getItem('lcjak_customer');

  var xhrc = new XMLHttpRequest();

  // Call the file to verify and start the process
  xhrc.open('POST', base_url+'include/chatdata.php?id='+lcjakwidgetid+'&run=sendmsg&lang='+lcjak_lang, true);

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

          loadchat = true;
          scrollchat = true;
          show_notifiy = true;
          if (data.html) {
            chat_container.insertAdjacentHTML('beforeend', data.html);
            msgfield.setAttribute('placeholder', data.placeholder);
            ulastmsgid = data.lastid;
            ulastmsg = data.lastmsg;
            lcjak_scrollBottom();
          }
          msgfield.value = "";
          answerconv = "false";
          utyping = "false";
          lcjak_sseJAK(5000);

          loadbtn.classList.remove("fa-spinner","fa-pulse");
          loadbtn.classList.add("fa-paper-plane");

        }

      } else {
          if (debugme) console.log(data.error);
          loadbtn.classList.remove("fa-spinner","fa-pulse");
          loadbtn.classList.add("fa-paper-plane");
          msgfield.value = "";
          msgfield.classList.add("error");
          msgfield.setAttribute('placeholder', data.error);
      }

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

  // Attach Data
  var formData = new FormData();
  formData.append("msg", msg);
  formData.append("lastmsg", ulastmsg);
  formData.append("lang", jrc_lang);
  formData.append("rlbid", lcjak_session);
  formData.append("customer", lcjak_customer);

  // Finally send the data
  xhrc.send(formData);

}

function lcjak_loadInput() {
  loadchat = true;
  lcjak_getInput();
}

function lcjak_getInput() {

  if (loadchat) {

    // Let's get the current status of the local storage
    lcjak_chatstatus = localStorage.getItem('lcjak_chatstatus');

    // Get the session
    var lcjak_session = "0";
    if (localStorage.getItem('lcjak_session')) lcjak_session = localStorage.getItem('lcjak_session');

    // Get the customer
    var lcjak_customer = "0";
    if (localStorage.getItem('lcjak_customer')) lcjak_customer = localStorage.getItem('lcjak_customer');

    var xhrc = new XMLHttpRequest();

    // Call the file to verify and start the process
    xhrc.open('POST', base_url+'include/chatdata.php?id='+lcjakwidgetid+'&run=getmsg&lang='+lcjak_lang, true);

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

            if (data.redirecturl) {
              if (parent.postMessage) {
                    parent.postMessage('redirecturl::'+data.redirecturl, cross_url);
                }
            }

            // Same user, same minute just add it after
            chat_container.insertAdjacentHTML('beforeend', data.html);
            ulastmsgid = data.lastid;

            if (scrollchat) {
              lcjak_scrollBottom();
            }
            
            // Stop loading new answer until we need to do it again
            loadchat = false;

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
    formData.append("lang", jrc_lang);
    formData.append("lastid", ulastmsgid);
    formData.append("chatstatus", lcjak_chatstatus);
    formData.append("rlbid", lcjak_session);
    formData.append("customer", lcjak_customer);

    // Finally send the data
    xhrc.send(formData);

  }
}

function lcjak_userTyping() {

  utyping = "true";

  // Get the session
  var lcjak_session = "0";
  if (localStorage.getItem('lcjak_session')) lcjak_session = localStorage.getItem('lcjak_session');

  // Get the customer
  var lcjak_customer = "0";
  if (localStorage.getItem('lcjak_customer')) lcjak_customer = localStorage.getItem('lcjak_customer');

  var xhrc = new XMLHttpRequest();

  // Call the file to verify and start the process
  xhrc.open('POST', base_url+'include/chatdata.php?id='+lcjakwidgetid+'&run=typing&lang='+lcjak_lang, true);

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
  formData.append("typestatus", 1);
  formData.append("rlbid", lcjak_session);
  formData.append("customer", lcjak_customer);

  // Finally send the data
  xhrc.send(formData);
  
}

function lcjak_userNotTyping() {

  utyping = "false";

  // Get the session
  var lcjak_session = "0";
  if (localStorage.getItem('lcjak_session')) lcjak_session = localStorage.getItem('lcjak_session');

  // Get the customer
  var lcjak_customer = "0";
  if (localStorage.getItem('lcjak_customer')) lcjak_customer = localStorage.getItem('lcjak_customer');

  var xhrc = new XMLHttpRequest();

  // Call the file to verify and start the process
  xhrc.open('POST', base_url+'include/chatdata.php?id='+lcjakwidgetid+'&run=typing&lang='+lcjak_lang, true);

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
  formData.append("typestatus", 0);
  formData.append("rlbid", lcjak_session);
  formData.append("customer", lcjak_customer);

  // Finally send the data
  xhrc.send(formData);

}

function lcjak_livepreview(message) {

  if (livetype) return false;
  
  livetype = true;

  // Get the session
  var lcjak_session = "0";
  if (localStorage.getItem('lcjak_session')) lcjak_session = localStorage.getItem('lcjak_session');

  // Get the customer
  var lcjak_customer = "0";
  if (localStorage.getItem('lcjak_customer')) lcjak_customer = localStorage.getItem('lcjak_customer');

  var xhrc = new XMLHttpRequest();

  // Call the file to verify and start the process
  xhrc.open('POST', base_url+'include/chatdata.php?id='+lcjakwidgetid+'&run=livetyping&lang='+lcjak_lang, true);

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

          livetype = false;
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
  formData.append("msg", message);
  formData.append("rlbid", lcjak_session);
  formData.append("customer", lcjak_customer);

  // Finally send the data
  xhrc.send(formData);

}

function lcjak_setChecker() {

  // Let's get the current status of the local storage
  lcjak_chatstatus = localStorage.getItem('lcjak_chatstatus');

  // Get the session
  var lcjak_session = "0";
  if (localStorage.getItem('lcjak_session')) lcjak_session = localStorage.getItem('lcjak_session');

  // Get the customer
  var lcjak_customer = "0";
  if (localStorage.getItem('lcjak_customer')) lcjak_customer = localStorage.getItem('lcjak_customer');

  // Get the answid
  var lcjak_answid = "0";
  if (localStorage.getItem('lcjak_answid')) lcjak_answid = localStorage.getItem('lcjak_answid');

  var xhrc = new XMLHttpRequest();

  // Call the file to verify and start the process
  xhrc.open('POST', base_url+'include/chatdata.php?id='+lcjakwidgetid+'&run=chatupdate&lang='+lcjak_lang, true);

  // time in milliseconds
  xhrc.timeout = 5000; 

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

          lcjak_handlemsg(data);
          return true;
        }

      } else {
        
        if (debugme) console.log(data.error);

        // Something went terrible wrong, reset data
        if (data.action == "notfound") {
          if (localStorage.getItem('lcjak_customer')) localStorage.removeItem('lcjak_customer');

          // We also need to send the customer data to the other domain if
          if (cross_url && parent.postMessage) {
            parent.postMessage('removedata::0', cross_url);
          }

          window.location = data.url;
        }
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
  formData.append("lang", jrc_lang);
  formData.append("chatstatus", lcjak_chatstatus);
  formData.append("rlbid", lcjak_session);
  formData.append("customer", lcjak_customer);
  formData.append("answid", lcjak_answid);
  if (opname) formData.append("opname", opname);

  // Finally send the data
  xhrc.send(formData);
  
}

function lcjak_handlemsg(data) {
  
  if (data.redirect_c) {
    // Close the message requests
    if (rlsbint) {
      clearInterval(rlsbint);
      rlsbint = null;
    }
    if (data.action == "contactform") {

      // Set the local storage to the contactform
      localStorage.setItem('lcjak_chatstatus', "contactform");

      // We also need to send the customer data to the other domain if
      if (cross_url && parent.postMessage) {
        parent.postMessage('chatstatus::contactform', cross_url);
      }

      // Remove the local storage
      if (localStorage.getItem('lcjak_customer')) localStorage.removeItem('lcjak_customer');

      // We also need to send the customer data to the other domain if
      if (cross_url && parent.postMessage) {
        parent.postMessage('removedata::0', cross_url);
      }

      // Redirect to close the chat
      window.location = data.contacturl;

    } else {
      // We reload the location
      location.reload();
    }

  // Redirect for custom contact url
  } else if (data.redirect_cu) {

    if (data.redirect_cu) {
      if (parent.postMessage) {
          parent.postMessage('redirecturl::'+data.redirect_cu, cross_url);
          return true;
      }
    }

  } else {

    // We have an operator, let's show it.
    if (data.operator) {
      if (opname != data.operator) {
        document.getElementById('jaklcb_oname').innerHTML = data.operator;
        document.getElementById('jaklcb_oabout').innerHTML = data.aboutme;
        document.getElementById('jaklcb_oimage').innerHTML = '<img src="'+data.avaimg+'" class="jaklcb_popup_avatar" width="65" alt="'+data.operator+'">';
      }
      // set the current operator name
      opname = data.operator;
      if (localStorage.getItem('lcjak_answid')) localStorage.removeItem('lcjak_answid');
    }
    
    if (data.knockknock) {
      if (data.pushnotify == 1 && Notification.permission==='granted') {
        lcjak_dNotifyNew(title, data.knockknock);
      } else {
        alert(data.knockknock);
      }
      if (muted) lcjak_playSound(ls_sound+'.webm', ls_sound+'.mp3');
    }
    
    // We have a new message
    if (data.newmsg == 1) {
      
      scrollchat = true;
      lcjak_loadInput();
      
      if (answerconv == "false") {
        if (data.pushnotify == 1) lcjak_dNotifyNew(title, data.newmsgtxt);
        if (muted) lcjak_playSound(ls_sound+'.webm', ls_sound+'.mp3');
      }

      // Set the local storage to closed
      if (data.answid) localStorage.setItem('lcjak_answid', JSON.stringify(data.answid));
    
    }
    
    // Client can upload files
    if (data.files == 1) {

      // Add a class so it is obvious
      uploadbtn.classList.add("active", "animate__rotateIn");

      // Activate the upload field
      if (!myDropzone) {
        Dropzone.autoDiscover = false;
        myDropzone = new Dropzone("i#cUploadDrop", { url: base_url+"uploader/uploader.php", acceptedFiles: document.getElementById("allowedFiles").value, dictDefaultMessage: ""});
        myDropzone.on("sending", function(file, xhr, formData) {
            // Will send the filesize along with the file as POST data.
            formData.append("customer", localStorage.getItem('lcjak_customer'));
            formData.append("base_url", base_url);
            formData.append("lang", lcjak_lang);
        });
          myDropzone.on("complete", function(file) {
            myDropzone.removeAllFiles();
            loadchat = true;
            scrollchat = true;
            lcjak_getInput();
        });
      }
    
    } else if (data.files == 0) {
      
      // Destroy dropzone
      if (myDropzone) {
        myDropzone.destroy();
        myDropzone = false;
      }

      // Remove class so it is obvious
      uploadbtn.classList.remove("active", "animate__rotateIn");
      
    }
    
    // We have someone typing
    if (data.typing != 0) {
      document.getElementById("lc_typing").style.display = "block";
    } else {
      document.getElementById("lc_typing").style.display = "none";
    }

    // We have a message to delete
    if (data.delmsg != 0) {
      var delm = document.getElementById("postid_"+data.delmsg);
      if (delm.style.display === "none") {
        delm.style.display = "block";
      } else {
        delm.style.display = "none";
      }
    }

    // We have a message to edit
    if (data.msgedit != 0) {
      document.getElementById("msg"+data.msgedit).innerHTML = data.editmsg;
      document.getElementById("edited_"+data.msgedit).innerHTML = data.showedit
    }

    // We have a name change, we need to make a reload
    if (data.datac == 1) {
      // Make sure we have the correct client
      if (data.customer) localStorage.setItem('lcjak_customer', data.customer);
      location.reload();
    }

    if (data.softended == 1) {
      clearInterval(rlsbint);
      rlsbint = null;
    }
  
  }

}

// Toggle Sound Alert
function lcjak_soundOff() {
  if(muted) {
    $('#soundoff').html('<i class="fa fa-volume-off"></i>');
    muted = 0;
  } else {
    $('#soundoff').html('<i class="fa fa-volume-up"></i>');
    muted = 1;
  }
  return true;
}

function lcjak_scrollBottom() {

  chat_container.scrollTop = chat_container.scrollHeight;

  // set scrollchat to false
  scrollchat = false;
}

function lcjak_playSound(soundfile, soundfile2) {

  var sound = new Howl({
    src: [base_url+soundfile2, base_url+soundfile]
  });
  
  sound.play();
  
  answerconv = "true";
}

function lcjak_dNotifyNew(title, msg) {
  // Let's check if the browser supports notifications
  if (!("Notification" in window)) {
    console.log("This browser does not support notifications.");
  }

  // Let's check whether notification permissions have alredy been granted
  else if (Notification.permission === "granted") {
    // If it's okay let's create a notification
    var notification = new Notification(title, {icon: base_url + "img/logo.png"}, {body: msg});
  }

  // Otherwise, we need to ask the user for permission
  else if (location.protocol === 'https:' && (Notification.permission !== 'denied' || Notification.permission === "default")) {
    Notification.requestPermission(function (permission) {
      // If the user accepts, let's create a notification
      if (permission === "granted") {
        var notification = new Notification(title, {icon: base_url + "img/logo.png"}, {body: msg});
      }
    });
  }

  show_notifiy = false;
  return true;

  // At last, if the user has denied notifications, and you
  // want to be respectful there is no need to bother them any more.
}

function getHiddenProp(){
    var prefixes = ['webkit','moz','ms','o'];
    
    // if 'hidden' is natively supported just return it
    if ('hidden' in document) return 'hidden';
    
    // otherwise loop over all the known prefixes until we find one
    for (var i = 0; i < prefixes.length; i++){
        if ((prefixes[i] + 'Hidden') in document) 
            return prefixes[i] + 'Hidden';
    }

    // otherwise it's not supported
    return null;
}

function isHidden() {
    var prop = getHiddenProp();
    if (!prop) return false;
    
    return document[prop];
}

function lcjak_profile(anim) {

  const element = document.querySelector('.lcb_profile');
  element.classList.add('animate__animated', 'animate__fadeOut');

  element.addEventListener('animationend', () => {

    // Replace the current url with the new one /prevent ajax request
    let old_url = window.location.href;

    // Let us check if we have rewrite enabled
    var searchurl = "lc&sp=open";
    if (localStorage.getItem('lcjak_chatstatus') == "big") {
      searchurl = "lc&sp=big";
    }
    var repurl = "lc&sp=profile";
    
    if (base_rewrite == 1) {
      searchurl = "lc/open";
      if (localStorage.getItem('lcjak_chatstatus') == "big") {
        searchurl = "lc/big";
      }
      repurl = "lc/profile";
    }
    let new_url = old_url.replace(searchurl, repurl);
    if (debugme) console.log(new_url);

    // Set the local storage to closed
    localStorage.setItem('lcjak_chatstatus', "profile");

    // We also need to send the customer data to the other domain if
    if (cross_url && parent.postMessage) {
      parent.postMessage('chatstatus::profile', cross_url);
    }

    // Redirect to close the chat
    window.location = new_url;

  });

}

function lcjak_profilebig(anim) {

  const element = document.querySelector('.lcb_profile');
  element.classList.add('animate__animated', 'animate__fadeOut');

  element.addEventListener('animationend', () => {

    // Replace the current url with the new one /prevent ajax request
    let old_url = window.location.href;

    // Let us check if we have rewrite enabled
    var searchurl = "lc&sp=big";
    var repurl = "lc&sp=bigprofile";
    
    if (base_rewrite == 1) {
      searchurl = "lc/big";
      repurl = "lc/bigprofile";
    }
    let new_url = old_url.replace(searchurl, repurl);
    if (debugme) console.log(new_url);

    // Set the local storage to closed
    localStorage.setItem('lcjak_chatstatus', "bigprofile");

    // We also need to send the customer data to the other domain if
    if (cross_url && parent.postMessage) {
      parent.postMessage('chatstatus::bigprofile', cross_url);
    }

    // Redirect to close the chat
    window.location = new_url;
    
  });

}

function lcjak_endchat(anim) {

  if (working) return false;
  working = true;

  // Replace the current url with the new one /prevent ajax request
  let old_url = window.location.href;

  const element = document.querySelector('.lcb_end');
  element.classList.add('animate__animated', 'animate__hinge');

  element.addEventListener('animationend', () => {

    // Get the session
    var lcjak_session = "0";
    if (localStorage.getItem('lcjak_session')) lcjak_session = localStorage.getItem('lcjak_session');

    // Get the customer
    var lcjak_customer = "0";
    if (localStorage.getItem('lcjak_customer')) lcjak_customer = localStorage.getItem('lcjak_customer');

    // We set the chat to open
  var xhrc = new XMLHttpRequest();
  xhrc.open('POST', base_url+'include/chatcontrol.php?id='+lcjakwidgetid+'&run=stopchat&lang='+lcjak_lang, true);

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

          if (data.feedbackform == "yes") {

            // Let us check if we have rewrite enabled
            var searchurl = "lc&sp=open";
            var repurl = "lc&sp=feedback";
            
            if (base_rewrite == 1) {
              searchurl = "lc/open";
              repurl = "lc/feedback";
            }
            let new_url = old_url.replace(searchurl, repurl);
            if (debugme) console.log(new_url);

            // Set the local storage to closed
            localStorage.setItem('lcjak_chatstatus', "feedback");

            // We also need to send the customer data to the other domain if
            if (cross_url && parent.postMessage) {
              parent.postMessage('chatstatus::feedback', cross_url);
            }

            // Redirect to close the chat
            window.location = new_url;

          } else {

            // Finally remove the customer storage
            if (localStorage.getItem('lcjak_customer')) localStorage.removeItem('lcjak_customer');

            // We also need to send the customer data to the other domain if
            if (cross_url && parent.postMessage) {
              parent.postMessage('removedata::0', cross_url);
            }

            // Let us check if we have rewrite enabled
            var searchurl = "lc&sp=open";
            var repurl = "lc&sp=closed";
            
            if (base_rewrite == 1) {
              searchurl = "lc/open";
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

          }

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

  // Finally send the data
  xhrc.send(formData);
  });

}

function lcjak_endchatbig(anim) {

  if (working) return false;
  working = true;

  // Replace the current url with the new one /prevent ajax request
  let old_url = window.location.href;

  const element = document.querySelector('.lcb_end');
  element.classList.add('animate__animated', 'animate__hinge');

  element.addEventListener('animationend', () => {

    // Get the session
    var lcjak_session = "0";
    if (localStorage.getItem('lcjak_session')) lcjak_session = localStorage.getItem('lcjak_session');

    // Get the customer
    var lcjak_customer = "0";
    if (localStorage.getItem('lcjak_customer')) lcjak_customer = localStorage.getItem('lcjak_customer');

    // We set the chat to open
  var xhrc = new XMLHttpRequest();
  xhrc.open('POST', base_url+'include/chatcontrol.php?id='+lcjakwidgetid+'&run=stopchat&lang='+lcjak_lang, true);

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

          if (data.feedbackform == "yes") {

            // Let us check if we have rewrite enabled
            var searchurl = "lc&sp=big";
            var repurl = "lc&sp=bigfeedback";
            
            if (base_rewrite == 1) {
              searchurl = "lc/big";
              repurl = "lc/bigfeedback";
            }
            let new_url = old_url.replace(searchurl, repurl);
            if (debugme) console.log(new_url);

            // Set the local storage to closed
            localStorage.setItem('lcjak_chatstatus', "bigfeedback");

            // We also need to send the customer data to the other domain if
            if (cross_url && parent.postMessage) {
              parent.postMessage('chatstatus::bigfeedback', cross_url);
            }

            // Redirect to close the chat
            window.location = new_url;

          } else {

            // Finally remove the customer storage
            if (localStorage.getItem('lcjak_customer')) localStorage.removeItem('lcjak_customer');

            // We also need to send the customer data to the other domain if
            if (cross_url && parent.postMessage) {
              parent.postMessage('removedata::0', cross_url);
            }

            // Let us check if we have rewrite enabled
            var searchurl = "lc&sp=big";
            var repurl = "lc&sp=closed";
            
            if (base_rewrite == 1) {
              searchurl = "lc/big";
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

          }

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

  // Finally send the data
  xhrc.send(formData);
  });

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