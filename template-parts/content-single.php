<?php
/**
 * Template part for displaying single posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WebAtome
 * @subpackage martialwc
 * @since martialwc 0.1
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php do_action( 'martialwc_before_post_content' ); ?>

	<div class="entry-thumbnail">
		<?php if ( get_theme_mod('martialwc_postmeta', '') == '' && get_theme_mod( 'martialwc_postmeta_date', '') == '' )  { ?>
		<span class="posted-on"><?php the_time( get_option( 'date_format' ) ); ?></span>
		<?php }
		if ( has_post_thumbnail() ) { ?>
			<?php the_post_thumbnail( 'martialwc-slider' ); ?>
		<?php } ?>
	</div>
	<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

	<div class="entry-content-text-wrapper clearfix">
		<div class="entry-content-wrapper">
			<?php martialwc_entry_meta(); ?>
			<div class="entry-content">
				<?php the_content(); ?>
				<?php
				$martialwc_tag_list = get_the_tag_list( '', ',&nbsp;', '' );
				if( !empty( $martialwc_tag_list ) ) {
				?>
				<div class="tags">
					<?php esc_html_e( 'Tagged on: ', 'martialwc' ); echo $martialwc_tag_list; ?>
				</div>
                  <?php
				}
				?>
				<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'martialwc' ),
					'after'  => '</div>',
				) );
				?>
			</div><!-- .entry-content -->
		</div>
	</div>

	<?php do_action( 'martialwc_after_post_content' ); ?>
</article><!-- #post-## -->
