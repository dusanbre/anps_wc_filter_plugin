<?php
/*
Plugin Name: Anps Wc Filter
Plugin URI: https://anpsthemes.com/
Description: Plugin for adding filter for wc products
Version: 1.0.0
Author: Anpsthemes
Text Domain: anps_wc_filter
*/

defined( 'ABSPATH' ) || exit;

require_once 'widgets/wc_ajax_filter.php';

function anps_enqueue_plugin_assets_front() {
	wp_enqueue_script( 'anps-widget-plugin-frontend', plugin_dir_url( __FILE__ ) . 'js/main.js', array( 'jquery' ), true );
	wp_enqueue_style( 'anps-widget-style-frontend', plugin_dir_url( __FILE__ ) . 'css/main.css', array(), '1.0', false );
}

function anps_init_widget_plugin() {
	register_widget( 'Anps_WC_Ajax_Filter_Widget' );
}

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	add_action( 'widgets_init', 'anps_init_widget_plugin' );
	add_action( 'wp_enqueue_scripts', 'anps_enqueue_plugin_assets_front' );
} else {
	echo 'WooCommerce is not Active.';
}

