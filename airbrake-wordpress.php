<?php

/*
Plugin Name: airbrake-wordpress
Description: Airbrake Wordpress

Author: Airbrake.io
Author URI: https://github.com/airbrake/airbrake-wordpress

Description: Airbrake lets you discover errors and bugs in your Wordpress install. 

Version: 0.1
License: GPL 
*/

global $wpdb;

define( 'AW_TITLE', 'Airbrake Wordpress' );
define( 'AW_SLUG', 'airbrake-wordpress' );

define( 'AW_DOCROOT', dirname( __FILE__ ) );
define( 'AW_WEBROOT', str_replace( getcwd(), home_url(), dirname(__FILE__) ) );

register_activation_hook( __FILE__, 'airbrake_wordpress_install' );
register_deactivation_hook( __FILE__, 'airbrake_wordpress_uninstall' );

add_action( 'admin_menu', 'airbrake_wordpress_admin_menu' );

include 'classes/install.php';
include 'classes/controller.php';

if (getenv('AIRBRAKE_ENABLED')) {
  $active = getenv('AIRBRAKE_ENABLED') == 'true' ? '1' : '0';
  update_option('airbrake_wordpress_setting_status', $active);
} else {
  $active = get_option('airbrake_wordpress_setting_status');
}

if ( $active ) {
  // require_once 'classes/airbrake-php/src/Airbrake/EventHandler.php';
  $apiKey   = trim( get_option( 'airbrake_wordpress_setting_apikey' ) );
  $async    = (boolean) get_option( 'airbrake_wordpress_setting_async' );
  $timeout  = (int) get_option( 'airbrake_wordpress_setting_timeout' );

  $notifier = new Airbrake\Notifier([
    'projectId'  => 12345,
    'projectKey' => $apiKey
  ]);

  $notifier->addFilter(function ($notice) {
    $environment = getenv('WP_ENV') ? getenv('WP_ENV') : 'production';
    $notice['context']['environment'] = $environment;
    return $notice;
  });

  Airbrake\Instance::set($notifier);
  $handler = new Airbrake\ErrorHandler($notifier);
  $handler->register();

}

