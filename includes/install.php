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
        $wpdb->query("CREATE TABLE " . $wpdb->prefix . "new_places (
                        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                          `name` varchar(255) DEFAULT NULL,
                          `lat` decimal(20,17) DEFAULT NULL,
                          `lng` decimal(20,17) DEFAULT NULL,
                          `id_country` int(10) NOT NULL,
                          `id_city` int(10) NOT NULL,
                          `address` varchar(255) NOT NULL,
                          PRIMARY KEY (`id`),
                          KEY `el_name` (`name`)
                        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
    } else {
        var_dump($unzipfile);
    }
}

