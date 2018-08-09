<?php
/**
 * The template for displaying 404 pages (Page Not Found).
 *
 * @package WebAtome
 * @subpackage martialwc
 * @since martialwc 1.0
 */

get_header(); ?>

	<?php do_action( 'martialwc_before_body_content' );

	$martialwc_layout = martialwc_layout_class();
	?>

	<div id="content" class="site-content">
		<div class="page-header clearfix">
			<div class="tg-container">
				<h2 class="entry-title"><?php esc_html_e( '404', 'martialwc' );?></h2>
				<h3 class="entry-sub-title"><?php martialwc_breadcrumbs(); ?></h3>
			</div>
		</div>
		<main id="main" class="clearfix <?php echo esc_attr($martialwc_layout); ?>">
			<div class="tg-container">
				<div id="primary">
					<section class="error-404 not-found">
						<div class="page-content clearfix">

						<?php if ( ! dynamic_sidebar( 'martialwc_error_404_page_sidebar' ) ) : ?>
							<div class="error-wrap">
								<span class="num-404">
									<?php esc_html_e( '404', 'martialwc' ); ?>
								</span>
								<span class="error"><?php esc_html_e( 'error', 'martialwc' ); ?></span>
							</div>
							<header class="page-not-found">
								<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.' , 'martialwc' ); ?></h1>
							</header>

							<p class="message"><?php esc_html_e( 'It looks like nothing was found at this location. Try the search below.', 'martialwc' ); ?></p>

							<div class="form-wrapper">
							<?php get_search_form(); ?>
							</div>
						<?php endif; ?>
						</div>
					</section>
				</div>
				<?php martialwc_sidebar_select(); ?>
			</div>
		</main>
	</div>

	<?php do_action( 'martialwc_after_body_content' ); ?>

<?php get_footer(); ?>
