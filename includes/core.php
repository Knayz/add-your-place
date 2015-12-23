<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 17.12.15
 * Time: 12:41
 */
add_action('admin_menu', 'add_your_place_create_settings_submenu');
add_shortcode( 'add-your-place', 'add_your_place_shortcode');
add_action( 'wp_enqueue_scripts', 'register_add_your_place_styles' );
add_action( 'wp_enqueue_scripts', 'register_add_your_place_scripts' );
add_action('wp_loaded', 'add_your_place_save_place');

function add_your_place_create_settings_submenu()
{
    add_options_page( "Add Your Place Settins Page", "Add Your Place", "manage_options", 'add_your_place_settins_page', 'add_your_place_settings_submenu' );
    add_action('admin_init', 'add_your_place_register_settings');
}

function add_your_place_register_settings()
{
    register_setting('add-your-place-group', 'add_your_place_options', 'add_your_place_sanitize_options');
}

function add_your_place_settings_submenu()
{ ?>
    <div class="wrap">
        <h2>Add Your Place Plugin Options</h2>

        <form action="options.php" method="post">
            <?php settings_fields('add-your-place-group');?>
            <?php $add_your_place_options = get_option('add_your_place_options');?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Title</th>
                    <td>
                        <input type="text" name="add_your_place_options[option_title]"
                         value="<?php echo esc_attr($add_your_place_options['option_title']); ?>">
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Text</th>
                    <td>
                        <input type="text" name="add_your_place_options[option_text]"
                               value="<?php echo esc_attr($add_your_place_options['option_text']); ?>">
                    </td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" class="button-primary" value="Save Changes">
            </p>
        </form>

    </div>
<?php
}

function add_your_place_sanitize_options( $input )
{
    $input['option_title'] = sanitize_text_field($input['option_title']);
    $input['option_text'] = sanitize_text_field($input['option_text']);
    return $input;
}

function register_add_your_place_styles()
{
    wp_register_style( 'add-your-place-main-style', ADD_YOUR_PLACE_STYLES_DIR . 'main.css' );
    wp_enqueue_style( 'add-your-place-main-style' );
}

function register_add_your_place_scripts()
{
    wp_enqueue_script( 'add-your-place-gmaps','http://maps.google.com/maps/api/js?sensor=false', array('jquery') );
    wp_enqueue_script( 'add-your-place-main-script',ADD_YOUR_PLACE_SCRIPTS_DIR . 'main.js', array('jquery') );
}


function add_your_place_shortcode()
{
    //exit("stop");
    global $wpdb;
    $add_your_place_options = get_option('add_your_place_options');
    $edit_page = false;
    if(isset($_GET['addyourplace']) && $_GET['addyourplace'] == 1)
        $edit_page = true;
    if(!$edit_page) {
        $output = "<script>
var addMarker = false;
jQuery(document).ready(function($){

    var myLatLng = {}; ";

        $sql = "SELECT lat, lng, name from " . $wpdb->prefix . "new_places";
        $markers = $wpdb->get_results($sql);
        foreach ($markers as $marker) {
            $output .= "
                myLatLng = new google.maps.LatLng( {$marker->lat} , {$marker->lng} );
                    new google.maps.Marker({
                            position: myLatLng,
                            map: map,
                            title: '{$marker->name}'
                        }
                    );

            ";
        }
        $output .= "
});
    </script>
        <div class='add-your-place-wrap'>
            <div class='add-your-place-title'>
                <h3>{$add_your_place_options['option_title']}</h3>";
        if (is_user_logged_in())
            $output .= "<a href='" . get_permalink() . "?addyourplace=1'>Add</a> ";
        $output .= "
            </div>
            <p class='add-your-place-description'>
                {$add_your_place_options['option_text']}
            </p>
            <div class='add-your-place-gmap'>
                <div class='ui-widget-content form_pad'>
                    <div id='map_canvas'></div>
                </div>
            </div>
        </div>
        ";
    }
    else {
        if (!is_user_logged_in())
            $output = "<a href='" . get_permalink() . "'>Back</a> and Log in please!";
        else {

            $output = "
    <script>
    var addMarker = true;
    var mapMarkers = false;
</script>
<div class='add-your-place-wrap'>
    <div class='add-your-place-title'>
        <h3>Params</h3>
    </div>
    <form action='" . get_permalink() . "?addyourplace=1' method='post'>
        <input type='hidden' name='lat' id='latFld'>
        <input type='hidden' name='long' id='lngFld'>
        <h3>Place title</h3>
        <input type='text' name='place-title'>
        <h3>Address</h3>
        Country: <select name='country' id='country'>";
            $sql = "SELECT id, name from " . $wpdb->prefix . "countries";
            $countries = $wpdb->get_results($sql);
            foreach ($countries as $country) {
                $selected = ($country->id == '224') ? " selected " : "";
                $output .= "<option value='" . $country->id . "'" . $selected . ">" . $country->name . "</option>";
            }
        $output .= " </select>
        City: <select name='city' id='city'>";
            $sql = "SELECT id, name from " . $wpdb->prefix . "cities where country = '224' limit 500";
            $cities = $wpdb->get_results($sql);
            foreach ($cities as $city) {
                $selected = ($city->id == '562740') ? " selected " : "";
                $output .= "<option value='" . $city->id . "'" . $selected . ">" . $city->name . "</option>";
            }
        $output .= "</select>
        <br>
        <input type='text' name='address'>
        <input type='submit' value='Go' name='go'>
    </form>

    <div class='add-your-place-gmap'>
    <div class='ui-widget-content form_pad'>
                    <div id='map_canvas'></div>
                </div>
    </div>
</div>";
        }
    }
    return $output;
}

function add_your_place_save_place()
{
    if(is_user_logged_in())
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['go'] == "Go")
        {
            $lat = (isset($_POST['lat']) && !empty($_POST['lat'])) ? esc_sql($_POST['lat']) : 0;
            $long = (isset($_POST['long']) && !empty($_POST['long'])) ? esc_sql($_POST['long']) : 0;
            $place_title = esc_sql($_POST['place-title']);
            $country = esc_sql($_POST['country']);
            $city = esc_sql($_POST['city']);
            $address = esc_sql($_POST['address']);
            global $wpdb;
            $wpdb->query("INSERT INTO `" . $wpdb->prefix . "new_places`
                    (`lat`, `lng`, `name`, `id_country`, `id_city`, `address`)
                  VALUES  ($lat, $long, '$place_title', $country, $city, '$address')");
            $url = get_permalink();
            wp_redirect($url, 200);}
    }

}