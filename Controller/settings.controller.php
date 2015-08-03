<?php
namespace Magento\API\SOAP;

// Avoid that files are directly loaded
if ( ! function_exists( 'add_action' ) ) :
	exit(0);
endif;

App::uses( 'settings', 'View' );

class Settings_Controller
{
	/**
	 * The constructor of this class
	 * @since 1.0.0
	 */
    public function __construct()
    {
        add_action( 'admin_menu', array( &$this, 'add_admin_menu' ) );
        add_action( 'admin_init', array( &$this, 'init' ) );
    }

	/**
	 * Add admin menu
	 * @since 1.0.0
	 */
    public function add_admin_menu()
    {
        add_menu_page( 'Magento', 'Magento', 'manage_options', 'magento_api_soap', array( 'Magento\API\SOAP\Settings_View', 'options_page_callback' ), 'dashicons-admin-plugins' );
    }

	/**
	 * Init
	 * @since  1.0.0
	 * @return void
	 */
    public function init()
    {
        register_setting( 'pluginPage', 'magento_api_soap' );

    	add_settings_section(
    		'section_api_soap',
    		'API',
    		 array( 'Magento\API\SOAP\Settings_View', 'section_api_soap_callback' ),
    		'pluginPage'
    	);

		add_settings_field(
    		'store_url',
    		'Loja URL',
    		array( 'Magento\API\SOAP\Settings_View', 'store_url_field_callback' ),
    		'pluginPage',
    		'section_api_soap'
    	);

		add_settings_field(
    		'wsdl',
    		'WSDL',
    		array( 'Magento\API\SOAP\Settings_View', 'wsdl_field_callback' ),
    		'pluginPage',
    		'section_api_soap'
    	);

		add_settings_field(
    		'user_name',
    		'Usuário',
    		array( 'Magento\API\SOAP\Settings_View', 'user_name_field_callback' ),
    		'pluginPage',
    		'section_api_soap'
    	);

		add_settings_field(
    		'api_key',
    		'API Key',
    		array( 'Magento\API\SOAP\Settings_View', 'api_key_field_callback' ),
    		'pluginPage',
    		'section_api_soap'
    	);

		add_settings_section(
    		'section_cache',
    		'Cache',
    		 array( 'Magento\API\SOAP\Settings_View', 'section_cache_callback' ),
    		'pluginPage'
    	);

    	add_settings_field(
    		'use_cache',
    		'Usar cache',
    		array( 'Magento\API\SOAP\Settings_View', 'use_cache_field_callback' ),
    		'pluginPage',
    		'section_cache'
		);

		add_settings_field(
    		'cache_time',
    		'Tempo de cache',
    		array( 'Magento\API\SOAP\Settings_View', 'cache_time_field_callback' ),
    		'pluginPage',
    		'section_cache'
		);

    }
}