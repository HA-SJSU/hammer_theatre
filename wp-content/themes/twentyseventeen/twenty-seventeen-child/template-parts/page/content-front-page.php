<?php
/**
 * Displays content for front page
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'twentyseventeen-panel ' ); ?> >

	<?php if ( has_post_thumbnail() ) :
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'twentyseventeen-featured-image' );

		$post_thumbnail_id = get_post_thumbnail_id( $post->ID );

		$thumbnail_attributes = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'twentyseventeen-featured-image' );

		// Calculate aspect ratio: h / w * 100%.
		$ratio = $thumbnail_attributes[2] / $thumbnail_attributes[1] * 100;
		?>

		<div class="panel-image" style="background-image: url(<?php echo esc_url( $thumbnail[0] ); ?>);">
			<div class="panel-image-prop" style="padding-top: <?php echo esc_attr( $ratio ); ?>%"></div>
		</div><!-- .panel-image -->

	<?php endif; ?>
	
	<div class="panel-content">
		<div class="wrap">


			<div class="entry-content">
				<?php
					/* translators: %s: Name of current post */
					the_content();
				?>
			</div><!-- .entry-content -->

		</div><!-- .wrap -->
	</div><!-- .panel-content -->

</article><!-- #post-## -->

<!-- Code snippet is to render 4 upcoming events -->
<?php
if (function_exists('tribe_get_events')) {

// Retrieve the next 4 upcoming events
$events = tribe_get_events( array(
   'posts_per_page' => 4,
   'start_date' => date( 'Y-m-d H:i:s' )
) );

$counter = 1;
foreach ( $events as $post ) {
    setup_postdata( $post );

        // Image
        echo '<div id="home-event-img-'.$counter.'"">';
        echo the_post_thumbnail();
        echo '</div>';

        echo '<div id="home-event-subcontainer-'.$counter.'">';
        echo '<p id="home-event-series-'.$counter.'">'.tribe_get_text_categories().' <span style="color: var('.tribe_get_color_for_categories( tribe_get_text_categories() ).');">&#x2794;</span></p>';
        echo '<h1 id="home-event-title-'.$counter.'">'.$post->post_title.'</h1>';
        echo '<p id="home-event-excerpt-'.$counter.'">'.tribe_events_get_the_excerpt( null, wp_kses_allowed_html( 'post' ) ).'</p>';
        echo '<p id="home-event-date-'.$counter.'">'.tribe_get_start_date($post).'</p>';
        echo '<button class="series-button" style="background-color: var('.tribe_get_color_for_categories( tribe_get_text_categories() ).');">View Event</button>';
        echo '</div>';

        $counter++;
 }
}
?>

<script>
	for(i = 1; i < 5; i++){
		var eventRow = document.getElementById("home-event-" + i);

		var eventImg = document.getElementById("home-event-img-" + i);
		var eventTextContainer = document.getElementById("home-event-subcontainer-" + i)


		if(i % 2 == 0){
			eventRow.appendChild(eventTextContainer);
			eventRow.appendChild(eventImg);
		} else {
			eventRow.appendChild(eventImg);
			eventRow.appendChild(eventTextContainer);
		}

	}
</script>

<!-- Code snippet to render 3 post on home page -->
<?php
$counter = 1;
query_posts('posts_per_page=3'); /*1, 2*/
if ( have_posts() ) while ( have_posts() ) : the_post();
	echo '<div id="featured-post-'.$counter.'">';
	echo the_category();

    echo '<div id="featured-img-'.$counter.'"">';
	echo the_post_thumbnail();
	echo '</div>';

    echo '<div id="featured-title-'.$counter.'"">';
	echo the_title();
	echo '</div>';

    echo '<div id="featured-excerpt-'.$counter.'"">';
	echo the_excerpt();
	echo '</div>';

	echo '</div>';
	$counter++;
endwhile;
wp_reset_query();?>


<script>
	for(i = 1; i < 4; i++){
		var postColumn = document.getElementById("featured-col-" + i);
		var post = document.getElementById("featured-post-" + i);
		postColumn.appendChild(post);
	}
</script>
