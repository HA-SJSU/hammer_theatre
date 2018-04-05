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
                $url = '/wp-content/uploads/2018/02/HAMR-0001_Website_Design_Template_R3_Graphic-Also.png';
                break;
        case 'ArtTech':
        case 'ArtTech Series':
                $url = '/wp-content/uploads/2018/02/HAMR-0001_Website_Design_Template_R3_Graphic-Art-and-Tech.png';
                break;
        case 'SJSU @ Hammer':
        case 'SJSU @ Hammer Series':
                $url = '/wp-content/uploads/2018/02/HAMR-0001_Website_Design_Template_R3_Graphic-SJSU@Hammer.png';
                break;
        case 'Hammer Speaks':
        case 'Hammer Speaks Series':
                $url = '/wp-content/uploads/2018/02/HAMR-0001_Website_Design_Template_R3_Graphic-Hammer-Speaks.png';
                break;
        case 'Holidays @ Hammer':
        case 'Holidays @ Hammer Series':
                $url = '/wp-content/uploads/2018/02/HAMR-0001_Website_Design_Template_R3_Graphic-Holidays-@-Hammer.png';
                break;
        case 'Music Without Borders':
        case 'Music Without Borders Series':
                $url = '/wp-content/uploads/2018/02/HAMR-0001_Website_Design_Template_R3_Graphic-Music-Without-Borders.png';
                break;
        case 'Lights &amp; Action':
        case 'Lights &amp; Action Series':
                $url = '/wp-content/uploads/2018/02/HAMR-0001_Website_Design_Template_R3_Graphic-Lights-and-Action.png';
                break;
        default:
                $url = '';
                break;
        }
        return $url;
}

/*
 * This function returns the url for banner used in the home page
 * based on the category of the event.
 * @param category_text String: category name in text. For example, "Also @ Hammer".
 * @return url for the banner. For example,
 *         It returns empty string if the category does not match
 *
 * TODO Need to make sure this works on both dev and production (Maybe url can be an issue)
 */
function get_banner_url ( $category_text ) {
        $url = '';
        switch ($category_text) {
        case 'Also @ Hammer':
        case 'Also @ Hammer Series':
                $url = '/wp-content/uploads/2018/03/Also-At-Hammer-Logo.png';
                break;
        case 'ArtTech':
        case 'ArtTech Series':
                $url = '/wp-content/uploads/2018/03/ArtTech-Logo.png';
                break;
        case 'SJSU @ Hammer':
        case 'SJSU @ Hammer Series':
                $url = '/wp-content/uploads/2018/03/SJSU-At-Hammer-Logo.png';
                break;
        case 'Hammer Speaks':
        case 'Hammer Speaks Series':
                $url = '/wp-content/uploads/2018/03/Hammer-Speaks-Logo.png';
                break;
        case 'Holidays @ Hammer':
        case 'Holidays @ Hammer Series':
                $url = '/wp-content/uploads/2018/03/Holidays-At-Hammer-Logo.png';
                break;
        case 'Music Without Borders':
        case 'Music Without Borders Series':
                $url = '/wp-content/uploads/2018/03/Music-Without-Borders-Logo.png';
                break;
        case 'Lights &amp; Action':
        case 'Lights &amp; Action Series':
                $url = '/wp-content/uploads/2018/03/Lights-And-Action-Logo.png';
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
                        $style_text = 'margin-top: 3%; margin-left: 44.9%; max-width: 7%;';
                } else {
                        $style_text = 'margin-top: 3%; margin-left: -3.5%; max-width: 7%;';
                }
                break;
        case 'ArtTech':
        case 'ArtTech Series':
                if ( $is_left ) {
                        $style_text = 'margin-top: -3.5%; margin-left: 37%; max-width: 7%;';
                } else {
                        $style_text = 'margin-top: 4%; margin-left: -3.5%; max-width: 7%;';
                }
                break;
        case 'SJSU @ Hammer':
        case 'SJSU @ Hammer Series':
                if ( $is_left ) {
                        $style_text = 'margin-top: -3.8%; margin-left: 38%; max-width: 7%;';
                } else {
                        $style_text = 'margin-top: -3.8%; margin-left: 2.5%; max-width: 7%;';
                }
                break;
        case 'Hammer Speaks':
        case 'Hammer Speaks Series':
                if ( $is_left ) {
                        $style_text = 'margin-top: -3.8%; margin-left: 38%; max-width: 7%;';
                } else {
                        $style_text = 'margin-top: -3.8%; margin-left: -3.5%; max-width: 7%;';
                }
                break;
        case 'Holidays @ Hammer':
        case 'Holidays @ Hammer Series':
                if ( $is_left ) {
                        $style_text = 'margin-top: -3.5%; margin-left: 38%; max-width: 7%;';
                } else {
                        $style_text = 'margin-top: 3%; margin-left: -3.3%; max-width: 7%;';
                }
                break;
        case 'Music Without Borders':
        case 'Music Without Borders Series':
                if ( $is_left ) {
                        $style_text = 'margin-top: 3%; margin-left: 30%; max-width: 30%;';
                } else {
                        $style_text = 'margin-top: 7%; margin-left: -22%; max-width: 36%;';
                }
                break;
        case 'Lights &amp; Action':
        case 'Lights &amp; Action Series':
                if ( $is_left ) {
                        $style_text = 'margin-top: -4%; margin-left: 38%; max-width: 7%;';
                } else {
                        $style_text = 'margin-top: 2%; margin-left: -3.5%; max-width: 7%;';
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
 * This function returns styling for logo in past event list.
 * This function is very similar to get_logo_css_margin
 *
 * @param category_text String: category name in text. For example, "Also @ Hammer".
 * @return Styling for the logo. For example, "margin-top: 40px; margin-left: 304px;"
 *         It returns empty string if the category does not match
 */
function get_logo_css_margin_past_event_list ( $category_text ) {
        $style_text = '';
        switch ($category_text) {
        case 'Also @ Hammer':
        case 'Also @ Hammer Series':
                $style_text = 'margin-top: 2%; margin-left: 14%; max-width: 4%;';
                break;
        case 'ArtTech':
        case 'ArtTech Series':
                $style_text = 'margin-top: 1%; margin-left: 15%; max-width: 2%;';
                break;
        case 'SJSU @ Hammer':
        case 'SJSU @ Hammer Series':
                $style_text = 'margin-top: 1%; margin-left: 15%; max-width: 2%;';
                break;
        case 'Hammer Speaks':
        case 'Hammer Speaks Series':
                $style_text = 'margin-top: 1%; margin-left: 14.5%; max-width: 3%;';
                break;
        case 'Holidays @ Hammer':
        case 'Holidays @ Hammer Series':
                $style_text = 'margin-top: 1%; margin-left: 15%; max-width: 2%;';
                break;
        case 'Music Without Borders':
        case 'Music Without Borders Series':
                $style_text = 'margin-top: 1%; margin-left: 9%; max-width: 10%;';
                break;
        case 'Lights &amp; Action':
        case 'Lights &amp; Action Series':
                $style_text = 'margin-top: 1%; margin-left: 15%; max-width: 2%;';
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
function show_past_event ( $category="", $start_date="1/1/2017", $num_post=10 ) {

        $time = strtotime( $start_date );
        $start_date = date( 'Y-m-d H:i:s', $time );

        if ( $category == "" ) {
                $events = tribe_get_events( array(
                        'posts_per_page' => $num_post,
                        'order' => 'DESC',
                        'eventDisplay'=>'past',
                        'start_date' => $start_date,
                ));
        } else {
                $events = tribe_get_events( array(
                        'posts_per_page' => $num_post,
                        'eventDisplay'=>'past',
                        'start_date' => $start_date,
                        'order' => 'DESC',
                        'tax_query'=> array(
                                array(
                                        'taxonomy' => 'tribe_events_cat',
                                        'field' => 'slug',
                                        'terms' => $category
                                )
                        )
                ));
        }

        echo '<div class="past-events-banner background-hammerBlue margin-top-80 margin-bottom-80"><div class="marginLeft marginRight"><h1 class="color-white">PAST EVENTS</h1></div></div>';

        $counter = 1;
        foreach ( $events as $post ) {
                setup_postdata( $post );
                $postID = $post->ID;
                $category_text = tribe_get_text_categories($postID);
                $category_color = tribe_get_color_for_categories($category_text);

                echo '<div class="event-container margin-top-50">';
                  echo '<div class="event-list-img-logo-container">';
                    // Show only when category is not specified
                    if ( $category == "" ) {
                            $logo_url = get_logo_url($category_text);
                            $logo_style_text = get_logo_css_margin_past_event_list($category_text);
                            echo '<img class="event-list-logo" src="'.$logo_url.'" style="'.$logo_style_text.'">';
                    }
                    echo tribe_event_featured_image( $post->ID, null, 'medium' );
                  echo '</div>';

                  echo '<div id="event-subcontainer2-with-logo" class="margin-left-60" style="padding-right: 5%;">';
                    echo '<h2><a href="'.esc_url( tribe_get_event_link($postID) ).'">'.$post->post_title.'</a><h2>';
                     echo '<!-- subtitle -->';
                     echo '<h4 class="subtitle">';
                     echo get_post_meta($postID, 'subtitle', true);
                     echo '</h4>';
                     echo '<!-- End of subtitle -->';
                    echo '<div class="tribe-events-list-event-description tribe-events-content description entry-summary">';
                        echo '<p>'.get_the_excerpt($postID).'</p>';
                    echo'</div>';
                  echo '</div>';

                  echo '<div id="event-subcontainer3" class="margin-left-20">';
                    echo '<span style="font-size: 13px; font-weight: 700">'.tribe_get_start_date($post).'</span>';
                    echo '<a href="'.esc_url( tribe_get_event_link($postID) ).'" class="button learn-more-button" rel="bookmark" style="background-color: var('.$category_color.');">Learn More</a>';
                  echo '</div>';
                echo '</div>';
                $counter++;
        }
}

function show_past_event_sc_wrapper ( $atts ) {
        $a = shortcode_atts( array(
                'category' => '',
                'start_date' => '1/1/2017',
                'num_post' => 10,
            ), $atts );
        show_past_event($a['category'], $a['start_date'], $a['num_post']);
}

add_shortcode( 'show_past_event', 'show_past_event_sc_wrapper' );

/*
 * Display recurring event dates.
 * This functions must be called only for the recurring event.
 * To check if the event is recurring, use `tribe_is_recurring_event( $event_id )`
 *
 * @param event_id
 * @return None. This function does not return anything. It only renders recurring event dates to the browser
 */
function show_recurring_dates ( $event_id ) {
        $dates =  tribe_get_recurrence_start_dates( $event_id );
        foreach ($dates as $date) {
                $date_text = strtotime($date);
                echo '<span class="tribe-event-date-start">'.date('F j \@  g:i a', $date_text).'</span><br>';
        }

}

/*
 * Get category name based on the request uri
 *
 * @param request_uri
 * @return category name in text
 */
function get_category_from_uri ( $request_uri ) {
        $category_text = "";
        switch ($request_uri) {
                case '/also-hammer/':
                        $category_text = 'also-at-hammer';
                        break;
                case '/art-tech/':
                        $category_text = 'art-tech';
                        break;
                case '/sjsu-hammer/':
                        $category_text = 'sjsu-at-hammer';
                        break;
                case '/hammer-speaks/':
                        $category_text = 'hammer-speaks';
                        break;
                case '/holidays-hammer/':
                        $category_text = 'holidays-hammer';
                        break;
                case '/music-without-borders/':
                        $category_text = 'music-without-borders';
                        break;
                case '/lights-and-action/':
                        $category_text = 'lights-and-action';
                        break;
                default:
                        $category_text = '';
                        break;
        }
        return $category_text;
}

/*
 * Shortcode for rendering google map and location information
 * To use place this [location_map]
 */
function location_map_shortcode( $atts ){
        return '<div class="location_map_container marginLeft marginRight margin-top-80" style="display: flex;">
                <div class="location_map_info inline-block va-text-top margin-right-20">
                <div class="divTable">
                <div class="divTableBody">
                <div class="divTableRow">
                <div class="divTableCell padding-right va-top"><p><strong>Location</strong></p></div>
                <div class="divTableCell right-col"><p>Hammer Theatre Center, 101 Paseo de San Antonio, San Jose, CA 95113 Located between 2nd and 3rd St. in downtown San Jose.</p></div>
                </div>
                <div class="divTableRow">
                <div class="divTableCell padding-right va-top"><p><strong>Parking</strong></p></div>
                <div class="divTableCell right-col"><p>Convenient parking available @ 2nd &amp; San Carlos Garage $5 flat fee after 6 PM</p></div>
                </div>
                <div class="divTableRow">
                <div class="divTableCell padding-right va-top"><p><strong>Public Transit</strong></p></div>
                <div class="divTableCell right-col">
                <p class="inline-block va-text-top">Accessible by BART and VTA (2nd & Paseo De San Antonio exit is in front of the Hammer Theatre)</p>

                </div>
                </div>
                </div>
                </div>
                </div>
                <div class="location_map inline-block va-text-top">
                <iframe id="location-map-iframe" style="" src="https://www.google.com/maps/embed/v1/place?q=hammer%20theatre%20center&amp;key=AIzaSyDLTX-ZPlO7pPc0tsDiJ7_5FUsz8mNl688" width="480" height="360" frameborder="0" allowfullscreen="allowfullscreen"></iframe>
                </div>
                </div>';
}
add_shortcode( 'location_map', 'location_map_shortcode' );

function get_sub_brand_logo ( $category_text ) {
        $sjsu_at_hammer_url = 'http://hammertheatre.staging.wpengine.com/wp-content/uploads/2018/03/425x35_sjsu@hammer.jpg';
        $also_at_hammer_url = 'http://hammertheatre.staging.wpengine.com/wp-content/uploads/2018/03/425x35_also@hammer.jpg';
        $arttech_url = 'http://hammertheatre.staging.wpengine.com/wp-content/uploads/2018/03/425x35_arttech.jpg';
        $hammer_speaks_url = 'http://hammertheatre.staging.wpengine.com/wp-content/uploads/2018/03/425x35_hammerspeaks.jpg';
        $holidays_at_hammer_url = 'http://hammertheatre.staging.wpengine.com/wp-content/uploads/2018/03/425x35_holidays@hammer.jpg';
        $lights_and_action_url = 'http://hammertheatre.staging.wpengine.com/wp-content/uploads/2018/03/425x35_lights_action.jpg';
        $music_wo_borders_url = 'http://hammertheatre.staging.wpengine.com/wp-content/uploads/2018/03/425x35_musicwoborders.jpg';

        $subbrand_url = '';
        switch ($category_text) {
        case 'Also @ Hammer':
        case 'Also @ Hammer Series':
                $subbrand_url = $also_at_hammer_url;
                break;
        case 'ArtTech':
        case 'ArtTech Series':
                $subbrand_url = $arttech_url;
                break;
        case 'SJSU @ Hammer':
        case 'SJSU @ Hammer Series':
                $subbrand_url = $sjsu_at_hammer_url;
                break;
        case 'Hammer Speaks':
        case 'Hammer Speaks Series':
                $subbrand_url = $hammer_speaks_url;
                break;
        case 'Holidays @ Hammer':
        case 'Holidays @ Hammer Series':
                $subbrand_url = $holidays_at_hammer_url;
                break;
        case 'Music Without Borders':
        case 'Music Without Borders Series':
                $subbrand_url = $music_wo_borders_url;
                break;
        case 'Lights &amp; Action':
        case 'Lights &amp; Action Series':
                $subbrand_url = $lights_and_action_url;
                break;
        default:
                $subbrand_url = 'http://hammertheatre.staging.wpengine.com/wp-content/uploads/2018/03/425x35_hammernews.jpg';
                break;
        }
        return $subbrand_url;
}

function get_sub_brand_url ( $category_text ) {
    $url = '';
switch ($category_text) {
        case 'Also @ Hammer':
                $url = 'also-hammer';
                break;
        case 'ArtTech':
                $url = '/art-tech';
                break;
        case 'SJSU @ Hammer':
                $url = '/sjsu-hammer';
                break;
        case 'Hammer Speaks':
                $url = '/art-tech';
                break;
        case 'Holidays @ Hammer':
                $url = '/hammer-speaks';
                break;
        case 'Music Without Borders':
                $url = '/music-without-borders';
                break;
        case 'Lights &amp; Action':
                $url = '/lights-and-action';
                break;
        default:
                $url = 'Error';
                break;
        }
        return $url;
}

function my_theme_enqueue_styles() {

    $parent_style = 'parent-style'; // This is 'twentyseventeen-style' for the Twenty Seventeen theme.

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
    get_stylesheet_directory_uri() . '/style.css',
    array($parent_style),
    '1.2.0' );
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
