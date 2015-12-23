<?php
if( !defined( 'ABSPATH' ) && !defined( 'WP_UNINSTALL_PLUGIN' ) )
    exit();

global $wpdb;

$wpdb->query("DROP TABLE " . $wpdb->prefix . "cities");
$wpdb->query("DROP TABLE " . $wpdb->prefix . "countries");
$wpdb->query("DROP TABLE " . $wpdb->prefix . "regions");
$wpdb->query("DROP TABLE " . $wpdb->prefix . "new_places");
?>