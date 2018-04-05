<?php

# Register extra footer area 3
register_sidebar(array(
  'name' => __('Footer 3', 'twentyseventeen'),
  'id' => 'sidebar-4',
  'description' => __('Add widgets here to appear in your footer.', 'twentyseventeen'),
  'before_widget' => '<section id="%1$s" class="widget %2$s">',
  'after_widget' => '</section>',
  'before_title' => '<h2 class="widget-title">',
  'after_title' => '</h2>',
));

# Register extra footer area 4
register_sidebar(array(
  'name' => __('Footer 4', 'twentyseventeen'),
  'id' => 'sidebar-5',
  'description' => __('Add widgets here to appear in your footer.', 'twentyseventeen'),
  'before_widget' => '<section id="%1$s" class="widget %2$s">',
  'after_widget' => '</section>',
  'before_title' => '<h2 class="widget-title">',
  'after_title' => '</h2>',
));

# Set logo to left of navigation bar
add_filter( 'wp_nav_menu_items', 'wpsites_add_logo_nav_menu', 10, 2 );

function wpsites_add_logo_nav_menu( $menu, stdClass $args ){
if ( 'primary' != $args->theme_location )
    return $menu;
$menu .= '<nav class="nav-image"><img src="' . get_stylesheet_directory_uri() . 'assets/images/hammer-logo.png" /></nav>';
return $menu;
}

/*
 * This function is used to get a String representing the "category" of event
 *
 * Usage:
 * copy below
 * <php echo tribe_get_text_categories (); ?>
 *
 * For more information go to:
 * https://theeventscalendar.com/support/forums/topic/display-just-category-name/
 */
if ( class_exists('Tribe__Events__Main') ){

        /* get event category names in text format */
        function tribe_get_text_categories ( $event_id = null ) {

                if ( is_null( $event_id ) ) {
                        $event_id = get_the_ID();
                }
                $event_cats = '';
                $term_list = wp_get_post_terms( $event_id, Tribe__Events__Main::TAXONOMY );

                foreach( $term_list as $term_single ) {
                        $event_cats .= $term_single->name . ', ';
                }
                return rtrim($event_cats, ', ');
        }
}

/*
 * This function gets the category name
 * and returns the color in hex
 */
function tribe_get_color_for_categories ( $category_text ) {
        $color = '';
        switch ($category_text) {
        case 'Also @ Hammer':
        case 'Also @ Hammer Series':
                $color = '--alsoAtHammer';
                break;
        case 'ArtTech':
        case 'ArtTech Series':
                $color = '--artTech';
                break;
        case 'SJSU @ Hammer':
        case 'SJSU @ Hammer Series':
                $color = '--sjsuAtHammer';
                break;
        case 'Hammer Speaks':
        case 'Hammer Speaks Series':
                $color = '--hammerSpeaks';
                break;
        case 'Holidays @ Hammer':
        case 'Holidays @ Hammer Series':
                $color = '--holidaysAtHammer';
                break;
        case 'Music Without Borders':
        case 'Music Without Borders Series':
                $color = '--musicWithoutBorders';
                break;
        case 'Lights &amp; Action':
        case 'Lights &amp; Action Series':
                $color = '--lightsAndAction';
                break;
        default:
                $color = '--gray';
                break;
        }
        return $color;
}

/*
 * This function returns the url for logo used in the home page
 * based on the category of the event.
 * @param category_text String: category name in text. For example, "Also @ Hammer".
 * @return url for the logo. For example, http://hammertheatre.staging.wpengine.com/wp-content/uploads/2018/02/HAMR-0001_Website_Design_Template_R3_Graphic-SJSU@Hammer.png
 *         It returns empty string if the category does not match
 *
 * TODO Need to make sure this works on both dev and production (Maybe url can be an issue)
 */
function get_logo_url ( $category_text ) {
        $url = '';
        switch ($category_text) {
        case 'Also @ Hammer':
        case 'Also @ Hammer Series':
                $url = 'http://hammertheatre.staging.wpengine.com/wp-content/uploads/2018/02/HAMR-0001_Website_Design_Template_R3_Graphic-Also.png';
                break;
        case 'ArtTech':
        case 'ArtTech Series':
                $url = 'http://hammertheatre.staging.wpengine.com/wp-content/uploads/2018/02/HAMR-0001_Website_Design_Template_R3_Graphic-Art-and-Tech.png';
                break;
        case 'SJSU @ Hammer':
        case 'SJSU @ Hammer Series':
                $url = 'http://hammertheatre.staging.wpengine.com/wp-content/uploads/2018/02/HAMR-0001_Website_Design_Template_R3_Graphic-SJSU@Hammer.png';
                break;
        case 'Hammer Speaks':
        case 'Hammer Speaks Series':
                $url = 'http://hammertheatre.staging.wpengine.com/wp-content/uploads/2018/02/HAMR-0001_Website_Design_Template_R3_Graphic-Hammer-Speaks.png';
                break;
        case 'Holidays @ Hammer':
        case 'Holidays @ Hammer Series':
                $url = 'http://hammertheatre.staging.wpengine.com/wp-content/uploads/2018/02/HAMR-0001_Website_Design_Template_R3_Graphic-Holidays-@-Hammer.png';
                break;
        case 'Music Without Borders':
        case 'Music Without Borders Series':
                $url = 'http://hammertheatre.staging.wpengine.com/wp-content/uploads/2018/02/HAMR-0001_Website_Design_Template_R3_Graphic-Music-Without-Borders.png';
                break;
        case 'Lights &amp; Action':
        case 'Lights &amp; Action Series':
                $url = 'http://hammertheatre.staging.wpengine.com/wp-content/uploads/2018/02/HAMR-0001_Website_Design_Template_R3_Graphic-Lights-and-Action.png';
                break;
        default:
                $url = '';
                break;
        }
        return $url;
}

/*
 * This function returns styling for logo in home page.
 * Please take a look at spec for https://projects.invisionapp.com/share/RNELD1MGY#/screens/266256245
 * The location of logo is followings:
 * * Category of the event
 * * Location of image
 *
 * @param category_text String: category name in text. For example, "Also @ Hammer".
 * @param is_left Boolean: Location of image.
 *        If true, it means that image is displayed on left column.
 *        If false, it means that image is displayed on right column.
 * @return Styling for the logo. For example, "margin-top: 40px; margin-left: 304px;"
 *         It returns empty string if the category does not match
 *
 * TODO Need to make sure this works on both dev and production (Maybe url can be an issue)
 */
function get_logo_css_margin ( $category_text, $is_left ) {
        $style_text = '';
        switch ($category_text) {
        case 'Also @ Hammer':
        case 'Also @ Hammer Series':
                if ( $is_left ) {
                        $style_text = 'margin-top: 5%; margin-left: 89.3%; max-width: 16%;';
                } else {
                        $style_text = 'margin-top: 5%; margin-left: -7.5%; max-width: 16%;';
                }
                break;
        case 'ArtTech':
        case 'ArtTech Series':
                if ( $is_left ) {
                        $style_text = 'margin-top: -7%; margin-left: 76%; max-width: 14%;';
                } else {
                        $style_text = 'margin-top: 4%; margin-left: -7.5%; max-width: 14%;';
                }
                break;
        case 'SJSU @ Hammer':
        case 'SJSU @ Hammer Series':
                if ( $is_left ) {
                        $style_text = 'margin-top: -7%; margin-left: 75%; max-width: 14%;';
                } else {
                        $style_text = 'margin-top: -7%; margin-left: 13%; max-width: 14%;';
                }
                break;
        case 'Hammer Speaks':
        case 'Hammer Speaks Series':
                if ( $is_left ) {
                        $style_text = 'margin-top: -7.3%; margin-left: 76%; max-width: 14%;';
                } else {
                        $style_text = 'margin-top: -7.3%; margin-left: -6.81%; max-width: 14%;';
                }
                break;
        case 'Holidays @ Hammer':
        case 'Holidays @ Hammer Series':
                if ( $is_left ) {
                        $style_text = 'margin-top: -7%; margin-left: 76%; max-width: 14%;';
                } else {
                        $style_text = 'margin-top: 5%; margin-left: -6.5%; max-width: 14%;';
                }
                break;
        case 'Music Without Borders':
        case 'Music Without Borders Series':
                if ( $is_left ) {
                        $style_text = 'margin-top: 6%; margin-left: 61%; max-width: 59%;';
                } else {
                        $style_text = 'margin-top: 6%; margin-left: -27%; max-width: 59%;';
                }
                break;
        case 'Lights &amp; Action':
        case 'Lights &amp; Action Series':
                if ( $is_left ) {
                        $style_text = 'margin-top: -7%; margin-left: 70%; max-width: 14%;';
                } else {
                        $style_text = 'margin-top: 3%; margin-left: -7%; max-width: 14%;';
                }
                break;
        default:
                $style_text = '';
                break;
        }
        return $style_text;
}

/*
 * This function returns styling for logo in event list.
 * This function is very similar to get_logo_css_margin
 *
 * @param category_text String: category name in text. For example, "Also @ Hammer".
 * @return Styling for the logo. For example, "margin-top: 40px; margin-left: 304px;"
 *         It returns empty string if the category does not match
 */
function get_logo_css_margin_event_list ( $category_text ) {
        $style_text = '';
        switch ($category_text) {
        case 'Also @ Hammer':
        case 'Also @ Hammer Series':
                $style_text = 'margin-top: 2%; margin-left: 15.6%; max-width: 4%;';
                break;
        case 'ArtTech':
        case 'ArtTech Series':
                $style_text = 'margin-top: 1%; margin-left: 16.7%; max-width: 2%;';
                break;
        case 'SJSU @ Hammer':
        case 'SJSU @ Hammer Series':
                $style_text = 'margin-top: 1%; margin-left: 16.6%; max-width: 2%;';
                break;
        case 'Hammer Speaks':
        case 'Hammer Speaks Series':
                $style_text = 'margin-top: 1%; margin-left: 16%; max-width: 3%;';
                break;
        case 'Holidays @ Hammer':
        case 'Holidays @ Hammer Series':
                $style_text = 'margin-top: 1%; margin-left: 16.6%; max-width: 2%;';
                break;
        case 'Music Without Borders':
        case 'Music Without Borders Series':
                $style_text = 'margin-top: 1%; margin-left: 10%; max-width: 10%;';
                break;
        case 'Lights &amp; Action':
        case 'Lights &amp; Action Series':
                $style_text = 'margin-top: 1%; margin-left: 16.6%; max-width: 2%;';
                break;
        default:
                $style_text = '';
                break;
        }
        return $style_text;
}


/*
 * This function renderes the past event to browser.
 *
 *
 * @param category_text String: category name in text. For example, "Also @ Hammer".
 * @param start_date String: Date that you want the oldest past event occured.
 *        For example, if start_date is 2016-02-10, then this functions will show past event from 2016-02-10 to the current date
 * @return None. This function does not return anything. It only renders past events to browser
 */
function show_past_event ( $category_text="", $start_date="1/1/2017", $num_post=10 ) {

        echo '<div class="past-events-banner background-hammerBlue"><div class="marginLeft marginRight"><h1 class="color-white">PAST EVENTS</h1></div></div>';

        $time = strtotime( $start_date );
        $start_date = date( 'Y-m-d H:i:s', $time );
        echo $newformat;

        $events = tribe_get_events( array(
                'posts_per_page' => $num_post,
                'eventDisplay'=>'past',
                'start_date' => $start_date,
                // Pass the yestreday
                'end_date' => date( 'Y-m-d H:i:s', time() - 60 * 60 * 24 )
        ) );

        // Reversed the events
        // I thought this will help users to understand the order
        // becuase it shows the most recent past event at the top.
        // Not sure what is the best way to show this
        //
        $event_reversed = array_reverse($events);

        $counter = 1;

        foreach ( $event_reversed as $post ) {
                setup_postdata( $post );
                $postID = $post->ID;
                // Use this to show the post object data
                // print_r ($post);
                echo '<div class="event-container" style="margin-bottom: 2rem;">';
                  echo '<div class="event-list-img-logo-container">';
                    echo tribe_event_featured_image( $post->ID, null, 'medium' );
                  echo '</div>';

                  echo '<div id="event-subcontainer2-with-logo" style="padding-right: 5%;">';
                    echo '<h2><a href="'.esc_url( tribe_get_event_link($postID) ).'">'.$post->post_title.'</a><h2>';
                    echo '<div class="tribe-events-list-event-description tribe-events-content description entry-summary">';
                        echo '<p>'.get_the_excerpt($postID).'</p>';
                    echo'</div>';
                  echo '</div>';

                  $category_text = tribe_get_text_categories($postID);
                  $category_color = tribe_get_color_for_categories($category_text);
                  echo '<div id="event-subcontainer3">';
                    echo '<span style="font-size: 13px; font-weight: 700">'.tribe_get_start_date($post).'</span>';
                    echo '<div class="flex-hor-ver-venter-container series-button" style="background-color: var('.$category_color.');">';
                      echo '<a href="'.esc_url( tribe_get_event_link($postID) ).'" class="tribe-events-readmore white-text rel="bookmark">Learn More</a>';
                    echo '</div>';
                  echo '</div>';
                echo '</div>';
                $counter++;
        }
}

// function tribe_is_past_event( $event = null ){
// 	if ( ! tribe_is_event( $event ) ){
// 		return false;
// 	}
// 	$event = tribe_events_get_event( $event );
// 	// Grab the event End Date as UNIX time
// 	$end_date = tribe_get_end_date( $event, true, 'U' );
//         echo '<p>In function</p>';
// 	
// 	return time() > $end_date;
// }

/*
 * Shortcode for rendering google map and location information
 * To use place this [location_map]
 */
function location_map_shortcode( $atts ){
        return '<div class="location_map_container marginLeft marginRight" style="display: flex;">
                <div class="location_map_info inline-block va-text-top">
                <div class="divTable">
                <div class="divTableBody">
                <div class="divTableRow">
                <div class="divTableCell padding-right"><p><strong>Location</strong></p></div>
                <div class="divTableCell right-col"><p>Hammer Theatre Center 101 Paseo de San Antonio San Jose, CA 95113 Located between 2nd and 3rd St. in downtown San Jose.</p></div>
                </div>
                <div class="divTableRow">
                <div class="divTableCell padding-right"><p><strong>Parking</strong></p></div>
                <div class="divTableCell right-col"><p>Convenient parking available @ 2nd &amp; San Carlos Garage $5 flat fee after 6 PM</p></div>
                </div>
                <div class="divTableRow">
                <div class="divTableCell padding-right"><p><strong>Public Transit</strong></p></div>
                <div class="divTableCell right-col">
                <p class="inline-block va-text-top">Accessible by BART and VTA (2nd & Paseo De San Antonio exit is right in front of the Hammer Theatre</p>

                </div>
                </div>
                </div>
                </div>
                </div>
                <div class="location_map inline-block va-text-top">
                <iframe style="border: 0; float: right;" src="https://www.google.com/maps/embed/v1/place?q=hammer%20theatre%20center&amp;key=AIzaSyDLTX-ZPlO7pPc0tsDiJ7_5FUsz8mNl688" width="480" height="360" frameborder="0" allowfullscreen="allowfullscreen"></iframe>
                </div>
                </div>';
}
add_shortcode( 'location_map', 'location_map_shortcode' );

function addGoogleTagManager() {
  echo "<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TXSNHMP');</script>
<!-- End Google Tag Manager -->";
}

add_action('wp_head', addGoogleTagManager);
