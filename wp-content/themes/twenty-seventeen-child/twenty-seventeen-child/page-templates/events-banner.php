<?php
    /**
    * Template Name: About Page
    */

get_header(); ?>
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

<!-- Refactor this later, create a single method -->
<!-- Code snippet is to render 3 upcoming events -->
<?php
if (function_exists('tribe_get_events')) {
// Retrieve the next 3 upcoming events
$events = tribe_get_events( array(
   'posts_per_page' => 3,
   'start_date' => date( 'Y-m-d H:i:s' )
) );
$counter = 1;
foreach ( $events as $post ) {
    setup_postdata( $post );
        // Image
        echo '<div id="upcoming-banner-img-'.$counter.'">';
        echo the_post_thumbnail();
        echo '</div>';
        echo '<div style="text-align: center;" id="upcoming-banner-meta-'.$counter.'">';
                echo '<a href="'.esc_url( tribe_get_event_link() ).'" class="va-text-middle white-text buy-tickets-link" rel="bookmark">View Event</a>';
        echo '</div>';
        $counter++;
 }
}
?>
<script>
    for(i = 1; i <= 3; i++){
        var card_front = document.getElementById("upcoming-events-banner-" + i);
        var event_thumbnail = document.getElementById("upcoming-banner-img-" + i);
        var event_meta = document.getElementById("upcoming-banner-meta-" + i);
        card_front.appendChild(event_thumbnail);
        card_front.appendChild(event_meta);
    }
</script>
