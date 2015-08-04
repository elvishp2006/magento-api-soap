<?php
/*
	Plugin Name: Magento API SOAP
	Plugin URI: http://apiki.com.br/
	Version: 1.0.1
	Author: Apiki WordPress
	Author URI: http://apiki.com.br/
	License: MIT
	Description: Integration with Magento API SOAP
*/
namespace Magento\API\SOAP;

// Avoid that files are directly loaded
if ( ! function_exists( 'add_action' ) ) :
	exit(0);
endif;

class App
{
	/**
	 * The plugin slug
	 */
	const PLUGIN_SLUG = 'magento-api-soap';

	/**
	 * Method to include files
	 * @since  1.0.0
	 * @param  string    $class_name The class name
	 * @param  string    $location   The file location
	 * @return void
	 */
	public static function uses( $class_name, $location )
	{
		$locations = array(
			'Controller',
			'View',
			'Helper',
			'Widget',
			'Vendor',
		);

		$extension = 'php';

		if ( in_array( $location, $locations ) )
			$extension = strtolower( $location ) . '.php';

		include "{$location}/{$class_name}.{$extension}";
	}

	/**
	 * Get plugin url
	 * @since  1.0.0
	 * @param  string    $path The path to cancat
	 * @return string          The path
	 */
	public static function plugins_url( $path )
	{
		return plugins_url( $path, __FILE__ );
	}

	/**
	 * Get plugin dir path
	 * @since  1.0.0
	 * @param  string    $path The path to cancat
	 * @return string          The path
	 */
	public static function plugin_dir_path( $path )
	{
		return plugin_dir_path( __FILE__ ) . $path;
	}

	/**
	 * Get the filemtime
	 * @since  1.0.0
	 * @param  string    $path Path to file
	 * @return integer         The filemtime
	 */
	public static function filemtime( $path )
	{
		return filemtime( self::plugin_dir_path( $path ) );
	}
}

App::uses( 'core', 'Config' );

$core = new Core();

register_activation_hook( __FILE__, array( $core, 'activate' ) );
