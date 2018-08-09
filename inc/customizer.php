<?php
/**
 * martialwc Theme Customizer.
 *
 * @package martialwc
 */

/**
 * Loads custom control for layout settings
 */
function martialwc_custom_controls() {

	require_once get_template_directory() . '/inc/admin/customize-image-radio-control.php';
	require_once get_template_directory() . '/inc/admin/customize-custom-css-control.php';
	require_once get_template_directory() . '/inc/admin/customize-texteditor-control.php';

}
add_action( 'customize_register', 'martialwc_custom_controls' );
/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function martialwc_customize_register( $wp_customize ) {
 	// Transport postMessage variable set
    $customizer_selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
     $wp_customize->selective_refresh->add_partial( 'blogname', array(
        'selector'        => '#site-title a',
        'render_callback' => 'martialwc_customize_partial_blogname',
     ) );

     $wp_customize->selective_refresh->add_partial( 'blogdescription', array(
        'selector'        => '#site-description',
        'render_callback' => 'martialwc_customize_partial_blogdescription',
     ) );
   }

	// Theme important links started
	class martialwc_Important_Links extends WP_Customize_Control {

		public $type = "martialwc-important-links";

		public function render_content() {
			//Add Theme instruction, Support Forum, Demo Link, Rating Link
			$important_links = array(
				'view-pro' => array(
					'link' => esc_url('https://WebAtome.com/themes/martialwc/'),
					'text' => esc_html__('View Pro', 'martialwc'),
				),
				'theme-info' => array(
					'link' => esc_url('https://WebAtome.com/themes/martialwc/'),
					'text' => esc_html__('Theme Info', 'martialwc'),
				),
				'support' => array(
					'link' => esc_url('https://WebAtome.com/support-forum/'),
					'text' => esc_html__('Support', 'martialwc'),
				),
				'documentation' => array(
					'link' => esc_url('https://docs.WebAtome.com/martialwc/'),
					'text' => esc_html__('Documentation', 'martialwc'),
				),
				'view-pro' => array(
					'link' => esc_url('https://WebAtome.com/themes/martialwc/'),
					'text' => esc_html__('View Pro', 'martialwc'),
				),
				'demo' => array(
					'link' => esc_url('https://demo.WebAtome.com/martialwc/'),
					'text' => esc_html__('View Demo', 'martialwc'),
				),
				'rating' => array(
					'link' => esc_url('http://wordpress.org/support/view/theme-reviews/martialwc?filter=5'),
					'text' => esc_html__('Rate this theme', 'martialwc'),
				),
			);
			foreach ($important_links as $important_link) {
				echo '<p><a target="_blank" href="' . $important_link['link'] . '" >' . esc_attr($important_link['text']) . ' </a></p>';
			}
		}

	}

	$wp_customize->add_section('martialwc_important_links',
		array(
			'priority' => 1,
			'title'    => esc_html__('martialwc Important Links', 'martialwc'),
		)
	);

	/**
	* This setting has the dummy Sanitizaition function as it contains no value to be sanitized
	*/
	$wp_customize->add_setting('martialwc_important_links',
		array(
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'martialwc_links_sanitize'
		)
	);

	$wp_customize->add_control(
		new martialwc_Important_Links(
			$wp_customize, 'important_links', array(
				'label'    => esc_html__('Important Links', 'martialwc'),
				'section'  => 'martialwc_important_links',
				'settings' => 'martialwc_important_links'
			)
		)
	);
	// Theme Important Links Ended

	// Header Media Placement
	$wp_customize->add_setting(
		'martialwc_header_media_placement',
		array(
			'default'            => 'header_media_below_main_menu',
			'capability'         => 'edit_theme_options',
			'sanitize_callback'  => 'martialwc_sanitize_radio'
		)
	);

	$wp_customize->add_control(
		'martialwc_header_media_placement',
		array(
			'label'    => esc_html__( 'Choose the required option for Header Media placement', 'martialwc' ),
			'section'  => 'header_image',
			'type'     => 'radio',
			'choices'  => array(
				'header_media_above_site_title' => esc_html__( 'Position One: Display Header Media just above the site Title/Text', 'martialwc' ),
				'header_media_below_main_menu' => esc_html__( 'Postion Two: Display Header Media just below the Main/Primary Menu', 'martialwc' ),
			)
		)
	);

	// Header Options
	$wp_customize->add_panel(
		'martialwc_header_options',
		array(
			'capabitity'  => 'edit_theme_options',
			'description' => esc_html__( 'Change Header Settings here', 'martialwc' ),
			'priority'    => 160,
			'title'       => esc_html__( 'Header Options', 'martialwc' )
			)
		);

	// Header Integrations
	$wp_customize->add_section( 'martialwc_header_integrations', array(
		'priority' => 30,
		'title'    => esc_html__( 'Header Integrations', 'martialwc' ),
		'panel'    => 'martialwc_header_options'
	));

	// WPML Languages
	$wp_customize->add_setting( 'martialwc_header_lang', array(
			'default'              => '',
			'capability'           => 'edit_theme_options',
			'sanitize_callback'    => 'martialwc_sanitize_checkbox',
		)
	);

	$wp_customize->add_control( 'martialwc_header_lang', array(
			'label'           => esc_html__( 'Enable Language Selection (WPML)', 'martialwc' ),
			'section'         => 'martialwc_header_integrations',
			'type'            => 'checkbox',
			'active_callback' => 'martialwc_is_wpml_activate',
			'priority'        => 40 // 10,20,30 for woocommerce settings
		)
	);

	// Logo Section
	$wp_customize->add_section(
		'martialwc_header_logo',
		array(
			'priority'   => 10,
			'title'      => esc_html__( 'Header Logo', 'martialwc' ),
			'panel'      => 'martialwc_header_options'
		)
	);

	if ( ! function_exists('the_custom_logo') ) {
		// Logo Upload
		$wp_customize->add_setting(
			'martialwc_logo',
			array(
				'default'            => '',
				'capability'         => 'edit_theme_options',
				'sanitize_callback'  => 'esc_url_raw'
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'martialwc_logo',
				array(
					'label'    => esc_html__( 'Upload logo' , 'martialwc' ),
					'section'  => 'martialwc_header_logo',
					'setting'  => 'martialwc_logo'
				)
			)
		);
	}


	// Logo Placement
	$wp_customize->add_setting(
		'martialwc_logo_placement',
		array(
			'default'            => '',
			'capability'         => 'edit_theme_options',
			'sanitize_callback'  => 'martialwc_sanitize_radio'
		)
	);

	$wp_customize->add_control(
		'martialwc_logo_placement',
		array(
			'label'    => esc_html__( 'Choose the required option', 'martialwc' ),
			'section'  => 'martialwc_header_logo',
			'type'     => 'radio',
			'choices'  => array(
				'header_logo_only' => esc_html__( 'Header Logo Only', 'martialwc' ),
				'header_text_only' => esc_html__( 'Header Text Only', 'martialwc' ),
				'show_both'        => esc_html__( 'Show both header logo and text', 'martialwc' ),
				'disable'          => esc_html__( 'Disable', 'martialwc' )
			)
		)
	);

	// Header Top Bar Section
	$wp_customize->add_section(
		'martialwc_header_bar',
		array(
			'priority'   => 20,
			'title'      => esc_html__( 'Header Top Bar', 'martialwc' ),
			'panel'      => 'martialwc_header_options'
		)
	);

	// Header Bar Activation
	$wp_customize->add_setting(
		'martialwc_bar_activation',
		array(
			'default'            => '',
			'capability'         => 'edit_theme_options',
			'sanitize_callback'  => 'martialwc_sanitize_checkbox'
		)
	);

	$wp_customize->add_control(
		'martialwc_bar_activation',
		array(
			'label'    => esc_html__( 'Activate the header top bar', 'martialwc' ),
			'section'  => 'martialwc_header_bar',
			'type'     => 'checkbox'
		)
	);

	// Header Bar Left Section
	$wp_customize->add_setting(
		'martialwc_bar_text',
		array(
			'default'            => '',
			'capability'         => 'edit_theme_options',
			'transport'          => $customizer_selective_refresh,
			'sanitize_callback'  => 'martialwc_sanitize_text'
		)
	);

	$wp_customize->add_control(
		new martialwc_Text_Editor_Control(
			$wp_customize,
			'martialwc_bar_text',
			array(
				'label'       => esc_html__( 'Header Text', 'martialwc' ),
				'description' => esc_html__( 'Paste the Font Awesome icon font', 'martialwc' ),
				'section'     => 'martialwc_header_bar',
				'setting'     => 'martialwc_bar_text'
			)
		)
	);

	// Selective refresh for header top bar text
	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'martialwc_bar_text', array(
			'selector'        => '#header-ticker',
			'render_callback' => 'martialwc_bar_text',
		) );
	}

	// Design Related Options
	$wp_customize->add_panel(
		'martialwc_design_options',
		array(
			'capability'  => 'edit_theme_options',
			'description' => esc_html__( 'Design Related Settings', 'martialwc' ),
			'priority'    => 180,
			'title'       => esc_html__( 'Design Options', 'martialwc' )
		)
	);

	// Primary Color Setting
	$wp_customize->add_section(
		'martialwc_primary_color_section',
		array(
			'priority'   => 40,
			'title'      => esc_html__( 'Primary Color Option', 'martialwc' ),
			'panel'      => 'martialwc_design_options'
		)
	);

	$wp_customize->add_setting(
		'martialwc_primary_color',
		array(
			'default'              => '#00a9e0',
			'capability'           => 'edit_theme_options',
			'transport'            => 'postMessage',
			'sanitize_callback'    => 'martialwc_hex_color_sanitize',
			'sanitize_js_callback' => 'martialwc_color_escaping_sanitize'
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'martialwc_primary_color',
			array(
				'label'    => esc_html__( 'This will reflect in links, buttons and many others. Choose a color to match your site', 'martialwc' ),
				'section'  => 'martialwc_primary_color_section'
			)
		)
	);

	// Default Layout
	$wp_customize->add_section(
		'martialwc_global_layout_section',
		array(
			'priority'  => 10,
			'title'     => esc_html__( 'Default Layout', 'martialwc' ),
			'panel'     => 'martialwc_design_options'
		)
	);

	$wp_customize->add_setting(
		'martialwc_global_layout',
		array(
			'default'           => 'right_sidebar',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'martialwc_sanitize_radio'
		)
	);

	$wp_customize->add_control(
		new martialwc_Image_Radio_Control (
			$wp_customize,
			'martialwc_global_layout',
			array(
				'label'   => esc_html__( 'Select default layout. This layout will be reflected in whole site archives, categories, search page etc. The layout for a single post and page can be controlled from below options', 'martialwc' ),
				'section' => 'martialwc_global_layout_section',
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

	// Default Pages Layout
	$wp_customize->add_section(
		'martialwc_default_page_layout_section',
		array(
			'priority'  => 20,
			'title'     => esc_html__( 'Default Page Layout', 'martialwc' ),
			'panel'     => 'martialwc_design_options'
		)
	);

	$wp_customize->add_setting(
		'martialwc_default_page_layout',
		array(
			'default'           => 'right_sidebar',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'martialwc_sanitize_radio'
		)
	);

	$wp_customize->add_control(
		new martialwc_Image_Radio_Control (
			$wp_customize,
			'martialwc_default_page_layout',
			array(
				'label'   => esc_html__( 'Select default layout for pages. This layout will be reflected in all pages unless unique layout is set for specific page', 'martialwc' ),
				'section' => 'martialwc_default_page_layout_section',
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

	// Default Single Post Layout
	$wp_customize->add_section(
		'martialwc_default_single_post_layout_section',
		array(
			'priority'  => 30,
			'title'     => esc_html__( 'Default Single Post Layout', 'martialwc' ),
			'panel'     => 'martialwc_design_options'
		)
	);

	$wp_customize->add_setting(
		'martialwc_default_single_post_layout',
		array(
			'default'           => 'right_sidebar',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'martialwc_sanitize_radio'
		)
	);

	$wp_customize->add_control(
		new martialwc_Image_Radio_Control (
			$wp_customize,
			'martialwc_default_single_post_layout',
			array(
				'label'   => esc_html__( 'Select default layout for single posts. This layout will be reflected in all single posts unless unique layout is set for specific post', 'martialwc' ),
				'section' => 'martialwc_default_single_post_layout_section',
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

	// Default Single Post Layout
	$wp_customize->add_section(
		'martialwc_archive_page_section',
		array(
			'priority'  => 40,
			'title'     => esc_html__( 'Blog Layout', 'martialwc' ),
			'panel'     => 'martialwc_design_options'
		)
	);

	$wp_customize->add_setting(
		'martialwc_archive_page_style',
		array(
			'default'           => 'archive-list',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'martialwc_sanitize_choices'
		)
	);

	$wp_customize->add_control(
		'martialwc_archive_page_style',
		array(
			'label'    => esc_html__( 'Choose the layout style for archive pages.', 'martialwc' ),
			'section'  => 'martialwc_archive_page_section',
			'type'     => 'select',
			'choices'    => array(
				'archive-list' => esc_html__('List View', 'martialwc'),
				'archive-grid' => esc_html__('Grid View', 'martialwc'),
        	),
		)
	);

	if ( ! function_exists( 'wp_update_custom_css_post' ) ) {
		// Custom CSS Section
		$wp_customize->add_section(
			'martialwc_custom_css_section',
			array(
				'priority'  => 50,
				'title'     => esc_html__( 'Custom CSS', 'martialwc' ),
				'panel'     => 'martialwc_design_options'
			)
		);

		$wp_customize->add_setting(
			'martialwc_custom_css',
			array(
				'default'              => '',
				'capability'           => 'edit_theme_options',
				'sanitize_callback'    => 'wp_filter_nohtml_kses',
				'sanitize_js_callback' => 'wp_filter_nohtml_kses'
			)
		);

		$wp_customize->add_control(
			new martialwc_Custom_CSS_Control(
			$wp_customize,
			'martialwc_custom_css',
			array(
				'label'   => esc_html__( 'Write your Custom CSS here', 'martialwc' ),
				'section' => 'martialwc_custom_css_section',
			)
		) );
	}

	// Footer Widget Section
	$wp_customize->add_section(
		'martialwc_footer_widget_section',
		array(
			'priority'   => 60,
			'title'      => esc_html__( 'Footer Widgets', 'martialwc' ),
			'panel'      => 'martialwc_design_options'
		)
	);

	$wp_customize->add_setting(
		'martialwc_footer_widgets',
		array(
			'default'            => 4,
			'capability'         => 'edit_theme_options',
			'sanitize_callback'  => 'martialwc_sanitize_integer'
		)
	);

	$wp_customize->add_control(
		'martialwc_footer_widgets',
		array(
			'label'    => esc_html__( 'Choose the number of widget area you want in footer', 'martialwc' ),
			'section'  => 'martialwc_footer_widget_section',
			'type'     => 'select',
			'choices'    => array(
            	'1' => esc_html__('1 Footer Widget Area', 'martialwc'),
            	'2' => esc_html__('2 Footer Widget Area', 'martialwc'),
            	'3' => esc_html__('3 Footer Widget Area', 'martialwc'),
            	'4' => esc_html__('4 Footer Widget Area', 'martialwc')
        	),
 		)
	);

	// Additional Options
	$wp_customize->add_panel(
		'martialwc_additional_options',
		array(
			'capability'  => 'edit_theme_options',
			'description' => esc_html__( 'Some additional settings.', 'martialwc' ),
			'priority'    => 180,
			'title'       => esc_html__( 'Additional Options', 'martialwc' )
			)
		);

	// Category Color Section
	$wp_customize->add_section( 'martialwc_category_color_setting', array(
		'priority' => 1,
		'title'    => esc_html__( 'Category Color Settings', 'martialwc' ),
		'panel'    => 'martialwc_additional_options'
	));

	$priority = 1;
	$categories = get_terms( 'category' ); // Get all Categories
	$wp_category_list = array();

	foreach ($categories as $category_list ) {

		$wp_customize->add_setting( 'martialwc_category_color_'.esc_html( strtolower($category_list->name) ),
			array(
				'default'              => '',
				'capability'           => 'edit_theme_options',
				'sanitize_callback'    => 'martialwc_hex_color_sanitize',
				'sanitize_js_callback' => 'martialwc_color_escaping_sanitize'
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize, 'martialwc_category_color_'.esc_html( strtolower($category_list->name) ),
				array(
					'label'    => sprintf(esc_html__(' %s', 'martialwc' ), esc_html( $category_list->name ) ),
					'section'  => 'martialwc_category_color_setting',
					'settings' => 'martialwc_category_color_'.esc_html( strtolower($category_list->name) ),
					'priority' => $priority
				)
			)
		);
		$priority++;
	}

	// Post Meta Section
	$wp_customize->add_section(
		'martialwc_postmeta_section',
		array(
			'priority'   => 30,
			'title'      => esc_html__( 'Post Meta Settings', 'martialwc'),
			'panel'      => 'martialwc_additional_options',
			'description'=> esc_html__( 'Note: This will only work in single posts.', 'martialwc' )
		)
	);

	// Post Meta Setting
	$wp_customize->add_setting(
		'martialwc_postmeta',
		array(
			'default'            => '',
			'capability'         => 'edit_theme_options',
			'sanitize_callback'  => 'martialwc_sanitize_checkbox'
		)
	);

	$wp_customize->add_control(
		'martialwc_postmeta',
		array(
			'label'    => esc_html__( 'Hide all post meta data under post title.' , 'martialwc' ),
			'section'  => 'martialwc_postmeta_section',
			'priority' => 10,
			'type'     => 'checkbox'
		)
	);

	// Post Meta Date Setting
	$wp_customize->add_setting(
		'martialwc_postmeta_date',
		array(
			'default'            => '',
			'capability'         => 'edit_theme_options',
			'sanitize_callback'  => 'martialwc_sanitize_checkbox'
		)
	);

	$wp_customize->add_control(
		'martialwc_postmeta_date',
		array(
			'label'    => esc_html__( 'Hide date under post title.' , 'martialwc' ),
			'section'  => 'martialwc_postmeta_section',
			'priority' => 20,
			'type'     => 'checkbox'
		)
	);

	// Post Meta Author Setting
	$wp_customize->add_setting(
		'martialwc_postmeta_author',
		array(
			'default'            => '',
			'capability'         => 'edit_theme_options',
			'sanitize_callback'  => 'martialwc_sanitize_checkbox'
		)
	);

	$wp_customize->add_control(
		'martialwc_postmeta_author',
		array(
			'label'    => esc_html__( 'Hide author under post title.' , 'martialwc' ),
			'section'  => 'martialwc_postmeta_section',
			'priority' => 30,
			'type'     => 'checkbox'
		)
	);

	// Post Meta Comment Count Setting
	$wp_customize->add_setting(
		'martialwc_postmeta_comment',
		array(
			'default'            => '',
			'capability'         => 'edit_theme_options',
			'sanitize_callback'  => 'martialwc_sanitize_checkbox'
		)
	);

	$wp_customize->add_control(
		'martialwc_postmeta_comment',
		array(
			'label'    => esc_html__( 'Hide comment count under post title.' , 'martialwc' ),
			'section'  => 'martialwc_postmeta_section',
			'priority' => 40,
			'type'     => 'checkbox'
		)
	);

	// Post Meta Category Setting
	$wp_customize->add_setting(
		'martialwc_postmeta_category',
		array(
			'default'            => '',
			'capability'         => 'edit_theme_options',
			'sanitize_callback'  => 'martialwc_sanitize_checkbox'
		)
	);

	$wp_customize->add_control(
		'martialwc_postmeta_category',
		array(
			'label'    => esc_html__( 'Hide category under post title.' , 'martialwc' ),
			'section'  => 'martialwc_postmeta_section',
			'priority' => 50,
			'type'     => 'checkbox'
		)
	);

	// Post Meta Tags Setting
	$wp_customize->add_setting(
		'martialwc_postmeta_tags',
		array(
			'default'            => '',
			'capability'         => 'edit_theme_options',
			'sanitize_callback'  => 'martialwc_sanitize_checkbox'
		)
	);

	$wp_customize->add_control(
		'martialwc_postmeta_tags',
		array(
			'label'    => esc_html__( 'Hide tags under post title.' , 'martialwc' ),
			'section'  => 'martialwc_postmeta_tags',
			'priority' => 60,
			'type'     => 'checkbox'
		)
	);

	// Payment Partners Logo Section
	$wp_customize->add_section(
		'martialwc_payment_logo_section',
		array(
			'priority'   => 40,
			'title'      => esc_html__( 'Payment Partners Logo', 'martialwc' ),
			'panel'      => 'martialwc_additional_options'
		)
	);

	for ( $i = 1; $i < 5; $i++ ) {
		// Logo Upload
		$wp_customize->add_setting(
			'martialwc_payment_logo'.$i,
			array(
				'default'            => '',
				'capability'         => 'edit_theme_options',
				'transport'          => $customizer_selective_refresh,
				'sanitize_callback'  => 'esc_url_raw'
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'martialwc_payment_logo'.$i,
				array(
					'label'    => esc_html__( 'Upload logo' , 'martialwc' ).$i,
					'section'  => 'martialwc_payment_logo_section',
					'setting'  => 'martialwc_payment_logo'.$i
				)
			)
		);

		// Selective refresh for payment logo
		if ( isset( $wp_customize->selective_refresh ) ) {
			$wp_customize->selective_refresh->add_partial( 'martialwc_payment_logo'.$i, array(
				'selector'        => '.payment-partner-wrapper',
				'render_callback' => '',
			) );
		}
	}

	// Check if WPML Active
	function martialwc_is_wpml_activate() {
		if ( function_exists('icl_object_id') ) {
			return true;
		} else {
			return false;
		}
	}

	// Sanitize Radio Button
	function martialwc_sanitize_radio( $input, $setting ) {
		// Ensure input is a slug.
		$input = sanitize_key( $input );

		// Get list of choices from the control associated with the setting.
		$choices = $setting->manager->get_control( $setting->id )->choices;

		// If the input is a valid key, return it; otherwise, return the default.
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
	}

	// Sanitize Checkbox
	function martialwc_sanitize_checkbox( $input ) {
		if ( $input == 1 ) {
			return 1;
		} else {
			return '';
		}
	}

	// Sanitize Integer
	function martialwc_sanitize_integer( $input ) {
		if( is_numeric( $input ) ) {
			return intval( $input );
		}
	}

	// Sanitize Text
	function martialwc_sanitize_text( $input ) {
		return wp_kses_post( force_balance_tags( $input ) );
	}

	// Sanitize Color
	function martialwc_hex_color_sanitize( $color ) {
		if ($unhashed = sanitize_hex_color_no_hash($color))
			return '#' . $unhashed;

		return $color;
	}
	// Escape Color
	function martialwc_color_escaping_sanitize( $input ) {
		$input = esc_attr($input);
		return $input;
	}
	// Sanitize Choices
	function martialwc_sanitize_choices( $input, $setting ) {
		global $wp_customize;

		$control = $wp_customize->get_control( $setting->id );

		if ( array_key_exists( $input, $control->choices ) ) {
			return $input;
		} else {
			return $setting->default;
		}
	}
}
add_action( 'customize_register', 'martialwc_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @since martialwc 1.2.3
 */
function martialwc_customize_preview_js() {
   wp_enqueue_script( 'martialwc-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), false, true );
}
add_action( 'customize_preview_init', 'martialwc_customize_preview_js' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function martialwc_customize_partial_blogname() {
   bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function martialwc_customize_partial_blogdescription() {
   bloginfo( 'description' );
}

// Function for top eader text selective refresh support
function martialwc_bar_text(){
	$header_bar_text = get_theme_mod( 'martialwc_bar_text' );
	echo wp_kses_post($header_bar_text);
} 


if ( ! function_exists( 'martialwc_customize_woocommerce_breadcrumb' ) ) {

	/**
	 * Output the WooCommerce Breadcrumb.
	 *
	 * @param array $args Arguments.
	 */
	function martialwc_customize_woocommerce_breadcrumb() {
		$args = array(
			'delimiter'   => '&nbsp;&gt;&nbsp;',
			'home'        => _x( 'Home', 'breadcrumb', 'woocommerce' )
		);

		woocommerce_breadcrumb($args);
		/*$breadcrumbs = new WC_Breadcrumb();

		if ( ! empty( $args['home'] ) ) {
			$breadcrumbs->add_crumb( $args['home'], apply_filters( 'woocommerce_breadcrumb_home_url', home_url() ) );
		}

		$args['breadcrumb'] = $breadcrumbs->generate();

		/**
		 * WooCommerce Breadcrumb hook
		 *
		 * @hooked WC_Structured_Data::generate_breadcrumblist_data() - 10
		 *
		do_action( 'woocommerce_breadcrumb', $breadcrumbs, $args );

		wc_get_template( 'global/breadcrumb.php', $args );*/
	}
}
