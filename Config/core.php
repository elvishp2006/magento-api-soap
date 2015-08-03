<?php
namespace Magento\API\SOAP;

// Avoid that files are directly loaded
if ( ! function_exists( 'add_action' ) ) :
	exit(0);
endif;

App::uses( 'product', 'Model' );
App::uses( 'setting', 'Model' );
App::uses( 'settings', 'Controller' );

class Core
{
	/**
	 * The constructor of this class
	 * @since 1.0.0
	 */
	public function __construct()
	{
		// add_action( 'admin_enqueue_scripts', array( &$this, 'scripts_admin' ) );
		// add_action( 'admin_enqueue_scripts', array( &$this, 'styles_admin' ) );
		new Settings_Controller();
	}

	/**
	 * Method to execute on plugin activation
	 * @since  1.0.0
	 * @return void
	 */
	public function activate()
	{

	}

	/**
	 * Enqueue admin scripts
	 * @since  1.0.0
	 * @return void
	 */
	public function scripts_admin()
	{
		// $this->_load_wp_media();

		wp_enqueue_script(
			'admin-script-' . App::PLUGIN_SLUG,
			App::plugins_url( '/assets/javascripts/built.js' ),
			array( 'jquery' ),
			App::filemtime( 'assets/javascripts/built.js' ),
			true
		);
	}

	/**
	 * Enqueue admin styles
	 * @since  1.0.0
	 * @return void
	 */
	public function styles_admin()
	{
		wp_enqueue_style(
			'admin-style-' . App::PLUGIN_SLUG,
			App::plugins_url( 'assets/stylesheets/style.css' ),
			array(),
			App::filemtime( 'assets/stylesheets/style.css' )
		);
	}

	/**
	 * Load WP Media
	 * @since  1.0.0
	 * @return void
	 */
	private function _load_wp_media()
	{
		global $pagenow;

		if ( did_action( 'wp_enqueue_media' ) )
			return;

		if ( in_array( $pagenow, array( 'post.php', 'post-new.php', 'themes.php' ) ) )
			wp_enqueue_media();
	}
}
