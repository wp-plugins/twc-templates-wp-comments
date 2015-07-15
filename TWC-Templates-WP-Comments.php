<?php
/*
Plugin Name: TWC-Templates-WP-Comments
Plugin URI: http://help-wp.ru/twc-templates-wordPress-comments
Description: Template for WordPress Comment
Version: 0.3
Author: Chaika Igor
Author URI: http://help-wp.ru/
Text Domain: TWC-Templates-WP-Comments
Domain Path: /lang/
License: GPL2
*/
?>
<?php
/*  Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : chaika-igor@ukr.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>
<?php
	//$twc_lang="TWC-Templates-WordPress-Comments";
	define ("twc_lang", "TWC-Templates-WP-Comments");
	define ("twc_const_antispam1", rand(1,9150));
	define ("twc_const_antispam2", rand(1,9150));
	define ("twc_const_antispam3", rand(1,9150));
	include("function.php");
	
	/*---------------activation-----------------*/
	function twc_plugin_activate(){
		if(!$twc_option_butt=get_option('twc_option_butt')) add_option("twc_option_butt","null");
		if(!$twc_option_butt_txt=get_option('twc_option_butt_txt')) add_option("twc_option_butt_txt","");
		if(!$twc_option_img_cite=get_option('twc_option_img_cite')) add_option("twc_option_img_cite","null");
		if(!$twc_option_show_cite=get_option('twc_option_show_cite')) add_option("twc_option_show_cite",1);
		if(!$twc_option_TXT_comm_h2=get_option('twc_option_TXT_comm_h2')) add_option("twc_option_TXT_comm_h2",__("Comments",twc_lang));
		if(!$twc_option_TXT_comm_form_h2=get_option('twc_option_TXT_comm_form_h2')) add_option("twc_option_TXT_comm_form_h2",__("Add new comment",twc_lang));
		if(!$twc_option_TXT_comm_no_comm=get_option('twc_option_TXT_comm_no_comm')) add_option("twc_option_TXT_comm_no_comm",__("No comments yet!",twc_lang));
		if(!$twc_option_TXT_comm_textarea=get_option('twc_option_TXT_comm_textarea')) add_option("twc_option_TXT_comm_textarea",__("Enter your message",twc_lang));
		if(!$twc_option_TXT_comm_input1=get_option('twc_option_TXT_comm_input1')) add_option("twc_option_TXT_comm_input1",__("Your name",twc_lang));
		if(!$twc_option_TXT_comm_input2=get_option('twc_option_TXT_comm_input2')) add_option("twc_option_TXT_comm_input2",__("Your e-mail",twc_lang));
	}
	register_activation_hook( __FILE__, 'twc_plugin_activate');
	/*--------------end-activation-----------------*/
	
	add_action( 'wp_enqueue_scripts', 'twc_stylesheet' ); //style & script add
	
	add_filter( "comments_template", "twc_my_plugin_comment_template" ); //add comment template
	
	add_filter('pre_comment_on_post', 'twc_onSendDataComm'); //antispam function
	
	add_filter('wp_die_handler', 'twc_get_die_handler'); // error for send comment
	
	add_action( 'plugins_loaded', 'twc_start_work' ); ////start work plagin in adminbar & other after load
	
?>