jQuery(function() {
	var context = jQuery( 'body' );

	MONKEY.vars = {
		body : context
	};

	//set route in application
	Dispatcher( MONKEY.MagentoApiSoap, window.pagenow, [context] );
});
