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

function airbrake_setting_section_callback() {
  $src = plugin_dir_url(__FILE__);
  $img = "$src../plugin/images/icon.png";
  echo "<img style='float:left; padding:4px; padding-top:8px; padding-right:12px' src='$img'></img>
    <h2 >Airbrake Wordpress</h2>
    <p>Airbrake is a tool that collects and aggregates errors for webapps. This Plugin makes it simple to track PHP errors in your Wordpress install. Once installed it\'ll collect all errors with the Wordpress Core and Wordpress Plugins.</p>
    <p>This plugin requires an Airbrake account. Sign up for a <a href='https://signup.airbrake.io/account/new?dev=true'>Paid</a> or a <a href='https://signup.airbrake.io/account/new/Free'>Free account</a>.";
}

function airbrake_wordpress_setting_status_callback() {
  $disabled_text = !get_option('airbrake_wordpress_setting_status') ? 'selected="selected"': '';
  $enabled_text  = get_option('airbrake_wordpress_setting_status') ? 'selected="selected"': '';
  echo "<select name='airbrake_wordpress_setting_status'>
          <option value='0' $disabled_text>Disabled</option>
          <option value='1' $enabled_text>Enabled</option>
        </select>";
}

function airbrake_wordpress_setting_apikey_callback() {
  $apikey = get_option('airbrake_wordpress_setting_apikey');
  echo "<input type='text' size='45' name='airbrake_wordpress_setting_apikey' value='$apikey' />";
}

function airbrake_wordpress_setting_projectid_callback() {
  $project_id = get_option('airbrake_wordpress_setting_projectid');
  echo "<input type='text' size='45' name='airbrake_wordpress_setting_projectid' value='$project_id' />";
}

function register_airbrake_settings() {
  add_settings_section(
    'airbrake_setting_section',
    'Airbrake settings',
    'airbrake_setting_section_callback',
    AW_SLUG
  );
  add_settings_field(
    'airbrake_wordpress_setting_status',
    'Status',
    'airbrake_wordpress_setting_status_callback',
    AW_SLUG,
    'airbrake_setting_section'
  );
  add_settings_field(
    'airbrake_wordpress_setting_projectid',
    'API Key',
    'airbrake_wordpress_setting_projectid_callback',
    AW_SLUG,
    'airbrake_setting_section'
  );
  add_settings_field(
    'airbrake_wordpress_setting_apikey',
    'API Key',
    'airbrake_wordpress_setting_apikey_callback',
    AW_SLUG,
    'airbrake_setting_section'
  );
  register_setting(AW_SLUG, 'airbrake_wordpress_setting_status');
  register_setting(AW_SLUG, 'airbrake_wordpress_setting_apikey');
  register_setting(AW_SLUG, 'airbrake_wordpress_setting_projectid');
}

register_activation_hook(__FILE__, 'airbrake_wordpress_install');
register_deactivation_hook(__FILE__, 'airbrake_wordpress_uninstall');
add_action('admin_init', 'register_airbrake_settings');
add_action('admin_menu', 'airbrake_wordpress_admin_menu');
