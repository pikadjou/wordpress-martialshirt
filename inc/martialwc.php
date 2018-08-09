<?php
/**
 * martialwc functions and definitions
 *
 * @package WebAtome
 * @subpackage martialwc
 * @since martialwc 1.0.1
 */

if ( ! function_exists( 'martialwc_entry_meta' ) ) :
/**
 * Display meta description of post.
 */
function martialwc_entry_meta() {
	if ( 'post' == get_post_type() && get_theme_mod('martialwc_postmeta', '') == '' ) :
	echo '<div class="entry-meta">';

   	?>
   		<?php if ( get_theme_mod('martialwc_postmeta_author', '') == '' ) { ?>
		<span class="byline author vcard"><i class="fa fa-user"></i><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" title="<?php echo esc_attr(get_the_author()); ?>"><?php echo esc_html( get_the_author() ); ?></a></span>
		<?php }

		if ( ! post_password_required() && comments_open() && get_theme_mod('martialwc_postmeta_comment', '') == '' ) { ?>
		<span class="comments-link"><i class="fa fa-comments-o"></i><?php comments_popup_link( esc_html__( '0 Comment', 'martialwc' ), esc_html__( '1 Comment', 'martialwc' ), esc_html__( ' % Comments', 'martialwc' ) ); ?></span>
		<?php }

		if( has_category() && get_theme_mod('martialwc_postmeta_category', '') == '' ){ ?>
		<span class="cat-links"><i class="fa fa-folder-open"></i><?php the_category(', '); ?></span>
		<?php }

		$tags_list = get_the_tag_list( '<span class="tag-links">', ', ', '</span>' );
		if ( $tags_list && get_theme_mod('martialwc_postmeta_tags', '') == '' ) echo $tags_list;

		echo '</div>';
	endif;
}
endif;

if ( ! function_exists( 'martialwc_layout_class' ) ) :
/**
 * Generate layout class for sidebar based on customizer and post meta settings.
 */
function martialwc_layout_class() {
	global $post;

	$layout = get_theme_mod( 'martialwc_global_layout', 'right_sidebar' );


	// Front page displays in Reading Settings
	$page_for_posts = get_option('page_for_posts');

	// Get Layout meta
	if($post) {
		$layout_meta = get_post_meta( $post->ID, 'martialwc_page_specific_layout', true );
	}
	// Home page if Posts page is assigned
	if( is_home() && !( is_front_page() ) ) {
		$queried_id = get_option( 'page_for_posts' );
		$layout_meta = get_post_meta( $queried_id, 'martialwc_page_specific_layout', true );

		if( $layout_meta != 'default_layout' && $layout_meta != '' ) {
	 		$layout = get_post_meta( $queried_id, 'martialwc_page_specific_layout', true );
		}
	}

	elseif( is_page() ) {
		$layout = get_theme_mod( 'martialwc_default_page_layout', 'right_sidebar' );
		if( $layout_meta != 'default_layout' && $layout_meta != '' ) {
			$layout = get_post_meta( $post->ID, 'martialwc_page_specific_layout', true );
		}
	}

	elseif( is_single() ) {
		$layout = get_theme_mod( 'martialwc_default_single_post_layout', 'right_sidebar' );
		if( $layout_meta != 'default_layout' && $layout_meta != '' ) {
			$layout = get_post_meta( $post->ID, 'martialwc_page_specific_layout', true );
		}
	}

	return $layout;
}
endif;

if ( ! function_exists( 'martialwc_breadcrumbs' ) ) :
/**
 * Display Breadcrumbs
 *
 * This code is a modified version of Melissacabral's original menu code for dimox_breadcrumbs().
 *
 */
function martialwc_breadcrumbs(){
  /* === OPTIONS === */
	$text['home']     = esc_html__('Home', 'martialwc'); // text for the 'Home' link
	$text['category'] = esc_html__('Archive by Category "%s"', 'martialwc'); // text for a category page
	$text['tax'] 	  = esc_html__('Archive for "%s"', 'martialwc'); // text for a taxonomy page
	$text['search']   = esc_html__('Search Results for "%s" query', 'martialwc'); // text for a search results page
	$text['tag']      = esc_html__('Posts Tagged "%s"', 'martialwc'); // text for a tag page
	$text['author']   = esc_html__('Articles Posted by %s', 'martialwc'); // text for an author page
	$text['404']      = esc_html__('Error 404', 'martialwc'); // text for the 404 page
	$showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
	$showOnHome  = 1; // 1 - show breadcrumbs on the homepage, 0 - don't show
	$delimiter   = '&nbsp;&frasl;&nbsp;'; // delimiter between crumbs
	$before      = '<span class="current">'; // tag before the current crumb
	$after       = '</span>'; // tag after the current crumb
	/* === END OF OPTIONS === */
	global $post;
	$homeLink   = esc_url(home_url()) . '/';
	$linkBefore = '<span typeof="v:Breadcrumb">';
	$linkAfter = '</span>';
	$linkAttr = ' rel="v:url" property="v:title"';
	$link = $linkBefore . '<a' . $linkAttr . ' href="%1$s">%2$s</a>' . $linkAfter;
	if (is_home() || is_front_page()) {
		if ($showOnHome == 1) echo '<div id="crumbs"><a href="' . $homeLink . '">' . $text['home'] . '</a></div>';
	} else {
		echo '<div id="crumbs" xmlns:v="http://rdf.data-vocabulary.org/#">' . sprintf($link, $homeLink, $text['home']) . $delimiter;

		if ( is_category() ) {
			$thisCat = get_category(get_query_var('cat'), false);
			if ($thisCat->parent != 0) {
				$cats = get_category_parents($thisCat->parent, TRUE, $delimiter);
				$cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
				$cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
				echo $cats;
			}
			echo $before . sprintf($text['category'], single_cat_title('', false)) . $after;
		} elseif( is_tax() ){
			$thisCat = get_category(get_query_var('cat'), false);
			if ($thisCat->parent != 0) {
				$cats = get_category_parents($thisCat->parent, TRUE, $delimiter);
				$cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
				$cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
				echo $cats;
			}
			echo $before . sprintf($text['tax'], single_cat_title('', false)) . $after;

		}elseif ( is_search() ) {
			echo $before . sprintf($text['search'], get_search_query()) . $after;
		} elseif ( is_day() ) {
			echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
			echo sprintf($link, get_month_link(get_the_time('Y'),get_the_time('m')), get_the_time('F')) . $delimiter;
			echo $before . get_the_time('d') . $after;
		} elseif ( is_month() ) {
			echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
			echo $before . get_the_time('F') . $after;
		} elseif ( is_year() ) {
			echo $before . get_the_time('Y') . $after;
		} elseif ( is_single() && !is_attachment() ) {
			if ( get_post_type() != 'post' ) {
				$post_type = get_post_type_object(get_post_type());
				$slug = $post_type->rewrite;
				printf($link, $homeLink . '/' . $slug['slug'] . '/', $post_type->labels->singular_name);
				if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after;
			} else {
				$cat = get_the_category(); $cat = $cat[0];
				$cats = get_category_parents($cat, TRUE, $delimiter);
				if ($showCurrent == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
				$cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
				$cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
				echo $cats;
				if ($showCurrent == 1) echo $before . get_the_title() . $after;
			}
		} elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
			$post_type = get_post_type_object(get_post_type());
			echo $before . $post_type->labels->singular_name . $after;
		} elseif ( is_attachment() ) {
			$parent = get_post($post->post_parent);
			$cat = get_the_category($parent->ID); $cat = $cat[0];
			$cats = get_category_parents($cat, TRUE, $delimiter);
			$cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
			$cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
			echo $cats;
			printf($link, get_permalink($parent), $parent->post_title);
			if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after;
		} elseif ( is_page() && !$post->post_parent ) {
			if ($showCurrent == 1) echo $before . get_the_title() . $after;
		} elseif ( is_page() && $post->post_parent ) {
			$parent_id  = $post->post_parent;
			$breadcrumbs = array();
			while ($parent_id) {
				$page = get_page($parent_id);
				$breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
				$parent_id  = $page->post_parent;
			}
			$breadcrumbs = array_reverse($breadcrumbs);
			for ($i = 0; $i < count($breadcrumbs); $i++) {
				echo $breadcrumbs[$i];
				if ($i != count($breadcrumbs)-1) echo $delimiter;
			}
			if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after;
		} elseif ( is_tag() ) {
			echo $before . sprintf($text['tag'], single_tag_title('', false)) . $after;
		} elseif ( is_author() ) {
	 		global $author;
			$userdata = get_userdata($author);
			echo $before . sprintf($text['author'], $userdata->display_name) . $after;
		} elseif ( is_404() ) {
			echo $before . $text['404'] . $after;
		}
		if ( get_query_var('paged') ) {
			if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
			echo esc_html__( 'Page', 'martialwc' ) . ' ' . get_query_var('paged');
			if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
		}
		echo '</div>';
	}
} // end martialwc_breadcrumbs()

endif;

if ( ! function_exists( 'martialwc_sidebar_select' ) ) :
/**
 * Select and show sidebar based on post meta and customizer default settings
 */
function martialwc_sidebar_select() {
	get_sidebar('left');
}
endif;


add_action( 'wp_enqueue_scripts', 'martialwc_prettyphoto' );

if (  ! function_exists( 'martialwc_prettyphoto' ) ) {

	/**
	 * Enqueue prettyphoto on pages
	 */
	function martialwc_prettyphoto() {
		global $woocommerce;
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		if( ( class_exists('woocommerce') && ( is_woocommerce() || is_cart() || is_checkout() ) ) || is_front_page() ) {
			wp_enqueue_script( 'prettyPhoto', get_template_directory_uri() . '/js/jquery.prettyPhoto' . $suffix . '.js', array( 'jquery' ), null, true );
			wp_enqueue_script( 'prettyPhoto-init', get_template_directory_uri() . '/js/jquery.prettyPhoto.init' . $suffix . '.js', array( 'jquery' ), null, true );
			wp_enqueue_style( 'woocommerce_prettyPhoto_css', get_template_directory_uri() . '/css/prettyPhoto.css' ); // Prettyphoto css prefixed with woocommerce to make sure it won't load twice
		}
	}
}

if ( ! function_exists( 'martialwc_the_custom_logo' ) ) {
	/**
	 * Displays the optional custom logo.
	 *	 *
	 */
	function martialwc_the_custom_logo() {
		if ( function_exists( 'the_custom_logo' )  && ( get_theme_mod( 'martialwc_logo', '' ) == '') ) {
			the_custom_logo();
		}
	}
}

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function martialwc_body_classes( $classes ) {
	// Adds a class for grid/list layout.
	$layout_class = esc_attr(get_theme_mod( 'martialwc_archive_page_style', 'archive-list' ));
	if (  is_archive() || is_search() || is_home() ) {
		$classes[] = $layout_class;
	}
	return $classes;
}
add_filter( 'body_class', 'martialwc_body_classes' );

/**
 * Migrate any existing theme CSS codes added in Customize Options to the core option added in WordPress 4.7
 */
function martialwc_custom_css_migrate() {
	if ( function_exists( 'wp_update_custom_css_post' ) ) {
		$custom_css = get_theme_mod( 'martialwc_custom_css' );
		if ( $custom_css ) {
			$core_css = wp_get_custom_css(); // Preserve any CSS already added to the core option.
			$return = wp_update_custom_css_post( $core_css . $custom_css );
			if ( ! is_wp_error( $return ) ) {
				// Remove the old theme_mod, so that the CSS is stored in only one place moving forward.
				remove_theme_mod( 'martialwc_custom_css' );
			}
		}
	}
}
add_action( 'after_setup_theme', 'martialwc_custom_css_migrate' );

/**
 * Function to transfer the Header Logo added in Customizer Options of theme to Site Logo in Site Identity section
 */
function martialwc_site_logo_migrate() {
	if ( function_exists( 'the_custom_logo' ) && ! has_custom_logo( $blog_id = 0 ) ) {
		$logo_url = get_theme_mod( 'martialwc_logo' );

		if ( $logo_url ) {
			$customizer_site_logo_id = attachment_url_to_postid( $logo_url );
			set_theme_mod( 'custom_logo', $customizer_site_logo_id );

			// Delete the old Site Logo theme_mod option.
			remove_theme_mod( 'martialwc_logo' );
		}
	}
}

add_action( 'after_setup_theme', 'martialwc_site_logo_migrate' );

if ( ! function_exists( 'martialwc_the_custom_logo' ) ) {
	/**
	 * Displays the optional custom logo.
	 *	 *
	 */
	function martialwc_the_custom_logo() {
		if ( function_exists( 'the_custom_logo' )  && ( get_theme_mod( 'martialwc_logo', '' ) == '') ) {
			the_custom_logo();
		}
	}
}

if ( ! function_exists( 'martialwc_slugify' ) ) {
	function martialwc_slugify($str){
		# special accents
		$a = array('À','Á','Â','Ã','Ä','Å','Æ','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ð','Ñ','Ò','Ó','Ô','Õ','Ö','Ø','Ù','Ú','Û','Ü','Ý','ß','à','á','â','ã','ä','å','æ','ç','è','é','ê','ë','ì','í','î','ï','ñ','ò','ó','ô','õ','ö','ø','ù','ú','û','ü','ý','ÿ','A','a','A','a','A','a','C','c','C','c','C','c','C','c','D','d','Ð','d','E','e','E','e','E','e','E','e','E','e','G','g','G','g','G','g','G','g','H','h','H','h','I','i','I','i','I','i','I','i','I','i','?','?','J','j','K','k','L','l','L','l','L','l','?','?','L','l','N','n','N','n','N','n','?','O','o','O','o','O','o','Œ','œ','R','r','R','r','R','r','S','s','S','s','S','s','Š','š','T','t','T','t','T','t','U','u','U','u','U','u','U','u','U','u','U','u','W','w','Y','y','Ÿ','Z','z','Z','z','Ž','ž','?','ƒ','O','o','U','u','A','a','I','i','O','o','U','u','U','u','U','u','U','u','U','u','?','?','?','?','?','?');
		$b = array('A','A','A','A','A','A','AE','C','E','E','E','E','I','I','I','I','D','N','O','O','O','O','O','O','U','U','U','U','Y','s','a','a','a','a','a','a','ae','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','o','u','u','u','u','y','y','A','a','A','a','A','a','C','c','C','c','C','c','C','c','D','d','D','d','E','e','E','e','E','e','E','e','E','e','G','g','G','g','G','g','G','g','H','h','H','h','I','i','I','i','I','i','I','i','I','i','IJ','ij','J','j','K','k','L','l','L','l','L','l','L','l','l','l','N','n','N','n','N','n','n','O','o','O','o','O','o','OE','oe','R','r','R','r','R','r','S','s','S','s','S','s','S','s','T','t','T','t','T','t','U','u','U','u','U','u','U','u','U','u','U','u','W','w','Y','y','Y','Z','z','Z','z','Z','z','s','f','O','o','U','u','A','a','I','i','O','o','U','u','U','u','U','u','U','u','U','u','A','a','AE','ae','O','o');
		return strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/','/[ -]+/','/^-|-$/'),array('','-',''),str_replace($a,$b,$str)));
	}
}
