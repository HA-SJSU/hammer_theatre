<?php
/**
 * List View Single Event
 * This file contains one event in the list view
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/list/single-event.php
 *
 * @version 4.6.3
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

// Setup an array of venue details for use later in the template
$venue_details = tribe_get_venue_details();

// The address string via tribe_get_venue_details will often be populated even when there's
// no address, so let's get the address string on its own for a couple of checks below.
$venue_address = tribe_get_address();

// Venue
$has_venue_address = ( ! empty( $venue_details['address'] ) ) ? ' location' : '';

// Organizer
$organizer = tribe_get_organizer();

$event_id = Tribe__Main::post_id_helper();

/*
 * Here, I am getting the category in String
 * Determining the following:
 * - the color of "learn more" button
 * based on the category
 */
// This function "tribe_get_text_categories();"
// is custom function in the `twenty-seventeen-child/function.php`
// For more detail go to there and please read the doc there. Thank you!
$category_text = tribe_get_text_categories();
$category_color = tribe_get_color_for_categories($category_text);

// Check if current page is /events/
$eventPage = "/events/";
$currentPage = $_SERVER['REQUEST_URI'];
$is_current_page_events = false;
if($eventPage == $currentPage){
        $is_current_page_events = true;
        // Get logo url
        $logo_url = get_logo_url($category_text);
        $logo_style_text = get_logo_css_margin_event_list($category_text);
}
?>

<!-- Show logo only in event page -->
<?php
if($is_current_page_events){
        echo '<div class="event-list-img-logo-container">';
        echo '<img class="event-list-logo" src="'.$logo_url.'" style="'.$logo_style_text.'">';
        echo '<!-- Event Image -->';
        echo tribe_event_featured_image( null, 'medium' );
        echo '</div>';
        echo '<div id="event-subcontainer2-with-logo" class="margin-left-60">';
} else {
        echo '<!-- Event Image -->';
        echo tribe_event_featured_image( null, 'medium' );
        echo '<div id="event-subcontainer2">';
}
?>



<!-- Event Title -->
<?php do_action( 'tribe_events_before_the_event_title' ) ?>
<h2 class="tribe-events-list-event-title">
	<a class="tribe-event-url" href="<?php echo esc_url( tribe_get_event_link() ); ?>" title="<?php the_title_attribute() ?>" rel="bookmark">
		<?php the_title() ?>
	</a>
</h2>
<?php do_action( 'tribe_events_after_the_event_title' ) ?>

<!-- subtitle -->
<h3 class="subtitle">
<?php echo get_post_meta($event_id, 'subtitle', true); ?>
</h3>
<!-- End of subtitle -->

<!-- Event Content -->
<?php do_action( 'tribe_events_before_the_content' ); ?>
<div class="tribe-events-list-event-description tribe-events-content description entry-summary">
	<?php echo tribe_events_get_the_excerpt( null, wp_kses_allowed_html( 'post' ) ); ?>
</div><!-- .tribe-events-list-event-description -->
<?php do_action( 'tribe_events_after_the_content' ); ?>
</div>

<!-- Event Meta -->
<div id="event-subcontainer3" class="margin-left-20">
<?php do_action( 'tribe_events_before_the_meta' ) ?>
<div class="tribe-events-event-meta">
	<div class="author <?php echo esc_attr( $has_venue_address ); ?>">

		<!-- Schedule & Recurrence Details -->
		<div class="tribe-event-schedule-details">
			<!-- If/else to render single event time or multi-day event time -->
			<?php
                                if ( tribe_is_recurring_event($event_id) ) {
                                        show_recurring_dates( $event_id );
                                } else {
                                        // Single day event
                                        $custom_date = tribe_get_event_meta(null, 'custom_date', true);
                                        if($custom_date){
                                                echo $custom_date;
                                        }
                                        else {
                                                echo tribe_events_event_schedule_details();
                                        }
                                }
			?>
		</div>
	</div>
</div><!-- .tribe-events-event-meta -->
<?php do_action( 'tribe_events_after_the_meta' ) ?>

<!-- Series button -->
<a href="<?php echo esc_url( tribe_get_event_link() ); ?>" class="button learn-more-button" rel="bookmark" style="background-color: var(<?php echo $category_color ?>);"><?php esc_html_e( 'Learn more', 'the-events-calendar' ) ?></a>

<!-- Render ticket link in single event page -->
<?php
      $ticket_url = get_post_meta($event_id, 'Ticket_Link', true);
      if ($ticket_url) { ?>
            <a href="<?php echo $ticket_url; ?>" class="button event-buy-ticket-button-container buy-ticket-button">BUY TICKETS</a>
      <?php } else {
                // do nothing;
                }
                ?>
</div>
