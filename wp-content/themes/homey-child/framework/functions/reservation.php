<?php

if(!function_exists('homey_get_monthly_prices')) {
    function homey_get_monthly_prices($check_in_date, $check_out_date, $listing_id, $guests, $extra_options = null) {
        $prefix = 'homey_';

        $enable_services_fee = homey_option('enable_services_fee');
        $enable_taxes = homey_option('enable_taxes');
        $offsite_payment = homey_option('off-site-payment');
        $reservation_payment_type = homey_option('reservation_payment');
        $booking_percent = homey_option('booking_percent');
        $tax_type = homey_option('tax_type');
        $apply_taxes_on_service_fee  =   homey_option('apply_taxes_on_service_fee');
        $taxes_percent_global  =   homey_option('taxes_percent');
        $single_listing_tax = get_post_meta($listing_id, 'homey_tax_rate', true);

        $period_price = get_post_meta($listing_id, 'homey_custom_period', true);
        if(empty($period_price)) {
            $period_price =  array();
        }

        $total_extra_services = 0;
        $extra_prices_html = '';
        $taxes_final = 0;
        $months_total_price = 0;
        $total_months_count = 0;
        $price_per_month = 0;
        $taxable_amount = 0;
        $total_months_count_for_price = 0;
        $days_after_months_price = 0;
        $days_after_months = 0;
        $taxes_percent = 0;
        $total_price = 0;
        $total_months_price = 0;
        $total_guests_price = 0;
        $upfront_payment = 0;
        $weeks_total_price = 0;
        $booking_has_weekend = 0;
        $booking_has_custom_pricing = 0;
        $balance = 0;
        $period_days = 0;
        $security_deposit = '';
        $additional_guests = '';
        $additional_guests_total_price = '';
        $services_fee_final = '';
        $taxes_fee_final = '';
        $prices_array = array();

        $listing_guests          = floatval( get_post_meta($listing_id, $prefix.'guests', true) );
        $monthly_price           = floatval( get_post_meta($listing_id, $prefix.'night_price', true));
        $price_per_month         = $monthly_price;
        $security_deposit        = floatval( get_post_meta($listing_id, $prefix.'security_deposit', true) );

        $cleaning_fee            = floatval( get_post_meta($listing_id, $prefix.'cleaning_fee', true) );
        $cleaning_fee_type       = get_post_meta($listing_id, $prefix.'cleaning_fee_type', true);

        $city_fee                = floatval( get_post_meta($listing_id, $prefix.'city_fee', true) );
        $city_fee_type           = get_post_meta($listing_id, $prefix.'city_fee_type', true);

        $extra_guests_price      = floatval( get_post_meta($listing_id, $prefix.'additional_guests_price', true) );
        $additional_guests_price = $extra_guests_price;

        $allow_additional_guests = get_post_meta($listing_id, $prefix.'allow_additional_guests', true);

        $check_in        =  new DateTime($check_in_date);
        $check_in_unix   =  $check_in->getTimestamp();
        $check_in_unix_first_day   =  $check_in->getTimestamp();
        $check_out       =  new DateTime($check_out_date);
        $check_out_unix  =  $check_out->getTimestamp();

        $time_difference = abs( strtotime($check_in_date) - strtotime($check_out_date) );
        $days_count      = $time_difference/86400;
        $days_count      = intval($days_count);

        $years = floor($time_difference / (365*60*60*24));
        $total_months_count = floor(($time_difference - $years * 365*60*60*24) / (30*60*60*24));
        $days_after_months = floor(($time_difference - $years * 365*60*60*24 - $total_months_count*30*60*60*24)/ (60*60*24));

        if($years > 0) {
            $years_months = $years * 12;
            $total_months_count = $total_months_count + $years_months;
        }

        $total_months_count_for_price = $days_count / 30;
        $days_after_months = $days_count % 30;

        $months_total_price = $price_per_month * $total_months_count_for_price;

        if( $total_months_count < 1 ) {
            $months_total_price = $price_per_month;
            $days_after_months = 0;
        }

        $total_price = $months_total_price;


        // Check additional guests price
        if( $allow_additional_guests == 'yes' && $guests > 0 && !empty($guests) ) {
            if( $guests > $listing_guests) {
                $additional_guests = $guests - $listing_guests;

                $guests_price_return = homey_calculate_guests_price($period_price, $check_in_unix, $additional_guests, $additional_guests_price);

                //zahid k calculation for extra months and days
                $divider_to_find_day_fee = (int) 30 / $days_after_months;
                $ex_price_for_days = ($guests_price_return / $divider_to_find_day_fee) * $days_after_months;
                //echo  'beo'.$guests_price_return .'+'. $ex_price_for_days;
                $guests_price_return = $guests_price_return + $ex_price_for_days;
                //add per month price
                //echo ' amaze '.$guests_price_return ."*". $total_months_count.' << ';
                $guests_price_return = $guests_price_return * $total_months_count;
                //zahid k calculation for extra months and days

                $total_guests_price = $total_guests_price + $guests_price_return;
            }
        }


        if( $cleaning_fee_type == 'daily' ) {
            $cleaning_fee = $cleaning_fee * $total_months_count_for_price;
            $total_price = $total_price + $cleaning_fee;
        } else {
            $total_price = $total_price + $cleaning_fee;
        }


        //Extra prices =======================================
        if($extra_options != '') {
            $multiply_factor = $total_months_count_for_price > 0 ? $total_months_count_for_price : $days_count;
            $extra_prices_output = '';
            $is_first = 0;
            foreach ($extra_options as $extra_price) {
                if($is_first == 0){
                    $extra_prices_output .= '<li class="sub-total">'.esc_html__('Extra Services', 'homey').'</li>';
                } $is_first = 2;

                $ex_single_price = explode('|', $extra_price);
                $ex_name = $ex_single_price[0];
                $ex_price = $ex_single_price[1];
                $ex_type = $ex_single_price[2];

                if($ex_type == 'single_fee') {
                    $ex_price = $ex_price;
                } elseif($ex_type == 'per_night') {
                    $ex_price = $ex_price * $multiply_factor;
                    if($days_after_months > 0){
                        $divider_to_find_day_fee = (int) 30 / $days_after_months;
                        $ex_price_for_days = ($ex_price / $divider_to_find_day_fee) * $days_after_months;
                        //echo ' days prices wih month';
                        $ex_price = $ex_price + $ex_price_for_days;
                    }
                } elseif($ex_type == 'per_guest') {
                    $ex_price = $ex_price * $guests;
                } elseif($ex_type == 'per_night_per_guest') {
                    $ex_price = $ex_price * $multiply_factor*$guests;
                    if($days_after_months > 0){
                        $divider_to_find_day_fee = (int) 30 / $days_after_months;
                        $ex_price_for_days += ($ex_price / $divider_to_find_day_fee) * $days_after_months * $guests;
                        //echo ' days prices with month and guest';
                        $ex_price = $ex_price + $ex_price_for_days;
                    }
                }
                $total_extra_services = $total_extra_services + $ex_price;
                $extra_prices_output .= '<li>'.esc_attr($ex_name).'<span>'.homey_formatted_price($ex_price).'</span></li>';
            }
            $total_price = $total_price + $total_extra_services;
            $extra_prices_html = $extra_prices_output;
        }
        //Calculate taxes based of original price (Excluding city, security deposit etc)
        if($enable_taxes == 1) {

            if($tax_type == 'global_tax') {
                $taxes_percent = $taxes_percent_global;
            } else {
                if(!empty($single_listing_tax)) {
                    $taxes_percent = $single_listing_tax;
                }
            }

            $taxable_amount = $total_price + $total_guests_price;
            $taxes_final = homey_calculate_taxes($taxes_percent, $taxable_amount);
            $total_price = $total_price + $taxes_final;
        }


        //Calculate sevices fee based of original price (Excluding cleaning, city, sevices fee etc)
        if($enable_services_fee == 1 && $offsite_payment != 1) {
            $services_fee_type  = homey_option('services_fee_type');
            $services_fee  =   homey_option('services_fee');
            $price_for_services_fee = $total_price + $total_guests_price;
            $services_fee_final = homey_calculate_services_fee($services_fee_type, $services_fee, $price_for_services_fee);
            $total_price = $total_price + $services_fee_final;
        }


        if( $city_fee_type == 'daily' ) {
            $city_fee = $city_fee * $total_months_count_for_price;
            $total_price = $total_price + $city_fee;
        } else {
            $total_price = $total_price + $city_fee;
        }

        if(!empty($security_deposit) && $security_deposit != 0) {
            $total_price = $total_price + $security_deposit;
        }

        if($total_guests_price !=0) {
            $total_price = $total_price + $total_guests_price;
        }



        $listing_host_id = get_post_field( 'post_author', $listing_id );
        $host_reservation_payment_type = get_user_meta($listing_host_id, 'host_reservation_payment', true);
        $host_booking_percent = get_user_meta($listing_host_id, 'host_booking_percent', true);

        if($offsite_payment == 1 && !empty($host_reservation_payment_type)) {

            if($host_reservation_payment_type == 'percent') {
                if(!empty($host_booking_percent) && $host_booking_percent != 0) {
                    $upfront_payment = round($host_booking_percent*$total_price/100,2);
                }

            } elseif($host_reservation_payment_type == 'full') {
                $upfront_payment = $total_price;

            } elseif($host_reservation_payment_type == 'only_security') {
                $upfront_payment = $security_deposit;

            } elseif($host_reservation_payment_type == 'only_services') {
                $upfront_payment = $services_fee_final;

            } elseif($host_reservation_payment_type == 'services_security') {
                $upfront_payment = $security_deposit+$services_fee_final;
            }

        } else {

            if($reservation_payment_type == 'percent') {
                if(!empty($booking_percent) && $booking_percent != 0) {
                    $upfront_payment = round($booking_percent*$total_price/100,2);
                }

            } elseif($reservation_payment_type == 'full') {
                $upfront_payment = $total_price;

            } elseif($reservation_payment_type == 'only_security') {
                $upfront_payment = $security_deposit;

            } elseif($reservation_payment_type == 'only_services') {
                $upfront_payment = $services_fee_final;

            } elseif($reservation_payment_type == 'services_security') {
                $upfront_payment = $security_deposit+$services_fee_final;
            }
        }
        
        $balance = $total_price - $price_per_month;
        $total_price = $price_per_month;
        

        $prices_array['price_per_month'] = $price_per_month;
        $prices_array['months_total_price'] = $months_total_price;
        $prices_array['total_months_count'] = $total_months_count;
        
        $prices_array['total_price']     = $total_price;
        $prices_array['check_in_date']   = $check_in_date;
        $prices_array['check_out_date']  = $check_out_date;
        $prices_array['cleaning_fee']    = $cleaning_fee;
        $prices_array['city_fee']        = $city_fee;
        $prices_array['services_fee']    = $services_fee_final;
        $prices_array['days_count']      = $days_after_months;
        $prices_array['taxes']           = $taxes_final;
        $prices_array['taxes_percent']   = $taxes_percent;
        $prices_array['security_deposit'] = $security_deposit;
        $prices_array['additional_guests'] = $additional_guests;
        $prices_array['additional_guests_price'] = $additional_guests_price;
        $prices_array['additional_guests_total_price'] = $total_guests_price;
        $prices_array['extra_prices_html'] = $extra_prices_html;
        $prices_array['balance'] = $balance;
        $prices_array['upfront_payment'] = $price_per_month;
//        $prices_array['upfront_payment'] = $upfront_payment;

        return $prices_array;

    }
}

