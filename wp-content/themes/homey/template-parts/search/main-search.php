<?php
global $template, $post, $homey_local, $homey_prefix, $homey_search_type;
$homey_search_type = homey_search_type();

if($homey_search_type == "per_hour") {
    get_template_part('template-parts/search/main-search', 'hourly');
} else {
    get_template_part('template-parts/search/main-search', 'daily');
}