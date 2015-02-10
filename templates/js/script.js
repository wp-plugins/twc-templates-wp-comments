jQuery(document).ready(function(){
	jQuery("#comment_spam_3").val("");
	jQuery("#comment_spam_2").val("");
	jQuery(".shsh").each(function(){
		var ind=jQuery(this).attr("data-rel");
		var str=jQuery("#"+ind).parent("li").children("ul").html();
		var count=jQuery("#"+ind).parent("li").children("ul").children("li").length;
		if(str){
			jQuery(this).children("span").children("span").html(count);
		}else{
			jQuery(this).css("display","none");
		}
	});
	jQuery(".shsh").click(function(){
		var ind=jQuery(this).attr("data-rel");
		var str=jQuery(this).attr("data-close-btn-text");
		var str2=jQuery(this).html();
		var jj=str.indexOf("<span",0);
		if(jj>0){
			jQuery("#"+ind).parent("li").children("ul").slideUp(1000);
			jQuery(this).attr("data-close-btn-text",str2);
			jQuery(this).html(str);
		}else{
			jQuery("#"+ind).parent("li").children("ul").slideDown(1000);
			jQuery(this).attr("data-close-btn-text",str2);
			jQuery(this).html(str);
		}
		return false;
	});
});