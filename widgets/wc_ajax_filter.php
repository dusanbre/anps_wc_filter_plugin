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
		$title                 = isset( $instance['title'] ) ? $instance['title'] : '';
		$anps_wc_filter_cat    = isset( $instance['anps_wc_filter_cat'] ) ? $instance['anps_wc_filter_cat'] : '';
		$anps_wc_filter_attr   = isset( $instance['anps_wc_filter_attr'] ) ? $instance['anps_wc_filter_attr'] : '';
		$anps_wc_filter_onsale = isset( $instance['anps_wc_filter_onsale'] ) ? $instance['anps_wc_filter_onsale'] : '';
		$anps_wc_filter_price  = isset( $instance['anps_wc_filter_price'] ) ? $instance['anps_wc_filter_price'] : '';

		$all_attr    = wc_get_attribute_taxonomies();
		$all_cat     = get_terms( 'product_cat' );
		$price_range = $this->helper_get_price_range();

		$min_price = floor( $price_range->min_price / 10 ) * 10;
		$max_price = ceil( $price_range->max_price / 10 ) * 10;
		?>


<!-- provera -->
<section class="sidebar-widget">
    <div class="sidebar-widget__filter">
        <h3 class="sidebar-widget__filter-heading"><?php echo esc_html__( 'Filter', 'anps_wc_filter' ); ?></h3>
    </div>

    <div class="sidebar-widget__category">
        <h1 class="sidebar-widget__category-heading" onclick="toggleCat()">
            <?php echo esc_html__( 'Category', 'anps_wc_filter' ); ?>
        </h1>
        <div class="sidebar-widget__category-group-1" id="visible-cat">
            <ul>
                <?php
				foreach ( $all_cat as $cat ) :
					?>
                <li>
                    <label><input type="checkbox"
                            value="<?php echo esc_attr( $cat->slug ); ?>" /><?php echo esc_html__( $cat->name, 'anps_wc_filter' ); ?>
                    </label><span><?php echo esc_html__( $cat->count, 'anps_wc_filter' ); ?></span>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="sidebar-widget__category-group-2">
            <ul>
                <li>
                    <label><?php echo esc_html__( 'Onsale', 'anps_wc_filter' ); ?><input type="checkbox"
                            value="onsale" /></label>
                </li>
                <li>
                    <label>Price<input type="checkbox" /></label>
                </li>
            </ul>
        </div>
    </div>

    <div class="sidebar-widget__price">
        <div class="multi-range-slider">
            <input type="range" id="input-left" min="0" max="600" value="0" />
            <input type="range" id="input-right" min="0" max="600" value="600" />

            <div class="sidebar-widget__price-slider">
                <div class="sidebar-widget__price-slider-track"></div>
                <div class="sidebar-widget__price-slider-range"></div>
                <div class="sidebar-widget__price-slider-thumb left"></div>
                <div class="sidebar-widget__price-slider-thumb right"></div>
            </div>

            <div class="value">
                <span class="value-left"></span>
                <span class="value-right"></span>
            </div>
        </div>
    </div>

    <div class="sidebar-widget__menu">
        <h1 class="sidebar-widget__menu-heading" onclick="toggleColor()">
            Color
        </h1>

        <div class="sidebar-widget__menu-group-1" id="visible-color">
            <ul>
                <li>
                    <label><input type="checkbox" value="green" class="color_inp" /><span
                            class="green color"></span>Green</label><span class="num">2</span>
                </li>
                <li>
                    <label><input type="checkbox" value="blue" class="color_inp" /><span
                            class="blue color"></span>Blue</label><span class="num">2</span>
                </li>
                <li>
                    <label><input type="checkbox" value="red" class="color_inp" /><span
                            class="red color"></span>Red</label><span class="num">2</span>
                </li>
            </ul>
        </div>
    </div>

    <div class="sidebar-widget__menu">
        <h1 class="sidebar-widget__menu-heading" onclick="toggleSize()">Size</h1>

        <div class="sidebar-widget__menu-group-1" id="visible-size">
            <ul>
                <li>
                    <label><input type="checkbox" value="m" /><span>M</span></label><span class="num">2</span>
                </li>
                <li>
                    <label><input type="checkbox" value="s" /><span>S</span></label><span class="num">2</span>
                </li>
                <li>
                    <label><input type="checkbox" value="XL" /><span>XL</span></label><span class="num">2</span>
                </li>
                <li>
                    <label><input type="checkbox" value="XXL" /><span>XXL</span></label><span class="num">2</span>
                </li>
            </ul>
        </div>
    </div>

    <button class="filter-btn">Filter</button>
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