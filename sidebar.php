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

if ( !is_active_sidebar( 'martialwc_sidebar_left' ) ) {
   return;
}
?>

<aside id="secondary" class="widget-area" role="complementary">
	<?php do_action( 'martialwc_before_left_sidebar' ); ?>

	<?php dynamic_sidebar( 'martialwc_sidebar_left' ); ?>

	<?php do_action( 'martialwc_after_left_sidebar' ); ?>
</aside><!-- #secondary -->
