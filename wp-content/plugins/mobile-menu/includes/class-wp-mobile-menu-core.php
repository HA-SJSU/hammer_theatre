<?php

if ( ! class_exists( 'WP_Mobile_Menu' ) ) {
	die;
}
class WP_Mobile_Menu_Core {
	public function __construct() {
	}

	/**
	 * Init WP Mobile Menu
	 *
	 * @since 1.0
	 */
	public function add_body_class() {

			add_action( 'body_class', function ( $classes ) {
				$titan = TitanFramework::getInstance( 'mobmenu' );
				$display_type = $titan->getOption( 'menu_display_type' );

				if ( 'slideout-over' === $display_type ) {
					$menu_display_type = 'mob-menu-slideout-over';
				} else {
					$menu_display_type = 'mob-menu-slideout';
				}

				$classes[] = $menu_display_type;
				return $classes;
			} );

	}

	/***
	 * Frontend Scripts.
	 */
	public function frontend_enqueue_scripts() {

		if ( ! $this->is_page_menu_disabled() ) {
			wp_register_script( 'mobmenujs', plugins_url( 'js/mobmenu.js', __FILE__ ), array( 'jquery' ) );
			wp_enqueue_script( 'mobmenujs' );
			wp_enqueue_style( 'cssmobmenu-icons', plugins_url( 'css/mobmenu-icons.css', __FILE__ ) );
			// Filters.
			add_filter( 'wp_head', array( $this, 'load_dynamic_css_style' ) );
		}

	}

	/***
	 * Load dynamic css
	 */
	public function load_dynamic_css_style() {
		if ( ! $this->is_page_menu_disabled() ) {
			include_once 'dynamic-style.php';
		}
	}

	/***
	 * Dismiss the WP Mobile Menu Pro Banner
	 */
	public function dismiss_wp_mobile_notice() {
		if ( check_ajax_referer( 'wp-mobile-menu-security-nonce', 'security' ) ) {
			update_option( 'wp_mobile_menu_banner_dismissed', 'yes' );
		}
		wp_die();
	}

	/***
	 * Build the icons HTML
	 */
	public function get_icons_html() {
		if ( isset( $_POST['menu_item_id'] ) ) {
			$menu_item_id = absint( $_POST['menu_item_id'] );
		}
		if ( isset( $_POST['menu_id'] ) ) {
			$menu_id = absint( $_POST['menu_id'] );
		}
		if ( isset( $_POST['menu_title'] ) ) {
			$menu_title = $_POST['menu_title'];
		}
		if ( isset( $_POST['full_content'] ) ) {
			$full_content = $_POST['full_content'];
		}
		$seleted_icon = get_post_meta( $menu_item_id, '_mobmenu_icon', true );

		if ( ! empty( $seleted_icon ) ) {
			$selected = ' data-selected-icon="' . $seleted_icon . '" ';
		} else {
			$selected = '';
		}

		$icons = $this->get_icons_list();

		if ( 'yes' === $full_content ) {
			$output = '<div class="mobmenu-icons-overlay"></div><div class="mobmenu-icons-content" data-menu-id="' . $menu_id . '" data-menu-item-id="' . $menu_item_id . '">';
			$output .= '<div id="mobmenu-modal-header"><h2>' . $menu_title . ' - Menu Item Icon</h2><div class="mobmenu-icons-close-overlay"><span class="mobmenu-item mobmenu-close-overlay mob-icon-cancel-7"></span></div>';
			$output .= '<div class="mobmenu-icons-search"><input type="text" name="mobmenu_search_icons" id="mobmenu_search_icons" value="" placeholder="Search"><span class="mobmenu-item mob-icon-search-7"></span></div>';
			$output .= '<div class="mobmenu-icons-remove-selected">' . __( 'Remove Icon Selection', 'mob-menu-lang' ) . '</div>';
			$output .= '</div><div id="mobmenu-modal-body"><div class="mobmenu-icons-holder" ' . $selected . '>';

			// Loop through all the icons to create the icons list.
			foreach ( $icons as $icon ) {
				$output .= '<span class="mobmenu-item mob-icon-' . $icon . '" data-icon-key="' . $icon . '"></span>';
			}
			$output .= '</div></div>';
		} else {
			$output = '<div class="mobmenu-icons-holder" ' . $selected . ' data-title="' . esc_attr( $menu_title ) . '" - Menu Item Icon" >';
		}

		echo $output;
		wp_die();
	}

	/***
	 * Build the WP Mobile Menu Html Markup.
	 */
	public function load_menu_html_markup() {
		global  $mm_fs ;
		$left_logged_in_user = false;
		$right_logged_in_user = false;
		$titan = TitanFramework::getInstance( 'mobmenu' );
		$menu_display_type = 'mob-menu-slideout';
		$output = '';
		$output .= '<div class="mobmenu-overlay"></div>';

		// Check if Header Menu Toolbar is enabled.
		if ( $titan->getOption( 'enabled' ) && ! $this->is_page_menu_disabled() ) {
			$header_text = $titan->getOption( 'header_text' );
			if ( '' === $header_text ) {
				$header_text = get_bloginfo();
			}

			$sticky_el_data_detach = '';
			if ( $titan->getOption( 'sticky_elements' ) ) {
				$sticky_el_data_detach = 'data-detach-el="' . $titan->getOption( 'sticky_elements' ) . '"';
			}
			$output .= '<div class="mob-menu-header-holder mobmenu" ' . $sticky_el_data_detach . '>';

			if ( $titan->getOption( 'enable_left_menu' ) && ! $left_logged_in_user ) {
				$left_menu_text = '';
				if ( '' !== $titan->getOption( 'left_menu_text' ) ) {
					$left_menu_text .= '<span class="left-menu-icon-text">' . $titan->getOption( 'left_menu_text' ) . '</span>';
				}

				if ( $titan->getOption( 'left_menu_icon_action' ) ) {
					$output .= '<div  class="mobmenul-container"><a href="#" class="mobmenu-left-bt">';
				} else {

					if ( $titan->getOption( 'left_icon_url_target' ) ) {
						$left_icon_url_target = '_self';
					} else {
						$left_icon_url_target = '_blank';
					}

					$output .= '<div  class="mobmenul-container"><a href="' . $titan->getOption( 'left_icon_url' ) . '" target="' . $left_icon_url_target . '" id="mobmenu-center">';
				}

				$left_icon_image = wp_get_attachment_image_src( $titan->getOption( 'left_menu_icon' ) );
				$left_icon_image = $left_icon_image[0];

				if ( ! $titan->getOption( 'left_menu_icon_opt' ) || '' === $left_icon_image ) {
					$output .= '<i class="mob-icon-' . $titan->getOption( 'left_menu_icon_font' ) . ' mob-menu-icon"></i><i class="mob-icon-cancel mob-cancel-button"></i>';
				} else {
					$output .= '<img src="' . $left_icon_image . '" alt="' . __( 'Left Menu Icon', 'mob-menu-lang' ) . '">';
				}

				$output .= $left_menu_text;
				$output .= '</a></div>';
			}

			$logo_img = wp_get_attachment_image_src( $titan->getOption( 'logo_img' ), 'full' );
			$logo_img = $logo_img[0];

			// Premium options.
			if ( $mm_fs->is__premium_only() && $titan->getOption( 'logo_img_retina' ) ) {
				$logo_img_retina = wp_get_attachment_image_src( $titan->getOption( 'logo_img_retina' ), 'full' );
				$logo_img_retina = $logo_img_retina[0];
				$logo_img_retina_metadata = wp_get_attachment_metadata( $titan->getOption( 'logo_img_retina' ) );
				$logo_img_retina_width = intval( $logo_img_retina_metadata['width'], 10 ) / 2;
			}

			if ( $titan->getOption( 'disabled_logo_url' ) ) {
				$logo_url = '<h3 class="headertext">';
				$logo_url_end = '</h3>';
			} else {

				if ( '' === $titan->getOption( 'logo_url' ) ) {
					$logo_url = get_bloginfo( 'url' );
				} else {
					$logo_url = $titan->getOption( 'logo_url' );
				}

				$logo_url_end = '</a>';
				$logo_url = '<a href="' . $logo_url . '" class="headertext">';
			}

			$output .= '<div class="mob-menu-logo-holder">' . $logo_url;
			$header_branding = $titan->getOption( 'header_branding' );
			$logo_output = '';

			if ( ('logo' === $header_branding || 'logo-text' === $header_branding || 'text-logo' === $header_branding) && '' !== $logo_img ) {
				$logo_output = '<img class="mob-standard-logo" src="' . $logo_img . '"  alt=" ' . __( 'Logo Header Menu', 'mob-menu-lang' ) . '">';
			}

			$header_text = '<span>' . $header_text . '</span>';

			if ( $header_branding ) {
				switch ( $header_branding ) {
					case 'logo':
						$output .= $logo_output;
						break;
					case 'text':
						$output .= $header_text;
						break;
					case 'logo-text':
						$output .= $logo_output;
						$output .= $header_text;
						break;
					case 'text-logo':
						$output .= $header_text;
						$output .= $logo_output;
						break;
				}
			}
			$output .= $logo_url_end . '<h1>Hello World!</h1></div>';
			/* BEGIN CUSTOM PHP */
			$output .= '<div id="mob-sjsu-logo"><img src="http://hammertheatre.staging.wpengine.com/wp-content/themes/twenty-seventeen-child/assets/images/sjsu-logo-gold.png" alt="SJSU logo" width="45px" height="25px"></div>';
			/* END CUSTOM PHP */
			if ( $titan->getOption( 'enable_right_menu' ) && ! $right_logged_in_user ) {
				$right_menu_text = '';
				if ( '' !== $titan->getOption( 'right_menu_text' ) ) {
                                        $right_menu_text .= '<span class="right-menu-icon-text">' . $titan->getOption( 'right_menu_text' ) . '</span>';
				}

				if ( $titan->getOption( 'right_menu_icon_action' ) ) {
                                        /* BEGIN CUSTOM Box office and facebook instagram icons */
                                        $output .= '<div class="mobmenur-container">
                                                        <span class="right-menu-icon-text" style="float: left; font-size: 1.25rem !important; margin-top: 7px;">BOX OFFICE: (408) 924-8501</span>
                                                        <a href="https://m.facebook.com/HammerTheatreCenter/" style="margin: 0 0 5px 5px;">
							    <img src="http://hammertheatre.staging.wpengine.com/wp-content/themes/twenty-seventeen-child/assets/images/facebook-icon.png" alt="Facebook" width="18px" height="18px">
                                                        </a>
                                                        <a href="https://www.instagram.com/hammertheatrecenter/" style="margin: 0 0 5px 5px;">
                                                            <img src="http://hammertheatre.staging.wpengine.com/wp-content/themes/twenty-seventeen-child/assets/images/instagram-icon.png" alt="Instagram" width="18px" height="18px">
                                                        </a>
                                                        <a href="https://twitter.com/hammer_sjsu?lang=en" style="margin: 0 0 5px 5px;">
                                                            <img src="http://hammertheatre.staging.wpengine.com/wp-content/themes/twenty-seventeen-child/assets/images/twitter-icon.png" alt="Twitter" width="18px" height="18px">
                                                        </a>
                                                        <a href="#" class="mobmenu-right-bt">';
                                        /* END CUSTOM Box office and facebook instagram icons */
				} else {

					if ( $titan->getOption( 'right_icon_url_target' ) ) {
						$right_icon_url_target = '_self';
					} else {
						$right_icon_url_target = '_blank';
					}

					$output .= '<div  class="mobmenur-container"><a href="' . $titan->getOption( 'right_icon_url' ) . '" target="' . $right_icon_url_target . '">';
				}

				$right_icon_image = wp_get_attachment_image_src( $titan->getOption( 'right_menu_icon' ) );
				$right_icon_image = $right_icon_image[0];

				if ( ! $titan->getOption( 'right_menu_icon_opt' ) || '' === $right_icon_image ) {
					$output .= '<i class="mob-icon-' . $titan->getOption( 'right_menu_icon_font' ) . ' mob-menu-icon"></i><i class="mob-icon-cancel mob-cancel-button"></i>';
				} else {
					$output .= '<img src="' . $right_icon_image . '" alt="' . __( 'Right Menu Icon', 'mob-menu-lang' ) . '">';
				}

				$output .= $right_menu_text;
				$output .= '</a></div>';
			}

			$output .= '</div>';
			echo $output;

			if ( $titan->getOption( 'enable_left_menu' ) && ! $left_logged_in_user ) {
				$mobmenu_parent_link = '';
				if ( $titan->getOption( 'left_menu_parent_link_submenu' ) ) {
					$mobmenu_parent_link = 'mobmenu-parent-link';
				}
				?>

				<div class="mob-menu-left-panel mobmenu <?php echo $mobmenu_parent_link; ?> ">
					<div class="mobmenu_content">
				<?php

				if ( is_active_sidebar( 'mobmlefttop' ) ) {
					?>
					<ul class="leftmtop">
						<?php dynamic_sidebar( 'Left Menu Top' ); ?>
					</ul>
				<?php
				}

				// Grab the current left menu.
				$current_left_menu = $titan->getOption( 'left_menu' );

				// Display the left menu.
				wp_nav_menu( array(
					'menu'        => $current_left_menu,
					'items_wrap'  => '<ul id="mobmenuleft">%3$s</ul>',
					'fallback_cb' => false,
					'depth'       => 2,
					'walker'      => new WP_Mobile_Menu_Walker_Nav_Menu( 'left' ),
				) );

				// Check if the Left Menu Bottom Widget has any content.
				if ( is_active_sidebar( 'mobmleftbottom' ) ) {
					?>
						<ul class="leftmbottom">
							<?php dynamic_sidebar( 'Left Menu Bottom' ); ?>
						</ul>
				<?php
				}

				?>

				</div><div class="mob-menu-left-bg-holder"></div></div>

			<?php
			}

			if ( $titan->getOption( 'enable_right_menu' ) && ! $right_logged_in_user ) {
				$mobmenu_parent_link = '';
				if ( $titan->getOption( 'right_menu_parent_link_submenu' ) ) {
					$mobmenu_parent_link = 'mobmenu-parent-link';
				}
				?>
				<!--  Right Panel Structure -->
				<div class="mob-menu-right-panel mobmenu <?php echo $mobmenu_parent_link; ?> ">
					<div class="mobmenu_content">

			<?php
			// Check if the Right Menu Top Widget has any content.
			if ( is_active_sidebar( 'mobmrighttop' ) ) {
			?>
				<ul class="rightmtop">
					<?php dynamic_sidebar( 'Right Menu Top' ); ?>
				</ul>
			<?php
			}
			?>

		<?php
		// Grab the select menu.
		$current_right_menu = $titan->getOption( 'right_menu' );

		// Display the right menu.
		wp_nav_menu( array(
			'menu'        => $current_right_menu,
			'items_wrap'  => '<ul id="mobmenuright">%3$s</ul>',
			'fallback_cb' => false,
			'depth'       => 2,
			'walker'      => new WP_Mobile_Menu_Walker_Nav_Menu( 'right' ),
		) );

		// Check if the Right Menu Bottom Widget has any content.
		if ( is_active_sidebar( 'mobmrightbottom' ) ) {
			?>
		<ul class="rightmbottom">
			<?php dynamic_sidebar( 'Right Menu Bottom' ); ?>
		</ul>
		<?php
		}
		?>

			</div><div class="mob-menu-right-bg-holder"></div></div>

		<?php
			}
		}
	}

	public function save_menu_item_icon() {

		if ( isset( $_POST['menu_item_id'] ) ) {
			$menu_item_id = absint( esc_attr( $_POST['menu_item_id'] ) );
			$menu_item_icon = esc_attr( $_POST['menu_item_icon'] );
			if ( $menu_item_id > 0 ) {
				update_post_meta( $menu_item_id, '_mobmenu_icon', $menu_item_icon );
			}
			wp_send_json_success();
		}
	}

	// Register Sidebar Menu Widgets.
	public function register_sidebar() {

		$args = array(
			'name'          => 'Left Menu Top',
			'id'            => 'mobmlefttop',
			'description'   => '',
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget'  => '</li>',
			'before_title'  => '<h2 class="widgettitle">',
			'after_title'   => '</h2>',
		);
		register_sidebar( $args );

		$args = array(
			'name'          => 'Left Menu Bottom',
			'id'            => 'mobmleftbottom',
			'description'   => '',
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget'  => '</li>',
			'before_title'  => '<h2 class="widgettitle">',
			'after_title'   => '</h2>',
		);
		register_sidebar( $args );

		$args = array(
			'name'          => 'Right Menu Top',
			'id'            => 'mobmrighttop',
			'description'   => '',
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '<h2 class="widgettitle">',
			'after_title'   => '</h2>',
		);
		register_sidebar( $args );

		$args = array(
			'name'          => 'Right Menu Bottom',
			'id'            => 'mobmrightbottom',
			'description'   => '',
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget'  => '</li>',
			'before_title'  => '<h2 class="widgettitle">',
			'after_title'   => '</h2>',
		);
		register_sidebar( $args );
	}

	// Check if WP Mobile Menu should be disabled in this page.
	public function is_page_menu_disabled() {
		global  $mm_fs ;
		global  $wp_query ;
		$titan = TitanFramework::getInstance( 'mobmenu' );

		// Premium options.
		if ( $mm_fs->is__premium_only() && $titan->getOption( 'enabled' ) ) {
			$current_id = 0;
			if ( isset( $wp_query->post ) ) {
				$current_id = $wp_query->post->ID;
			}

			if ( ! $titan->getOption( 'disable_menu_pages' ) ) {
				return false;
			} else {
				return in_array( $current_id, $titan->getOption( 'disable_menu_pages' ) );
			}
		} else {

			if ( $titan->getOption( 'enabled' ) ) {
				return false;
			} else {
				return true;
			}
		}

	}

	public function get_icons_list() {
		global  $mm_fs ;
		$icons_base = array(
			'menu',
			'menu-2',
			'menu-3',
			'menu-1',
			'menu-outline',
			'plus',
			'user-1',
			'star-1',
			'ok-1',
			'ok-circled',
			'ok-circled2',
		);
		return $icons_base;
	}
}
