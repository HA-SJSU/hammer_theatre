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
				<div class="site-title">
					<!-- SJSU Logo -->
					<div id="sjsu-logo">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/sjsu-logo-gold.png" alt="SJSU logo" width="60px" height="25px"/>
					</div>
					<!-- 'OPERATED BY SAN JOSE STATE UNIVERSITY' -->
					<div id="sjsu-copy">
						<h1 style="color: #e8af2a">OPERATED BY SAN JOSÉ STATE UNIVERSITY</h1>
					</div>
					<div id="sitebranding-right">
						<!-- Twitter Logo -->
						<div id="twitter-icon">
							<a href="https://twitter.com/hammer_sjsu?lang=en">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/twitter-icon.png" alt="Twitter" width="25px" height="25px" />
						</a>
						</div>
						<!-- Instagram Logo -->
						<div id="instagram-icon">
							<a href="https://www.instagram.com/hammertheatrecenter/">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/instagram-icon.png" alt="Instagram" width="25px" height="25px" />
						</a>
						</div>
						<!-- Facebook Logo -->
						<div id="facebook-icon">
							<a href="https://facebook.com/HammerTheatreCenter/">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/facebook-icon.png" alt="Facebook" width="25px" height="25px" />
							</a>
						</div>
						<!-- Box Office # -->
						<div id="boxoffice-copy">
							<h1 class="white-text">Box office: (408) 924-8501</h1>
						</div>
					</div>
				</div>
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

<!-- Mobile 2nd row -->
<div id="mob-hammer-logo-row" class="paddingLeft paddingRight">
        <a href="/"><img src="/wp-content/uploads/2017/11/cropped-cropped-Hammer_logo_Artboard-46-copy@3x-1.png" style="margin-top: 4px;" alt="Hammer Theatre logo" width="125px"></a>
        <span class="hammerblue-text margin-right-30" style="font-weight:500; font-size: 1.3rem; float: right; margin-top: 10px;">OPERATED BY SAN JOSE STATE UNIVERSITY</span>
</div>
<!-- End of Mobile 2nd row -->
