
<footer class="blog-footer">
	<div class="blog-footer-inner">
		<a class="voa-logo" title="<?php esc_attr_e( 'Return to VOA', 'voa-top-content' ); ?>" href="https://www.voanews.com/<?php echo VOA_METRICS_TRACKING_PARAMETER; ?>"><img src="<?php echo get_template_directory_uri(); ?>/img/voa-logo_142x60_2x_f8f8f8.png" height="20" alt="VOA" /></a>
		<div class="footer-nav-menu"><?php 
			wp_nav_menu( array( 
				'theme_location' => 'blog_footer_menu',
				'container'       => 'div',
				'container_class' => 'menu',
				'menu_class'      => '',
				'depth'           => 1
			));
		?></div>
	</div>
</footer>

<?php /* <script async defer src="//assets.pinterest.com/js/pinit.js"></script> */ ?>

<script type="text/javascript">
	var $j = jQuery.noConflict();

	$j(document).ready(function() {
		
		(function() {
			$j('.blog-nav-trigger').on('click', function() {
				$j('.blog-nav > .menu').slideToggle( 200 );
				
				// hide other dropdown menus
				$j( '.search-nav > .search-form-container').hide();
				$j( '.voa-masthead' ).removeClass( 'masthead-search-expanded' );
			});
			
			$j('.search-nav-trigger').on('click', function() {
				$j( '.search-nav > .search-form-container').slideToggle( 200 );
				$j( '.voa-masthead' ).toggleClass( 'masthead-search-expanded' );
				
				// hide other dropdown menus
				$j('.blog-nav > .menu').hide();
			});
    	})();

	});

</script>

<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>

<?php wp_footer(); ?>

</body>
</html>