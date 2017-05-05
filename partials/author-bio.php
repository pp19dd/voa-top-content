<?php

$o = get_option("voa_opt_comm");
//if( isset( $o['show_author'] ) ) {

	// set the current author
	$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
	
	$social_networks = array( 
		'facebook'   => array( 'name' => 'Facebook',   'fa_icon' => 'facebook-f' ),
		'googleplus' => array( 'name' => 'Google+',    'fa_icon' => 'google-plus' ),
		'instagram'  => array( 'name' => 'Instagram',  'fa_icon' => 'instagram' ),
		'pinterest'  => array( 'name' => 'Pinterest',  'fa_icon' => 'pinterest-p' ),
		'soundcloud' => array( 'name' => 'SoundCloud', 'fa_icon' => 'soundcloud' ),
		'twitter'    => array( 'name' => 'Twitter',    'fa_icon' => 'twitter' ),
		'youtube'    => array( 'name' => 'YouTube',    'fa_icon' => 'youtube-play' )
	);
	
	?>
	
	<div class="author-bio-box clearfix">
		<div class="author-avatar"><a title="Show all posts by <?php echo get_the_author_meta('display_name'); ?>" href="<?php echo get_author_posts_url( $curauth->ID ); ?>"><?php echo get_avatar( get_the_author_meta('user_email'), 160 ); ?></a></div>
		<div class="author-bio">
			<h1><?php echo $curauth->display_name; ?></h1>
			<p><?php echo $curauth->description; ?></p>
	
			<ul class="author-social">
				<?php
				foreach( $social_networks as $slug => $data ) {
					if ( $curauth->$slug != '' ) {
				?>
				<li class="author-<?php echo $slug; ?>"><a href="<?php echo $curauth->$slug; ?>" title="Follow <?php echo $curauth->display_name; ?> on <?php echo $data['name']; ?>"><span class="fa-stack fa"><i class="fa fa-square fa-stack-2x"></i><i class="fa fa-<?php echo $data['fa_icon']; ?> fa-stack-1x fa-inverse"></i></span><span class="sr-only"><?php echo $data['name']; ?></span></a></li>
				<?php
					}
				}
				?>
			</ul>
		</div>
	</div><!-- end .author-bio-box -->
	
<?php 
//} # end if show author
