<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if this file is accessed directly

if( !class_exists( 'TF_Zenith_Slider' ) )
{
    class TF_Zenith_Slider
    {
    	static $version = ZENITH_VERSION;

    	/**
    	* Zenith SLider Hooks()
    	*/
    	protected static function hooks()
    	{
    		//enqueue front-end scripts and styles	
    		add_action( 'wp_enqueue_scripts', array( 'TF_Zenith_Slider', 'enqueue_scripts' ) );
    		//enqueue back-end scripts and styles
    		add_action( 'admin_enqueue_scripts', array( 'TF_Zenith_Slider', 'admin_enqueue_scripts' ) );
    		//	Activation
			register_activation_hook( __FILE__,	array( 'TF_Zenith_Slider', 'zenith_activate' ) );
			//	Deactivation
			register_deactivation_hook( __FILE__, array( 'TF_Zenith_Slider', 'zenith_deactivate' ) );
			// unistall
			register_uninstall_hook( __FILE__, array( 'TF_Zenith_Slider', ' zenith_unistall' ) ); 
			//register zenith_slider post_type
			add_action( 'init', array( 'TF_Zenith_Slider', 'zenith_register' ) );
    	}

   		/**
	    * Enqueue scripts and styles
	    *
	    */
	    public static function enqueue_scripts()
	    {   	
	    	wp_enqueue_style( 'awesome-css', ZENITH_DIR . 'assets/css/font-awesome.min.css', self::$version );
	    	//wp_enqueue_style( 'zenith-style', ZENITH_DIR . 'assets/css/style.min.css', self::$version );
	    	wp_enqueue_style( 'zenith-stylesz', ZENITH_DIR . 'assets/css/style.min.css', self::$version );
	    	wp_enqueue_script( 'zenith', ZENITH_DIR . 'assets/js/zenith.min.js', array(), self::$version, true );
	    }

	    /**
	    * Flush Rewrite rules in activation/deactivation hooks
	    *
	    */	
	    public static function zenith_activate(){
	    	flush_rewrite_rules();
	 	}
		public static function zenith_deactivate(){
		    flush_rewrite_rules();
		}
	    public static function zenith_unistall(){ }

	    /**
	    * Enqueue admin scripts and styles
	    *
	    */
	    public static function admin_enqueue_scripts()
	    {
	       wp_enqueue_style( 'zenith-css-admin', ZENITH_DIR . 'assets/css/admin.css', self::$version );
	       wp_enqueue_script( 'zenith-admin', ZENITH_DIR . 'assets/js/admin.js', array('jquery'), self::$version, true );
	       $url = array(
				'base' => ZENITH_DIR
			);
	       wp_localize_script( 'zenith-admin', 'obj', $url  );
	    }

	    /**
	    *
	    * register zenith_slider custom post type
	    *
	    */
	    public static function zenith_register()
	    {
	  		$labels = array(
	  			'name'	 => 'Zenith Slider',
	  			'singular_name' => __( 'Slide', 'zenith' ),
	  			'plural_name'  => __( 'Slides', 'zenith' ),
	  			'add_new'     => __('Add Slider', 'zenith'),
	            'add_new_item'    => __('Add Slide', 'zenith'),
	            'new_item'      => __('New Slide', 'zenith'),
	            'edit_item'     => __('Edit Slider', 'zenith'),
	            'all_items'     => __('All Sliders', 'zenith'),
	            'view_item'     => __('View Slider', 'zenith'),
	            'not_found'     => __('No Slider found'),
	            'not_found_in_trash'  => __('No Slider found in trash', 'zenith'),
	  		);  
	  		register_post_type( 
	  			'zenith_slider', array( 
	  				'labels' => $labels,
	  				'public'  => false,
	  				'supports' => array('title'),
	  				'rewrite' => false,
	  				'publicly_queriable' => true, 
					'show_ui' => true, 
					'exclude_from_search' => true,  
					'show_in_nav_menus' => false,  
					'has_archive' => false,
	  				'menu_icon' => 'dashicons-slides',
	  				'menu_position'  => 65
	  			)
	  		);	
	    }

	    public static function init()
	    {
	    	self::hooks();
	    }

    }//end TF_Zenith_Slider class
}//if !class_exists
?>