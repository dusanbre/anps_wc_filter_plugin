<?php
defined( 'ABSPATH' ) || exit;
// Creating the widget
class Anps_WC_Ajax_Filter_Widget extends WP_Widget {


	function __construct() {
		parent::__construct(
			'Anps_WC_Ajax_Filter_Widget',
			__( 'Anpstheme WC Ajax Filter', 'anps_wc_filter' ),
			array( 'description' => __( 'Widget for filtering products', 'anps_wc_filter' ) )
		);
	}

		// Creating widget front-end
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		?>
		<section class="widget">

			<div class="widget__filter">
				<h3 class="widget__filter-heading">Filter</h3>
			</div>

			<div class="widget__category">
				<h1 class="widget__category-heading" onclick='toggle()'>Category</h1>
				<div class="widget__category-group-1" id='visible'>
					<ul>
						<li><label><input type="checkbox">Category-1 </label><span>1</span></li>
						<li><label><input type="checkbox">Category-2</label><span>5</span></li>
						<li><label><input type="checkbox">Category-3</label><span>1</span></li>
						<li><label><input type="checkbox">Category-4</label><span>3</span></li>
						<li><label><input type="checkbox">Category-5</label><span>2</span></li>
					</ul>
				</div>
				<div class="widget__category-group-2">
					<ul>
						<li><label>Category<input type="checkbox"></label></li>
						<li><label>Category<input type="checkbox"></label></li>
					</ul>
				</div>
			</div>

		</section>

		
		
		<?php
	}

		// Widget Backend
	public function form( $instance ) {
		$title              = isset( $instance['title'] ) ? $instance['title'] : '';
		$anps_wc_filter_cat = isset( $instance['instance_chechbox_array']['anps_wc_filter_cat'] ) ? $instance['instance_chechbox_array']['anps_wc_filter_cat'] : '';

		$all_attr    = wc_get_attribute_taxonomies();
		$all_cat     = get_terms( 'product_cat' );
		$price_range = $this->helper_get_price_range();

		$min_price = floor( $price_range->min_price / 10 ) * 10;
		$max_price = ceil( $price_range->max_price / 10 ) * 10;
		// echo '<pre>';
		print_r( $instance['instance_chechbox_array']['anps_wc_filter_cat'] );
		// Widget admin form
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Widget Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'anps_wc_filter_cat' ); ?>"><?php echo esc_html__( 'Filter By Category:', 'anps_wc_filter' ); ?></label>
		<input class="widefat" type="checkbox" name="<?php echo $this->get_field_name( 'anps_wc_filter_cat' ); ?>" id="<?php echo $this->get_field_id( 'anps_wc_filter_cat' ); ?>" value="1" <?php echo $anps_wc_filter_cat == '1' ? esc_attr( 'checked' ) : ''; ?> style="float:right;">
		</p>
		<p>
		<p>sadsa</p>
		</p>
		<?php
	}

		// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance_chechbox_array                       = array();
		$instance_chechbox_array['anps_wc_filter_cat'] = ( ! empty( $new_instance['anps_wc_filter_cat'] ) ) ? $new_instance['anps_wc_filter_cat'] : '';
		// $instance_chechbox_array['anps_wc_filter_attr'] = ( ! empty( $new_instance['anps_wc_filter_attr'] ) ) ? $new_instance['anps_wc_filter_attr'] : '';

		$instance                            = array();
		$instance['title']                   = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['instance_chechbox_array'] = ( ! empty( $instance_chechbox_array ) ) ? $instance_chechbox_array : '';
		return $instance;
	}

	protected function helper_get_price_range() {
		global $wpdb;

		$args       = WC()->query->get_main_query()->query_vars;
		$tax_query  = isset( $args['tax_query'] ) ? $args['tax_query'] : array();
		$meta_query = isset( $args['meta_query'] ) ? $args['meta_query'] : array();

		if ( ! is_post_type_archive( 'product' ) && ! empty( $args['taxonomy'] ) && ! empty( $args['term'] ) ) {
			$tax_query[] = WC()->query->get_main_tax_query();
		}

		foreach ( $meta_query + $tax_query as $key => $query ) {
			if ( ! empty( $query['price_filter'] ) || ! empty( $query['rating_filter'] ) ) {
				unset( $meta_query[ $key ] );
			}
		}

		$meta_query = new WP_Meta_Query( $meta_query );
		$tax_query  = new WP_Tax_Query( $tax_query );
		$search     = WC_Query::get_main_search_query_sql();

		$meta_query_sql   = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql    = $tax_query->get_sql( $wpdb->posts, 'ID' );
		$search_query_sql = $search ? ' AND ' . $search : '';

		$sql = "
			SELECT min( min_price ) as min_price, MAX( max_price ) as max_price
			FROM {$wpdb->wc_product_meta_lookup}
			WHERE product_id IN (
				SELECT ID FROM {$wpdb->posts}
				" . $tax_query_sql['join'] . $meta_query_sql['join'] . "
				WHERE {$wpdb->posts}.post_type IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_post_type', array( 'product' ) ) ) ) . "')
				AND {$wpdb->posts}.post_status = 'publish'
				" . $tax_query_sql['where'] . $meta_query_sql['where'] . $search_query_sql . '
			)';

		$sql = apply_filters( 'woocommerce_price_filter_sql', $sql, $meta_query_sql, $tax_query_sql );

		return $wpdb->get_row( $sql );
	}
}
