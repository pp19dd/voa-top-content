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
		echo $comments_number;
		echo ($comments_number === 1 ? ' Comment' : ' Comments' );
	?></header>

<?php } else { // Check for have_comments(). ?>
	
	<header>Comments</header>

<?php } // Check for have_comments() ?>


	<div class="comments-inner">
	
		<?php if ( have_comments() ) : ?>
			
			<?php the_comments_navigation(); ?>

			<ol class="comment-list">
				<?php
					wp_list_comments( array(
						'style'       => 'ol',
						'type'        => 'comment',
						'short_ping'  => true,
						'avatar_size' => 67
					) );
				?>
			</ol><!-- .comment-list -->

			<?php the_comments_navigation(); ?>

		<?php endif; // Check for have_comments(). ?>

		<?php
			// If comments are closed and there are comments, let's leave a little note, shall we?
			if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
		?>
			<p class="no-comments"><?php _e( 'Comments are closed.', 'twentysixteen' ); ?></p>
		<?php endif; ?>

		<?php
			comment_form( array(
				'title_reply_before' => '<h2 id="reply-title" class="comment-reply-title">',
				'title_reply_after'  => '</h2>',
			) );
		?>

	</div><!-- .comments-inner -->


<?php } ?>

<?php if( isset( $o['voa_commenting'] ) && $o['voa_commenting'] == 'facebook' ) { ?>


	<header>Comments</header>
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
