<?php
/**
 * Displays header site branding
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

?>
<div class="site-branding">
	<div class="wrap" id="site-branding-wrap">

			<?php if ( is_front_page() ) : ?>
				<div class="site-title">
				<p class="float-left"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
					<div class="float-right">
						<p class="color-white" style="display:inline-block">Box office: (408) 924-8501</p>
						<a href="https://m.facebook.com/HammerTheatreCenter/">
						<img class="inline-block" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/facebook-icon.png" alt="Facebook" width="25px" height="25px" />
						</a>
						<a href="https://www.instagram.com/hammertheatrecenter/">
						<img class="inline-block" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/instagram-icon.png" alt="Instagram" width="25px" height="25px" />
						</a>
					</div>
				</div>
			<?php else : ?>
				<div class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></div>
			<?php endif; ?>

			<?php
			$description = get_bloginfo( 'description', 'display' );

			if ( $description || is_customize_preview() ) :
			?>
				<p class="site-description"><?php echo $description; ?></p>
			<?php endif; ?>

		<?php if ( ( twentyseventeen_is_frontpage() || ( is_home() && is_front_page() ) ) && ! has_nav_menu( 'top' ) ) : ?>
		<a href="#content" class="menu-scroll-down"><?php echo twentyseventeen_get_svg( array( 'icon' => 'arrow-right' ) ); ?><span class="screen-reader-text"><?php _e( 'Scroll down to content', 'twentyseventeen' ); ?></span></a>
	<?php endif; ?>

	</div><!-- .wrap -->
</div><!-- .site-branding -->
