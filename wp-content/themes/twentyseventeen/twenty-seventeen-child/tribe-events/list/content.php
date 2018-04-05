<?php
/**
 * List View Content Template
 * The content template for the list view. This template is also used for
 * the response that is returned on list view ajax requests.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/list/content.php
 *
 * @package TribeEventsCalendar
 * @version  4.3
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
} ?>

<div id="tribe-events-content" class="tribe-events-list">


	<?php
	/**
	 * Fires before any content is printed inside the list view.
	 */
	do_action( 'tribe_events_list_before_the_content' );
	?>

        <!-- Before Event content -->
        <div class="before-event-content"> 
        <img src="http://hammertheatre.staging.wpengine.com/wp-content/uploads/2018/01/Screen-Shot-2018-01-05-at-11.59.49-AM.png" />
        <div id="upcoming-events-banner-noButton" class="wpb_column vc_column_container vc_col-sm-12">
          <div class="vc_column-inner ">
            <div class="wpb_wrapper">
              <div class="wpb_text_column wpb_content_element color-white">
                <div class="wpb_wrapper">
                  <h1>UPCOMING EVENTS</h1> From traditional music, theatre, and dance performances to influential speakers and modern ArtTech, the Hammer Theatre hosts an array of events year round.

                </div>
              </div>
            </div>
          </div>
        </div>
        </div>

        <!-- Events Loop -->
	<?php if ( have_posts() ) : ?>
		<?php do_action( 'tribe_events_before_loop' ); ?>
		<?php tribe_get_template_part( 'list/loop' ) ?>
		<?php do_action( 'tribe_events_after_loop' ); ?>
	<?php endif; ?>

	<!-- List Footer -->
	<?php do_action( 'tribe_events_before_footer' ); ?>
	<div id="tribe-events-footer">

		<!-- Footer Navigation -->
		<?php do_action( 'tribe_events_before_footer_nav' ); ?>
		<?php tribe_get_template_part( 'list/nav', 'footer' ); ?>
		<?php do_action( 'tribe_events_after_footer_nav' ); ?>

	</div>
	<!-- #tribe-events-footer -->

</div><!-- #tribe-events-content -->