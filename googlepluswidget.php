<?php
/*
Plugin Name: My Google Plus Widget
Plugin URI: http://www.ketelaar.info/googlepluswidget/
Description: Enables a Widget which shows you Google Plus Updates (based on official Google API)
Author: Arjen Ketelaar (Ketelaar Museum)
Version: 1.3
Author URI: http://arjen.ketelaar.info

Copyright 2011 Arjen Ketelaar (email: projects -at- ketelaar -dot- info)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

// init calls
add_action( 'admin_menu', 'gpw_plugin_add_page');
add_action( 'admin_menu', 'gpw_create_menu' );


require_once 'google-api-php-client/src/apiClient.php';
require_once 'google-api-php-client/src/contrib/apiPlusService.php';

$client = new apiClient();
$client->setApplicationName("Google Plus Widget");
// Visit https://code.google.com/apis/console to generate your
// oauth2_client_id, oauth2_client_secret, and to register your oauth2_redirect_uri.
$options = get_option('gpw_plugin_options');
$txt = $options['oauth2_client_id'];
$client->setClientId($txt);
$txt = $options['oauth2_client_secret'];
$client->setClientSecret($txt);
$txt = $options['oauth2_redirect_uri'];
$client->setRedirectUri($txt);
$txt = $options['developer_id'];
$client->setDeveloperKey($txt);
$client->setScopes(array('https://www.googleapis.com/auth/plus.me'));
$plus = new apiPlusService($client);
$meurl = get_option('gpw_plugin_google_plus_id');

if (isset($_SESSION['access_token'])) {
  $client->setAccessToken($_SESSION['access_token']);
}

if ($client->getAccessToken()) {
  $me = $plus->people->get('me');
  $optParams = array('maxResults' => 100);
  $activities = $plus->activities->listActivities($meurl, 'public', $optParams);

  // The access token may have been updated lazily.
  $_SESSION['access_token'] = $client->getAccessToken();
} else {
  $authUrl = $client->createAuthUrl();
}

// add_action( 'admin_menu', 'gpw_create_menu' );

function gpw_create_menu() { 
	add_menu_page( 'Google Plus Widget Page', 'Google Plus', 'manage_options', 'gpw', 'gpw_main_page', 
	plugins_url('/images/plus-icon.png', __FILE__) );
	add_submenu_page('gpw', 'Google Plus Widget Options', 'options', __FILE__, 'settings', 'gpw_settings_page', ''); 
	add_submenu_page('gpw', 'Google Plus Widget Test', 'test', __FILE__, 'test', 'gpw_test_page', '');
}

// function gpw

function gpw_main_page () {
	echo "Welcome by the Google Plus Widget Main Page";
}

function gpw_settings_page () {
	echo "Welcome by the Google Plus Widget Settings";
}

function gpw_test_page () {
	echo "Welcome by the Google Plus Widget Test";
}

add_action( 'widgets_init', 'gpw_widget_register');

function gpw_widget_register () {
	register_widget( 'gpw_widget_info' );
}

class gpw_widget_info extends WP_Widget {
	function gpw_widget_info() {
		$widget_ops = array(
			'classname' => 'gpw_widget_class',
			'description' => 'Display Google Plus Widget'
		);
		$this->WP_Widget( 'gpw_widget_info', 'Google Plus Widget', $widget_ops ) ;
	}
	
	function form($instance) {
		$defaults = array( 'id' => 'Google Plus ID' );
		$instance = wp_parse_args( (array) $instance, $defaults );
		$id = $instance['title'];
		$id = $instance['id'];
		echo "<p>Title: <input class='widefat' name='";
		echo $this->get_field_name( 'title' )."' type='text' value ='";
		echo esc_attr( $title )."'/></p>";
		echo "<p>Google Plus ID: <input class='widefat' name='";
		echo $this->get_field_name( 'id' )."' type='text' value ='";
		echo esc_attr( $id )."'/></p>";
		
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['id'] = strip_tags( $new_instance['id'] );
		return $instance;
	}

	function widget($args, $instance) {
	global $debugtxt, $client;
		extract($args);
		$title = apply_filters( 'widget_title', $instance['title'] );
		if (!empty( $title ) ) $title = "My Google Plus Updates";
		echo $before_title . $title . $after_title;
		// echo $debugtxt;
		$id = empty( $instance['id'] ) ? '&nbsp;': $instance['id'];
		$client->setApplicationName("Google Plus Widget");
		$client->setScopes(array('https://www.googleapis.com/auth/plus.me'));
		$plus = new apiPlusService($client);
		$optParams = array('maxResults' => 100);
		$activities = $plus->activities->listActivities($id, 'public', $optParams);

  // The access token may have been updated lazily.
		echo "<ul>";
		foreach($activities['items'] as $activity):
			echo "<li><a href='".$activity['url']."'>".$activity['title']."</a></li>";
		endforeach;
		echo "</ul>";
		echo $after_widget;		
	}
}

// Administration page onder general settings

// add_action('admin_menu', 'gpw_plugin_add_page');
function gpw_plugin_add_page() {
	add_options_page( 'Google Plus Widget Settings', 'Google Plus Widget', 'manage_options', 'gpw_plugin', 'gpw_plugin_option_page' );
}

// Draw the option page
function gpw_plugin_option_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2>Google Plus Widget Settings</h2>
		<form action="options.php" method="post">
			<?php settings_fields('gpw_plugin_options'); ?>
			<?php do_settings_sections('gpw_plugin'); ?>
			<input name="Submit" type="submit" value="Save Changes" />
		</form>
	</div>
	<?php
}

// Register and define the settings
add_action('admin_init', 'gpw_plugin_admin_init');
function gpw_plugin_admin_init(){
	register_setting(
		'gpw_plugin_options',
		'gpw_plugin_options',
		'gpw_plugin_validate_options'
	);
	add_settings_section(
		'gpw_plugin_main',
		'Google Plus Widget Settings',
		'gpw_plugin_section_text',
		'gpw_plugin'
	);
	add_settings_field(
		'gpw_plugin_google_plus_id',
		'google_plus_id',
		'gpw_plugin_setting_input0',
		'gpw_plugin',
		'gpw_plugin_main'
	);
	add_settings_field(
		'gpw_plugin_oauth2_client_id',
		'oauth2_client_id',
		'gpw_plugin_setting_input',
		'gpw_plugin',
		'gpw_plugin_main'
	);
	add_settings_field(
		'gpw_plugin_oauth2_client_secret',
		'oauth2_client_secret',
		'gpw_plugin_setting_input2',
		'gpw_plugin',
		'gpw_plugin_main'
	);
	add_settings_field(
		'gpw_plugin_oauth2_redirect_uri',
		'oauth2_redirect_uri',
		'gpw_plugin_setting_input3',
		'gpw_plugin',
		'gpw_plugin_main'
	);
	add_settings_field(
		'gpw_plugin_developer_id',
		'developer_id',
		'gpw_plugin_setting_input4',
		'gpw_plugin',
		'gpw_plugin_main'
	);
}

// Draw the section header
function gpw_plugin_section_text() {
	echo "<p>Enter your settings here. Copy the settings from a new project which you need to create on the Google API Console <a href='https://code.google.com/apis/console'>Google API Console. See the <a href='http://wordpress.org/extend/plugins/mygooglepluswidget/installation/'>instruction page</a> of the plugin.</p>";
}

// Display and fill the form field
function gpw_plugin_setting_input0() {
	// get option 'text_string' value from the database
	$options = get_option( 'gpw_plugin_options' );
	$google_plus_id = $options['google_plus_id'];
	// echo the field
	echo "<input id='google_plus_id' name='gpw_plugin_options[google_plus_id]' type='text' value='$google_plus_id' />";
}

function gpw_plugin_setting_input() {
	// get option 'text_string' value from the database
	$options = get_option( 'gpw_plugin_options' );
	$oauth2_client_id = $options['oauth2_client_id'];
	// echo the field
	echo "<input id='oauth2_client_id' name='gpw_plugin_options[oauth2_client_id]' type='text' value='$oauth2_client_id' />";
}

function gpw_plugin_setting_input2() {
	// get option 'text_string' value from the database
	$options = get_option( 'gpw_plugin_options' );
	$oauth2_client_secret = $options['oauth2_client_secret'];
	// echo the field
	echo "<input id='oauth2_client_secret' name='gpw_plugin_options[oauth2_client_secret]' type='text' value='$oauth2_client_secret' />";
}

function gpw_plugin_setting_input3() {
	// get option 'text_string' value from the database
	$options = get_option( 'gpw_plugin_options' );
	$oauth2_redirect_uri = $options['oauth2_redirect_uri'];
	// echo the field
	echo "<input id='oauth2_redirect_uri' name='gpw_plugin_options[oauth2_redirect_uri]' type='text' value='$oauth2_redirect_uri' />";
}

function gpw_plugin_setting_input4() {
	// get option 'text_string' value from the database
	$options = get_option( 'gpw_plugin_options' );
	$developer_id = $options['developer_id'];
	// echo the field
	echo "<input id='developer_id' name='gpw_plugin_options[developer_id]' type='text' value='$developer_id' />";
}



// Validate user input (we want text only)
function gpw_plugin_validate_options( $input ) {

	$valid['google_plus_id'] = preg_replace( '/[^.:!<>@&\/a-zA-Z0-9]/', '', $input['google_plus_id'] );	
	if( $valid['google_plus_id'] != $input['google_plus_id'] ) {
		add_settings_error(
			'gpw_plugin_google_plus_id',
			'gpw_plugin_texterror',
			'Incorrect value entered!',
			'error'
		);		
	}

	$valid['oauth2_client_id'] = preg_replace( '/[^.:!<>@&\/a-zA-Z0-9]/', '', $input['oauth2_client_id'] );
	
	if( $valid['oauth2_client_id'] != $input['oauth2_client_id'] ) {
		add_settings_error(
			'gpw_plugin_oauth2_client_id',
			'gpw_plugin_texterror',
			'Incorrect value entered!',
			'error'
		);		
	}
	
	$valid['oauth2_client_secret'] = preg_replace( '/[^!.:<>@&\/a-zA-Z0-9]/', '', $input['oauth2_client_secret'] );
	
	if( $valid['oauth2_client_secret'] != $input['oauth2_client_secret'] ) {
		add_settings_error(
			'gpw_plugin_oauth2_client_secret',
			'gpw_plugin_texterror',
			'Incorrect value entered!',
			'error'
		);		
	}
	
	$valid['oauth2_redirect_uri'] = preg_replace( '/[^!.:<>@&\/a-zA-Z0-9]/', '', $input['oauth2_redirect_uri'] );
	if( $valid['oauth2_redirect_uri'] != $input['oauth2_redirect_uri'] ) {
		add_settings_error(
			'gpw_plugin_oauth2_redirect_uri',
			'gpw_plugin_texterror',
			'Incorrect value entered!',
			'error'
		);		
	}
	
	$valid['developer_id'] = preg_replace( '/[^!.:<>@&\/a-zA-Z0-9]/', '', $input['developer_id'] );
	if( $valid['developer_id'] != $input['developer_id'] ) {
		add_settings_error(
			'gpw_plugin_developer_id',
			'gpw_plugin_texterror',
			'Incorrect value entered!',
			'error'
		);		
	}
	
	
	
	return $valid;
}

?>