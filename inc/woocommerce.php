<?php
/**
 * martialwc functions and definitions related to WooCommerce.
 *
 * @package WebAtome
 * @subpackage martialwc
 * @since martialwc 1.0.1
 */

add_filter( 'woocommerce_enqueue_styles', '__return_false' );
add_filter( 'woocommerce_product_variation_title_include_attributes', '__return_false' );

/* Removes woocommerce_breadcrumbs function from woocommerce_before_main_content hook */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

// Removes product-thumbnail hook from loop
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
// Removes sales-tag
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );

// Adds our own product-thumbnail to loop
add_action( 'woocommerce_before_shop_loop_item_title', 'martialwc_template_loop_product_thumbnail', 11 );

// Removes link end
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

// Adds the wishlist button
//add_action( 'woocommerce_after_shop_loop_item', 'martialwc_template_loop_add_to_wishlist', 10 );

// Single Page - Rating
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 11 );

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

add_action( 'woocommerce_after_single_product', 'woocommerce_output_related_products', 20 );


add_action( 'woocommerce_before_main_content', 'martialwc_wrapper_start', 10 );
add_action( 'woocommerce_after_main_content', 'martialwc_wrapper_end', 10 );

function woocommerce_breadcrumb_defaults_custom(){
	return array(
		'delimiter'   => '<span class="sep"><i class="fa fa-chevron-right"></i></span>',
		'home'        => _x( 'Home', 'breadcrumb', 'woocommerce' )
	);
}
add_filter('woocommerce_breadcrumb_defaults', 'woocommerce_breadcrumb_defaults_custom');

function martialwc_wrapper_start() {
	echo '<div id="primary">';
}

function martialwc_wrapper_end() {
	echo '</div>';
}

add_action( 'widgets_init', 'martialwc_woocommerce_widgets_init' );

/**
 * Register widget area related to WooCommerce.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function martialwc_woocommerce_widgets_init() {

	// Register sidebar for WooCommerce Pages
	register_sidebar( array(
		'name'            => esc_html__( 'Shop Sidebar', 'martialwc' ),
		'id'              => 'martialwc_woocommerce_sidebar',
		'description'     => esc_html__( 'Widget area for WooCommerce Pages.', 'martialwc' ),
		'before_widget'   => '<div id="%1$s" class="widget %2$s">',
		'after_widget'    => '</div>',
		'before_title'    => '<h4 class="widget-title"><span>',
		'after_title'     => '</span></h4>'
	) );

	// Register Widgets using WooCommerce data
	/*register_widget( "martialwc_woocommerce_full_width_promo_widget" );
	register_widget( "martialwc_woocommerce_product_carousel" );
	register_widget( "martialwc_woocommerce_product_grid" );
	register_widget( "martialwc_woocommerce_product_slider" );
	register_widget( "martialwc_woocommerce_vertical_promo_widget" );*/
}

/**
 * Register WooCommerce related Theme Settings
 *
 */
function martialwc_woocommerce_settings_register($wp_customize) {

	// WooCommerce Category Color Options
	$wp_customize->add_panel( 'martialwc_woocommerce_panel', array(
		'priority'     => 1000,
		'title'        => esc_html__( 'WooCommerce Settings', 'martialwc' ),
		'capability'   => 'edit_theme_options',
		'description'  => esc_html__( 'Change WooCommerce settings related to theme.', 'martialwc' )
	));

	// Header My Account Link
	$wp_customize->add_setting( 'martialwc_header_ac_btn', array(
			'default'              => '',
			'capability'           => 'edit_theme_options',
			'sanitize_callback'    => 'martialwc_sanitize_checkbox',
		)
	);

	$wp_customize->add_control( 'martialwc_header_ac_btn', array(
			'label'     => esc_html__( 'Enable My Account Button', 'martialwc' ),
			'section'   => 'martialwc_header_integrations',
			'type'      => 'checkbox',
			'priority'  => 10
		)
	);

	// Header Currency Info
	$wp_customize->add_setting( 'martialwc_header_currency', array(
			'default'              => '',
			'capability'           => 'edit_theme_options',
			'sanitize_callback'    => 'martialwc_sanitize_checkbox',
		)
	);

	$wp_customize->add_control( 'martialwc_header_currency', array(
			'label'     => esc_html__( 'Enable Currency Symbol', 'martialwc' ),
			'section'   => 'martialwc_header_integrations',
			'type'      => 'checkbox',
			'priority'  => 20
		)
	);

	$wp_customize->add_section( 'martialwc_woocommerce_category_color_setting', array(
		'priority' => 1,
		'title'    => esc_html__( 'Category Color Settings', 'martialwc' ),
		'panel'    => 'martialwc_woocommerce_panel'
	));

	$priority = 1;
	$categories = get_terms( 'product_cat' ); // Get all WooCommerce Categories
	$wp_category_list = array();

	foreach ($categories as $category_list ) {

		$wp_customize->add_setting( 'martialwc_woocommerce_category_color_'.$category_list->term_id,
			array(
				'default'              => '',
				'capability'           => 'edit_theme_options',
				'sanitize_callback'    => 'martialwc_hex_color_sanitize',
				'sanitize_js_callback' => 'martialwc_color_escaping_sanitize'
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize, 'martialwc_woocommerce_category_color_'.$category_list->term_id,
				array(
					'label'    => sprintf(__(' %s', 'martialwc' ), $category_list->name ),
					'section'  => 'martialwc_woocommerce_category_color_setting',
					'settings' => 'martialwc_woocommerce_category_color_'.$category_list->term_id,
					'priority' => $priority
				)
			)
		);
		$priority++;
	}

	// WooCommerce Pages layout
	$wp_customize->add_section(
		'martialwc_woocommerce_global_layout_section',
		array(
			'priority'  => 10,
			'title'     => esc_html__( 'Archive Page Layout', 'martialwc' ),
			'panel'     => 'martialwc_woocommerce_panel'
		)
	);

	$wp_customize->add_setting(
		'martialwc_woocommerce_global_layout',
		array(
			'default'           => 'no_sidebar_full_width',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'martialwc_sanitize_radio'
		)
	);

	$wp_customize->add_control(
		new martialwc_Image_Radio_Control (
			$wp_customize,
			'martialwc_woocommerce_global_layout',
			array(
				'label'   => esc_html__( 'This layout will be reflected in archives, categories, search page etc. of WooCommerce.', 'martialwc' ),
				'section' => 'martialwc_woocommerce_global_layout_section',
				'type'    => 'radio',
				'choices' => array(
					'right_sidebar'               => martialwc_ADMIN_IMAGES_URL . '/right-sidebar.png',
					'left_sidebar'                => martialwc_ADMIN_IMAGES_URL . '/left-sidebar.png',
					'no_sidebar_full_width'       => martialwc_ADMIN_IMAGES_URL . '/no-sidebar-full-width-layout.png',
					'no_sidebar_content_centered' => martialwc_ADMIN_IMAGES_URL . '/no-sidebar-content-centered-layout.png'
				)
			)
		)
	);

	// WooCommerce Product Page Layout
	$wp_customize->add_section(
		'martialwc_woocommerce_product_layout_section',
		array(
			'priority'  => 20,
			'title'     => esc_html__( 'Product Page Layout', 'martialwc' ),
			'panel'     => 'martialwc_woocommerce_panel'
		)
	);

	$wp_customize->add_setting(
		'martialwc_woocommerce_product_layout',
		array(
			'default'           => 'right_sidebar',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'martialwc_sanitize_radio'
		)
	);

	$wp_customize->add_control(
		new martialwc_Image_Radio_Control (
			$wp_customize,
			'martialwc_woocommerce_product_layout',
			array(
				'label'   => esc_html__( 'This layout will be reflected in product page of WooCommerce.', 'martialwc' ),
				'section' => 'martialwc_woocommerce_product_layout_section',
				'type'    => 'radio',
				'choices' => array(
					'right_sidebar'               => martialwc_ADMIN_IMAGES_URL . '/right-sidebar.png',
					'left_sidebar'                => martialwc_ADMIN_IMAGES_URL . '/left-sidebar.png',
					'no_sidebar_full_width'       => martialwc_ADMIN_IMAGES_URL . '/no-sidebar-full-width-layout.png',
					'no_sidebar_content_centered' => martialwc_ADMIN_IMAGES_URL . '/no-sidebar-content-centered-layout.png'
				)
			)
		)
	);

	$wp_customize->add_setting(
		'martialwc_woocommerce_product_thumb_mask',
		array(
			'default'           => '',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'martialwc_sanitize_checkbox'
		)
	);

	$wp_customize->add_control(
		'martialwc_woocommerce_product_thumb_mask',
		array(
			'label'   => esc_html__( 'Check to hide hover effect on Product Images in WooCommerce Archive Pages.', 'martialwc' ),
			'section' => 'martialwc_woocommerce_global_layout_section',
			'type'    => 'checkbox',
		)
	);

}

add_action( 'customize_register', 'martialwc_woocommerce_settings_register' );


if ( ! function_exists( 'martialwc_template_loop_product_thumbnail' ) ) {

	/**
	 * Get the product thumbnail, or the placeholder if not set.
	 *
	 * @subpackage	Loop
	 * @param string $size (default: 'shop_catalog')
	 * @return string
	 */
	function martialwc_template_loop_product_thumbnail() {
		global $product, $post;

		$size = 'shop_catalog';

		$image_id = get_post_thumbnail_id($post->ID);
		$image_url = wp_get_attachment_image_src($image_id, $size, false); ?>
		<figure class="products-thumbnail">
			<a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>" alt="<?php the_title(); ?>">
				<div class="brut">
					<img src="<?php echo esc_url( $image_url[0] ); ?>">
					<div class="sep"></div>
					<div class="sub">
						<?php
							do_action( 'woocommerce_shop_loop_item_title' );
							do_action( 'woocommerce_after_shop_loop_item_title' );
						?>
					</div>
				</div>
				<div class="hover">
					<div class="bg">
						<div class="cercle target">afficher l'article</div>
					</div>
					<div class="info">
						<?php
							do_action( 'woocommerce_shop_loop_item_title' );
							do_action( 'woocommerce_after_shop_loop_item_title' );
						?>
						<div class="colors">
							<div class="color-title">
								Couleur
							</div>
							<div class="color-content">
								<?php foreach(explode("|", $product->get_attribute("color")) as $color): ?>
									<span class="color <?php echo martialwc_slugify($color); ?>"></span>
								<?php endforeach; ?>
							</div>
							
						</div>
					</div>
					
				</div>
			</a>
		</figure>
		<?php
	}
}

if ( ! function_exists( 'martialwc_template_loop_add_to_wishlist' ) ) {

	/**
	 * Get the add-to-wishlist button.
	 *
	 * @subpackage	Loop
	 * @return string
	 */
	function martialwc_template_loop_add_to_wishlist() {
		if( function_exists( 'YITH_WCWL' ) ){
			echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
		}
	}
}

if (  ! function_exists( 'woocommerce_template_loop_product_title' ) ) {

	/**
	 * Show the product title in the product loop. By default this is an H3.
	 */
	function woocommerce_template_loop_product_title() {
		echo '<h3 class="title"><a href=' . esc_url(get_the_permalink()) . '>' . esc_html(get_the_title()) . '</a></h3>';
	}
}

add_filter( 'body_class', 'martialwc_woocommerce_body_class' );

if (  ! function_exists( 'martialwc_woocommerce_body_class' ) ) {

	/**
	 * Adds class to body based on page template
	 */
	function martialwc_woocommerce_body_class( $woocommerce_class ) {
		if ( is_page_template( 'page-templates/template-wc-collection.php' ) ) {
			// add 'woocommerce-page' class to the $classes array
			$woocommerce_class[] = 'woocommerce-page';
			// return the $woocommerce_class array
		}
		return $woocommerce_class;
	}
}

add_filter('loop_shop_columns', 'martialwc_woocommerce_loop_columns');

if (  ! function_exists( 'martialwc_woocommerce_loop_columns' ) ) {

	/**
	 * Change product per row to 4
	 */
	function martialwc_woocommerce_loop_columns() {
		return 4; // 4 products per row
	}
}

if ( ! function_exists( 'martialwc_woocommerce_layout_class' ) ) :
/**
 * Generate layout class for sidebar based on customizer and post meta settings for woocommerce pages.
 */
function martialwc_woocommerce_layout_class() {
	global $post;

	$layout = get_theme_mod( 'martialwc_woocommerce_global_layout', 'right_sidebar' );


	// Front page displays in Reading Settings
	$page_for_posts = get_option('page_for_posts');

	// Get Layout meta
	if($post) {
		$layout_meta = get_post_meta( $post->ID, 'martialwc_page_specific_layout', true );
	}
	// Home page if Posts page is assigned
	if( is_home() && !( is_front_page() ) ) {
		$queried_id = get_option( 'page_for_posts' );
		$layout_meta = get_post_meta( $queried_id, 'martialwc_page_specific_layout', true );

		if( $layout_meta != 'default_layout' && $layout_meta != '' ) {
	 		$layout = get_post_meta( $queried_id, 'martialwc_page_specific_layout', true );
		}
	}

	elseif( is_page() ) {
		$layout = get_theme_mod( 'martialwc_woocommerce_global_layout', 'right_sidebar' );
		if( $layout_meta != 'default_layout' && $layout_meta != '' ) {
			$layout = get_post_meta( $post->ID, 'martialwc_page_specific_layout', true );
		}
	}

	elseif( is_single() ) {
		$layout = get_theme_mod( 'martialwc_woocommerce_product_layout', 'right_sidebar' );
		if( $layout_meta != 'default_layout' && $layout_meta != '' ) {
			$layout = get_post_meta( $post->ID, 'martialwc_page_specific_layout', true );
		}
	}

	return $layout;
}
endif;

/**
 * Get the martialwc's placeholder image URL for products.
 *
 * @return string
 */
function martialwc_woocommerce_placeholder_img_src( $image_size = '' ) {

	if($image_size == ''){
		return apply_filters( 'woocommerce_placeholder_img_src', get_template_directory_uri() . '/images/placeholder-shop.jpg' );
	} else {

		$size           = martialwc_get_image_size($image_size);
		$size['width']  = isset( $size['width'] ) ? $size['width'] : '';
		$size['height'] = isset( $size['height'] ) ? $size['height'] : '';


		return apply_filters( 'woocommerce_placeholder_img_src', get_template_directory_uri() . '/images/placeholder-shop-'.$size['width'].'x'.$size['height'].'.jpg' );
	}
}

function martialwc_get_image_size( $name ) {
	global $_wp_additional_image_sizes;

	if ( isset( $_wp_additional_image_sizes[$name] ) )
		return $_wp_additional_image_sizes[$name];

	return false;
}

// Ensure cart contents update when products are added to the cart via AJAX
add_filter( 'woocommerce_add_to_cart_fragments', 'martialwc_woocommerce_header_add_to_cart_fragment' );

function martialwc_woocommerce_header_add_to_cart_fragment( $fragments ) {
	ob_start();
	?>
	<div class="martialwc-cart-views">
		<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="wcmenucart-contents">
			<i class="fa fa-shopping-cart"></i>
			<span class="cart-value"><?php echo wp_kses_data ( WC()->cart->get_cart_contents_count() ); ?></span>
		</a> <!-- quick wishlist end -->
		<div class="my-cart-wrap">
			<div class="my-cart"><?php esc_html_e('Total', 'martialwc'); ?></div>
			<div class="cart-total"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></div>
		</div>
	</div>
	<?php

	$fragments['div.martialwc-cart-views'] = ob_get_clean();
	return $fragments;
}


/*
* Tab Single Product
*/
add_filter( 'woocommerce_product_tabs', 'woo_custom_description_tab', 98 );

function woo_custom_description_tab( $tabs ) {

	unset( $tabs['additional_information'] );

    return $tabs;
}