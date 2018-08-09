<?php
/**
 * Template part for displaying posts.
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
		<?php if ( get_theme_mod('martialwc_postmeta', '') == '' && get_theme_mod( 'martialwc_postmeta_date', '') == '' ) { ?>
		<span class="posted-on"><a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php the_time( get_option( 'date_format' ) ); ?></a></span>
		<?php }
		if ( has_post_thumbnail() ) { ?>
			<?php the_post_thumbnail( 'martialwc-slider' ); ?>
		<?php } ?>
	</div>
	<?php
		if ( is_single() ) {
			the_title( '<h1 class="entry-title">', '</h1>' );
		} else {
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_the_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		}
	?>

	<div class="entry-content-text-wrapper clearfix">
		<div class="entry-content-wrapper">
			<?php martialwc_entry_meta(); ?>
			<div class="entry-content">
				<?php the_excerpt(); ?>
				<div class="entry-btn">
					<a href="<?php echo esc_url( get_the_permalink() ); ?>" class="btn"><?php esc_html_e( 'Read more', 'martialwc' ); ?></a>
				</div>
			</div>
		</div>
	</div>

	<?php do_action( 'martialwc_after_post_content' ); ?>
</article><!-- #post-## -->
