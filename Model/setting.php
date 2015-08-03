<?php
namespace Magento\API\SOAP;

// Avoid that files are directly loaded
if ( ! function_exists( 'add_action' ) ) :
	exit(0);
endif;

class Setting
{
    /**
     * The Option
     * @var array
     */
    private static $option;

	/**
	 * The store url
	 * @var string
	 */
	private $store_url;

    /**
     * WSDL Url
     * @var string
     */
    private $wsdl;

    /**
     * The user name of API
     * @var string
     */
    private $user_name;

    /**
     * The api_key of API
     * @var string
     */
    private $api_key;

    /**
     * Use cache
     * @var integer
     */
    private $use_cache;

    /**
     * The cache time
     * @var integer
     */
    private $cache_time;

    /**
     * The option name
     */
    const OPTION = 'magento_api_soap';

    /**
     * The constructor of this class
     * @since 1.0.0
     */
    public function __construct()
    {
        if ( ! self::$option ) :
            self::$option = get_option( self::OPTION  );
        endif;
    }

    /**
     * Magic method to get properties
     * @since  1.0.0
     * @param  string    $prop_name The parameter name
     * @return mixed                The parameter value
     */
    public function __get( $prop_name )
	{
		return $this->_get_property( $prop_name );
	}

    /**
     * Get property value by name
     * @since  1.0.0
     * @param  string    $prop_name The property name
     * @return mixed                The property value
     */
    private function _get_property( $prop_name )
	{
		switch ( $prop_name ) :

			case 'store_url' :
				if ( ! isset( $this->store_url ) )
					$this->store_url = isset( self::$option[ 'store_url' ] ) ? self::$option[ 'store_url' ] : '';
				break;

			case 'wsdl' :
				if ( ! isset( $this->wsdl ) )
					$this->wsdl = isset( self::$option[ 'wsdl' ] ) ? self::$option[ 'wsdl' ] : '';
				break;

            case 'user_name' :
				if ( ! isset( $this->user_name ) )
					$this->user_name = isset( self::$option[ 'user_name' ] ) ? self::$option[ 'user_name' ] : '';
				break;

            case 'api_key' :
				if ( ! isset( $this->api_key ) )
					$this->api_key = isset( self::$option[ 'api_key' ] ) ? self::$option[ 'api_key' ] : '';
				break;

            case 'use_cache' :
				if ( ! isset( $this->use_cache ) )
					$this->use_cache = isset( self::$option[ 'use_cache' ] ) ? self::$option[ 'use_cache' ] : 0;
				break;

            case 'cache_time' :
				if ( ! isset( $this->cache_time ) )
					$this->cache_time = isset( self::$option[ 'cache_time' ] ) ? self::$option[ 'cache_time' ] : 1440;
				break;

			default :
				return false;
				break;
		endswitch;

		return $this->$prop_name;
	}
}