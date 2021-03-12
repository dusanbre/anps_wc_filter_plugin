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
require_once 'inc/meta_box.php';

function anps_enqueue_plugin_assets_front() {

	wp_enqueue_script( 'anps-jq-slider', plugin_dir_url( __FILE__ ) . 'js/jquery-ui-slider.min.js', array( 'jquery' ), true );
	wp_enqueue_script( 'anps-widget-plugin-frontend', plugin_dir_url( __FILE__ ) . 'js/main.js', array( 'jquery' ), true );

	wp_enqueue_style( 'anps-jq-slide-style', plugin_dir_url( __FILE__ ) . 'css/jquery-ui-slider.min.css', array(), '1.12.1', false );
	wp_enqueue_style( 'anps-widget-style-frontend', plugin_dir_url( __FILE__ ) . 'css/main.css', array(), '1.0', false );
}

function anps_init_widget_plugin() {
	register_widget( 'Anps_WC_Ajax_Filter_Widget' );
}

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	add_action( 'pa_color_add_form_fields', 'anps_color_add_term_fields' );
	add_action( 'pa_color_edit_form_fields', 'anps_color_edit_term_fields', 10, 2 );
	add_action( 'created_pa_color', 'anps_color_save_term_fields' );
	add_action( 'edited_pa_color', 'anps_color_save_term_fields' );
	add_action( 'widgets_init', 'anps_init_widget_plugin' );
	add_action( 'wp_enqueue_scripts', 'anps_enqueue_plugin_assets_front' );
} else {
	echo 'WooCommerce is not Active.';
}