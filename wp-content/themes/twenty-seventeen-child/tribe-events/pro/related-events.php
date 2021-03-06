<?php
/**
 * Related Events Template
 * The template for displaying related events on the single event page.
 *
 * You can recreate an ENTIRELY new related events view by doing a template override, and placing
 * a related-events.php file in a tribe-events/pro/ directory within your theme directory, which
 * will override the /views/pro/related-events.php.
 *
 * You can use any or all filters included in this file or create your own filters in
 * your functions.php. In order to modify or extend a single filter, please see our
 * readme on templates hooks and filters
 *
 * @package TribeEventsCalendarPro
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$posts = tribe_get_related_posts();
$category = tribe_get_text_categories();

if ( is_array( $posts ) && ! empty( $posts ) ) : 

echo '<h3 class="tribe-events-related-events-title">More events from '.$category.'</h3>' ?>

<ul class="tribe-related-events tribe-clearfix">
	<?php foreach ( $posts as $post ) : ?>
	<li>
		<?php $thumb = ( has_post_thumbnail( $post->ID ) ) ? get_the_post_thumbnail( $post->ID, 'large' ) : '<img src="' . esc_url( trailingslashit( Tribe__Events__Pro__Main::instance()->pluginUrl ) . 'src/resources/images/tribe-related-events-placeholder.png' ) . '" alt="' . esc_attr( get_the_title( $post->ID ) ) . '" />'; ?>
		<div class="tribe-related-events-thumbnail">
			<a href="<?php echo esc_url( tribe_get_event_link( $post ) ); ?>" class="url" rel="bookmark"><?php echo $thumb ?></a>
		</div>
		<div class="tribe-related-event-info">
			<p class="tribe-related-events-title"><a href="<?php echo tribe_get_event_link( $post ); ?>" class="tribe-event-url" rel="bookmark"><?php echo get_the_title( $post->ID ); ?></a><br/>
			<?php
				if ( $post->post_type == Tribe__Events__Main::POSTTYPE ) {
					echo tribe_events_event_schedule_details( $post );
				}
			?>
			</p>
		</div>
	</li>
	<?php endforeach; ?>
</ul>
<?php
endif;
