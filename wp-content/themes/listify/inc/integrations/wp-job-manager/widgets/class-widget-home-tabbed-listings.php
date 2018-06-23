<?php
/**
 * Home: Tabbed Listings
 *
 * @since Listify 1.0.0
 */
class Listify_Widget_Tabbed_Listings extends Listify_Widget {

	public function __construct() {
		$this->widget_description = __( 'Display a tabbed layout of listing types', 'listify' );
		$this->widget_id          = 'listify_widget_tabbed_listings';
		$this->widget_name        = __( 'Listify - Page: Category Tabs', 'listify' );
		$this->widget_areas       = array( 'widget-area-home', 'widget-area-page' ); // valid widget areas
		$this->settings           = array(
			'title'    => array(
				'type'  => 'text',
				'std'   => 'What\'s New',
				'label' => __( 'Title:', 'listify' ),
			),
			'limit'    => array(
				'type'  => 'number',
				'std'   => 3,
				'min'   => 3,
				'max'   => 30,
				'step'  => 1,
				'label' => __( 'Number per tab:', 'listify' ),
			),
			'columns'  => array(
				'type'    => 'select',
				'std'     => '3',
				'label'   => __( 'Display Columns:', 'listify' ),
				'options' => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
				),
			),
			'featured' => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => __( 'Use Featured listings', 'listify' ),
			),
			'terms'    => array(
				'label'   => __( 'Categories:', 'listify' ),
				'type'    => 'multiselect-term',
				'std'     => '',
				'options' => 'job_listing_category',
			),
		);

		parent::__construct();
	}

	function widget( $args, $instance ) {
		// Check widget areas context.
		if ( ! is_singular( 'page' ) ) {
			echo $this->widget_areas_notice(); // WPCS: XSS ok.

			return false;
		}

		$this->instance = $instance;

		extract( $args );

		$title    = apply_filters( 'widget_title', isset( $instance['title'] ) ? $instance['title'] : '', $instance, $this->id_base );
		$limit    = isset( $instance['limit'] ) ? absint( $instance['limit'] ) : 3;
		$columns  = isset( $instance['columns'] ) ? absint( $instance['columns'] ) : 3;
		$featured = isset( $instance['featured'] ) && 1 == $instance['featured'] ? true : null;
		$orderby  = isset( $instance['orderby'] ) ? $instance['orderby'] : 'date';

		$terms = isset( $instance['terms'] ) ? maybe_unserialize( $instance['terms'] ) : false;

		$args = apply_filters(
			'listify_widget_tabbed_categories_get_terms', array(
				'include' => $terms,
			)
		);

		$terms = listify_get_terms( $args );

		if ( ! $terms ) {
			return;
		}

		ob_start();

		echo $before_widget; // WPCS: XSS ok.

		if ( $title ) {
			echo $before_title . $title . $after_title; // WPCS: XSS ok.
		}
?>

<ul class="tabbed-listings-tabs">
	<?php foreach ( $terms as $term ) : ?>
		<li><a href="#tab-<?php echo esc_attr( $term->term_id ); ?>"><?php echo esc_attr( $term->name ); ?></a></li>
	<?php endforeach; ?>

	<li><a href="<?php echo esc_url( get_post_type_archive_link( 'job_listing' ) ); ?>"><?php esc_html_e( 'See More', 'listify' ); ?></a></li>
</ul>

<div class="tabbed-listings-tabs-wrapper">

	<?php foreach ( $terms as $term ) : ?>

	<div id="tab-<?php echo esc_attr( $term->term_id ); ?>" class="listings-tab">

		<?php
			$listings = listify_get_listings(
				array(
					'anchor'     => '#tab-' . esc_attr( $term->term_id ) . ' ul.job_listings',
					'query_args' => array(
						'posts_per_page' => $limit,
						'featured'       => $featured,
						'orderby'        => $orderby,
						'no_found_rows'  => true,
						'post__in'       => get_objects_in_term(
							$term->term_id, 'job_listing_category', array(
								'orderby' => $orderby,
							)
						),
					),
					'columns'    => $columns,
				)
			);

			echo '<ul class="job_listings"></ul>'
		?>

	</div>

	<?php endforeach; ?>

</div>

<?php
		echo $after_widget; // WPCS: XSS ok.

		$content = ob_get_clean();

		echo apply_filters( $this->widget_id, $content ); // WPCS: XSS ok.
	}

}
