<?php
function homey_enqueue_styles() {
    
    // enqueue parent styles
    wp_enqueue_style('homey-parent-theme', get_template_directory_uri() .'/style.css');
    
    // enqueue child styles
    wp_enqueue_style('homey-child-theme', get_stylesheet_directory_uri() .'/style.css', array('homey-parent-theme'));
    
}
add_action('wp_enqueue_scripts', 'homey_enqueue_styles');

require_once(get_stylesheet_directory().'/framework/functions/reservation.php');
require_once(get_stylesheet_directory().'/localization.php');
//require_once(get_stylesheet_directory() . '/vc_shortcodes/register.php');

function add_custom_fields($user_id){
    $user = get_userdata($user_id);
    $user->first_name = isset($_POST['fname']) ? $_POST['fname'] : '';
    $user->last_name = isset($_POST['lname']) ? $_POST['lname'] : '';
    $phone_number = isset($_POST['phone']) ? $_POST['phone'] : '';
    update_user_meta($user_id, 'billing_phone', $phone_number);
    update_user_meta($user_id, 'shipping_phone', $phone_number);
    wp_update_user($user);
}
add_action('user_register','add_custom_fields');

?>