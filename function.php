<?php
//style & script add
function ruc_stylesheet(){
	if( is_singular() && comments_open()){
		wp_enqueue_style("ruc_style1_templ",plugins_url('css/style.css',__FILE__),"0.1.2",true);
		wp_enqueue_script("ruc_script_templ",plugins_url('js/script.js',__FILE__),array('jquery'),"0.1.2",true);
		wp_localize_script("ruc_script_templ", "ruc_Ajax", array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce('ruc_Ajax-nonce') ) );
	}
}
//echo button
function ruc_printcode($id=null){
	if(empty($id)){$id=$GLOBALS['comment']->comment_ID;}
	echo get_ruc_printcode($id);
}
function get_ruc_printcode($id=null, $voice=null){
	if(empty($id)){$id=$GLOBALS['comment']->comment_ID;}
	$rty="";
	if(!empty($voice)){$_COOKIE['commYN'.$id]=$voice;}
	$rating=get_ruc_comment_rating($id);
	$htmlSpan="";
	if($rating>0){$htmlSpan=' class="gr"';}
	if($rating<0){$htmlSpan=' class="red"';}
	$say="";
	$rty.='<div id="rucBox-'.$id.'" class="rucBox">';
	$rty.='<div class="rucBoxTXT">Отзыв полезный?</div>';
	if(empty($_COOKIE['commYN'.$id])){$rty.='<a href="#yes" onClick="ruc_ajax_send('.$id.',1); return false;" class="rucBoxA rucBoxAYES" title="Да"><img src="'.plugins_url('img/comm_up.gif',__FILE__).'"/></a>';}
	$rty.='<div class="rucBoxRes"><span'.$htmlSpan.'>'.$rating.'</span></div>';
	if(!empty($_COOKIE['commYN'.$id])){
		if($_COOKIE['commYN'.$id]==1){$say="&laquo;Полезный&raquo;";}else{$say="&laquo;Неполезный&raquo;";}
		$rty.='<div class="rucBoxRes">Вы уже проголосовали: '.$say.'</div>';
	}
	if(empty($_COOKIE['commYN'.$id])){$rty.='<a href="#no" onClick="ruc_ajax_send('.$id.',-1); return false;" class="rucBoxA rucBoxANO" title="Нет"><img src="'.plugins_url('img/comm_down.gif',__FILE__).'"/></a>';}
	$rty.='</div>';
	return $rty;
}
function get_ruc_comment_rating($id){
	if(!$ruc_str=get_comment_meta($id, 'ruc_comment_rating', true)){$ruc_str=0;}
	return $ruc_str;
}
function insert_ruc_comment_rating($id, $val){
	update_comment_meta($id, 'ruc_comment_rating', $val);
}
//start plugin
function ruc_start_work(){
	add_action('wp_ajax_nopriv_ruc_send_otz_action', 'ruc_send_option_action_callback');
	add_action('wp_ajax_ruc_send_otz_action', 'ruc_send_option_action_callback');
}
//ajax send
function ruc_send_option_action_callback(){
	$rtr='{"error":"Error. Not ADD!"}';
	global $wpdb;
	$nonce=$_POST['nonce'];
	if (!wp_verify_nonce( $nonce, 'ruc_Ajax-nonce'))wp_die('Stop!');
	$host="://".$_SERVER['SERVER_NAME'];
	if($i=strpos($_SERVER['HTTP_REFERER'],$host)){
		$do=0;
		if(!empty($_POST['ruc_do'])){
			if($_POST['ruc_do']==1) $do=1;
			if($_POST['ruc_do']==-1) $do=-1;
		}
		if(!empty($_POST['ruc_comm_id'])){
			if(empty($_COOKIE['commYN'.$_POST['ruc_comm_id']])){
				$curr=(int)get_ruc_comment_rating($_POST['ruc_comm_id'])+$do;
				insert_ruc_comment_rating($_POST['ruc_comm_id'],$curr);
				$currHtml=str_replace(array("'",'"'),array("\'",'\"'),get_ruc_printcode($_POST['ruc_comm_id'],$do));
				$rtr='{"val":"'.$currHtml.'", "id":"'.$_POST['ruc_comm_id'].'","error":""}';
				setcookie('commYN'.$_POST['ruc_comm_id'], $do, time()+360000,"/");
			}else{
				$rtr='{"error":"Вы уже голосовали за этот комментарий", "voice":"error"}';
			}
		}
		echo $rtr;
	}else{
		echo '{"error":"Error. This is spam!"}';
	}
	wp_die();
}
?>