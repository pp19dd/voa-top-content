<?php

add_action( 'widgets_init', function(){
	register_widget( 'VOA_Newsletter_Teaser' );
});

class VOA_Newsletter_Teaser extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'voa-newsletter-teaser',
			'description' => 'Displays an image and link to sign up for the Today@VOA Newsletter.',
		);
		parent::__construct( 'voa_newsletter_teaser', 'VOA Newsletter Teaser', $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		// outputs the content of the widget
		?>
		
		<style type="text/css">
			
			.voa-newsletter-teaser {
				background: #fff; 
				border: 2px solid #1330bf; 
				margin-bottom: 4em; 
				padding: 0 0 1.5em 0 !important; 
				text-align: center;
			}
			
			.voa-newsletter-teaser-img img {
				display: block;
				height: auto;
				width: 100%;
			}
			
			a.voa-newsletter-teaser-button {
				display: inline-block; 
				background-color: #ddd; 
				border: 1px solid #ccc; 
				border-radius: .25em; 
				color: #666; 
				font-size: .9em; 
				font-weight: bold; 
				letter-spacing: .05em; 
				padding: .75em 1em; 
				text-decoration: none; 
				text-transform: uppercase;
				transition: background-color 1s, border-color 1s, color 1s;
			}
			
			a.voa-newsletter-teaser-button:hover {
				background-color: #d41010;
				border-color: #d41010;
				color: #fff;
				transition: background-color .25s, border-color .25s, color .25s;
			}
			
		</style>
		
		<div class="voa-newsletter-teaser">
			<a class="voa-newsletter-teaser-img" href="http://www.voanews.com/subscribe.html?source=editors-picks-img-tile"><img src="<?php echo get_template_directory_uri(); ?>/img/2016-12_today-at-voa_promo-tile.png" alt="today at VOA: Informing, engaging, and connecting the people of the world." /></a>
			<a class="voa-newsletter-teaser-button" href="http://www.voanews.com/subscribe.html?source=editors-picks-signup-button">Subscribe Today!</a>
		</div>
		
		<?php
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		// outputs the options form on admin
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
	}
}