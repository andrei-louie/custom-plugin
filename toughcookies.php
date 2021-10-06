<?php
/*
Plugin Name: toughcookies
Plugin URI: 
Description: A toughcookies wordpress plugin
Version: 1.0
Author: Louie Sanchez
Author URI: 
License: 
*/
defined('ABSPATH') or die("No script kiddies please!");

if (!class_exists('toughcookies')): //class if starts

   	class toughcookies {
      function __construct() {
        // Register style sheet.
        add_action( 'wp_enqueue_scripts', array($this, 'register_plugin_styles'),11);
      }
   		private static $instance;

   		/**
       	* Main Instance
       	* @staticvar 	array 	$instance
       	* @return the one true instance
       	*/
      public static function instance() {
         if (!isset(self::$instance)) {
            self::$instance = new self;
            self::$instance->constants();
            self::$instance->required();
            self::$instance->_hooks();
            self::$instance->init();
         }
         return self::$instance;
      }

      /*Init instance*/
      function init(){
      } 
      /**/ 

      private function constants() {
         define('TOUGHCOOKIES_DIR', plugin_dir_path(__FILE__));
         define('TOUGHCOOKIES_URL', plugin_dir_url(__FILE__));
         define('TOUGHCOOKIES_FILE', __FILE__);
         define('TOUGHCOOKIES_SENDINBLUE_API_KEY', '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');
         define('TOUGHCOOKIES_GOOGLE_API_KEY', 'AIzaSyCHO2GeZgBfl8THuz7l3xBq49PVfJ7AqTw');
         define('TOUGHCOOKIES_JUSTCALL_API_KEY', '7ff6b402c478ef23e11bfd01f7b53eb64090a16d');
         define('TOUGHCOOKIES_JUSTCALL_API_SECRET', '60a1c65a83170162aa658254a085bd1af5146088');
         define('TOUGHCOOKIES_JUSTCALL_SENDER_NUMBER', 17814360235);
      }

      private function required() {
        require_once( TOUGHCOOKIES_DIR . '/functions.php' );
      }

      function _hooks() {
         require_once(dirname(__FILE__) . "/hooks.php");
      }

      function register_plugin_styles(){
        
      }
      
    //******** class end *******//  
	}

   endif; //class if end

  if (class_exists('toughcookies')) {
     // instantiate the plugin class
     toughcookies::instance();
  }
?>