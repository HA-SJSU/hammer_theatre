<?php
/**
 * Single Event Template
 * A single event. This displays the event title, description, meta, and
 * optionally, the Google map for the event.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/single-event.php
 *
 * @package TribeEventsCalendar
 * @version 4.6.3
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$events_label_singular = tribe_get_event_label_singular();
$events_label_plural   = tribe_get_event_label_plural();
$event_id = get_the_ID();

/*
 * Here, I am getting the category in String
 * Determining the following:
 * - the color of "learn more" button
 * based on the category
 */
// This function "tribe_get_text_categories();"
// is custom function in the `twenty-seleventeen-child/function.php`
// For more detail go to there and please read the doc there. Thank you!
$category_text = tribe_get_text_categories();
$category_color = tribe_get_color_for_categories($category_text);

?>

<div id="tribe-events-content" class="tribe-events-single">

	<p class="tribe-events-back">
		<a href="<?php echo esc_url( tribe_get_events_link() ); ?>"> <?php printf( '&laquo; ' . esc_html_x( 'All %s', '%s Events plural label', 'the-events-calendar' ), $events_label_plural ); ?></a>
	</p>

        <!-- Category -->
        <div style="font-size: 15px;">
                <h2 style="vertical-align: middle;">
                        <?php echo $category_text ?> 
                        <span style="color: var(<?php echo $category_color ?>); font-size: 1.4em; vertical-align: middle;">&#x2794;</span>
                </h2>
        </div>

        <!-- Notices -->
	<?php tribe_the_notices() ?>

	<?php the_title( '<h1 class="tribe-events-single-event-title">', '</h1>' ); ?>

	<div class="tribe-events-schedule tribe-clearfix">
		<!-- <?php echo tribe_events_event_schedule_details( $event_id, '<h2>', '</h2>' ); ?> -->
		<?php if ( tribe_get_cost() ) : ?>
			<!-- <span class="tribe-events-cost"><?php echo tribe_get_cost( null, true ) ?></span> -->
		<?php endif; ?>
	</div>

	<!-- Event header -->
	<div id="tribe-events-header" <?php tribe_events_the_header_attributes() ?>>
		<!-- Navigation -->
		<h3 class="tribe-events-visuallyhidden"><?php printf( esc_html__( '%s Navigation', 'the-events-calendar' ), $events_label_singular ); ?></h3>
		<ul class="tribe-events-sub-nav">
			<li class="tribe-events-nav-previous"><?php tribe_the_prev_event_link( '<span>&laquo;</span> %title%' ) ?></li>
			<li class="tribe-events-nav-next"><?php tribe_the_next_event_link( '%title% <span>&raquo;</span>' ) ?></li>
		</ul>
		<!-- .tribe-events-sub-nav -->
	</div>
	<!-- #tribe-events-header -->

	<?php while ( have_posts() ) :  the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<!-- Event featured image, but exclude link -->
			<?php echo tribe_event_featured_image( $event_id, 'full', false ); ?>

                        <!-- Event content two col -->
                        <div class="event-content-two-col">
                        <!-- Event content -->
                        <!-- Left Col -->
                        <div class="left-col .va-text-top">
                        <?php do_action( 'tribe_events_single_event_before_the_content' ) ?>
                        <div class="tribe-events-single-event-description tribe-events-content">
                                <?php the_content(); ?>
                        </div>
                        </div> <!-- END left col -->

			<!-- Event meta -->
                        <!-- Right Col -->
                        <div class="right-col .va-text-top">
			<?php do_action( 'tribe_events_single_event_before_the_meta' ) ?>
			<?php tribe_get_template_part( 'modules/meta' ); ?>
			<?php do_action( 'tribe_events_single_event_after_the_meta' ) ?>
                        </div> <!-- END right col -->
                        </div> <!-- END Event content two col -->

		</div> <!-- #post-x -->
		<?php if ( get_post_type() == Tribe__Events__Main::POSTTYPE && tribe_get_option( 'showComments', false ) ) comments_template() ?>
	<?php endwhile; ?>

	<!-- Event footer -->
	<div id="tribe-events-footer">
		<!-- Navigation -->
		<h3 class="tribe-events-visuallyhidden"><?php printf( esc_html__( '%s Navigation', 'the-events-calendar' ), $events_label_singular ); ?></h3>
		<ul class="tribe-events-sub-nav">
			<li class="tribe-events-nav-previous"><?php tribe_the_prev_event_link( '<span>&laquo;</span> %title%' ) ?></li>
			<li class="tribe-events-nav-next"><?php tribe_the_next_event_link( '%title% <span>&raquo;</span>' ) ?></li>
		</ul>
		<!-- .tribe-events-sub-nav -->
	</div>
	<!-- #tribe-events-footer -->
</div><!-- #tribe-events-content -->
