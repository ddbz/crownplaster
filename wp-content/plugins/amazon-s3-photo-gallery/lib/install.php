<?php
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

/**
 * creates all tables for the gallery
 * called during register_activation hook
 * 
 * @access internal
 * @return void
 */
function amazon_install () {
	
   	global $wpdb , $wp_roles, $wp_version;
   	
	// Check for capability
	if ( !current_user_can('activate_plugins') ) 
		return;
	
	// Set the capabilities for the administrator
	$role = get_role('administrator');
	// We need this role, no other chance
	if ( empty($role) ) {
		update_option( "ngg_init_check", __('Sorry, NextGEN Gallery works only with a role called administrator',"nggallery") );
		return;
	}
	
	
	// upgrade function changed in WordPress 2.3	
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	
	// add charset & collate like wp core
	$charset_collate = '';

	if ( version_compare(mysql_get_server_info(), '4.1.0', '>=') ) {
		if ( ! empty($wpdb->charset) )
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		if ( ! empty($wpdb->collate) )
			$charset_collate .= " COLLATE $wpdb->collate";
	}
		
   	$amgalbum = $wpdb->prefix . 'amg_album';

    // could be case senstive : http://dev.mysql.com/doc/refman/5.1/en/identifier-case-sensitivity.html
	if( !$wpdb->get_var( "SHOW TABLES LIKE '$amgalbum'" ) ) {
      
		$sql = "CREATE TABLE " . $amgalbum . " (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  album_name varchar(100) NOT NULL,
		  url varchar(500) NOT NULL,
		  status enum('Yes','No') NOT NULL default 'Yes',
		  PRIMARY KEY  id (id)
		) $charset_collate;";
	
    dbDelta($sql);
	  
	$caption = $wpdb->prefix . 'amg_caption';
  
	  $sql="CREATE TABLE IF NOT EXISTS `".$caption."` (
  `c_id` int(11) NOT NULL AUTO_INCREMENT,
  `photo_hash` varchar(255) NOT NULL,
  `caption` text NOT NULL,
  `when_add_c` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`c_id`)
) $charset_collate;";
    }
	
	dbDelta($sql);
	
	$comment = $wpdb->prefix . 'amg_comment';

	$sql="CREATE TABLE IF NOT EXISTS `".$comment."` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `photo_hash` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `when_add` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
	
	dbDelta($sql);
	
	// check one table again, to be sure
	if( !$wpdb->get_var( "SHOW TABLES LIKE '$nggpictures'" ) ) {
		//update_option( "ngg_init_check", __('NextGEN Gallery : Tables could not created, please check your database settings',"nggallery") );
		return;
	}
	
 	

}

/**
 * Uninstall all settings and tables
 * Called via Setup and register_unstall hook
 * 
 * @access internal
 * @return void
 */
function amazon_uninstall() {
	global $wpdb;
	
	// first remove all tables
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}amg_album");

	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}amg_caption");
	
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}amg_comment");
	
	// then remove all options
	delete_option( 'amg_api_key' );
	delete_option( 'amg_app_secret' );
}

?>