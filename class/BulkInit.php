<?php
class BulkInit{
	private $BulkObj;

	/**
	 * __construct
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since 0.1
	 *@return  void
	 */
	public function __construct()
	{
		//define some informations
		define( BKO_PLUGIN_NAME . '_VERSION', '1.2' );
		define( BKO_PLUGIN_NAME . '_MINIMUM_WP_VERSION', '4.5.2' );
		define( BKO_PLUGIN_NAME . '_PLUGIN_CLASS_DIR', plugin_dir_path( __FILE__ ) );
		define( BKO_PLUGIN_NAME . '_DELETE_LIMIT', 100000 );
		//Model & Controller loading

	}

	/**
	 * admScript
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since 0.1
	 *@return void
	 */
	public function admScript()
	{
		wp_enqueue_script( 'aa-admin-script', plugins_url(  '../js/admin.js', __FILE__ ), array(), '20151215', true );
		wp_enqueue_media();
	}

	/**
	 * hook
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since 0.1
	 *@return void
	 */
	public function hook()
	{
		add_action('admin_enqueue_scripts', array( $this, 'admScript' ) );
	}

	/**
	 * initHook
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since 0.1
	 *@return void
	 */
	public function initHook()
	{
		$this->hook();
		foreach ( $this->BulkObj as $objKey => $obj )
		{
			if ( method_exists( $this->BulkObj->$objKey , "hook") )
			{
				$this->BulkObj->$objKey->hook();
			}
		}
	}

	/**
	 * initObj
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since 0.1
	 *@return Array of mixed obj BulkObj
	 */
	public function initObj()
	{
		// First of all requier Core class
		require_once( constant( BKO_PLUGIN_NAME . '_PLUGIN_CLASS_DIR' ) . '/Core.php' );

		$BulkObj = [];
		$ClassList = array_diff(
			scandir( constant( BKO_PLUGIN_NAME . '_PLUGIN_CLASS_DIR' ) ),
			[ '..', '.', 'BulkInit.php', 'Core.php' ] // Ignore unwanted file
		);

		foreach ( $ClassList as $key => $ClassFile )
		{
			require_once( constant( BKO_PLUGIN_NAME . '_PLUGIN_CLASS_DIR' ) . $ClassFile );
			$ClassName = substr( $ClassFile, 0, -4 );
			$BulkObj[$ClassName] = new $ClassName();
		}
		$this->BulkObj = (object) $BulkObj;
		return $this->BulkObj;
	}
}
?>
