<?php
/**
 * Single Product title
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/title.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author     WooThemes
 * @package    WooCommerce/Templates
 * @version    1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
// get all product cats for the current post
$categories = get_the_terms( get_the_ID(), 'product_cat' ); 

// wrapper to hide any errors from top level categories or products without category
if ( $categories && ! is_wp_error( $category ) ) : 

    // loop through each cat
    foreach($categories as $category) :

      // get the children (if any) of the current cat
      $children = get_categories( array ('taxonomy' => 'product_cat', 'parent' => $category->term_id ));

      if ( count($children) == 0 ) {
         // if no children, then echo the category name.
          echo "<h1>".$category->name."</h1>";
      }
    endforeach;

endif;
the_title( '<h2 class="product_title entry-title">', '</h2>' );
