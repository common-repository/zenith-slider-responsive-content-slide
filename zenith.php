<?php
/**
  * Plugin Name: Zenith Slider
  * Plugin URI: http://themeflection.com
  * Version: 1.0.0
  * Author: Aleksej Vukomanovic
  * Author URI: http://themeflection.com
  * Description: Highlights Showcase Slider for WordPress
  * Text Domain: zenith
  * Domain Path: /languages
  * License: GPL
  */
	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) exit;

	// Plugin Folder Path
	if ( ! defined( 'ZENITH_DIR' ) ) {
		define( 'ZENITH_DIR', plugin_dir_url( __FILE__ ) );
	}
	//Plugin Version
	if ( ! defined('ZENITH_VERSION') ){
		define( 'ZENITH_VERSION', '1.0' );
	}

	if ( file_exists( dirname( __FILE__ ) . '/cmb2/init.php' ) ) {
		require_once dirname( __FILE__ ) . '/cmb2/init.php';
	} elseif ( file_exists( dirname( __FILE__ ) . '/CMB2/init.php' ) ) {
		require_once dirname( __FILE__ ) . '/CMB2/init.php';
	}

	//include core files
	require_once 'core/font-awesome.php';
	require_once 'core/setup.php';
	require_once 'core/metaboxes.php';
	require_once 'core/interface.php';
	require_once 'core/shortcode.php';
	//initialize Zenith SLider
	TF_Zenith_Slider::init();
	Zenith_Metaboxes::init();
	TF_Zenith_Interface::init();
	$shortcode = new Zenith_Shortcode;
?>