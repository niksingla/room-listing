<?php
/*
Plugin Name: WP All Import - Homey Experiences Add-On
Plugin URI: http://www.wpallimport.com/
Description: Supporting imports into the Homey theme.
Version: 1.0.0
Author: Favethemes
*/

include "rapid-addon.php";

$homey_addon = new RapidAddon( 'Homey Experience Add-On', 'homey_addon' );

$homey_addon->disable_default_images();

$homey_addon->import_images( 'homey_addon_exp_gallery_images', 'Gallery Images' );

function homey_addon_exp_gallery_images( $post_id, $attachment_id, $image_filepath, $import_options ) {
    add_post_meta( $post_id, 'homey_experience_images', $attachment_id ) || update_post_meta( $post_id, 'homey_experience_images', $attachment_id );
}

$prefix = 'homey_';

$homey_addon->add_field( $prefix.'experience_describe_yourself', 'Describe yourself and your qualifications', 'textarea', null, '' );
$homey_addon->add_field( $prefix.'guests', 'Guests', 'text', null, 'example: 2' );
$homey_addon->add_field( $prefix.'night_price', 'Price', 'text', null, 'example: 500' );
$homey_addon->add_field( $prefix.'price_postfix', 'Price Postfix', 'text', null, 'example: Person' );
$homey_addon->add_field( $prefix.'start_end_open', 'Open Time', 'text', null, 'example: 8:00 AM or 8:30 AM' );
$homey_addon->add_field( $prefix.'start_end_close', 'Close Time', 'text', null, 'example: 5:00 PM or 5:30 PM' );

// What will be provided 
$homey_wwbp_help = "If there are multiple then separate each value with a '|'";
$homey_addon->add_options( false, 'What Will Be Provided', array(
    $homey_addon->add_field( 'wwbp_name', 'Titles', 'text', null, $homey_wwbp_help ),
) );

// What to bring
$homey_wtb_help = "If there are multiple then separate each value with a '|'";
$homey_addon->add_options( false, 'What Bring Item Type', array(
    $homey_addon->add_field( 'wbit_name', 'Name', 'text', null, $homey_wtb_help ),
) );

$homey_addon->add_field( $prefix.'instant_booking', 'Instant Booking?', 'text', null, 'Value must be 0 or 1' );
$homey_addon->add_field( $prefix.'featured', 'Featured Listing?', 'text', null, 'Value must be 0 or 1' );

$homey_addon->add_field( $prefix.'experience_smoke', 'Smoking Allowed?', 'text', null, 'Value must be 0 or 1' );

$homey_addon->add_field( $prefix.'experience_pets', 'Pets Allowed?', 'text', null, 'Value must be 0 or 1' );
$homey_addon->add_field( $prefix.'experience_party', 'Party Allowed?', 'text', null, 'Value must be 0 or 1' );
$homey_addon->add_field( $prefix.'experience_children', 'Children Allowed?', 'text', null, 'Value must be 0 or 1' );
$homey_addon->add_field( $prefix.'additional_rules', 'additional_rules', 'textarea', null, '' );

$homey_addon->add_field( $prefix.'exp_video_url', 'Virtual Tour Video URL', 'text', null, 'Provide virtual tour video URL. YouTube, Vimeo are supported.' );


$homey_addon->add_field( $prefix.'show_map', 'Show Map', 'radio', array(
    '1' => 'Yes',
    '0' => 'No'
) );

$homey_addon->add_field(
    'location_settings',
    'Listing Map Location',
    'radio',
    array(
        'search_by_address'     => array(
            'Search by Address',
            $homey_addon->add_options(
                $homey_addon->add_field(
                    $prefix.'experience_address',
                    'Listing Address',
                    'text'
                ),
                'Google Geocode API Settings',
                array(
                    $homey_addon->add_field(
                        'address_geocode',
                        'Request Method',
                        'radio',
                        array(
                            'address_no_key'            => array(
                                'No API Key',
                                'Limited number of requests.'
                            ),
                            'address_google_developers' => array(
                                'Google Developers API Key - <a href="https://developers.google.com/maps/documentation/geocoding/#api_key">Get free API key</a>',
                                $homey_addon->add_field(
                                    'address_google_developers_api_key',
                                    'API Key',
                                    'text'
                                ),
                                'Up to 2500 requests per day and 5 requests per second.'
                            ),
                            'address_google_for_work'   => array(
                                'Google for Work Client ID & Digital Signature - <a href="https://developers.google.com/maps/documentation/business">Sign up for Google for Work</a>',
                                $homey_addon->add_field(
                                    'address_google_for_work_client_id',
                                    'Google for Work Client ID',
                                    'text'
                                ),
                                $homey_addon->add_field(
                                    'address_google_for_work_digital_signature',
                                    'Google for Work Digital Signature',
                                    'text'
                                ),
                                'Up to 100,000 requests per day and 10 requests per second'
                            )
                        ) // end Request Method options array
                    ) // end Request Method nested radio field
                ) // end Google Geocode API Settings fields
            ) // end Google Gecode API Settings options panel
        ), // end Search by Address radio field
        'search_by_coordinates' => array(
            'Search by Coordinates',
            $homey_addon->add_field(
                'listing_latitude',
                'Latitude',
                'text',
                null,
                'Example: 34.0194543'
            ),
            $homey_addon->add_options(
                $homey_addon->add_field(
                    'listing_longitude',
                    'Longitude',
                    'text',
                    null,
                    'Example: -118.4911912'
                ),
                'Google Geocode API Settings',
                array(
                    $homey_addon->add_field(
                        'coord_geocode',
                        'Request Method',
                        'radio',
                        array(
                            'coord_no_key'            => array(
                                'No API Key',
                                'Limited number of requests.'
                            ),
                            'coord_google_developers' => array(
                                'Google Developers API Key - <a href="https://developers.google.com/maps/documentation/geocoding/#api_key">Get free API key</a>',
                                $homey_addon->add_field(
                                    'coord_google_developers_api_key',
                                    'API Key',
                                    'text'
                                ),
                                'Up to 2500 requests per day and 5 requests per second.'
                            ),
                            'coord_google_for_work'   => array(
                                'Google for Work Client ID & Digital Signature - <a href="https://developers.google.com/maps/documentation/business">Sign up for Google for Work</a>',
                                $homey_addon->add_field(
                                    'coord_google_for_work_client_id',
                                    'Google for Work Client ID',
                                    'text'
                                ),
                                $homey_addon->add_field(
                                    'coord_google_for_work_digital_signature',
                                    'Google for Work Digital Signature',
                                    'text'
                                ),
                                'Up to 100,000 requests per day and 10 requests per second'
                            )
                        ) // end Geocode API options array
                    ) // end Geocode nested radio field
                ) // end Geocode settings
            ) // end coordinates Option panel
        ) // end Search by Coordinates radio field
    ) // end Property Location radio field
);

$homey_addon->add_field( 'homey_cancellation_policy', 'Cancellation Policy', 'text' );
$homey_addon->add_field( 'homey_cancellation_policy_des', 'Cancellation Policy Content', 'textarea' );


$homey_addon->set_import_function( 'homey_esp_addon_import' );

$homey_addon->admin_notice();
/* Check dependent plugins */
$homey_addon->admin_notice( 'Homey Add-on requires WP All Import <a href="http://www.wpallimport.com/order-now/?utm_source=free-plugin&utm_medium=dot-org&utm_campaign=homey" target="_blank">Pro</a> or <a href="http://wordpress.org/plugins/wp-all-import" target="_blank">Free</a>, and the <a href="https://themeforest.net/item/homey-booking-wordpress-theme/23338013">Homey</a> theme.',
    array('themes' => array("Homey"))
);

$homey_addon->run( array(
    "themes"     => array("Homey"),
    "post_types" => array("experience")
) );

function homey_esp_addon_import( $post_id, $data, $import_options, $article ) {

    global $homey_addon;
    $prefix = 'homey_';

    // all fields except for slider and image fields
    $fields = array(
        $prefix.'experience_describe_yourself',
        $prefix.'guests',
        $prefix.'instant_booking',
        $prefix.'night_price',
        $prefix.'price_postfix',
        $prefix.'start_end_open',
        $prefix.'start_end_close',
        $prefix.'featured',
        $prefix.'experience_smoke',
        $prefix.'experience_pets',
        $prefix.'experience_party',
        $prefix.'experience_children',
        $prefix.'additional_rules',
        $prefix.'exp_video_url',
        $prefix.'show_map',
        $prefix.'experience_address',
    );

    // What to provide Fields
    $homey_wwbp_fields = array(
        'wwbp_name',
    );

    // Bedrooms Fields
    $homey_wtb_fields = array(
        'wbit_name',
    );

    $fields = array_merge( $fields, $homey_wwbp_fields, $homey_wtb_fields );

    // update everything in fields arrays
    foreach ( $fields as $field ) {

        if ( empty( $article['ID'] ) or $homey_addon->can_update_meta( $field, $import_options ) ) {

            if ( in_array( $field, $homey_wwbp_fields ) ) {
                foreach ( explode( "|", $data[$field] ) as $fp_key => $fp_value ) {
                    $t_fp_value = trim( $fp_value );
                    if (!empty($t_fp_value)) {
                        $what_to_provide_meta[$fp_key][$field] = trim($fp_value);
                    }
                }
            } else if ( in_array( $field, $homey_wtb_fields ) ) {
                foreach ( explode( "|", $data[$field] ) as $fp_key => $fp_value ) {
                    $t_fp_value = trim( $fp_value );
                    if (!empty($t_fp_value)) {
                        $what_to_bring_meta[$fp_key][$field] = trim($fp_value);
                    }
                }
            } else {

                if ( strlen( $data[$field] ) == 0 ) {
                    delete_post_meta( $post_id, $field );
                } else {
                    update_post_meta( $post_id, $field, $data[$field] );
                }
            }
        }
    }

    update_post_meta( $post_id, 'homey_what_to_provided', $what_to_provide_meta );
    update_post_meta( $post_id, 'homey_what_to_bring', $what_to_bring_meta );

    // clear image fields to override import settings
    $fields = array(
        'homey_experience_images'
    );

    if ( empty( $article['ID'] ) or $homey_addon->can_update_image( $import_options ) ) {

        foreach ( $fields as $field ) {

            delete_post_meta( $post_id, $field );

        }

    }


    // update cancellation policy, create a new one if not found
    $cancelpolicy_title = 'homey_cancellation_policy';
    $cancelpolicy_description = 'homey_cancellation_policy_des';
    $post_type = 'homey_cancel_policy';

    if ( empty( $article['ID'] ) or $homey_addon->can_update_meta( $cancelpolicy_title, $import_options ) ) {

        $post = get_page_by_title( $data[$cancelpolicy_title], 'OBJECT', $post_type );

        if ( !empty($post) ) {

            update_post_meta( $post_id, $cancelpolicy_title, $post->ID );

        } else {

            // insert title and attach to property
            $postarr = array(
                'post_content' => $data[$cancelpolicy_description],
                'post_name'    => $data[$cancelpolicy_title],
                'post_title'   => $data[$cancelpolicy_title],
                'post_type'    => $post_type,
                'post_status'  => 'publish',
                'post_excerpt' => ''
            );

            wp_insert_post( $postarr );

            if ( $post = get_page_by_title( $data[$cancelpolicy_title], 'OBJECT', $post_type ) ) {

                update_post_meta( $post_id, $cancelpolicy_title, $post->ID );

            }

        }
    }


    // update property location
    $field = 'homey_experience_map_address';

    $address = $data[$field];

    $lat = $data['listing_latitude'];

    $long = $data['listing_longitude'];

    //  build search query
    if ( $data['location_settings'] == 'search_by_address' ) {

        $search = (!empty($address) ? 'address=' . rawurlencode( $address ) : null);

    } else {

        $search = (!empty($lat) && !empty($long) ? 'latlng=' . rawurlencode( $lat . ',' . $long ) : null);

    }

    // build api key
    if ( $data['location_settings'] == 'search_by_address' ) {

        if ( $data['address_geocode'] == 'address_google_developers' && !empty($data['address_google_developers_api_key']) ) {

            $api_key = '&key=' . $data['address_google_developers_api_key'];

        } elseif ( $data['address_geocode'] == 'address_google_for_work' && !empty($data['address_google_for_work_client_id']) && !empty($data['address_google_for_work_signature']) ) {

            $api_key = '&client=' . $data['address_google_for_work_client_id'] . '&signature=' . $data['address_google_for_work_signature'];

        }

    } else {

        if ( $data['coord_geocode'] == 'coord_google_developers' && !empty($data['coord_google_developers_api_key']) ) {

            $api_key = '&key=' . $data['coord_google_developers_api_key'];

        } elseif ( $data['coord_geocode'] == 'coord_google_for_work' && !empty($data['coord_google_for_work_client_id']) && !empty($data['coord_google_for_work_signature']) ) {

            $api_key = '&client=' . $data['coord_google_for_work_client_id'] . '&signature=' . $data['coord_google_for_work_signature'];

        }

    }

    // if all fields are updateable and $search has a value
    if ( $homey_addon->can_update_meta( $field, $import_options ) && $homey_addon->can_update_meta( 'homey_experience_location', $import_options ) && !empty ($search) ) {

        // build $request_url for api call
        $request_url = 'https://maps.googleapis.com/maps/api/geocode/json?' . $search . $api_key;
        $curl = curl_init();

        curl_setopt( $curl, CURLOPT_URL, $request_url );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );

        $homey_addon->log( '- Getting location data from Geocoding API: ' . $request_url );

        $json = curl_exec( $curl );
        curl_close( $curl );

        // parse api response
        if ( !empty($json) ) {

            $details = json_decode( $json, true );

            if ( $data['location_settings'] == 'search_by_address' ) {

                $lat = $details[results][0][geometry][location][lat];

                $long = $details[results][0][geometry][location][lng];

            } else {

                $address = $details[results][0][formatted_address];

            }

        }

    }

    // update location fields
    $fields = array(
        'homey_experience_map_address'  => $address,
        'homey_experience_location' => $lat . ',' . $long
    );

    $homey_addon->log( '- Updating location data' );

    foreach ( $fields as $key => $value ) {

        if ( empty( $article['ID'] ) or $homey_addon->can_update_meta( $key, $import_options ) ) {

            update_post_meta( $post_id, $key, $value );

        }
    }
    
}
