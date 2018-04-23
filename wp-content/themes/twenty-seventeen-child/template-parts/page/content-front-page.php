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

    $category_text = tribe_get_text_categories();
    $logo_url = get_logo_url($category_text);
    $is_left = ( $counter % 2 == 1 ? true : false );
    $logo_style_text = get_logo_css_margin ( $category_text, $is_left );

        // Image
        echo '<div class="va-top" id="home-event-img-'.$counter.'">';
        echo '<img class="home-event-logo" src="'.$logo_url.'" style="'.$logo_style_text.'">';
        echo tribe_event_featured_image( $post->ID, null, 'medium' );
        echo '</div>';

        echo '<div id="home-event-subcontainer-'.$counter.'">';
        echo '<h3 class="margin-bottom-50 home-event-series"><a href="'.get_sub_brand_url($category_text).'">'.$category_text.'<span style="color: var('.tribe_get_color_for_categories( tribe_get_text_categories() ).');">&#x2794;</span></a></h3>';
        echo '<h2 class="featured-event-title">'.$post->post_title.'</h2>';
        echo '<h4 class="subtitle">' . get_post_meta($post->ID, 'subtitle', true) . '</h4>';
        echo '<p id="home-event-excerpt-'.$counter.'">'.tribe_events_get_the_excerpt( null, wp_kses_allowed_html( 'post' ) ).'</p>';
        echo '<p id="home-event-date-'.$counter.'" class="margin-bottom-50">'.show_event_dates($post->ID, true).'</p>';

        echo '<a href="'.esc_url( tribe_get_event_link() ).'" class="home-event-button button width-50-percent buy-tickets-link white-text" rel="bookmark" style="width: 50%!important; background-color: var('.tribe_get_color_for_categories( tribe_get_text_categories() ).');">VIEW EVENT</a>';

        // Render ticket link in single event page
        $ticket_url = get_post_meta($post->ID, 'Ticket_Link', true);
        if ($ticket_url) {
                echo '<a class="home-event-button button buy-ticket-button-container" href="'.$ticket_url.'">BUY TICKETS</a>';
        }

        echo '</div>';

        $counter++;
 }
}
?>


<script>
	for(i = 1; i <= 4; i++){
		var leftCol = document.getElementById("home-event-leftCol-" + i);
		var rightCol = document.getElementById("home-event-rightCol-" + i);

		var eventImg = document.getElementById("home-event-img-" + i);
		var eventTextContainer = document.getElementById("home-event-subcontainer-" + i);


		if(i % 2 == 0){
			leftCol.appendChild(eventTextContainer);
			rightCol.appendChild(eventImg);
		} else {
			leftCol.appendChild(eventImg);
			rightCol.appendChild(eventTextContainer);
		}

	}
</script>

<!-- Code snippet to render 3 post on home page -->
<?php
$counter = 1;
query_posts('posts_per_page=3'); /*1, 2*/
if ( have_posts() ) while ( have_posts() ) : the_post();
	echo '<div id="featured-post-'.$counter.'">';

	foreach((get_the_category()) as $categoryObj) {
		if(empty(get_sub_brand_logo( $categoryObj->cat_name ))) {
			// If empty, Hammer News
			echo '<h2 style="background-color: var(--gray);" class="featured-series">';
			echo $categoryObj->cat_name;
			echo '</h2>';
		} else {
			echo '<img src="'.get_sub_brand_logo( $categoryObj->cat_name ).'">';
		}

	break;
	}

    echo '<div id="featured-img-'.$counter.'"">';
    $featured_link = get_post_meta($post->ID, 'Featured_Post_Link', true);
    echo '<a href="'.$featured_link.'" target="_blank">';
	echo the_post_thumbnail();
	echo '</div>';
	echo '</a>';

  echo '<h2 class="featured-title">';
	echo the_title();
	echo '</h2>';

  echo '<div class="featured-post-excerpt">';
	echo the_content();
	echo '</div>';

	echo '</div>';
	$counter++;
endwhile;
wp_reset_query();?>

<script>
	var homeButton = document.getElementById("menu-item-379");
	homeButton.innerHTML = '<a href="/"><img src="http://hammertheatre.staging.wpengine.com/wp-content/uploads/2017/11/cropped-cropped-Hammer_logo_Artboard-46-copy@3x-1.png" /></a>';





	for(i = 1; i < 4; i++){
		var postColumn = document.getElementById("featured-col-" + i);
		var post = document.getElementById("featured-post-" + i);
		postColumn.appendChild(post);
	}

        function PopupCenter(url, title, w, h) {
            // Fixes dual-screen position                         Most browsers      Firefox
            var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
            var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

            var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
            var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

            var left = ((width / 2) - (w / 2)) + dualScreenLeft;
            var top = ((height / 2) - (h / 2)) + dualScreenTop;
            var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

            // Puts focus on the newWindow
            if (window.focus) {
              newWindow.focus();
            }
          }




</script>
