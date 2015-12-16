<?php
/*
Plugin Name: Add your place
Plugin URI: http://страница_с_описанием_плагина_и_его_обновлений
Description: This plugin allow your blogs' users add diffrent places in your site on Google maps. It allows other users be known about some places near them.
Version: 0.1
Author: Knayz
Author URI:
Lisense: GPLv2
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2015-2016 Knayz (email: pesterev.andruha@gmail.com)
*/



define ( "ADD_YOUR_PLACE_DIR", plugin_dir_path(__FILE__) );
define ( "ADD_YOUR_PLACE_INCLUDES_DIR", ADD_YOUR_PLACE_DIR . "includes/" );
define ( "ADD_YOUR_PLACE_URL", plugin_dir_url(__FILE__) );
require_once( ADD_YOUR_PLACE_INCLUDES_DIR . "install.php" );
register_activation_hook( __FILE__ , 'add_your_place_install');