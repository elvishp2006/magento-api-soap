<?php
namespace Magento\API\SOAP;

// Avoid that files are directly loaded
if ( ! function_exists( 'add_action' ) ) :
	exit(0);
endif;

class Category
{
	/**
	 * Category ID
	 * @var string
	 */
	private $category_id;

	/**
	 * Defines whether the category is active
	 * @var integer
	 */
	private $is_active;

	/**
	 * Category position
	 * @var string
	 */
	private $position;

	/**
	 * Category level
	 * @var string
	 */
	private $level;

	/**
	 * Parent category ID
	 * @var string
	 */
	private $parent_id;

	/**
	 * All child categories of the current category
	 * @var string
	 */
	private $all_children;

	/**
	 * Names of direct child categories
	 * @var string
	 */
	private $children;

	/**
	 * Date when the category was created
	 * @var string
	 */
	private $created_at;

	/**
	 * Date when the category was updated
	 * @var string
	 */
	private $updated_at;

	/**
	 * Category name
	 * @var string
	 */
	private $name;

	/**
	 * A relative URL path which can be entered in place of the standard target path (optional)
	 * @var string
	 */
	private $url_key;

	/**
	 * Category description
	 * @var string
	 */
	private $description;

	/**
	 * Category meta title
	 * @var string
	 */
	private $meta_title;

	/**
	 * Category meta keywords
	 * @var string
	 */
	private $meta_keywords;

	/**
	 * Category meta description
	 * @var string
	 */
	private $meta_description;

	/**
	 * Path
	 * @var string
	 */
	private $path;

	/**
	 * URL path
	 * @var string
	 */
	private $url_path;

	/**
	 * Number of child categories
	 * @var integer
	 */
	private $children_count;

	/**
	 * Content that will be displayed on the category view page (optional)
	 * @var string
	 */
	private $display_mode;

	/**
	 * Defines whether the category is anchored
	 * @var integer
	 */
	private $is_anchor;

	/**
	 * All available options by which products in the category can be sorted
	 * @var array
	 */
	private $available_sort_by;

	/**
	 * The custom design for the category (optional)
	 * @var string
	 */
	private $custom_design;

	/**
	 * Apply the custom design to all products assigned to the category (optional)
	 * @var string
	 */
	private $custom_apply_to_products;

	/**
	 * Date starting from which the custom design will be applied to the category (optional)
	 * @var string
	 */
	private $custom_design_from;

	/**
	 * Date till which the custom design will be applied to the category (optional)
	 * @var string
	 */
	private $custom_design_to;

	/**
	 * Type of page layout that the category should use (optional)
	 * @var string
	 */
	private $page_layout;

	/**
	 * Custom layout update (optional)
	 * @var string
	 */
	private $custom_layout_update;

	/**
	 * The default option by which products in the category are sorted
	 * @var string
	 */
	private $default_sort_by;

	/**
	 * Landing page (optional)
	 * @var integer
	 */
	private $landing_page;

	/**
	 * Defines whether the category is available on the Magento top menu bar
	 * @var integer
	 */
	private $include_in_menu;

	/**
	 * Price range of each price level displayed in the layered navigation block
	 * @var string
	 */
	private $filter_price_range;

	/**
	 * Defines whether the category will inherit custom design settings of the category to which it is assigned. 1 - Yes, 0 - No
	 * @var integer
	 */
	private $custom_use_parent_settings;

	/**
	 * The SOAP method name for category list
	 */
	const RESOURCE_LIST = 'catalog_category.tree';

	/**
	 * The SOAP method name for category info
	 */
	const RESOURCE_INFO = 'catalog_category.info';

	/**
	 * The constructor of this class
	 * @since 2.2.2
	 * @param integer    $ID The category id
	 */
	public function __construct( $ID = false )
	{
		if ( $ID ) :
			$this->_populate_fields( $ID );
		endif;
	}

	/**
	 * Magic method to get a property
	 * @since  2.2.2
	 * @param  string    $property_name The property name
	 * @return mixed                    The property value
	 */
	public function __get( $property_name )
	{
		return $this->$property_name;
	}

	/**
	 * Find instances of this class
	 * @since  2.2.2
	 * @param  array     $args Params to find
	 * @return mixed           Result of the find
	 */
	public function find( $args = array() )
	{
		$defaults = array(
			'parentId' => false,
		);

		$args = wp_parse_args( $args, $defaults );

		$magento_model = new Magento();

		return $this->_parse( $magento_model->get_api_result( $this::RESOURCE_LIST, $args[ 'parentId' ] ) );
	}

	/**
	 * Get permalink
	 * @since  2.3.2
	 * @return string    The permalink
	 */
	public function get_permalink()
	{
		$setting_model = new Setting();

		$store_url = $setting_model->store_url;

		if ( $store_url[ strlen( $store_url ) - 1 ] != '/' ) :
			$store_url .= '/';
		endif;

		return "{$store_url}{$this->url_path}";
	}

	/**
	 * Parse the find result
	 * @since  1.0.0
	 * @param  array    $category_list The result of find
	 * @return mixed                   The result of the parse
	 */
	private function _parse( $category_list, &$list = array() )
	{
		if ( ! $category_list ) :
			return false;
		endif;

		$category_list = isset( $category_list['children'] ) ? $category_list['children'] : $category_list;

		foreach ( $category_list as $category ) :
			$model  = new $this( $category['category_id'] );
			$list[] = $model;

			unset( $model );

			$this->_parse( $category['children'], $list );
		endforeach;

		$std                = new \stdClass();
		$std->list          = $list;
		$std->category_list = $category_list;

		return $std;
	}

	/**
	 * Populate the fields of this class
	 * @since  2.2.2
	 * @param  mixed     $ID The category id
	 * @return void
	 */
	private function _populate_fields( $ID )
	{
		$magento_model = new Magento();

		$category = $magento_model->get_api_result( $this::RESOURCE_INFO, $ID );

		foreach ( $this as $key => $value ) :
			$this->$key = isset( $category[ $key ] ) ? $category[ $key ] : $this->$key;
		endforeach;

		unset( $magento );
	}
}