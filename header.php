<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until </header>
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WebAtome
 * @subpackage MartialWC
 * @since MartialWC 0.1
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php do_action( 'tg_before' ); ?>
	<div id="page" class="hfeed site">
		<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'martialwc' ); ?></a>

		<?php do_action( 'martialwc_before_header' ); ?>

		<header id="masthead" class="site-header" role="banner">

			<div class="top-header-wrapper">
				<div class="title-container">
					<div class="site-title-wrapper">
						<div id="site-title">
							<span><?php bloginfo( 'name' ); ?></span>
							
							<!--<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>-->
						</div>
						<?php
						$description = get_bloginfo( 'description', 'display' );
						if ( $description || is_customize_preview() ) : ?>
							<p id="site-description"><?php echo $description; ?></p>
						<?php endif; ?>
					</div>
				</div>
				<div class="menu-container">
					<?php
					$menu_location  = 'header';
					if ( has_nav_menu( $menu_location ) ) {
					?>
					<div class="main-menu">
						<nav id="main-navigation" class="main-menu-wrapper" role="navigation">
							<?php wp_nav_menu(
								array(
									'theme_location' => $menu_location,
									'menu_id'        => 'main-menu',
									'menu_class'	=> 'menu horizontal',
									'fallback_cb'    => 'false'
								)
							);
							?>
						</nav>
					</div>
					<?php } ?>
				</div>
			</div>
			<div class="bottom-header-wrapper">
				<div class="blank"></div>
				<div class="search-wrapper">
					<div class="header-search-box">
						<?php get_search_form(); ?>
					</div>
				</div>
				<div class="user-wrapper">
					<div class="user-target">
						<?php if ( is_user_logged_in() ) { ?>
							<span>
								<?php esc_html_e('My Account','martialwc'); ?>
							</span>
							<!--<a href="<?php echo esc_url (get_permalink( get_option('woocommerce_myaccount_page_id') )); ?>" title="<?php esc_attr__('My Account','martialwc'); ?>">
								<?php esc_html_e('My Account','martialwc'); ?>
							</a>-->
						<?php }
						else { ?>
							<span>
								<?php esc_html_e('Login / Register','martialwc'); ?>
							</span>
							<!--<a href="<?php echo esc_url(get_permalink( get_option('woocommerce_myaccount_page_id') )); ?>" title="<?php esc_attr__('Login / Register','martialwc'); ?>">
								<?php esc_html_e('Login / Register','martialwc'); ?>
							</a>-->
						<?php } ?>
					</div>
					<div class="user-content">
						<?php if ( is_user_logged_in() ) { ?>
							Menu
						<?php }
						else { ?>
							<?php the_widget( 'login_wid' ); ?>
						<?php } ?>

					</div>
				</div>
				<div class="cart-wrapper">
					<?php
					if (function_exists('YITH_WCWL') || false) {
						$wishlist_url = YITH_WCWL()->get_wishlist_url();
						?>
						<div class="wishlist-container">
							<a class="quick-wishlist" href="<?php echo esc_url($wishlist_url); ?>" title="Wishlist">
								<i class="fa fa-heart"></i>
								<span class="wishlist-value"><?php echo absint( yith_wcwl_count_products() ); ?></span>
							</a>
						</div>
						<?php
					}
					if ( class_exists( 'woocommerce' ) ) : ?>
						<div class="cart-container">
							<div class="cart-views">
								<div class="cart-title">
									<i class="fa fa-shopping-cart"></i>
									<?php esc_html_e('Mon panier', 'martialwc'); ?>
								</div>
								<div class="cart-count">
									<span class="cart-value"><?php echo wp_kses_data ( WC()->cart->get_cart_contents_count() ); ?></span>
								</div>
								<!--<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="wcmenucart-contents"></a>-->
							</div>
							<div class="cart-overview">
								<?php the_widget( 'WC_Widget_Cart', '' ); ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
			
		</header>

	<?php do_action( 'martialwc_after_header' ); ?>
	<?php do_action( 'martialwc_before_main' ); ?>
