<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WebAtome
 * @subpackage martialwc
 * @since martialwc 0.1
 */

if ( !is_active_sidebar( 'martialwc_sidebar_header' ) ) {
   return;
}
?>

<aside id="header-sidebar" class="widget-area widget-large-advertise" role="complementary">

	<?php do_action( 'martialwc_before_header_sidebar' ); ?>

	<?php dynamic_sidebar( 'martialwc_sidebar_header' ); ?>

	<?php do_action( 'martialwc_after_header_sidebar' ); ?>

</aside><!-- #header-sidebar -->
