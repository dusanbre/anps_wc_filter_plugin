<?php
/*
Plugin Name: Anps Wc Filter
Plugin URI: https://anpsthemes.com/
Description: Plugin for adding filter for wc products
Version: 1.0.0
Author: Anpsthemes
Text Domain: anps_wc_filter
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once 'widgets/wc_ajax_filter.php';

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	function anps_init_widget_plugin() {
		register_widget( 'Anps_WC_Ajax_Filter_Widget' );
	}
	add_action( 'widgets_init', 'anps_init_widget_plugin' );
} else {
	echo 'WooCommerce is not Active.';
}
