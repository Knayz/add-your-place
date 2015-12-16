<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 15.12.15
 * Time: 13:01
 */


/**
 * Create new tables in your database
 */
function add_your_place_install()
{
    global $wpdb;
    //Define names of new tables;
    $cities_table = $wpdb->prefix . "cities";
    $countries_table = $wpdb->prefix . "countries";
    $regions_table = $wpdb->prefix . "regions";

    WP_Filesystem();
    $unzipfile = unzip_file( ADD_YOUR_PLACE_INCLUDES_DIR . 'world_cities.zip', ADD_YOUR_PLACE_INCLUDES_DIR);


    if ( $unzipfile ) {
       exec("mysql -u" . DB_USER . " -p" . DB_PASSWORD . " " . DB_NAME . " < " . ADD_YOUR_PLACE_INCLUDES_DIR . "countries.sql");
       exec("mysql -u" . DB_USER . " -p" . DB_PASSWORD . " " . DB_NAME . " < " . ADD_YOUR_PLACE_INCLUDES_DIR . "cities.sql");
       exec("mysql -u" . DB_USER . " -p" . DB_PASSWORD . " " . DB_NAME . " < " . ADD_YOUR_PLACE_INCLUDES_DIR . "regions.sql");
       $wpdb->query("RENAME TABLE countries TO " . $wpdb->prefix . "countries");
       $wpdb->query("RENAME TABLE cities TO " . $wpdb->prefix . "cities");
       $wpdb->query("RENAME TABLE regions TO " . $wpdb->prefix . "regions");
    } else {
        var_dump($unzipfile);
    }
}

