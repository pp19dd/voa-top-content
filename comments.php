<?php
/**
 * The template for displaying comments
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}

// get VOA comment option setting
$o = get_option("voa_opt_comm");

// if commenting option is not set, default to this type of commenting
// otherwise, if it is set, check for traditional type
if(
	!isset( $o['voa_commenting'] ) ||
	(isset( $o['voa_commenting'] ) && $o['voa_commenting'] == 'traditional' )
) { ?>


	
<?php if ( have_comments() ) { ?>
	
	<?php $comments_number = get_comments_number(); ?>

	<header><?php 
		printf(
			_n( '%s Comment', '%s Comments', $comments_number, 'voa-top-content' ), 
			number_format_i18n( $comments_number )
		);
	?></header>

<?php } else { // Check for have_comments(). ?>
	
	<header><?php _e( 'Comments', 'voa-top-content' ); ?></header>

<?php } // Check for have_comments() ?>


	<div class="comments-inner">
	
		<?php if ( have_comments() ) { ?>
			
			<?php the_comments_navigation(); ?>

			<ol class="comment-list">
				<?php
					wp_list_comments( array(
						'style'       => 'ol',
						'type'        => 'comment',
						'short_ping'  => true,
						'avatar_size' => 67,
						'max_depth'   => 1
					) );
				?>
			</ol><!-- .comment-list -->

			<?php the_comments_navigation(); ?>

		<?php } // Check for have_comments(). ?>

		<?php if ( ! comments_open() && post_type_supports( get_post_type(), 'comments' ) ) { ?>
			<p class="no-comments"><?php _e( 'Comments are closed.', 'voa-top-content' ); ?></p>
		<?php } ?>

		
		
		<?php
		
		// documentation: https://developer.wordpress.org/reference/functions/comment_form/
		
		$fields   =  array(
	        'author' => '<p class="comment-form-author">' . '<label for="author">' . _x( 'Name', 'noun', 'voa-top-content' ) . '</label> ' .
	                    '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" maxlength="245"' . $aria_req . $html_req . ' /></p>',
	        'email'  => '<p class="comment-form-email"><label for="email">' . _x( 'Email', 'noun', 'voa-top-content' ) . '</label> ' .
	                    '<input id="email" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" maxlength="100" aria-describedby="email-notes"' . $aria_req . $html_req  . ' /></p>',
	        'url'    => '' // don't show URL/website field
		);
		
		comment_form( array(
			'comment_notes_before' => '<p class="comment-notes"><span id="email-notes">' . __( 'Your email address will not be published.' ) . '</span> ' . __( 'All fields required.', 'voa-top-content' ) . '</p>',
			'comment_notes_after'  => '',
			'title_reply_before'   => '<h2 id="reply-title" class="comment-reply-title">',
			'title_reply_after'    => '</h2>',
			'title_reply_to'       => __( 'Replying to %s', 'voa-top-content' ),
			'cancel_reply_before'  => ' <small>',
			'cancel_reply_after'   => '</small>',
			'cancel_reply_link'    => __( 'Cancel', 'voa-top-content' ),
			'label_submit'         => _x( 'Post Comment', 'submit button', 'voa-top-content' ),
			'title_reply'          => __( 'Leave a Comment', 'voa-top-content' ),
			'comment_field'        => '<p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun', 'voa-top-content' ) . '</label> <textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" aria-required="true" required="required"></textarea></p>',
			'fields'               => $fields
		) );
		?>

	</div><!-- .comments-inner -->


<?php } ?>

<?php if( isset( $o['voa_commenting'] ) && $o['voa_commenting'] == 'facebook' ) { ?>

	<header><?php _e( 'Comments', 'voa-top-content' ); ?></header>
	<div class="comments-inner">
		<div class="fb-comments" data-href="<?php the_permalink(); ?>" data-width="100%" data-numposts="5"></div>

		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8&appId=<?php echo FB_APP_ID; ?>";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>
	</div><!-- .comments-inner -->

<?php }
