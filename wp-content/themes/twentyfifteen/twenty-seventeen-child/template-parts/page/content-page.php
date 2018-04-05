<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">
		<?php
			the_content();

			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'twentyseventeen' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->

<script>
var homeButton = document.getElementById("menu-item-379");
// Determines what URL to use for Home page since we have staging/production at the momment
if(window.location.href.indexOf('staging') != -1) {
		homeButton.innerHTML = '<a href="http://hammertheatre.staging.wpengine.com/"><img src="http://hammertheatre.staging.wpengine.com/wp-content/uploads/2017/11/cropped-cropped-Hammer_logo_Artboard-46-copy@3x-1.png" /></a>';
} else {
		homeButton.innerHTML = '<a href="http://hammertheatre.wpengine.com/"><img src="http://hammertheatre.staging.wpengine.com/wp-content/uploads/2017/11/cropped-cropped-Hammer_logo_Artboard-46-copy@3x-1.png" /></a>';
}
</script>
