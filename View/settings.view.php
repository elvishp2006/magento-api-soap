<?php
namespace Magento\API\SOAP;

// Avoid that files are directly loaded
if ( ! function_exists( 'add_action' ) ) :
	exit(0);
endif;

class Settings_View
{
	/**
	 * Section API SOAP Callback
	 * @since  1.0.0
	 * @return string    The result
	 */
    public static function section_api_soap_callback()
    {
    	?>
        <p class="description">Dados de conexão com a API.</p>
        <?php
    }

	/**
	 * Option page callback
	 * @since  1.0.0
	 * @return string    The result
	 */
    public static function options_page_callback()
    {
    	?>
    	<form action='options.php' method='post'>

    		<h2>Magento API SOAP</h2>

    		<?php
    		settings_fields( 'pluginPage' );
    		do_settings_sections( 'pluginPage' );
    		submit_button();
    		?>

    	</form>
    	<?php
    }

	/**
	 * Store URL field callback
	 * @since  1.0.0
	 * @return string    The result
	 */
    public static function store_url_field_callback()
    {
        $setting_model = new Setting();
    	?>
    	<input class="regular-text" type="text" name="<?php echo Setting::KEY_OPTIONS . '[store_url]'; ?>" value="<?php echo esc_url( $setting_model->store_url ); ?>">
        <p class="description">http://domain/</p>
    	<?php
    }

	/**
	 * WSDL field callback
	 * @since  1.0.0
	 * @return string    The result
	 */
    public static function wsdl_field_callback()
    {
        $setting_model = new Setting();
    	?>
    	<input class="regular-text" type="text" name="<?php echo Setting::KEY_OPTIONS . '[wsdl]'; ?>" value="<?php echo esc_url( $setting_model->wsdl ); ?>">
        <p class="description">http://domain/api/?wsdl</p>
    	<?php
    }

	/**
	 * User name field callback
	 * @since  1.0.0
	 * @return string    The result
	 */
    public static function user_name_field_callback()
    {
        $setting_model = new Setting();
        ?>
        <input class="regular-text" type="text" name="<?php echo Setting::KEY_OPTIONS . '[user_name]'; ?>" value="<?php echo esc_html( $setting_model->user_name ); ?>">
        <?php
    }

	/**
	 * Api Key field callback
	 * @since  1.0.0
	 * @return string    The result
	 */
    public static function api_key_field_callback()
    {
        $setting_model = new Setting();
        ?>
        <input class="regular-text" type="password" name="<?php echo Setting::KEY_OPTIONS . '[api_key]'; ?>" value="<?php echo esc_html( $setting_model->api_key ); ?>">
        <?php
    }

	/**
	 * Section cache callback
	 * @since  1.0.0
	 * @return string    The result
	 */
    public static function section_cache_callback()
    {
    	?>
        <p class="description">Opções de cache.</p>
        <?php
    }

	/**
	 * Use cache field callback
	 * @since  1.0.0
	 * @return string    The result
	 */
    public static function use_cache_field_callback()
    {
        $setting_model = new Setting();
    	?>
        <input type="checkbox" name="<?php echo Setting::KEY_OPTIONS . '[use_cache]'; ?>" <?php checked( 1, intval( $setting_model->use_cache ), true ); ?> value="1">
        <span class="description">Usar cache? esta opção aumenta a performance!</span>
    	<?php
    }

	/**
	 * Cache time field callback
	 * @since  1.0.0
	 * @return string    The result
	 */
    public static function cache_time_field_callback()
    {
        $setting_model = new Setting();
    	?>
        <input type="text" name="<?php echo Setting::KEY_OPTIONS . '[cache_time]'; ?>" value="<?php echo intval( $setting_model->cache_time ); ?>" size="3">
        <span class="description">Minutos</span>
    	<?php
    }

	/**
	 * Clear thumbnails callback
	 * @since  2.2.1
	 * @return void
	 */
	public static function thumbnails_field_callback()
	{
		$setting_model = new Setting();
		?>
		<input type="checkbox" name="<?php echo Setting::KEY_THUMBNAILS; ?>" value="">
		<?php
	}
}