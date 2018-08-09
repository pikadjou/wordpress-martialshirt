<?php
/**
 * Functions for configuring demo importer.
 *
 * @author   WebAtome
 * @category Admin
 * @package  Importer/Functions
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Setup demo importer packages.
 *
 * @param  array $packages
 * @return array
 */
/*function martialwc_demo_importer_packages( $packages ) {
	$new_packages = array(
		'martialwc-free' => array(
			'name'    => esc_html__( 'martialwc', 'martialwc' ),
			'preview' => 'https://demo.WebAtome.com/martialwc/',
		),
	);

	return array_merge( $new_packages, $packages );
}

add_filter( 'WebAtome_demo_importer_packages', 'martialwc_demo_importer_packages' );
*/