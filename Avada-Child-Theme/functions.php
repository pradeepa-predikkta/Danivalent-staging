<?php
function implement_ajax() {
	if (isset($_POST['couponcode'])){
		$product_id  = intval( $_POST['product_id'] );
		$couponcode = $_POST['couponcode'];
		global $woocommerce; 

		$items = $woocommerce->cart->get_cart();
		$ids = [];
        foreach($items as $item => $values) { 
            $_product =  wc_get_product( $values['data']->get_id());
            $ids[] =  $values['data']->get_id();
          
        }
        if(!in_array($product_id, $ids)){
			WC()->cart->add_to_cart( $product_id );
			WC()->cart->remove_coupons();
		    $ret = WC()->cart->add_discount( $couponcode ); 
		    $array = array('return' => $ret);
		    print_r(json_encode($ret));
		    exit();
		}
	    exit();
	}
    
    // include(TEMPLATEPATH . '/ajax_return.php');
}

add_action('wp_ajax_add_coupon_action', 'implement_ajax');
add_action('wp_ajax_nopriv_add_coupon_action', 'implement_ajax');

/*Check User log in function*/
function ajax_check_user_logged_in() {
    echo is_user_logged_in()?'yes':'no';
    die();
}
add_action('wp_ajax_is_user_logged_in', 'ajax_check_user_logged_in');
add_action('wp_ajax_nopriv_is_user_logged_in', 'ajax_check_user_logged_in');


/*add customer query*/
function add_custom_query_var( $vars ){
  $vars[] = "type";
  $vars[] = "value";
  return $vars;
}
add_filter( 'query_vars', 'add_custom_query_var' );

// Check if it is an switching
// 31116 is grouped produtc is, so we know which add to cart button is clicked
// if it is switching and the product added is 517 : annual membership
// then add coupon code.
function apply_product_on_coupon( ) {
   	// WC()->cart->add_discount($coupon_id);
   	if (isset($_GET["quantity"])) {
        $prodId = $_GET["quantity"];
    } else if (isset($_POST["quantity"])) {
        $prodId = $_POST["quantity"];
    } else {
        $prodId = null;
    }

    if (isset($_GET["add-to-cart"])) {
        $groupedId = (int)$_GET["add-to-cart"];
    } else if (isset($_POST["add-to-cart"])) {
        $groupedId = (int)$_POST["add-to-cart"];
    } else {
        $groupedId = null;
    }
    if($groupedId == "31116" && array_key_exists("517", $prodId)) {
    	$coupon_id = 'dvsubscriptionupgrade';
    	WC()->cart->add_discount($coupon_id);
    }
   
}
 add_action( 'woocommerce_add_to_cart', 'apply_product_on_coupon', 0); 

/**
 * @snippet       WooCommerce Add New Tab @ My Account
 * @how-to        Watch tutorial @ https://businessbloomer.com/?p=19055
 * @sourcecode    https://businessbloomer.com/?p=21253
 * @credits       https://github.com/woothemes/woocommerce/wiki/2.6-Tabbed-My-Account-page
 * @author        Rodolfo Melogli
 * @testedwith    WooCommerce 2.6.7
 */
 
 
// ------------------
// 1. Register new endpoint to use for My Account page
// Note: Resave Permalinks or it will give 404 error
 
// function bbloomer_add_favourite_endpoint() {
//     add_rewrite_endpoint( 'favourite', EP_ROOT | EP_PAGES );
// }
 
// add_action( 'init', 'bbloomer_add_favourite_endpoint' );
 
 
// // // ------------------
// // // 2. Add new query var
 
// function bbloomer_favourite_query_vars( $vars ) {
//     $vars[] = 'favourite';
//     return $vars;
// }
 
// add_filter( 'query_vars', 'bbloomer_favourite_query_vars', 0 );
 
 
// // // ------------------
// // // 3. Insert the new endpoint into the My Account menu
 
// function bbloomer_add_favourite_link_my_account( $items ) {
//     $items['favourite'] = 'Your Favourites';
//     return $items;
// }
 
// add_filter( 'woocommerce_account_menu_items', 'bbloomer_add_favourite_link_my_account' );
 
 
// // // ------------------
// // // 4. Add content to the new endpoint
 
// function bbloomer_favourite_content() {
// echo '<h3>Your Favourites</h3>';
// echo do_shortcode( '[user_favorites user_id="" include_links="true" site_id="" include_buttons="false" post_types="media" include_thumbnails="false" thumbnail_size="thumbnail" include_excerpt="false"] ' );
// }
 
// add_action( 'woocommerce_account_favourite_endpoint', 'bbloomer_favourite_content' );

/**
 * Add a confirm password field to the checkout registration form.
 */
function o_woocommerce_confirm_password_checkout( $checkout ) {
    if ( get_option( 'woocommerce_registration_generate_password' ) == 'no' ) {

        $fields = $checkout->get_checkout_fields();

        $fields['account']['account_confirm_password'] = array(
            'type'              => 'password',
            'label'             => __( 'Confirm password', 'woocommerce' ),
            'required'          => true,
            'placeholder'       => _x( 'Confirm Password', 'placeholder', 'woocommerce' )
        );
        $fields['billing']['billing_confirm_email'] = array(
            'type'              => 'text',
            'label'             => __( 'Confirm email', 'woocommerce' ),
            'required'          => true,
            'placeholder'       => _x( 'Confirm email', 'placeholder', 'woocommerce' )
        );

        $checkout->__set( 'checkout_fields', $fields );
    }
}
add_action( 'woocommerce_checkout_init', 'o_woocommerce_confirm_password_checkout', 10, 1 );

/**
 * Validate that the two password fields match.
 */
function o_woocommerce_confirm_password_validation( $posted ) {
    $checkout = WC()->checkout;
    if ( ! is_user_logged_in() && ( $checkout->must_create_account || ! empty( $posted['createaccount'] ) ) ) {
        if ( strcmp( $posted['account_password'], $posted['account_confirm_password'] ) !== 0 ) {
            wc_add_notice( __( 'Passwords do not match.', 'woocommerce' ), 'error' );
        }
        if ( strcmp( $posted['billing_email'], $posted['billing_confirm_email'] ) !== 0 ) {
            wc_add_notice( __( 'Emails do not match.', 'woocommerce' ), 'error' );
        }
    }
}
add_action( 'woocommerce_after_checkout_validation', 'o_woocommerce_confirm_password_validation', 10, 2 );

add_filter("woocommerce_checkout_fields", "order_fields");

function order_fields($fields) {
	if ( get_option( 'woocommerce_registration_generate_password' ) == 'no' ) {
	    $fields['billing']['billing_confirm_email']['priority'] = 20;
	}
	return $fields;
}



function avada_child_scripts() {
	if ( ! is_admin() && ! in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) ) ) {
		$theme_info = wp_get_theme();
		wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'avada-stylesheet' ) );
	}
}
add_action('wp_enqueue_scripts', 'avada_child_scripts');


function avada_lang_setup() {

	$lang = get_stylesheet_directory() . '/languages';

	load_child_theme_textdomain( 'Avada', $lang );

}

add_action( 'after_setup_theme', 'avada_lang_setup' );

/* Widget Locations */

if(function_exists('register_sidebar')) {

	   register_sidebar(array(
		'name' => 'Home Banner',
		'id' => 'hm-smartslider-position',
		'before_widget' => '<div class="hm-slider-widget">',
		'after_widget' => '<div style="clear:both;"></div></div>'
	));
}

/* Woocommerce - Single Products */


/*
 * wc_remove_related_products
 *
 * Clear the query arguments for related products so none show.
 * Add this code to your theme functions.php file.
 */


/*function wc_remove_related_products( $args ) {
	return array();
}
add_filter('woocommerce_related_products_args','wc_remove_related_products', 10); */

/*function hide_coupon_field_on_cart( $enabled ) {

	if ( is_cart() ) {
		$enabled = false;
	}

	return $enabled;
}
add_filter( 'woocommerce_coupons_enabled', 'hide_coupon_field_on_cart' );*/


/* RC modifications */
function rc_scripts() {

	/*
	if(is_single()) {
		wp_register_script( 'vimeo_script', 'http://f.vimeocdn.com/js/froogaloop2.min.js' );
		wp_enqueue_script( 'vimeo_script' );
	}*/
	wp_enqueue_script( 'rc_script', site_url() . '/wp-content/themes/Avada-Child-Theme/script.js' );
	wp_localize_script('rc_script', 'post_list', array( 'template_url' => site_url() . '/wp-content/themes/Avada-Child-Theme/' ) );
}
add_action( 'wp_enqueue_scripts', 'rc_scripts' );

/* Widgets */
/*
register_sidebar(array('name' => 'Recipes introduction'));
register_sidebar(array('name' => 'Chefs introduction'));
register_sidebar(array('name' => 'Category List'));
*/

/* Add columns to custom post type listing */
add_filter( 'manage_edit-media_columns', 'admin_media_header_columns', 10, 1);
add_action( 'manage_media_posts_custom_column', 'admin_media_data_row', 10, 2);
function admin_media_header_columns($columns)
{
	if (!isset($columns['category']))
		$columns['category'] = "Categories";
	// if (!isset($columns['home_feature']))
	// 	$columns['home_feature'] = "Home feature";
	if (!isset($columns['content_type']))
		$columns['content_type'] = "Content Type";
	// if (!isset($columns['sneak_peek']))
	// 	$columns['sneak_peek'] = "Sneak Peek";

	return $columns;
}
function admin_media_data_row($column_name, $post_id)
{
	switch($column_name)
	{
		case 'category':
			// Logic to display post 'Note' field information here.
			// $post_note = get_post_meta($post_id, 'note', true);
			//if ($post_note)	echo $post_note;
			$categories = get_the_terms($post_id, "media_categories");
			if($categories) {
				if(count($categories) > 0) {
					$i = 0;
					foreach($categories as $category) {
						$i++;
						if ($i == count($categories)) {
							echo $category->name;
						} else {
							echo $category->name . ", ";
						}
					}
				}
				else {
					echo "No category";
				}
			}
			break;
		// case 'home_feature':
		// 	$feature_article = get_post_meta($post_id, 'farticle', true);
		// 	if(isset($feature_article['free'])) { echo "Free<br>"; }
		// 	if(isset($feature_article['registered'])) { echo "Registered<br>"; }
		// 	if(isset($feature_article['paid'])) { echo "Paid"; }
		// 	break;
		case 'content_type':
			$content_type = get_post_meta($post_id, 'ispaid', true);
			if(isset($content_type['free'])) { echo "Free<br>"; }
			if(isset($content_type['paid'])) { echo "Paid"; }
			break;
		// case 'sneak_peek':
		// 	$sneakpeek = get_post_meta($post_id, 'sneakpeek', true);
		// 	if ($sneakpeek) {
		// 		echo "Yes";
		// 	}
		// 	break;
		default:
			break;
	}
}

add_action( 'restrict_manage_posts', 'my_restrict_manage_posts' );
function my_restrict_manage_posts() {
	$taxonomy = 'media_categories';
	$tax_obj = get_taxonomy('media_categories');
	$tax_name = $tax_obj->labels->name;
	$terms = get_terms('media_categories');
	echo "<select name='media_categories' id='media_categories' class='postform'>";
	echo "<option value=''>Show All Categories</option>";
	foreach ($terms as $term) {
		echo '<option value='. $term->slug, $_GET['media_categories'] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
	}
	echo "</select>";
}

/* Widget Locations */

if(function_exists('register_sidebar')) {

	   register_sidebar(array(
		'name' => 'Home Banner',
		'id' => 'hm-smartslider-position',
		'before_widget' => '<div class="hm-slider-widget">',
		'after_widget' => '<div style="clear:both;"></div></div>'
	));
}

/* Woocommerce - Single Products */


/*
 * wc_remove_related_products
 *
 * Clear the query arguments for related products so none show.
 * Add this code to your theme functions.php file.
 */


function wc_remove_related_products( $args ) {
	return array();
}
add_filter('woocommerce_related_products_args','wc_remove_related_products', 10);

function hide_coupon_field_on_cart( $enabled ) {

	if ( is_cart() ) {
		$enabled = false;
	}

	return $enabled;
}
add_filter( 'woocommerce_coupons_enabled', 'hide_coupon_field_on_cart' );

/**
 *Reduce the strength requirement on the woocommerce password.
 *
 * Strength Settings
 * 3 = Strong (default)
 * 2 = Medium
 * 1 = Weak
 * 0 = Very Weak / Anything
 */
 /*
function reduce_woocommerce_min_strength_requirement( $strength ) {
    return 1;
}
add_filter( 'woocommerce_min_password_strength', 'reduce_woocommerce_min_strength_requirement' );
*/

/* Remove requirement from billing phone field in checkout */

function unrequire_wc_phone_field( $fields ) {
    $fields['billing_phone']['required'] = false;
    return $fields;
}
add_filter( 'woocommerce_billing_fields', 'unrequire_wc_phone_field' );

add_filter( 'wc_password_strength_meter_params', 'mr_strength_meter_custom_strings' );
function mr_strength_meter_custom_strings( $data ) {
    $data_new = array(
		'min_password_strength' => apply_filters( 'woocommerce_min_password_strength', 1 ),
        'i18n_password_error'   => esc_attr__( '<span class="mr-red">Please enter a stronger password.</span>', 'woocommerce' ),
        'i18n_password_hint'    => esc_attr__( 'We advise password should be <strong>at least 7 characters</strong> and contain a mix of <strong>UPPER</strong> and <strong>lowercase</strong> letters, <strong>numbers</strong>, and <strong>symbols</strong> (e.g., <strong> ! " ? $ % ^ & </strong>).', 'woocommerce' )
    );

    return array_merge( $data, $data_new );
}

/* UC Custom Code */

// Custom redirect for users after registering
add_filter('woocommerce_registration_redirect', 'dv_register_redirect');
function dv_register_redirect( $redirect ) {
     $redirect = '/cook/recipes/';
     return $redirect;
}

// Custom redirect for users after logging in
add_filter('woocommerce_login_redirect', 'dv_login_redirect');
function dv_login_redirect( $redirect ) {
     $redirect = '/cook/recipes/';
     return $redirect;
}

add_filter( 'fusion_builder_shortcode_migration_post_types', 'add_custom_post_types' );
function add_custom_post_types( $post_types ) {
    $my_post_types = array(
        'media',
    );
    return $my_post_types;
}

// Custom Read more
add_filter( 'avada_blog_read_more_excerpt', 'my_read_more_symbol' );
function my_read_more_symbol( $read_more ) {
 $read_more = '...';

 return $read_more;
}

// Check if category has children
function check_cat_children() {
global $wpdb;
$term = get_queried_object();
$check = $wpdb->get_results(" SELECT * FROM wp_term_taxonomy WHERE parent = '$term->term_id' ");
     if ($check) {
          return true;
     } else {
          return false;
     }
}


// Remove website from user comments
function disable_website_field($fields)
{
if(isset($fields['url']))
unset($fields['url']);
return $fields;
}

add_filter('comment_form_default_fields', 'disable_website_field');


// Breadcrumbs
function custom_breadcrumbs() {
       
    // Settings
    $separator          = '&raquo;';
    $breadcrums_id      = 'breadcrumbs';
    $breadcrums_class   = 'breadcrumbs';
    $home_title         = 'Home';
      
    // If you have any custom post types with custom taxonomies, put the taxonomy name below (e.g. product_cat)
    $custom_taxonomy    = 'product_cat';
       
    // Get the query & post information
    global $post,$wp_query;
       
    // Do not display on the homepage
    if ( !is_front_page() ) {
       
        // Build the breadcrums
        echo '<ul id="' . $breadcrums_id . '" class="' . $breadcrums_class . '">';
           
        // Home page
        echo '<li class="item-home"><a class="bread-link bread-home" href="' . get_home_url() . '" title="' . $home_title . '">' . $home_title . '</a></li>';
        echo '<li class="separator separator-home"> ' . $separator . ' </li>';
           
        if ( is_archive() && !is_tax() && !is_category() && !is_tag() ) {
              
            echo '<li class="item-current item-archive"><strong class="bread-current bread-archive">' . post_type_archive_title($prefix, false) . '</strong></li>';
              
        } else if ( is_archive() && is_tax() && !is_category() && !is_tag() ) {
              
            // If post is a custom post type
            $post_type = get_post_type();
              
            // If it is a custom post type display name and link
            if($post_type != 'post') {
                  
                $post_type_object = get_post_type_object($post_type);
                $post_type_archive = get_post_type_archive_link($post_type);

                echo '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
                echo '<li class="separator"> ' . $separator . ' </li>';
              
            }
              
            $custom_tax_name = get_queried_object()->name;
            echo '<li class="item-current item-archive"><strong class="bread-current bread-archive">' . $custom_tax_name . '</strong></li>';
              
        } else if ( is_single() ) {
              
            // If post is a custom post type
            $post_type = get_post_type();
              
            // If it is a custom post type display name and link
            if($post_type != 'post') {
                  
                $post_type_object = get_post_type_object($post_type);
                $post_type_archive = get_post_type_archive_link($post_type);
              	if($post_type == "media"){
              		echo '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="/cook/" title="' . $post_type_object->labels->name . '">Cook</a></li>';
	                echo '<li class="separator"> ' . $separator . ' </li>';
	                echo '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="/cook/recipes/" title="' . $post_type_object->labels->name . '">Recipes</a></li>';
	                echo '<li class="separator"> ' . $separator . ' </li>';
	              
	                // echo '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
	                // echo '<li class="separator"> ' . $separator . ' </li>';
            	}else{
            		echo '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
	                echo '<li class="separator"> ' . $separator . ' </li>';
            	}
              
            }
              
            // Get post category info
            $category = get_the_category();
             
            if(!empty($category)) {
              
                // Get last category post is in
                $last_category = end(array_values($category));
                  
                // Get parent any categories and create array
                $get_cat_parents = rtrim(get_category_parents($last_category->term_id, true, ','),',');
                $cat_parents = explode(',',$get_cat_parents);
                  
                // Loop through parent categories and store in variable $cat_display
                $cat_display = '';
                foreach($cat_parents as $parents) {
                    $cat_display .= '<li class="item-cat">'.$parents.'</li>';
                    $cat_display .= '<li class="separator"> ' . $separator . ' </li>';
                }
             
            }
              
            // If it's a custom post type within a custom taxonomy
            $taxonomy_exists = taxonomy_exists($custom_taxonomy);
            if(empty($last_category) && !empty($custom_taxonomy) && $taxonomy_exists) {
                   
                $taxonomy_terms = get_the_terms( $post->ID, $custom_taxonomy );
                $cat_id         = $taxonomy_terms[0]->term_id;
                $cat_nicename   = $taxonomy_terms[0]->slug;
                $cat_link       = get_term_link($taxonomy_terms[0]->term_id, $custom_taxonomy);
                $cat_name       = $taxonomy_terms[0]->name;
               
            }
              
            // Check if the post is in a category
            if(!empty($last_category)) {
                echo $cat_display;
                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';
                  
            // Else if post is in a custom taxonomy
            } else if(!empty($cat_id)) {
                  
                echo '<li class="item-cat item-cat-' . $cat_id . ' item-cat-' . $cat_nicename . '"><a class="bread-cat bread-cat-' . $cat_id . ' bread-cat-' . $cat_nicename . '" href="' . $cat_link . '" title="' . $cat_name . '">' . $cat_name . '</a></li>';
                echo '<li class="separator"> ' . $separator . ' </li>';
                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';
              
            } else {
                  
                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';
                  
            }
              
        } else if ( is_category() ) {
               
            // Category page
            echo '<li class="item-current item-cat"><strong class="bread-current bread-cat">' . single_cat_title('', false) . '</strong></li>';
               
        } else if ( is_page() ) {
               
            // Standard page
            if( $post->post_parent ){
                   
                // If child page, get parents 
                $anc = get_post_ancestors( $post->ID );
                   
                // Get parents in the right order
                $anc = array_reverse($anc);
                   
                // Parent page loop
                if ( !isset( $parents ) ) $parents = null;
                foreach ( $anc as $ancestor ) {
                    $parents .= '<li class="item-parent item-parent-' . $ancestor . '"><a class="bread-parent bread-parent-' . $ancestor . '" href="' . get_permalink($ancestor) . '" title="' . get_the_title($ancestor) . '">' . get_the_title($ancestor) . '</a></li>';
                    $parents .= '<li class="separator separator-' . $ancestor . '"> ' . $separator . ' </li>';
                }
                   
                // Display parent pages
                echo $parents;
                   
                // Current page
                echo '<li class="item-current item-' . $post->ID . '"><strong title="' . get_the_title() . '"> ' . get_the_title() . '</strong></li>';
                   
            } else {
                   
                // Just display current page if not parents
                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '"> ' . get_the_title() . '</strong></li>';
                   
            }
               
        } else if ( is_tag() ) {
               
            // Tag page
               
            // Get tag information
            $term_id        = get_query_var('tag_id');
            $taxonomy       = 'post_tag';
            $args           = 'include=' . $term_id;
            $terms          = get_terms( $taxonomy, $args );
            $get_term_id    = $terms[0]->term_id;
            $get_term_slug  = $terms[0]->slug;
            $get_term_name  = $terms[0]->name;
               
            // Display the tag name
            echo '<li class="item-current item-tag-' . $get_term_id . ' item-tag-' . $get_term_slug . '"><strong class="bread-current bread-tag-' . $get_term_id . ' bread-tag-' . $get_term_slug . '">' . $get_term_name . '</strong></li>';
           
        } elseif ( is_day() ) {
               
            // Day archive
               
            // Year link
            echo '<li class="item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';
            echo '<li class="separator separator-' . get_the_time('Y') . '"> ' . $separator . ' </li>';
               
            // Month link
            echo '<li class="item-month item-month-' . get_the_time('m') . '"><a class="bread-month bread-month-' . get_the_time('m') . '" href="' . get_month_link( get_the_time('Y'), get_the_time('m') ) . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</a></li>';
            echo '<li class="separator separator-' . get_the_time('m') . '"> ' . $separator . ' </li>';
               
            // Day display
            echo '<li class="item-current item-' . get_the_time('j') . '"><strong class="bread-current bread-' . get_the_time('j') . '"> ' . get_the_time('jS') . ' ' . get_the_time('M') . ' Archives</strong></li>';
               
        } else if ( is_month() ) {
               
            // Month Archive
               
            // Year link
            echo '<li class="item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';
            echo '<li class="separator separator-' . get_the_time('Y') . '"> ' . $separator . ' </li>';
               
            // Month display
            echo '<li class="item-month item-month-' . get_the_time('m') . '"><strong class="bread-month bread-month-' . get_the_time('m') . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</strong></li>';
               
        } else if ( is_year() ) {
               
            // Display year archive
            echo '<li class="item-current item-current-' . get_the_time('Y') . '"><strong class="bread-current bread-current-' . get_the_time('Y') . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</strong></li>';
               
        } else if ( is_author() ) {
               
            // Auhor archive
               
            // Get the author information
            global $author;
            $userdata = get_userdata( $author );
               
            // Display author name
            echo '<li class="item-current item-current-' . $userdata->user_nicename . '"><strong class="bread-current bread-current-' . $userdata->user_nicename . '" title="' . $userdata->display_name . '">' . 'Author: ' . $userdata->display_name . '</strong></li>';
           
        } else if ( get_query_var('paged') ) {
               
            // Paginated archives
            echo '<li class="item-current item-current-' . get_query_var('paged') . '"><strong class="bread-current bread-current-' . get_query_var('paged') . '" title="Page ' . get_query_var('paged') . '">'.__('Page') . ' ' . get_query_var('paged') . '</strong></li>';
               
        } else if ( is_search() ) {
           
            // Search results page
            echo '<li class="item-current item-current-' . get_search_query() . '"><strong class="bread-current bread-current-' . get_search_query() . '" title="Search results for: ' . get_search_query() . '">Search results for: ' . get_search_query() . '</strong></li>';
           
        } elseif ( is_404() ) {
               
            // 404 page
            echo '<li>' . 'Error 404' . '</li>';
        }
       
        echo '</ul>';
           
    }
       
}
//remove the default function in includes/class-avada-woocommerce.php
add_action( 'woocommerce_init', 'remove_dashboard' );
function remove_dashboard() {
    global $avada_woocommerce;
    remove_action( 'woocommerce_account_dashboard', array( $avada_woocommerce, 'account_dashboard' ),5 );
}
//add our custom code here.
add_action('woocommerce_account_dashboard','move_bottom_information_to_bottom_of_dashboard',20);
function move_bottom_information_to_bottom_of_dashboard(){
	echo "<style>
		.woocommerce-MyAccount-content{/* display: -webkit-flex;*/display: -ms-flexbox;/*display:flex;*/-webkit-flex-flow: column wrap;flex-flow: column nowrap; }
		.avada-woocommerce-myaccount-heading{ -ms-flex-order: 0;-webkit-order: 0;order: 0; }
		.woocommerce-MyAccount-content > p, .woocommerce-MyAccount-content > div, .woocommerce-MyAccount-content > span{ -ms-flex-order: 1;-webkit-order: 1;order: 1; }
		.woocommerce-MyAccount-content > p:first-child { display: none; }
		</style>" ;
	if ( is_account_page() ) {
			$account_items = wc_get_account_menu_items();
			$heading_content = esc_attr__( 'Dashboard', 'Avada' );
			$isDashboard = true;
			if ( is_wc_endpoint_url( 'orders' ) ) {
				$heading_content = $account_items['orders'];
				$isDashboard = false;
			} elseif ( is_wc_endpoint_url( 'downloads' ) ) {
				$heading_content = $account_items['downloads'];
				$isDashboard = false;
			} elseif ( is_wc_endpoint_url( 'payment-methods' ) ) {
				$heading_content = $account_items['payment-methods'];
				$isDashboard = false;
			} elseif ( is_wc_endpoint_url( 'edit-account' ) ) {
				$heading_content = $account_items['edit-account'];
				$isDashboard = false;
			}
			?>
			<h2 class="avada-woocommerce-myaccount-heading test">
				<?php echo $heading_content; // WPCS: XSS ok. ?>
			</h2>
			<?php
				if($isDashboard ){ ?>
					<p><?php
						printf(
							__( 'From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">shipping and billing addresses</a> and <a href="%3$s">edit your password and account details</a>.', 'woocommerce' ),
							esc_url( wc_get_endpoint_url( 'orders' ) ),
							esc_url( wc_get_endpoint_url( 'edit-address' ) ),
							esc_url( wc_get_endpoint_url( 'edit-account' ) )
						);
					?></p>
				<?php } ?>
			<?php
		}
}
