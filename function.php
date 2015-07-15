<?php
//session work
function twc_startSession() {
	if ( session_id() ) return true;
	else return session_start();
}
function twc_destroySession() {
	if ( session_id() ) {
		setcookie(session_name(), session_id(), time()-60*60*24);
		session_unset();
		session_destroy();
	}
}
//style & script add
function twc_stylesheet(){
	$twc_option_butt=get_option('twc_option_butt');
	$twc_option_img_cite=get_option('twc_option_img_cite');
	$twc_option_show_cite=(int)get_option('twc_option_show_cite');
	$twc_urlDef2=plugins_url('templates/img/str-1.png',__FILE__);
	$twc_style_line="";
	twc_enqueue_comment_reply();
	if( is_singular() && comments_open()){
		wp_enqueue_style("twc_style1_templ",plugins_url('templates/css/style1.css',__FILE__),"0.3",true);
		wp_enqueue_script("twc_script_templ",plugins_url('templates/js/script.js',__FILE__),array('jquery'),"0.3",true);

		if(!empty($twc_option_butt) && $twc_option_butt!="null"){
			$twc_size=getimagesize($twc_option_butt);
			if($twc_size){
				$twc_style_line.="#coommentStyle #respond #twc_submit_btn{width:$twc_size[0]px; height:$twc_size[1]px; background-image:url('$twc_option_butt');}";
			}
		}
		if(!empty($twc_option_img_cite) && $twc_option_img_cite!="null"){
			$twc_style_line.="#coommentStyle .imgComm{background-image:url('$twc_option_img_cite');}";
		}
		if($twc_option_show_cite!=1){
			$twc_style_line.="body #coommentStyle #ccom ul.children{display:block;}";
			$twc_style_line.="#coommentStyle a.shsh{display:none;}";
		}
		wp_add_inline_style('twc_style1_templ',$twc_style_line);
	}
}
//add comment template
function twc_my_plugin_comment_template( $comment_template ) {
	return dirname (__FILE__) ."/templates/comments1.php";
}
//add code comment reply
function twc_enqueue_comment_reply(){
	if( is_singular() && comments_open() && (get_option('thread_comments') == 1) ) 
		wp_enqueue_script('comment-reply');
}
//antispam function
function twc_onSendDataComm($commentdata) {
  /*$spam_test_field = trim($_POST['comment']);
  if(!empty($spam_test_field)) wp_die('You send spam comment');
  $comment_content = trim($_POST['comment2']);
  $_POST['comment'] = $comment_content;*/
  $res=(int)$_POST['comment_spam_4']-(int)$_POST['comment_spam_3']+(int)$_POST['comment_spam_1'];
  $res2=(int)twc_const_antispam2-(int)twc_const_antispam3+(int)twc_const_antispam1;
  //$res2=$res;
  if(($_POST['comment_spam_3']=="") && ($_POST['comment_spam_2']=="") && ($_POST['comment_spam_1']!="") && ($_POST['comment_spam_4']!="")){
	  return $commentdata;
  }else{
	  wp_die('You send spam comment');
  }
}
function twc_antispam_echo(){
	$str='<input type="hidden" id="comment_spam_1" name="comment_spam_1" value="'.twc_const_antispam1.'"/>';
	$str.='<input type="hidden" id="comment_spam_2" name="comment_spam_2" value="'.twc_const_antispam2.'"/>';
	$str.='<input type="hidden" id="comment_spam_3" name="comment_spam_3" value="'.twc_const_antispam3.'"/>';
	$str.='<input type="hidden" id="comment_spam_4" name="comment_spam_4" value="0"/>';
	return $str;
}

// error for send comment function 1
function twc_get_die_handler(){
	return 'twc_custom_die_handler';
}
// error for send comment function 2
function twc_custom_die_handler($message, $title='', $args=array()) {
	global $session;
	if(!empty($_POST['comment_send_info'])){
		$url=$_SERVER['HTTP_REFERER'];
		if($urlI=strpos($_SERVER['HTTP_REFERER'], "?")){$url=substr($url,0,$urlI-1);}
		twc_startSession();
		$_SESSION['messErrComm']=$message;
		header("location:".$url."?error=1#respond");
		exit;
	}else{
		_default_wp_die_handler($message, $title, $args);
	}
}
//start work plagin in adminbar & other after load
function twc_start_work(){
	load_plugin_textdomain( twc_lang, false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	
	if(current_user_can('manage_options')){
		add_action('admin_menu', 'twc_CreatePageMenu');
	}
	
	if(isset($_GET['page']) && $_GET['page']=='TWC_Option_Ident'){
		add_action( 'admin_enqueue_scripts', 'twc_plugin_admin_scripts' );
		add_action('admin_head', 'twc_stylesheet_adm');
	}
	
	add_action('wp_ajax_twc_send_option_action', 'twc_send_option_action_callback');
	
}
/*----------code-for-create-admin-page-------------------*/
function twc_CreatePageMenu(){
	if (function_exists('add_options_page')){
        add_options_page('TWC-Templates-WP-Comments', 'TWC Option', 'manage_options', 'TWC_Option_Ident', 'twc_PageOptionFucntion');
    }
}
function twc_PageOptionFucntion(){
	$twc_option_butt=get_option('twc_option_butt');
	$twc_option_butt_txt=get_option('twc_option_butt_txt');
	$twc_option_img_cite=get_option('twc_option_img_cite');
	$twc_option_TXT_comm_h2=get_option('twc_option_TXT_comm_h2');
	$twc_option_TXT_comm_form_h2=get_option('twc_option_TXT_comm_form_h2');
	$twc_option_TXT_comm_no_comm=get_option('twc_option_TXT_comm_no_comm');
	$twc_option_TXT_comm_textarea=get_option('twc_option_TXT_comm_textarea');
	$twc_option_TXT_comm_input1=get_option('twc_option_TXT_comm_input1');
	$twc_option_TXT_comm_input2=get_option('twc_option_TXT_comm_input2');
	$twc_option_show_cite=(int)get_option('twc_option_show_cite');
	$twc_urlDef=plugins_url('img/add-img.png',__FILE__);
	$twc_urlDef2=plugins_url('templates/img/str-1.png',__FILE__);
	$twc_str="";
	echo "<div id='twc_form_style'>";
	echo "<h1>TWC - ".__("Template for WordPress Comment",twc_lang)."</h1>";
	echo '<p>'.__("Settings plugin for creating beautiful tree comments!",twc_lang).'</p>';
	echo '<p>'.__("Call the plugin is through standard function wordpress: comments_template()",twc_lang).'</p>';
	if(empty($twc_option_butt) || $twc_option_butt=="null"){$twc_option_butt=$twc_urlDef;}
	echo '<p>'.__("Button to send the comment",twc_lang).':</p>';
	echo '<div class="twc_butt1"><img id="twc_sub1" class="twc_upload_image" data-src="'.$twc_urlDef. '" src="'.$twc_option_butt.'" /> <button type="submit" class="twc_remove_image">&times;</button></div>';
	echo '<p>'.__("The text for button to send the comment",twc_lang).':</p><div class="twc_butt1"><input id="twc_sub2" type="text" value="'.$twc_option_butt_txt.'" placeholder="default"/></div>';
	echo '<p>'.__("The text for &laquo;Comments&raquo;",twc_lang).':</p><div class="twc_butt1"><input id="twc_option_TXT_comm_h2" type="text" value="'.$twc_option_TXT_comm_h2.'" placeholder="default"/></div>';
	echo '<p>'.__("The text for &laquo;Add new comment&raquo;",twc_lang).':</p><div class="twc_butt1"><input id="twc_option_TXT_comm_form_h2" type="text" value="'.$twc_option_TXT_comm_form_h2.'" placeholder="default"/></div>';
	echo '<p>'.__("The text for &laquo;No comments yet!&raquo;",twc_lang).':</p><div class="twc_butt1"><input id="twc_option_TXT_comm_no_comm" type="text" value="'.$twc_option_TXT_comm_no_comm.'" placeholder="default"/></div>';
	echo '<p>'.__("The text for &laquo;Enter your message&raquo;",twc_lang).':</p><div class="twc_butt1"><input id="twc_option_TXT_comm_textarea" type="text" value="'.$twc_option_TXT_comm_textarea.'" placeholder="default"/></div>';
	echo '<p>'.__("The text for &laquo;Your name&raquo;",twc_lang).':</p><div class="twc_butt1"><input id="twc_option_TXT_comm_input1" type="text" value="'.$twc_option_TXT_comm_input1.'" placeholder="default"/></div>';
	echo '<p>'.__("The text for &laquo;Your e-mail&raquo;",twc_lang).':</p><div class="twc_butt1"><input id="twc_option_TXT_comm_input2" type="text" value="'.$twc_option_TXT_comm_input2.'" placeholder="default"/></div>';
	if(empty($twc_option_img_cite) || $twc_option_img_cite=="null"){$twc_option_img_cite=$twc_urlDef2;}
	echo '<p>'.__("Image for the tree comments",twc_lang).' (50х50px):</p>';
	echo '<div class="twc_butt1"><img id="twc_sub3" class="twc_upload_image" data-src="'.$twc_urlDef2. '" src="'.$twc_option_img_cite.'" /> <button type="submit" class="twc_remove_image">&times;</button></div>';
	if($twc_option_show_cite==1){$twc_str=" checked";}
	echo '<p>'.__("Hide comments in the unfolding unit",twc_lang).':&nbsp;&nbsp;<input id="twc_sub4" type="checkbox"'.$twc_str.'/></p>';
	echo '<div class="twc_butt2"><input id="twc_sub_send" type="button" value="'.__("Save",twc_lang).'"/></div>';
	echo '<div id="twc_mess"></div>';
	echo "</div>";
}
function twc_plugin_admin_scripts() {
	if ( ! did_action( 'wp_enqueue_media' ) ){wp_enqueue_media();}
 	wp_enqueue_script( 'twc_upload_but_script', plugins_url('js/script-adm.js',__FILE__), array('jquery'), "0.3", false );
	wp_localize_script("twc_upload_but_script", "twc_Ajax", array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce('twc_Ajax-nonce') ) );
	twc_stylesheet_adm();
}
function twc_stylesheet_adm(){
	wp_enqueue_style("twc_style-adm",plugins_url('css/style-adm.css',__FILE__),"0.3",true);
}
/*----------end-code-for-create-admin-page-------------------*/
//ajax save option from admin page
function twc_send_option_action_callback(){
	global $wpdb;
	$host="://".$_SERVER['SERVER_NAME'];
	$nonce=$_POST['nonce'];
	if (!wp_verify_nonce( $nonce, 'twc_Ajax-nonce'))wp_die('Stop!');
	if(($_POST['ok1']=="2eRrHs6D") && $i=strpos($_SERVER['HTTP_REFERER'],$host)){
		if(!empty($_POST['twc_option_butt'])){update_option("twc_option_butt", $_POST['twc_option_butt']);}
		if(!empty($_POST['twc_option_img_cite'])){update_option("twc_option_img_cite", $_POST['twc_option_img_cite']);}
		update_option("twc_option_butt_txt", $_POST['twc_option_butt_txt']);
		update_option("twc_option_TXT_comm_h2", $_POST['twc_option_TXT_comm_h2']);
		update_option("twc_option_TXT_comm_form_h2", $_POST['twc_option_TXT_comm_form_h2']);
		update_option("twc_option_TXT_comm_no_comm", $_POST['twc_option_TXT_comm_no_comm']);
		update_option("twc_option_TXT_comm_textarea", $_POST['twc_option_TXT_comm_textarea']);
		update_option("twc_option_TXT_comm_input1", $_POST['twc_option_TXT_comm_input1']);
		update_option("twc_option_TXT_comm_input2", $_POST['twc_option_TXT_comm_input2']);
		update_option("twc_option_show_cite", $_POST['twc_option_show_cite']);
		echo "1".__("Settings have been saved",twc_lang)."";
		
	}else{
		echo "Error. This is spam!";
	}
	exit;
}
?>