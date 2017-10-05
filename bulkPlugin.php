<?php
/*
Plugin Name: Bko plugin template
Depends:
Provides: Notre Super Plugin!
Plugin URI:
Description: Notre Super Plugin!
Version: 1.0.0
Author: Bulko
Author URI: http://www.bulko.net/
License: http://www.wtfpl.net/
*/
// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) )
{
	wp_die( 'Hi there!  I\'m just a plugin, not much I can do when called directly.' );
}
//include required wp mod
require_once( ABSPATH . '/wp-config.php' );
require_once(  plugin_dir_path( __FILE__ ) . '/class/BulkInit.php' );

// TODO use plugin url path in all class file

define( "BKO_PLUGIN_NAME", "bulkPlugin" );
define( BKO_PLUGIN_NAME . '_PLUGIN_URL', plugins_url(  '../js/admin.js', __FILE__ ) );
$$pluginName = new BulkInit();
$plugin = $$pluginName->initObj();
$$pluginName->initHook();

$whitelist = array(
	'127.0.0.1',
	'r-ro.local',
	'localhost',
	'::1'
);

if( in_array($_SERVER['REMOTE_ADDR'], $whitelist) )
{
	$plugin->ReCaptchaForm->setTestMod();
}
