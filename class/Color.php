<?php
class Color
{
	/**
	 * __construct
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since 0.5
	 *@see https://github.com/Oyana/wp-colors
	 *@return void
	 */
	public function __construct()
	{
		return true;
	}
	/**
	 * add_login_logo
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since 0.5
	 *@see https://github.com/Oyana/wp-colors
	 *@return void
	 */
	function add_login_logo()
	{
		echo '<style type="text/css">';
		echo '#login h1 a, .login h1 a {';
		echo 'background-image: url(' . get_stylesheet_directory_uri() . '/img/favicon.png);';
		echo 'padding-bottom: 0px;';
		echo '}';
		echo '</style>';
	}
	/**
	 * addCustomAdminThemes
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since 0.5
	 *@see https://github.com/Oyana/wp-colors
	 *@return void
	 */
	public function addCustomAdminThemes()
	{
		//Model & Controller loading
		$suffix = is_rtl() ? '-rtl' : '';

		wp_admin_css_color(
			'backboard',
			__( 'BlackBoard' ),
			plugins_url( "../css/backboard$suffix.css", __FILE__ ),
			array( '#363b3f', '#018A00', '#01FF00' )
		);
	}
	/**
	 * bulk_admin_styles
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since 0.5
	 *@see https://github.com/Oyana/wp-colors
	 *@return void
	 */
	public function bulk_admin_styles()
	{
		wp_enqueue_style( 'aa_2016-admin-style', plugins_url( "../css/admin.css", __FILE__ ) );
	}

	/**
	 * hook
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since 0.5
	 *@see https://github.com/Oyana/wp-colors
	 *@return void
	 */
	public function hook()
	{
		add_action( 'admin_init', array($this, 'addCustomAdminThemes') );
		add_action( 'login_enqueue_scripts', array($this, 'add_login_logo' ) );
		add_action('admin_enqueue_scripts', array($this, 'bulk_admin_styles') );
		add_action('login_enqueue_scripts', array($this, 'bulk_admin_styles') );
	}
}