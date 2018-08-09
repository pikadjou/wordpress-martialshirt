<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WebAtome
 * @subpackage martialwc
 * @since martialwc 0.1
 */

get_header();

	do_action( 'martialwc_before_body_content' );

	$martialwc_layout = martialwc_layout_class();
	?>
	<div id="content" class="site-content"><!-- #content.site-content -->
		<div class="page-header clearfix">
			<div class="tg-container">
				<?php the_title('<h2 class="entry-title">', '</h2>'); ?>
				<h3 class="entry-sub-title"><?php martialwc_breadcrumbs(); ?></h3>
			</div>
		</div>
		<main id="main" class="clearfix <?php echo esc_attr($martialwc_layout); ?>">
			<div class="tg-container">
				<div id="primary">
					<?php
					while ( have_posts() ) : the_post(); ?>

						<?php get_template_part( 'template-parts/content', 'page' ); ?>

						<?php
						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;

						get_template_part('navigation', 'none');

					endwhile; // End of the loop. ?>
				</div> <!-- Primary end -->
					<?php martialwc_sidebar_select(); ?>
			</div>
		</main>
	</div>

	<?php do_action( 'martialwc_after_body_content' ); ?>

<?php get_footer(); ?>
