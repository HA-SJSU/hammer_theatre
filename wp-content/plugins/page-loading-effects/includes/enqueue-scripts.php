<?php
/**
 * Register and enqueue a stylesheets and scripts in the Frontend.
 *
 * @see WP Enqueue Scripts Docs (https://codex.wordpress.org/Plugin_API/Action_Reference/wp_enqueue_scripts)
 * @todo Replace only if you're creating your own Plugin
 * @todo ple - Find all and replace text
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Enqueue a CSS in the WordPress Frontend.
 */
function ple_style() {
        wp_register_style( 
          'ple-style', // ID for Enqueuing
          PAGE_LOADING_EFFECTS_URL. 'assets/css/style.css', // URI define( 'PAGE_LOADING_EFFECTS_URL', plugin_dir_url( __FILE__ )
          false, // shows at header styles
          '1.0.0' // version
        );
        wp_enqueue_style( 'ple-style' ); // Enqueuing this CSS file
}
add_action( 'wp_enqueue_scripts', 'ple_style' );

/**
 * Enqueue a JS in the WordPress Frontend.
 */
function ple_script() {
    wp_register_script( 
      'ple-script', // ID for Enqueuing
      PAGE_LOADING_EFFECTS_URL. 'assets/js/ple.preloader.min.js', 
      // array('jquery'), // jQuery Dependency
      '1.0.2', 
      false ); // shows at the header scripts
    wp_enqueue_script( 'ple-script' ); // Enqueuing this CSS file
}
add_action( 'wp_enqueue_scripts', 'ple_script' );

/**
 * Get Options Fixes
 * @param $db_field string
 * @param $default string
 */
function ple_display($db_field, $default){
  $get_option = get_option($db_field);
  if(empty($get_option)){
    $get_option = $default;
  }
  return $get_option;
}

function ple_wp_head_hook() {
  $disabled = get_option('ple_option_1');
  if(empty($disabled) && $disabled!=1):
  $ple_option_3 = ple_display('ple_option_3', 99);
  $ple_option_5 = ple_display('ple_option_5', '#ffffff');
?>
<script type="text/javascript">
  plePreloader.speed = "<?php echo str_replace(array('ms','MS',' '), '', get_option('ple_option_2')); ?>";
  if(!plePreloader.speed){
    plePreloader.speed=4000;
  }
  plePreloader.elem = "ple-loader-wraps<?php echo $ple_option_3; ?>";
  plePreloader.elemInner = "<?php
  if($ple_option_3==4){
    echo preg_replace('/^\s+|\n|\r|\s+$/m', '', get_option('ple_option_6'));
  } elseif($ple_option_3==2){
    echo '<div class=\"spinner\"><div class=\"dot1\"></div><div class=\"dot2\"></div></div>';

  } elseif($ple_option_3==3){
    echo '<div class=\"spinner\"></div>';
  } else{

  }
  ?>";
  plePreloader.kicks();
</script>
<style type="text/css">
  #ple-loader-wraps<?php echo $ple_option_3; ?> {
    background: <?php echo ple_display('ple_option_4', '#2c3e50'); ?>;
  }
  <?php
      if($ple_option_3 ==4){
        echo '#ple-loader-wraps'.$ple_option_3.' #ple-animates {
     background:inherit;
  }

  ';
  echo get_option('ple_option_7', '');

      } elseif($ple_option_3==2){
        echo '#ple-loader-wraps'.$ple_option_3.' .spinner .dot1, #ple-loader-wraps'.$ple_option_3.' .spinner .dot2{
     background:'.$ple_option_5.';}';

      } elseif($ple_option_3==3){
        $get_hex = $ple_option_5;
        $rgb = '';
        if(isset($get_hex)){
          list($r, $g, $b) = sscanf($get_hex, "#%02x%02x%02x");
          $rgb = "$r, $g, $b";
          echo '#ple-loader-wraps'.$ple_option_3.' .spinner {
            border-top-color:rgba('.$rgb.', 0.95);
            border-bottom-color:rgba('.$rgb.', 0.25);
            border-left-color:rgba('.$rgb.', 0.95);
            border-right-color:rgba('.$rgb.', 0.25);}';
        }
      }
      else{
        echo '#ple-loader-wraps'.$ple_option_3.' #ple-animates {
     background:'.$ple_option_5.';}';
      }
  ?>

</style>
<?php
  endif;
}
add_action( 'wp_head', 'ple_wp_head_hook' );

function ple_wp_footer_hook(){
   $disabled = get_option('ple_option_1');
  if(empty($disabled) && $disabled!=1):
?>
<script type="text/javascript">
  jQuery(document).ready(function($) {
    if ($("#ple-animates").length > 0 && $("#ple-animates").css("display") != "none") {
      $(window).load(function() {   
        $("#ple-loader-wraps<?php echo get_option('ple_option_3', 1); ?>").delay(450).fadeOut("slow");
        $("#ple-animates").fadeOut();         
      });
    }
  })
</script>
<?php
  endif;
}
add_action( 'wp_footer', 'ple_wp_footer_hook' );


