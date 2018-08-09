<?php
/**
 * Template Name: martialwc Home
 *
 * @package WebAtome
 * @subpackage martialwc
 * @since 1.0
 */

get_header(); ?>

	<div id="content">

	<section id="top_slider_section">
		<div class="tg-container">
			<div class="big-slider">
			<?php
				if( is_active_sidebar( 'martialwc_sidebar_slider' ) ) {
					if ( !dynamic_sidebar( 'martialwc_sidebar_slider' ) ):
					endif;
				}
			?>
			</div>

			<div class="small-slider-wrapper">
			<?php
				if( is_active_sidebar( 'martialwc_sidebar_slider_beside' ) ) {
					if ( !dynamic_sidebar( 'martialwc_sidebar_slider_beside' ) ):
					endif;
				}
			?>
			</div>
		</div>
	</section>

	<?php
	if( is_active_sidebar( 'martialwc_sidebar_front' ) ) {
		if ( !dynamic_sidebar( 'martialwc_sidebar_front' ) ):
			endif;
	}
	?>

	</div>

<?php get_footer(); ?>
