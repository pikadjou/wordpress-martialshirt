<?php
/**
 * The sidebar containing the widget area for WooCommerce Pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WebAtome
 * @subpackage martialwc
 * @since martialwc 0.1
 */

if ( !is_active_sidebar( 'martialwc_woocommerce_sidebar' ) ) {
   return;
}

$cat_ID = 0;
if( is_category() || is_product_category() ){
	$categoryobj = get_queried_object();
	if($categoryobj->term_id){
		$cat_ID = $categoryobj->term_id;
	}
}
?>
<aside id="sidebar" class="widget-area" role="complementary">

	<?php do_action( 'martialwc_before_left_sidebar' ); ?>

	<nav id="nav-categories">
		<h3>Categories</h3>
		<?php dynamic_sidebar( 'martialwc_woocommerce_sidebar' ); ?>
	</nav>


	<?php dynamic_sidebar( 'martialwc_sidebar_left_extra' ); ?>

	<?php do_action( 'martialwc_after_left_sidebar' ); ?>

</aside><!-- #secondary -->

