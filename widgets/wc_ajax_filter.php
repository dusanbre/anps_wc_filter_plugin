<?php
defined( 'ABSPATH' ) || exit;
// Creating the widget
class Anps_WC_Ajax_Filter_Widget extends WP_Widget {


	function __construct() {
		parent::__construct(
			'Anps_WC_Ajax_Filter_Widget',
			__( 'Anpsthemes WC Ajax Filter', 'anps_wc_filter' ),
			array( 'description' => __( 'Widget for filtering products', 'anps_wc_filter' ) )
		);
	}

		// Creating widget front-end
	public function widget( $args, $instance ) {
		$title                 = isset( $instance['title'] ) ? $instance['title'] : '';
		$anps_wc_filter_cat    = isset( $instance['anps_wc_filter_cat'] ) ? $instance['anps_wc_filter_cat'] : '';
		$anps_wc_filter_attr   = isset( $instance['anps_wc_filter_attr'] ) ? $instance['anps_wc_filter_attr'] : '';
		$anps_wc_filter_onsale = isset( $instance['anps_wc_filter_onsale'] ) ? $instance['anps_wc_filter_onsale'] : '';
		$anps_wc_filter_price  = isset( $instance['anps_wc_filter_price'] ) ? $instance['anps_wc_filter_price'] : '';

		$get_all_attr = wc_get_attribute_taxonomies();
		$all_cat      = get_terms(
			'product_cat',
			array(
				'order'      => 'ASC',
				'hide_empty' => true,
			)
		);
		$price_range  = $this->helper_get_price_range();

		$min_price = floor( $price_range->min_price / 10 ) * 10;
		$max_price = ceil( $price_range->max_price / 10 ) * 10;

		$get_cat       = isset( $_GET['product_cat'] ) ? explode( ',', $_GET['product_cat'] ) : '';
		$get_min_price = isset( $_GET['min_price'] ) ? $_GET['min_price'] : $min_price;
		$get_max_price = isset( $_GET['max_price'] ) ? $_GET['max_price'] : $max_price;

		$get_attr_array = array();
		$merged_attr    = array();

		foreach ( $_GET as $key => $item ) {
			if ( strpos( $key, 'filter_' ) !== false ) {
				array_push( $get_attr_array, explode( ',', $item ) );
			}
		}
		foreach ( $get_attr_array as $a ) {
			$merged_attr = array_merge( $merged_attr, $a );
		}
		?>



<section class="sbw_sidebar-widget">
	<div class="sbw_sidebar-widget__filter">
		<h3 class="sbw_sidebar-widget__filter-heading"><?php echo esc_html__( 'Filter', 'anps_wc_filter' ); ?></h3>
	</div>

	<div class="sbw_sidebar-widget__category"
		style="<?php echo $anps_wc_filter_cat ? esc_attr( 'display:block;' ) : esc_attr( 'display:none;' ); ?>">
		<h1 class="sbw_sidebar-widget__category-heading">
			<?php echo esc_html__( 'Category', 'anps_wc_filter' ); ?>
		</h1>
		<div class="sbw_sidebar-widget__category-group-1">
			<ul>
				<?php foreach ( $all_cat as $cat ) : ?>
					<?php
					if ( $cat->parent == 0 ) :
						$parent_checked = '';
						if ( $get_cat !== '' ) {
							if ( in_array( $cat->slug, $get_cat ) ) {
								$parent_checked = ' checked';
							}
						}
						?>
				<li>
					<label><input type="checkbox" id="<?php echo esc_attr( $cat->taxonomy ); ?>"
							value="<?php echo esc_attr( $cat->slug ); ?>"
							<?php echo $parent_checked; ?> /><span><?php echo esc_html__( $cat->name, 'anps_wc_filter' ); ?></span>
					</label><span><?php echo esc_html__( $cat->count, 'anps_wc_filter' ); ?></span>
				</li>
						<?php
						$sub = get_terms(
							'product_cat',
							array(
								'hide_empty' => true,
								'parent'     => $cat->term_id,
							)
						);
						foreach ( $sub as $sc ) :
							$child_checked = '';
							if ( $get_cat !== '' ) {
								if ( in_array( $sc->slug, $get_cat ) ) {
									$child_checked = ' checked';
								}
							}
							?>
				<li class="<?php echo esc_attr( 'child' ); ?>">
					<label><input type="checkbox" id="<?php echo esc_attr( $sc->taxonomy ); ?>"
							value="<?php echo esc_attr( $sc->slug ); ?>"
							<?php echo $child_checked; ?> /><?php echo esc_html__( $sc->name, 'anps_wc_filter' ); ?>
					</label><span><?php echo esc_html__( $sc->count, 'anps_wc_filter' ); ?></span>
				</li>
						<?php endforeach; ?>
				<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</div>
		<div class="sbw_sidebar-widget__category-group-2"
			style="<?php echo $anps_wc_filter_onsale ? esc_attr( 'display:block;' ) : esc_attr( 'display:none;' ); ?>">
			<ul>
				<?php if ( wc_get_product_ids_on_sale() ) : ?>
				<li>
					<label><?php echo esc_html__( 'Onsale', 'anps_wc_filter' ); ?><input type="checkbox" id="on_sale"
							value="onsale" /></label>
				</li>
				<?php endif; ?>
			</ul>
		</div>
	</div>

	<div class="sbw_sidebar-widget__price"
		style="<?php echo $anps_wc_filter_price ? esc_attr( 'display:block;' ) : esc_attr( 'display:none;' ); ?>">
		<h3><?php echo esc_html__( 'Price', 'anps_wc_filter' ); ?></h3>
		<div class="stw-multi-range-slider">
			<p id="range-values">
				<input type="text" id="amount_min" value="<?php echo esc_attr( $min_price ); ?>"
					style="display:none;" />
				<input type="text" id="amount_max" value="<?php echo esc_attr( $max_price ); ?>"
					style="display:none;" />
			</p>
			<!-- Slider element || empty div -->
			<div class="sbw_sidebar-widget__price-slider" id="anps-price-range-slider"></div>
			<div class="value">
				<span class="value-left" id="<?php echo esc_attr( $get_min_price ); ?>">Min:
					<?php echo $get_min_price; ?></span>
				<span class="value-right" id="<?php echo esc_attr( $get_max_price ); ?>">Max:
					<?php echo $get_max_price; ?></span>
			</div>
		</div>
	</div>

		<?php foreach ( $get_all_attr as $attr ) : ?>
			<?php
			$attr_variations                          = array();
			$attr_variations[ $attr->attribute_name ] = get_terms( 'pa_' . $attr->attribute_name );

			?>
	<div class="sbw_sidebar-widget__menu"
		style="<?php echo $anps_wc_filter_attr ? esc_attr( 'display:block;' ) : esc_attr( 'display:none;' ); ?>">
		<h1 class="sbw_sidebar-widget__menu-heading">
			<?php echo esc_html__( $attr->attribute_label, 'anps_wc_filter' ); ?>
		</h1>

		<div class="sbw_sidebar-widget__menu-group-1">
			<ul>
				<?php foreach ( $attr_variations as $key => $item ) : ?>

					<?php if ( $key == 'color' ) : ?>
						<?php

						foreach ( $item as $c ) :
							$item_color = get_term_meta( $c->term_id, 'anps_hex_color_attr', true );
							if ( $merged_attr !== '' ) {
								$color_checked = '';
								if ( in_array( $c->slug, $merged_attr ) ) {
									$color_checked = ' checked';
								}
							}
							?>
				<li data-attr="<?php echo esc_attr( $key ); ?>">
					<label><input type="checkbox" id="<?php echo esc_attr( $key ); ?>"
							value="<?php echo esc_attr( $c->slug ); ?>" class="color_inp"
							<?php echo $color_checked; ?> /><span class="color"
							style="background-color:<?php echo esc_attr( $item_color ); ?>"></span><?php echo esc_html__( $c->name, 'anps_wc_filter' ); ?></label><span
						class="num"><?php echo $c->count; ?></span>
				</li>
				<?php endforeach; ?>
				<?php else : ?>
					<?php
					foreach ( $item as $o ) :
						if ( $merged_attr !== '' ) {
							$attr_checked = '';
							if ( in_array( $o->slug, $merged_attr ) ) {
								$attr_checked = ' checked';
							}
						}
						?>
				<li data-attr="<?php echo esc_attr( $key ); ?>">
					<label><input type="checkbox" id="<?php echo esc_attr( $key ); ?>"
							value="<?php echo esc_attr( $o->slug ); ?>"
							<?php echo $attr_checked; ?> /><span><?php echo esc_html__( $o->name, 'anps_wc_filter' ); ?></span></label><span
						class="num"><?php echo $o->count; ?></span>
				</li>
				<?php endforeach; ?>
				<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<?php endforeach; ?>
</section>

		<?php
	}

		// Widget Backend
	public function form( $instance ) {
		$title                 = isset( $instance['title'] ) ? $instance['title'] : '';
		$anps_wc_filter_cat    = isset( $instance['anps_wc_filter_cat'] ) ? $instance['anps_wc_filter_cat'] : '';
		$anps_wc_filter_attr   = isset( $instance['anps_wc_filter_attr'] ) ? $instance['anps_wc_filter_attr'] : '';
		$anps_wc_filter_onsale = isset( $instance['anps_wc_filter_onsale'] ) ? $instance['anps_wc_filter_onsale'] : '';
		$anps_wc_filter_price  = isset( $instance['anps_wc_filter_price'] ) ? $instance['anps_wc_filter_price'] : '';

		// Widget admin form
		?>
<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Widget Title:' ); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
		name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<p>
	<label
		for="<?php echo $this->get_field_id( 'anps_wc_filter_cat' ); ?>"><?php echo esc_html__( 'Filter By Category:', 'anps_wc_filter' ); ?></label>
	<input class="widefat" type="checkbox" name="<?php echo $this->get_field_name( 'anps_wc_filter_cat' ); ?>"
		id="<?php echo $this->get_field_id( 'anps_wc_filter_cat' ); ?>" value="1"
		<?php echo $anps_wc_filter_cat == '1' ? esc_attr( 'checked' ) : ''; ?> style="float:right;">
</p>
<p>
	<label
		for="<?php echo $this->get_field_id( 'anps_wc_filter_attr' ); ?>"><?php echo esc_html__( 'Filter By Attributes:', 'anps_wc_filter' ); ?></label>
	<input class="widefat" type="checkbox" name="<?php echo $this->get_field_name( 'anps_wc_filter_attr' ); ?>"
		id="<?php echo $this->get_field_id( 'anps_wc_filter_attr' ); ?>" value="1"
		<?php echo $anps_wc_filter_attr == '1' ? esc_attr( 'checked' ) : ''; ?> style="float:right;">
</p>
<p>
	<label
		for="<?php echo $this->get_field_id( 'anps_wc_filter_onsale' ); ?>"><?php echo esc_html__( 'Filter Product Onsale:', 'anps_wc_filter' ); ?></label>
	<input class="widefat" type="checkbox" name="<?php echo $this->get_field_name( 'anps_wc_filter_onsale' ); ?>"
		id="<?php echo $this->get_field_id( 'anps_wc_filter_onsale' ); ?>" value="1"
		<?php echo $anps_wc_filter_onsale == '1' ? esc_attr( 'checked' ) : ''; ?> style="float:right;">
</p>
<p>
	<label
		for="<?php echo $this->get_field_id( 'anps_wc_filter_price' ); ?>"><?php echo esc_html__( 'Filter By Price:', 'anps_wc_filter' ); ?></label>
	<input class="widefat" type="checkbox" name="<?php echo $this->get_field_name( 'anps_wc_filter_price' ); ?>"
		id="<?php echo $this->get_field_id( 'anps_wc_filter_price' ); ?>" value="1"
		<?php echo $anps_wc_filter_price == '1' ? esc_attr( 'checked' ) : ''; ?> style="float:right;">
</p>
		<?php
	}

		// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance                          = array();
		$instance['title']                 = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['anps_wc_filter_cat']    = ( ! empty( $new_instance['anps_wc_filter_cat'] ) ) ? strip_tags( $new_instance['anps_wc_filter_cat'] ) : '';
		$instance['anps_wc_filter_attr']   = ( ! empty( $new_instance['anps_wc_filter_attr'] ) ) ? strip_tags( $new_instance['anps_wc_filter_attr'] ) : '';
		$instance['anps_wc_filter_onsale'] = ( ! empty( $new_instance['anps_wc_filter_onsale'] ) ) ? strip_tags( $new_instance['anps_wc_filter_onsale'] ) : '';
		$instance['anps_wc_filter_price']  = ( ! empty( $new_instance['anps_wc_filter_price'] ) ) ? strip_tags( $new_instance['anps_wc_filter_price'] ) : '';
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
