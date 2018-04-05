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
