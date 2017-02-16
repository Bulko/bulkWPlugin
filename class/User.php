<?php

class User
{
	/**
	 * __construct
	 *
	 * MasterUserController var
	 *
	 * @since VDO 1.4 (29/06/16 0564e55961f9178c5fead6f9fd949500e01a2a27)
	 * @author Golga <r-ro@bulko.net>
	 * @return boolean
	 */
	public function __construct()
	{
		return true;
	}

	/**
	 * removeMenu/removeMenuHook
	 *
	 * remove dummy menu from wp admin
	 *
	 * @since VDO 1.4 (01/07/16 e9cf9cf8c32b06b2ed50d002023ba0e4eb86f975)
	 * @author Golga <r-ro@bulko.net>
	 * @return boolean
	 */
	public function hook()
	{
		add_action( 'admin_menu', array( $this, 'removeMenuHook' ), 100 );
		add_action( 'admin_footer_text', array( $this, 'setAdminFooter' ), 100 );
		add_action( 'admin_bar_menu', array( $this, 'removeMenuTopHook' ), 100 );
		add_action('wp_dashboard_setup', array( $this, 'removeDashboardBox' ) );
		add_filter( 'screen_options_show_screen', array( $this, 'removeScreenOption' ), 50 );
		return true;
	}

	/**
	 * setAdminFooter
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since VDO 1.5 (09/07/16 4a222c0b4cf92a254621e2daee847ec53eaf7ccb)
	 *@see https://codex.wordpress.org/Dashboard_Widgets_API#Advanced:_Removing_Dashboard_Widgets
	 *@return boolean
	 */
	public function setAdminFooter()
	{
		// Remove WordPress Version From The Admin Footer
		remove_filter( 'update_footer', 'core_update_footer' );

		return "<a href='http://www.bulko.net/' title='bulko.net' >  &#169; Bulko</a> all rights reserved";
	}

	/**
	 * removeScreenOption
	 * 
	 *@author Golga <r-ro@bulko.net>
	 *@since VDO 1.5 (09/07/16 4a222c0b4cf92a254621e2daee847ec53eaf7ccb)
	 *@see https://codex.wordpress.org/Dashboard_Widgets_API#Advanced:_Removing_Dashboard_Widgets
	 *@return boolean
	 */
	public function removeScreenOption()
	{
		return current_user_can( 'manage_options' );
	}

	/**
	 *removeDashboardBox
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since VDO 1.5 (09/07/16 4a222c0b4cf92a254621e2daee847ec53eaf7ccb)
	 *@see https://codex.wordpress.org/Dashboard_Widgets_API#Advanced:_Removing_Dashboard_Widgets
	 *@return boolean
	 */
	function removeDashboardBox()
	{
		global $wp_meta_boxes;
		global $current_user;
		if ( $current_user->roles[0] != "administrator" )
		{
			remove_meta_box( 'wpseo-dashboard-overview', 'dashboard', 'side' );
			remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
			remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
			remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
			remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
			remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
			remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
			remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
			remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
			remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
			remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');//since 3.8
		}
		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
		return true;
	}

	/**
	 * removeMenuTopHook
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since VDO 1.5 (08/07/16 64d7e4aaabc38b1c3908f231c4ce76e8691cf25a)
	 *@see https://codex.wordpress.org/Function_Reference/remove_node
	 *@return boolean
	 */
	function removeMenuTopHook( WP_Admin_Bar $wp_admin_bar )
	{
		global $current_user;
		switch ( $current_user->roles[0] )
		{
			case 'administrator':
				$hardRemove = array(
					'wp-logo',
					'comments',
				);
			break;
			default:
				$hardRemove = array(
					'wp-logo',
					'comments',
					'wpseo-menu',
					'updates',
				);
			break;
		}
		foreach ( $hardRemove as $key => $value )
		{
			$wp_admin_bar->remove_node( $hardRemove[$key] );
		}
		return true;
	}

	/**
	 * removeMenuHook
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since VDO 1.4 (01/07/16 e9cf9cf8c32b06b2ed50d002023ba0e4eb86f975)
	 *@see http://www.wprecipes.com/how-to-remove-menus-in-wordpress-dashboard
	 *@return boolean
	 */
	function removeMenuHook()
	{
		global $menu;
		global $current_user;

		switch ( $current_user->roles[0] )
		{
			case 'administrator':
				$restricted = array(
					__('Links'),
					__('Comments'),
					__('Posts')
				);
				// @see $hardRemove in default case
				$hardRemove = array(
					'edit-comments.php',
				);
			break;
			default:
				$restricted = array(
					__('Dashboard'),
					__('Posts'),
					__('Media'),
					__('Links'),
					__('Pages'),
					__('Appearance'),
					__('Tools'),
					__('Users'),
					__('Settings'),
					__('Comments'),
					__('Slider'),
					__('Contact'),
					__('Silder Vdo'),
					__('Plugins')
				);
				$hardRemove = array(
					'wpcf7',				// contact form 7
					'CF7DBPluginSubmissions',		// contact form DB
					'pdf-light-viewer',			// filpbook (test)
					'pdf_lv',				// filpbook (test)
					'yop-polls',				// sondage
					'aiowpsec',				// all in one wp secuty
					'mainwp_child_tab',			// main wp child
					'pods',					// pods cctm
					'wpseo_dashboard',			// yoast SEO
					'mmenu',				// mmenu plugin page
					'edit-comments.php'		// comment menu
				);
			break;
		}
		end ($menu);
		while (prev($menu))
		{
			if( empty( $menu[key($menu)][0] ) || in_array( $menu[key($menu)][0] , $restricted ) || in_array( $menu[key($menu)][1] , $restricted ) )
			{
				unset($menu[key($menu)]);
			}
		}
		foreach ( $hardRemove as $key => $value )
		{
			remove_menu_page( $hardRemove[$key] );
		}
		return true;
	}
}