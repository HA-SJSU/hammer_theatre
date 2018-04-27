<?php
    /**
    * Template Name: Events & Tickets Page
    */

get_header(); ?>

<?php
$eventPage = "/events/";
$currentPage = $_SERVER['REQUEST_URI'];
if($eventPage == $currentPage){
	echo '<div>
			<img class="banner-img margin-bottom-60" src="http://hammertheatre.wpengine.com/wp-content/uploads/2018/01/test.png" />
			<div class="upcoming-event-banner background-hammerBlue">
			<div class="marginLeft marginRight">
			<h1 class="color-white">UPCOMING EVENTS</h1>
			<p class="color-white">From traditional music, theatre and dance performances to influential speakers and modern ArtTech, the Hammer Theatre hosts an array of over 250 performances year-round.</p>
			</div>
			</div>
		</div>';
}?>

<div class="wrap">
		<main id="main" class="site-main" role="main">
			<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/page/content', 'page' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
</div><!-- .wrap -->

<?php get_footer(); ?>
