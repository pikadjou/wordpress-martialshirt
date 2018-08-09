<?php
/**
 * Template part for displaying page content in page.php.
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
	<?php if ( has_post_thumbnail() ) { ?>
		<?php the_post_thumbnail( 'martialwc-slider' ); ?>
	<?php } ?>

		<div class="entry-content-text-wrapper clearfix">
			<div class="entry-content-wrapper">
				<div class="entry-content">
					<?php the_content(); ?>
					<?php
					wp_link_pages( array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'martialwc' ),
						'after'  => '</div>',
					) );
					?>
				</div><!-- .entry-content -->
			</div>
		</div>
	</div>

	<?php do_action( 'martialwc_after_post_content' ); ?>
</article><!-- #post-## -->
