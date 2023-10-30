jQuery(document).ready( function() {
    jQuery(".hm_membership_register").click( function(e) {
        e.preventDefault();
        jQuery("#plugin_register_btn").text(hm_register_scriptAjax.please_wait_text);
        jQuery("#plugin_register_btn").attr("disabled", "disabled");

        homey_register_security = jQuery(document).find("#homey_register_security").val();
        action = jQuery(document).find('input[name="action"]').val();
        role = jQuery(document).find('input[name="role"]').val();
        _wp_http_referer = jQuery(document).find('input[name="_wp_http_referer"]').val();

        username             = jQuery(document).find('input[name="username"]').val();
        useremail            = jQuery(document).find('input[name="useremail"]').val();
        register_pass        = jQuery(document).find('input[name="register_pass"]').val();
        register_pass_retype = jQuery(document).find('input[name="register_pass_retype"]').val();
        term_condition       = jQuery('input[name="term_condition"]:checked').val();
        privacy_policy       = jQuery('input[name="privacy_policy"]:checked').val();

        jQuery.ajax({
            type : "post",
            dataType : "json",
            url : hm_register_scriptAjax.hm_register_scriptAjaxUrl,
            data : {
                action: action,
                homey_register_security: homey_register_security,
                role: role,
                _wp_http_referer: _wp_http_referer,

                username: username,
                useremail: useremail,
                register_pass: register_pass,
                register_pass_retype: register_pass_retype,
                term_condition: term_condition,
                privacy_policy: privacy_policy,
            },
            success: function(response) {
                jQuery(document).find("#hm_register_msgs").html(response.msg);

                if(response.success == true) {
                    var btlClick = setInterval(function () {
                        jQuery("#hm_membership_login_btn").click();
                        jQuery("#memberships_register_form").trigger("reset");

                        jQuery("#plugin_register_btn").text(hm_register_scriptAjax.register_new_user_text);
                        jQuery("#plugin_register_btn").removeAttr( "disabled");

                        clearInterval(btlClick);
                    }, 1000);
                    console.log(response);
                }
                else {
                    jQuery("#plugin_register_btn").text(hm_register_scriptAjax.something_went_wrong_text);
                    jQuery("#plugin_register_btn").removeAttr( "disabled");

                    console.log('this is error..');
                    console.log(response);
                }
            }
        });
    });

    setInterval(function() {
        var listingIncluded = jQuery("#hm_settings_listings_included").val();
        if(listingIncluded > 0){
            jQuery("#hm_settings_unlimited_listings").prop('checked', false);
        }

        if(jQuery("#hm_settings_free_package").prop('checked')){
            jQuery("#hm_settings_package_price").val(0)
        }

        var packagePrice = jQuery("#hm_settings_package_price").val();
        var billingFrequency = jQuery("#hm_settings_billing_frequency").val();
        if(packagePrice < 0 || billingFrequency < 1 || (jQuery("#hm_settings_unlimited_listings").prop('checked') < 1 && listingIncluded < 1)){
            jQuery("#publishing-action").find("#publish").prop('disabled', true);
        }else{
            jQuery("#publishing-action").find("#publish").prop('disabled', false);
        }

    },500);

    jQuery("#hm_settings_bill_period").change( function () {
        jQuery("#hm_settings_billing_frequency").next().next().text(frequencyDescription(jQuery(this).val()));
    });

    jQuery("#hm_settings_unlimited_listings").click( function () {
        if(jQuery("#hm_settings_unlimited_listings").prop('checked') > 0){
            jQuery("#hm_settings_listings_included").val('');
        }else{
            jQuery("#hm_settings_listings_included").focus()
        }
    });

    function frequencyDescription(billingPeriodType){
        switch (billingPeriodType) {
            case "daily":
                return hm_register_scriptAjax.daily_limit_text;
                break;
            case "weekly":
                return hm_register_scriptAjax.weekly_limit_text;
                break;
            case "monthly":
                return hm_register_scriptAjax.monthly_limit_text;
                break;
            case "yearly":
                return hm_register_scriptAjax.yearly_limit_text;
                break;
            default:
                return hm_register_scriptAjax.daily_limit_text;
        }
    }
    jQuery("#hm_settings_billing_frequency").next().next().text(frequencyDescription());
});