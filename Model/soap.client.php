<?php
namespace Magento\API\SOAP;

// Avoid that files are directly loaded
if ( ! function_exists( 'add_action' ) ) :
	exit(0);
endif;

class Soap_Client
{
	/**
	 * Client SOAP
	 * @var object
	 */
    private static $client;

	/**
	 * Session SOAP
	 * @var string
	 */
	private static $session;

	/**
	 * Get SOAP client
	 * @since  1.0.0
	 * @param  string    $wsdl Url of API
	 * @return mixed           The SOAP client
	 */
    public static function get_client( $wsdl )
    {
		try {
			if ( $wsdl ) :
	            if ( ! isset( self::$client ) ) :
	                self::$client = new \SoapClient( $wsdl );
	            endif;
	        endif;
		} catch ( \SoapFault $e ) {
			error_log( sprintf( '%s - %s', $e->getMessage(), $e->getTraceAsString() ) );
		} catch ( \Exception $e ) {
			error_log( sprintf( '%s - %s', $e->getMessage(), $e->getTraceAsString() ) );
		}

        return self::$client;
    }

	/**
	 * Get SOAP session
	 * @since  1.0.0
	 * @param  string    $user_name User name
	 * @param  string    $api_key   The API Key
	 * @return mixed                The SOAP session
	 */
	public static function get_session( $user_name, $api_key )
	{
		try {
			if ( ! isset( self::$session ) && self::$client ) :
				self::$session = self::$client->login( $user_name, $api_key );
			endif;
		} catch ( \SoapFault $e ) {
			error_log( sprintf( '%s - %s', $e->getMessage(), $e->getTraceAsString() ) );
		} catch ( \Exception $e ) {
			error_log( sprintf( '%s - %s', $e->getMessage(), $e->getTraceAsString() ) );
		}

		return self::$session;
	}
}