<?php
namespace Magento\API\SOAP;

// Avoid that files are directly loaded
if ( ! function_exists( 'add_action' ) ) :
	exit(0);
endif;

App::uses( 'soap.client', 'Model' );

class Magento
{
	/**
	 * The soap client
	 * @var object
	 */
    private $soap_client;

	/**
	 * The wsdl url
	 * @var string
	 */
    private $wsdl;

	/**
	 * The API user name
	 * @var string
	 */
    private $user_name;

	/**
	 * The API password
	 * @var string
	 */
    private $api_key;

	/**
	 * The Session for API
	 * @var string
	 */
    private $session;

	/**
	 * Use cache or not
	 * @var boolean
	 */
	private $use_cache;

	/**
	 * Time to store cache in minutes
	 * @var integer
	 */
	private $cache_time;

	/**
	 * The constructor of this class
	 * @since 1.0.0
	 */
    public function __construct()
    {
		$this->_fill_options();
    }

	/**
	 * Method to all API requests
	 * @since  1.0.0
	 * @param  string    $resource Method name
	 * @param  array     $filter   The filter
	 * @return mixed               The result
	 */
    public function get_api_result( $resource, $filter = false )
    {
		if ( ! $this->use_cache || $this->cache_time <= 0 ) :
			return $this->_get_api_result( $resource, $filter );
		endif;

		$identifier = $this->_get_identifier( $resource, $filter );
		$result     = $this->_get_cache( $identifier );

		if ( $result === false ) :
			$result = $this->_get_api_result( $resource, $filter );
			$this->_set_cache( $result, $identifier );
		endif;

		return $result;
    }

	/**
	 * Private method to all API requests
	 * @since  1.0.0
	 * @param  string    $resource Method name
	 * @param  array     $filter   The filter
	 * @return mixed               The result
	 */
	private function _get_api_result( $resource, $filter )
	{
		$result = false;

		try {
			$this->soap_client = Soap_Client::get_client( $this->wsdl );
			$this->session     = Soap_Client::get_session( $this->user_name, $this->api_key );

			if ( $this->soap_client ) :
				if ( $filter ) :
					$result = $this->soap_client->call( $this->session, $resource, array( $filter ) );
				else :
					$result = $this->soap_client->call( $this->session, $resource );
				endif;
			endif;
		} catch ( \SoapFault $e ) {
			error_log( sprintf( '%s - %s - %s', $resource, $e->getMessage(), $e->getTraceAsString() ) );
		} catch ( \Exception $e ) {
			error_log( sprintf( '%s - %s - %s', $resource, $e->getMessage(), $e->getTraceAsString() ) );
		}

		return $result;
	}

	/**
	 * Get the identifier to transient
	 * @since  1.0.0
	 * @param  string    $resource The method name
	 * @param  mixed     $filter   The filter
	 * @return string              The identifier name
	 */
	private function _get_identifier( $resource, $filter )
	{
		$hash = hash( 'crc32', $resource . serialize( $filter ) );

		return "$hash-t-{$this->cache_time}";
	}

	/**
	 * Get Cache
	 * @since  1.0.0
	 * @param  string    $identifier The identifier of transient
	 * @return mixed                 The result
	 */
	private function _get_cache( $identifier )
	{
		return get_transient( $identifier );
	}

	/**
	 * Set Cache
	 * @since 1.0.0
	 * @param mixed     $result     The result to store
	 * @param string    $identifier The identifier of transient
	 */
	private function _set_cache( $result, $identifier )
	{
		set_transient( $identifier, $result, $this->cache_time * 60 );
	}

	/**
	 * Fill options
	 * @since  1.0.0
	 * @return void
	 */
	private function _fill_options()
	{
		$setting_model = new Setting();

		$this->wsdl       = $setting_model->wsdl;
		$this->user_name  = $setting_model->user_name;
		$this->api_key    = $setting_model->api_key;
		$this->use_cache  = intval( $setting_model->use_cache );
		$this->cache_time = intval( $setting_model->cache_time );
	}
}