<?php
/**
 * The template part for displaying navigation.
 *
 * @package WebAtome
 * @subpackage martialwc
 * @since martialwc 1.1
 */
if( is_archive() || is_home() || is_search() ) {
	/**
	 * Checking WP-PageNaviplugin exist
	 */
	if ( function_exists('wp_pagenavi' ) ) :
		wp_pagenavi();

	else:
		global $wp_query;
		if ( $wp_query->max_num_pages > 1 ) :
		?>
		<ul class="default-wp-page clearfix">
			<li class="previous"><?php next_posts_link( esc_html__( '&larr; Previous', 'martialwc' ) ); ?></li>
			<li class="next"><?php previous_posts_link( esc_html__( 'Next &rarr;', 'martialwc' ) ); ?></li>
		</ul>
		<?php
		endif;
	endif;
}

if ( is_single() ) {
	if( is_attachment() ) {
	?>
		<ul class="default-wp-page clearfix">
			<li class="previous"><?php previous_image_link( false, esc_html__( '&larr; Previous', 'martialwc' ) ); ?></li>
			<li class="next"><?php next_image_link( false, esc_html__( 'Next &rarr;', 'martialwc' ) ); ?></li>
		</ul>
	<?php
	}
	else {
	?>
		<ul class="default-wp-page clearfix">
			<li class="previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . esc_html_x( '&larr;', 'Previous post link', 'martialwc' ) . '</span> %title' ); ?></li>
			<li class="next"><?php next_post_link( '%link', '%title <span class="meta-nav">' . esc_html_x( '&rarr;', 'Next post link', 'martialwc' ) . '</span>' ); ?></li>
		</ul>
	<?php
	}
}
?>
