<?php
namespace Magento\API\SOAP;

// Avoid that files are directly loaded
if ( ! function_exists( 'add_action' ) ) :
	exit(0);
endif;

App::uses( 'magento', 'Model' );

class Product
{
    /**
     * Product ID
     * @var string
     */
    private $product_id;

    /**
     * Product SKU
     * @var string
     */
    private $sku;

    /**
     * Product set
     * @var string
     */
    private $set;

    /**
     * Product type
     * @var string
     */
    private $type;

    /**
     * Array of categories
     * @var array
     */
    private $categories;

    /**
     * Array of websites
     * @var array
     */
    private $websites;

    /**
     * Date when the product was created
     * @var string
     */
    private $created_at;

    /**
     * Date when the product was last updated
     * @var string
     */
    private $updated_at;

    /**
     * Type ID
     * @var string
     */
    private $type_id;

    /**
     * Product name
     * @var string
     */
    private $name;

    /**
     * Product description
     * @var string
     */
    private $description;

    /**
     * Short description for a product
     * @var string
     */
    private $short_description;

    /**
     * Product weight
     * @var string
     */
    private $weight;

    /**
     * Status of a product
     * 1: Enabled 2: Disabled 3: Out-of-stock
     * @var string
     */
    private $status;

    /**
     * Relative URL path that can be entered in place of a target path
     * @var string
     */
    private $url_key;

    /**
     * URL path
     * @var string
     */
    private $url_path;

    /**
     * Product visibility on the frontend
     * @var string
     */
    private $visibility;

    /**
     * Array of category IDs
     * @var array
     */
    private $category_ids;

    /**
     * Array of website IDs
     * @var array
     */
    private $website_ids;

    /**
     * Defines whether the product has options
     * @var string
     */
    private $has_options;

    /**
     * Defines whether the gift message is available for the product
     * @var string
     */
    private $gift_message_available;

    /**
     * Product price
     * @var string
     */
    private $price;

    /**
     * Product special price
     * @var string
     */
    private $special_price;

    /**
     * Date starting from which the special price is applied to the product
     * @var string
     */
    private $special_from_date;

    /**
     * Date till which the special price is applied to the product
     * @var string
     */
    private $special_to_date;

    /**
     * Tax class ID
     * @var string
     */
    private $tax_class_id;

    /**
     * Array of catalogProductTierPriceEntity
     * @var array
     */
    private $tier_price;

    /**
     * Meta title
     * @var string
     */
    private $meta_title;

    /**
     * Meta keyword
     * @var string
     */
    private $meta_keyword;

    /**
     * Meta description
     * @var string
     */
    private $meta_description;

    /**
     * Custom design
     * @var string
     */
    private $custom_design;

    /**
     * Custom layout update
     * @var string
     */
    private $custom_layout_update;

    /**
     * Options container
     * @var string
     */
    private $options_container;

    /**
     * Array of additional attributes
     * @var array
     */
    private $additional_attributes;

    /**
     * Defines whether Google Checkout is applied to the product
     * @var string
     */
    private $enable_googlecheckout;

    /**
     * The SOAP method name for product info
     * @var string
     */
    const RESOURCE_INFO = 'catalog_product.info';

    /**
    * The SOAP method name for product list
    * @var string
     */
    const RESOURCE_LIST = 'catalog_product.list';

    /**
    * The SOAP method name for product media
    * @var string
     */
    const RESOURCE_MEDIA = 'product_media.list';

	/**
    * The SOAP method name for product based on category
    * @var string
     */
    const RESOURCE_CATEGORY = 'catalog_category.assignedProducts';

    /**
     * The constructor of this class
     * @since 1.0.0
     * @param string    $ID The Product ID
     */
    public function __construct( $ID = false )
    {
        if ( $ID ) :
            $this->_populate_fields( $ID );
        endif;
    }

    /**
     * Magic method to get a property
     * @since  1.0.0
     * @param  string    $property_name The property name
     * @return mixed                    The property value
     */
    public function __get( $property_name )
    {
        return $this->$property_name;
    }

	/**
	 * Get thumbnail
	 * @since  2.0.1
	 * @param  string    $size Size to get
	 * @return string          The image html
	 */
	public function get_thumbnail( $size = '' )
	{
		$thumbnail = $this->_get_media();

		if ( ! $thumbnail ) :
			return '';
		endif;

		$url = $thumbnail[0]['url'];

		$setting_model = new Setting();

		$thumbnails    = $setting_model->thumbnails;
		$attachment_id = isset( $thumbnails[$url] ) ? $thumbnails[$url] : 0;

		if ( $attachment_id ) :
			return wp_get_attachment_image( $attachment_id, $size );
		else :
			$attachment_id = $this->_insert_attachment( $url );
		endif;

		if ( $attachment_id ) :
			$thumbnails[$url]          = $attachment_id;
			$setting_model->thumbnails = $thumbnails;

			return wp_get_attachment_image( $attachment_id, $size );
		endif;

		return '';
	}

	/**
	 * Get permalink
	 * @since  1.0.0
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

	public function get_formated_price()
	{
		if ( $this->price ) :
			return 'R$' . number_format( $this->price, 2, ',', '.' );
		endif;

		return 'Confira!';
	}

    /**
	 * Find instances of this class
	 * @since  1.0.0
	 * @param  array     $args Params to find
	 * @return mixed           Result of the find
	 */
    public function find( $args = array() )
    {
        $defaults = array(
            'magento_filter' => array(),
            'max'            => 0,
            'random'         => false,
			'cat'            => 0,
        );

        $args = wp_parse_args( $args, $defaults );

        $magento_model = new Magento();

		if ( $args[ 'cat' ] ) :
			return $this->_parse( $magento_model->get_api_result( $this::RESOURCE_CATEGORY, $args[ 'cat' ] ), $args );
		else :
        	return $this->_parse( $magento_model->get_api_result( $this::RESOURCE_LIST, $args[ 'magento_filter' ] ), $args );
		endif;
    }

    /**
	 * Parse the find result
	 * @since  1.0.0
	 * @param  array    $products_list The result of find
	 * @return mixed                   The result of the parse
	 */
    private function _parse( $products_list, $args )
	{
		if ( ! $products_list ) :
			return false;
        endif;

        $args = (object)$args;

        if ( $args->random ) :
            shuffle( $products_list );
        endif;

        if ( $args->max ) :
            $products_list = array_slice( $products_list, 0, $args->max );
        endif;

		foreach ( $products_list as $key => $product ) :
			$model  = new $this( $product[ 'product_id' ] );
			$list[] = $model;

			unset( $model );
		endforeach;

		$std                = new \stdClass();
		$std->list          = $list;
		$std->products_list = $products_list;

		return $std;
	}

    /**
     * Get the media
     * @since  1.0.0
     * @return array    Array of thumbnails
     */
    private function _get_media()
    {
        $magento_model = new Magento();

        return $magento_model->get_api_result( $this::RESOURCE_MEDIA, $this->__get( 'product_id' ) );
    }

    /**
	 * Populate the fields of this class
	 * @since  1.0.0
	 * @param mixed    $comment The ID of comment or associative array of fields
	 * @return void
	 */
    private function _populate_fields( $ID )
    {
        $magento_model = new Magento();

        $product = $magento_model->get_api_result( $this::RESOURCE_INFO, $ID );

        foreach ( $this as $key => $value ) :
            $this->$key = isset( $product[ $key ] ) ? $product[ $key ] : '';
        endforeach;

        unset( $magento );
    }

	/**
	 * Insert a image based on a url
	 * @since  2.0.1
	 * @param  string    $url The url of image
	 * @return integer        The attachment id
	 */
	private function _insert_attachment( $url )
	{
		if ( ! $url ) :
			return 0;
		endif;

		$upload_dir = wp_upload_dir();
		$image_data = file_get_contents( $url );
		$filename   = basename( $url );

		if( wp_mkdir_p( $upload_dir['path'] ) ) :
		    $file = "{$upload_dir['path']}/{$filename}";
		else :
		    $file = "{$upload_dir['basedir']}/{$filename}";
		endif;

		file_put_contents( $file, $image_data );

		$wp_filetype = wp_check_filetype( $filename, null );

		$attachment = array(
		    'post_mime_type' => $wp_filetype['type'],
		    'post_title'     => sanitize_file_name( $filename ),
		    'post_content'   => '',
		    'post_status'    => 'inherit'
		);

		$attach_id   = wp_insert_attachment( $attachment, $file );

		require_once( ABSPATH . 'wp-admin/includes/image.php' );

		$attach_data = wp_generate_attachment_metadata( $attach_id, $file );

		wp_update_attachment_metadata( $attach_id, $attach_data );

		return $attach_id;
	}
}