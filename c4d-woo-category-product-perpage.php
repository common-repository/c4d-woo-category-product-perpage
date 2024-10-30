<?php
/*
Plugin Name: C4D Woocommerce Category Product Perpage
Plugin URI: http://coffee4dev.com/
Description: Add a product per page box at Woocommerce category page.
Author: Coffee4dev.com
Author URI: http://coffee4dev.com/
Text Domain: c4d-woo-cpp
Version: 2.0.4
*/

define('C4DWCPP_PLUGIN_URI', plugins_url('', __FILE__));

add_action( 'woocommerce_before_shop_loop', 'c4dwcpp_auto_add_to_category_page', 50);
add_action( 'wp_enqueue_scripts', 'c4dwcpp_safely_add_stylesheet_to_frontsite');
add_filter( 'loop_shop_per_page', 'c4dwcpp_product_perpage', 20 );
add_filter( 'plugin_row_meta', 'c4dwcpp_plugin_row_meta', 10, 2 );
add_shortcode( 'c4dwcpp', 'c4dwcpp_shortcode');
add_action( 'c4d-plugin-manager-section', 'c4dwcpp_section_options');

function c4dwcpp_plugin_row_meta( $links, $file ) {
    if ( strpos( $file, basename(__FILE__) ) !== false ) {
        $new_links = array(
            'visit' => '<a href="http://coffee4dev.com">Visit Plugin Site</<a>',
            'premium' => '<a href="http://coffee4dev.com">Premium Support</<a>'
        );
        $links = array_merge( $links, $new_links );
    }
    return $links;
}

function c4dwcpp_safely_add_stylesheet_to_frontsite( $page ) {
	wp_enqueue_style( 'c4dwcpp-frontsite-style', C4DWCPP_PLUGIN_URI.'/assets/default.css' );
	wp_enqueue_script( 'c4dwcpp-frontsite-plugin-js', C4DWCPP_PLUGIN_URI.'/assets/default.js', array( 'jquery' ), false, true ); 
}

function c4dwcpp_product_perpage ( $count ) {
	global $c4d_plugin_manager;

	if (isset($c4d_plugin_manager['c4d-woo-cpp-default']) && $c4d_plugin_manager['c4d-woo-cpp-default'] != '') {
		$count = $c4d_plugin_manager['c4d-woo-cpp-default'];
	}

	if (isset($_GET['product_perpage']) && $_GET['product_perpage'] != '') {
		return esc_attr($_GET['product_perpage']);
	}

	return $count;
}

function c4dwcpp_shortcode ($params) {
	return c4dwcpp_select_box();
}

function c4dwcpp_auto_add_to_category_page() {
	echo c4dwcpp_select_box();
}

function c4dwcpp_select_box() {
	ob_start();
	$template = get_template_part('c4d-woo-category-product-perpage/templates/default');
	if ($template && file_exists($template)) {
		require $template;
	} else {
		require dirname(__FILE__). '/templates/default.php';
	}
	$html = ob_get_contents();
	ob_end_clean();
	return $html;
}

function c4dwcpp_section_options(){
    $opt_name = 'c4d_plugin_manager';
    Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Product Perpage', 'c4d-woo-cpp' ),
        'id'               => 'c4d-woo-cpp',
        'desc'             => '',
        'customizer_width' => '400px',
        'icon'             => 'el el-home',
        'fields'           => array(
            array(
                'id'       => 'c4d-woo-cpp-select-box',
                'type'     => 'text',
                'title'    => esc_html__('Select Box', 'c4d-woo-cpp'),
                'default'  => '3,6,12,9,15'
            ),
            array(
                'id'       => 'c4d-woo-cpp-default',
                'type'     => 'text',
                'title'    => esc_html__('Default', 'c4d-woo-cpp'),
                'default'  => '12'
            )
        )
    ));
}