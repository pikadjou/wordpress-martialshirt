<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<?php
$crosssells = WC()->cart->get_cross_sells();

if ( 0 === sizeof( $crosssells ) ) {
	$class = ' cart-total-full';
} else {
	$class = ' cart-total-with-cross-sell';
}
?>
<div class="cart_totals <?php if ( WC()->customer->has_calculated_shipping() ) echo 'calculated_shipping'; ?><?php echo $class; ?>">

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

	<h2 class="widget-title"><span><?php esc_html_e( 'Cart Totals', 'martialwc' ); ?></span></h2>

	<div class="shop_table">
		<div class="sub-info">
			<div class="subtotal">
				<span class="key">
					<?php esc_html_e( 'Subtotal', 'martialwc' ); ?>
				</span>
				<span class="value price">
					<?php wc_cart_totals_subtotal_html(); ?>
				</span>
			</div>
			<?php if (count(WC()->cart->get_coupons()) > 0) : ?>
				<div class="cart-discount">
					<span class="key">
						<?php esc_html_e( 'Coupon', 'martialwc' ); ?>
					</span>
					<span class="value">
						<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
							<div class="coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
								<span class="key"><?php wc_cart_totals_coupon_label( $coupon ); ?></span>
								<span class="value price"><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
							</div>
						<?php endforeach; ?>
					</span>
				</div>
			<?php endif; ?>

			<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

				<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

				<?php wc_cart_totals_shipping_html(); ?>

				<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

			<?php elseif ( WC()->cart->needs_shipping() ) : ?>

				<div class="shipping">
					<span class="key"><?php esc_html_e( 'Shipping', 'martialwc' ); ?></span>
					<span class="value"><?php woocommerce_shipping_calculator(); ?></span>
				</div>

			<?php endif; ?>

			<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
				<div class="fee">
					<span class="key"><?php echo esc_html( $fee->name ); ?></span>
					<span class="value"><?php wc_cart_totals_fee_html( $fee ); ?></span>
				</div>
			<?php endforeach; ?>

			<?php if ( wc_tax_enabled() && 'excl' === WC()->cart->tax_display_cart ) :
				$estimated_text = 1||WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping()
					? sprintf( ' <small>(' . esc_html__( 'estimated for %s', 'martialwc' ) . ')</small>', WC()->countries->estimated_for_prefix() . WC()->countries->countries[ WC()->countries->get_base_country() ] )
					: '';

				if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
					<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
						<div class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
							<span class="key"><?php echo esc_html( $tax->label ) . $estimated_text; ?></span>
							<span class="value"><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
						</div>
					<?php endforeach; ?>
				<?php else : ?>
					<div class="tax-total">
						<span class="key"><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; ?></span>
						<span class="value"><?php wc_cart_totals_taxes_total_html(); ?></span>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<div class="main-info">
			<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

			<div class="order-total">
				<span class="key"><?php esc_html_e( 'Total', 'martialwc' ); ?></span>
				<span class="value price"><?php wc_cart_totals_order_total_html(); ?></span>
			</div>

			<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>
		</div>
	</div>
	<div class="checkout">
		<div class="wc-proceed-to-checkout">
			<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
		</div>
		<?php do_action( 'woocommerce_after_cart_totals' ); ?>
	</div>
	
</div>
