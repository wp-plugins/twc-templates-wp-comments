jQuery(function($){
	$('.twc_upload_image').click(function(){
		var twc_send_attachment = wp.media.editor.send.attachment;
		var twc_button=$(this);
		wp.media.editor.send.attachment = function(props, attachment) {
			$(twc_button).attr('src', attachment.url);
			wp.media.editor.send.attachment=twc_send_attachment;
		}
		wp.media.editor.open(twc_button);
		return false;    
	});
	$('.twc_remove_image').click(function(){
		var twc_btn=$(this).parent().children("img");
		var twc_src=twc_btn.attr('data-src');
		twc_btn.attr('src', twc_src);
		twc_btn.val('');
		return false;
	});
});
jQuery(document).ready(function(){
	jQuery("#twc_sub_send").attr("alt","2eRrHs6D");
	jQuery("#twc_sub_send").click(function(){
		var twc_option_butt="null";
		var twc_option_butt_txt="";
		var twc_option_img_cite="null";
		var twc_option_show_cite=1;
		var twc_option_TXT_comm_h2=jQuery("#twc_option_TXT_comm_h2").val();
		var twc_option_TXT_comm_form_h2=jQuery("#twc_option_TXT_comm_form_h2").val();
		var twc_option_TXT_comm_no_comm=jQuery("#twc_option_TXT_comm_no_comm").val();
		var twc_option_TXT_comm_textarea=jQuery("#twc_option_TXT_comm_textarea").val();
		var twc_option_TXT_comm_input1=jQuery("#twc_option_TXT_comm_input1").val();
		var twc_option_TXT_comm_input2=jQuery("#twc_option_TXT_comm_input2").val();
		if(jQuery("#twc_sub1").attr("data-src")!=jQuery("#twc_sub1").attr("src")){twc_option_butt=jQuery("#twc_sub1").attr("src");}
		if(jQuery("#twc_sub2").val()!=""){twc_option_butt_txt=jQuery("#twc_sub2").val();}
		if(jQuery("#twc_sub3").attr("data-src")!=jQuery("#twc_sub3").attr("src")){twc_option_img_cite=jQuery("#twc_sub3").attr("src");}
		if(jQuery("#twc_sub4").attr("checked")){twc_option_show_cite=1;}else{twc_option_show_cite=0;}
		jQuery.ajax({
		  type: 'POST',
		  url: twc_Ajax.ajaxurl,
		  dataType: 'html',
		  data: {
			  'twc_option_butt':twc_option_butt,
			  'twc_option_butt_txt':twc_option_butt_txt,
			  'twc_option_img_cite':twc_option_img_cite,
			  'twc_option_show_cite':twc_option_show_cite,
			  'twc_option_TXT_comm_h2':twc_option_TXT_comm_h2,
			  'twc_option_TXT_comm_form_h2':twc_option_TXT_comm_form_h2,
			  'twc_option_TXT_comm_no_comm':twc_option_TXT_comm_no_comm,
			  'twc_option_TXT_comm_textarea':twc_option_TXT_comm_textarea,
			  'twc_option_TXT_comm_input1':twc_option_TXT_comm_input1,
			  'twc_option_TXT_comm_input2':twc_option_TXT_comm_input2,
			  'nonce': twc_Ajax.nonce,
			  'action':'twc_send_option_action',
			  'ok1':jQuery("#twc_sub_send").attr("alt")
		  },
		  success: function (data) {
			  var str=data;
			  if(str[0]=="1"){
				jQuery("#twc_mess").html(str.substr(1)).fadeIn(3).css("color","#47974C").delay(2000).fadeOut(300);
			  }else{
				  jQuery("#twc_mess").html(str).fadeIn(3).css("color","#C5153C").delay(2000).fadeOut(300);
			  }
		  },
		  error: function () {
			  jQuery("#twc_mess").html("Fatal error!").fadeIn(3).css("color","#C5153C").delay(2000).fadeOut(300);
		  }
		});
	});
});