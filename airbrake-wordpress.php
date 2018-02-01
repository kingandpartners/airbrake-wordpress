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

include 'classes/install.php';
include 'classes/controller.php';

if (getenv('AIRBRAKE_ENABLED')) {
  $active = getenv('AIRBRAKE_ENABLED') == 'true' ? '1' : '0';
  update_option('airbrake_wordpress_setting_status', $active);
} else {
  $active = get_option('airbrake_wordpress_setting_status');
}

if (getenv('AIRBRAKE_API_KEY') !== false) {
  $apikey = getenv('AIRBRAKE_API_KEY');
  update_option('airbrake_wordpress_setting_apikey', $apikey);
}

if (getenv('AIRBRAKE_PROJECT_ID') !== false) {
  $projectid = getenv('AIRBRAKE_PROJECT_ID');
  update_option('airbrake_wordpress_setting_projectid', $projectid);
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
