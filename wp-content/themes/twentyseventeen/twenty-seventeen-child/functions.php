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
        switch (true) {
        case stristr($category_text, 'Also @ Hammer'):
                $color = '--alsoAtHammer';
                break;
        case 'Art-Tech':
                $color = '--artTech';
                break;
        case 'SJSU @ Hammer':
                $color = '--sjsuAtHammer';
                break;
        case 'Hammer Speaks':
                $color = '--hammerSpeaks';
                break;
        case 'Holidays @ Hammer':
                $color = '--holidaysAtHammer';
                break;
        case 'Music Without Borders':
                $color = '--musicWithoutBorders';
                break;
        default:
                $color = 'gray';
                break;
        }
        return $color;
}




