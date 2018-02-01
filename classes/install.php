<?php

function airbrake_wordpress_install() {
  add_option('airbrake_wordpress_setting_status', '0', '', 'yes');
  add_option('airbrake_wordpress_setting_apikey', '', '', 'yes');
  add_option('airbrake_wordpress_setting_projectid', '', '', 'yes');
}

function airbrake_wordpress_uninstall() {
  delete_option('airbrake_wordpress_setting_status');
  delete_option('airbrake_wordpress_setting_apikey');
  delete_option('airbrake_wordpress_setting_projectid');
}