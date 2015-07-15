<div id="coommentStyle">
<?php if ( !empty($post->post_password) && $_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) : ?>
<?php return; endif; ?>
<?php if ( comments_open() ) : ?>
	<?php
		$twc_option_butt_txt=get_option('twc_option_butt_txt');
		if(empty($twc_option_butt_txt)){$twc_option_butt_txt=__("Send",twc_lang);}
		$twc_option_TXT_comm_h2=get_option('twc_option_TXT_comm_h2');
		if(empty($twc_option_TXT_comm_h2)){$twc_option_TXT_comm_h2=__("Comments",twc_lang);}
		$twc_option_TXT_comm_form_h2=get_option('twc_option_TXT_comm_form_h2');
		if(empty($twc_option_TXT_comm_form_h2)){$twc_option_TXT_comm_form_h2=__("Add new comment",twc_lang);}
		$twc_option_TXT_comm_no_comm=get_option('twc_option_TXT_comm_no_comm');
		if(empty($twc_option_TXT_comm_no_comm)){$twc_option_TXT_comm_no_comm=__("No comments yet!",twc_lang);}
		$twc_option_TXT_comm_textarea=get_option('twc_option_TXT_comm_textarea');
		if(empty($twc_option_TXT_comm_textarea)){$twc_option_TXT_comm_textarea=__("Enter your message",twc_lang);}
		$twc_option_TXT_comm_input1=get_option('twc_option_TXT_comm_input1');
		if(empty($twc_option_TXT_comm_input1)){$twc_option_TXT_comm_input1=__("Your name",twc_lang);}
		$twc_option_TXT_comm_input2=get_option('twc_option_TXT_comm_input2');
		if(empty($twc_option_TXT_comm_input2)){$twc_option_TXT_comm_input2=__("Your e-mail",twc_lang);}
	?>
	<div class="clear"></div>
	<div class="h2"><?php echo $twc_option_TXT_comm_h2;?></div>
	<div id="ccom">

<?php if ( $comments ) : ?>
<ul class="comp">
<?php wp_list_comments('type=comment&callback=twc_mytheme_comment'); ?>
</ul>
<?php
	$thisPag=paginate_comments_links(array('echo' => false));
	if($thisPag){
		echo '<div class="ComPag">'.$thisPag.'</div>';
	}
?>
<?php else : // If there are no comments yet ?>
<div class="comments">
<div class="comcont"><div><p><?php echo $twc_option_TXT_comm_no_comm;?></p></div></div>
<div class="clear"></div>
</div>

<?php endif; ?>
<?php endif; ?>


<?php if ( comments_open() ) : ?>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p><?php printf(__('You must be <a href="%s">logged in</a> to post a comment.'), get_option('siteurl')."/wp-login.php?redirect_to=".urlencode(get_permalink()));?></p>
<?php else : ?>
<div id="respond">
<form class="addcom" action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
<div>
	<div class="h2"><?php echo $twc_option_TXT_comm_form_h2;?></div>
	<div id="cancel-comment-reply"><small><?php cancel_comment_reply_link() ?></small></div>
<?php
	twc_startSession();
	if(isset($_SESSION['messErrComm'])){
		echo "<div class='messErr'>".$_SESSION['messErrComm']."</div>";
		twc_destroySession();
	}
?>
	<div id="boxComm">
<?php if ( $user_ID ) : ?>
<p><?php printf(__(''), '<a href="'.get_option('siteurl').'/wp-admin/profile.php">'.$user_identity.'</a>'); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="<?php _e('Log out of this account') ?>"><?php _e('Log out &raquo;'); ?></a></p>
	<textarea id="comm1" name="comment" placeholder="<?php echo $twc_option_TXT_comm_textarea;?>"></textarea>
	<input type="submit" id="twc_submit_btn" name="submit" class="submit" value="<?php echo $twc_option_butt_txt;?>" />
	<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
	<?php echo twc_antispam_echo();?>
	<input type="hidden" name="comment_send_info" value="1"/>
	<?php comment_id_fields(); ?>

<?php else : ?>

	<input class="text" type="text" id="name" name="author" placeholder="<?php echo $twc_option_TXT_comm_input1;?>" value="<?php echo $comment_author; ?>" />
	<input class="text" type="text" id="name" name="email" placeholder="<?php echo $twc_option_TXT_comm_input2;?>" value="<?php echo $comment_author_email; ?>" />
	<input type="hidden" name="url" id="url" value=""/>
	<textarea id="comm1" name="comment" placeholder="<?php echo $twc_option_TXT_comm_textarea;?>"></textarea>
	<input type="submit" id="twc_submit_btn" name="submit" class="submit" value="<?php echo $twc_option_butt_txt;?>" />
	<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
	<?php echo twc_antispam_echo();?>
	<input type="hidden" name="comment_send_info" value="1"/>
	<?php comment_id_fields(); ?>
<?php endif; ?>
	</div>
</div>
<?php do_action('comment_form', $post->ID); ?>
</form>
</div>
<?php endif; // If registration required and not logged in ?>
</div>
<?php else : // Comments are closed ?>
<?php endif; ?>
</div>
<?php
function twc_mytheme_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
   <li>
   <div id="comment-<?php comment_ID(); ?>" class="comments">
		<div class="imgComm"></div>
   <div class="commentse"><span class="user"><?php comment_author(); ?></span> <span class="comdate"><?php comment_date("d.m.Y"); ?></span> <span class="comtime"><?php comment_date("G:i"); ?></span></div>
	<div class="comcont"><div><?php if($ava=get_avatar($comment->comment_author_email, 48)){echo "<div class='avaSt'>".$ava."</div>";} ?><?php comment_text() ?><div class="clearComm"></div><?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?><?php edit_comment_link('Редактировать', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" data-rel="comment-<?php comment_ID(); ?>" class="shsh" data-close-btn-text="<?php _e("Hide answers",twc_lang);?>" ><?php _e("Show answers",twc_lang);?> <span>(<span>0</span>)</span></a></div>
	<?php echo $user_ID;?>
	  </div>
	 
	<div class="clear"></div>
	</div>

<?php
        }
?>