/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0                   # ||
|| # ----------------------------------------- # ||
|| # Copyright 2021 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

$(document).ready(function(){var buttonname=post_url='';var working=false;$('#cNotes').submit(function(e){e.preventDefault();if(working)return false;post_url=$(this).attr("action");working=true;buttonname=$('#formsubmit').html();$('#formsubmit').html('<i class="fa fa-spinner fa-spin"></i>');$('.form-group').removeClass("has-error");$('.form-group').removeClass("has-success");$.post(post_url,$(this).serialize(),function(msg){working=false;$('#formsubmit').html(buttonname);if(msg.status==1){$('label[for='+msg.label+']').closest(".form-group").addClass("has-success");if(msg.namechange)location.reload()}else if(msg.status==2){$.notify({icon:'fa fa-exclamation-triangle',message:msg.txt},{type:'danger',animate:{enter:'animated fadeInDown',exit:'animate__animated animate__fadeOutUp'}})}else{$.each(msg.errors,function(k,v){$('label[for='+k+']').closest(".form-group").addClass("has-error")})}},'json')})});