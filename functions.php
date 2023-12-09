Custom product meta field
-------------------------

/**
* Display custom product meta field in cart and checkout pages
*/

add_filter( 'woocommerce_cart_item_name', 'customizing_cart_item_name', 10, 3 );
function customizing_cart_item_name( $product_name, $cart_item, $cart_item_key ) {
	$product = $cart_item['data']; // Get the WC_Product Object

	if ( $value = $product->get_meta('isbn') ) {
		$product_name .= '<br><small>ISBN: '.$value.'</small>';
	}

	return $product_name;
}

/**
* Display custom product meta field in order-receive/my-account/emails
*/

add_action( 'woocommerce_order_item_meta_start', 'ecommercehints_order_item_meta_start', 10, 4 );
function ecommercehints_order_item_meta_start($item_id, $item, $order, $plain_text) {
	$the_order_id = $order->get_id();
	$order_items = $order->get_items();
	$custom_field_variable = get_post_meta( $item->get_product_id(), 'isbn', true ); // Change custom_field_name to the name of your custom product meta field
	echo '<p><span>ISBN:</span> ' . $custom_field_variable . '</p>';
}

/**
* Display custom product meta field only on WooCommerce admin orders
*/

add_action( 'woocommerce_after_order_itemmeta', 'display_admin_order_item_custom_button', 10, 3 );
function display_admin_order_item_custom_button( $item_id, $item, $product ){
    // Only "line" items and backend order pages
    if( ! ( is_admin() && $item->is_type('line_item') ) )
        return;

   $custom_field_variable = get_post_meta( $item->get_product_id(), 'isbn', true ); // Change custom_field_name to the name of your custom product meta field
	echo '<p><span>ISBN:</span> ' . $custom_field_variable . '</p>';
}

Custom product taxonomy
-----------------------

/**
* Display custom product texonomoy in cart and checkout pages
*/

add_filter('woocommerce_cart_item_name', 'display_publisher_taxonomy_link_in_cart', 10, 3);

function display_publisher_taxonomy_link_in_cart($product_name, $cart_item, $cart_item_key) {
    // Get the product ID from the cart item
    $product_id = $cart_item['product_id'];

    // Get the terms of the "publisher" custom taxonomy for the product
    $terms = wp_get_post_terms($product_id, 'publisher');

    // Display the "publisher" custom taxonomy term with link in the cart item name
    if (!empty($terms) && !is_wp_error($terms)) {
        $publisher = $terms[0]; // Assuming there's only one publisher per product
        $publisher_name = $publisher->name;
        $publisher_link = get_term_link($publisher);

        // Creating a link to the publisher term archive page
        if (!is_wp_error($publisher_link)) {
            $product_name .= '<br>Publisher: <a href="' . esc_url($publisher_link) . '">' . esc_html($publisher_name) . '</a>';
        }
    }

    return $product_name;
}

/**
* Display product category in cart and checkout pages
*/

add_filter( 'woocommerce_cart_item_name', 'bbloomer_cart_item_category', 9999, 3 );
function bbloomer_cart_item_category( $name, $cart_item, $cart_item_key ) {
 
   $product = $cart_item['data'];
   if ( $product->is_type( 'variation' ) ) {
      $product = wc_get_product( $product->get_parent_id() );
   }
	
   $cat_ids = $product->get_category_ids();
 
   if ( $cat_ids ){
	   $name .= '<br>' . wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( 'Genre:', 'Genre:', count( $cat_ids ), 'woocommerce' ) . ' ', '</span>' );
   } 
 
   return $name;
 
}
