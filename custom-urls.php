<?php 
add_action('init', 'bookerville_add_rewrite_rule');
function bookerville_add_rewrite_rule(){
    add_rewrite_rule('^search-results?','index.php?searchresults=1&post_type=bookerville_listings','top');
}

add_action('query_vars','bookerville_set_query_var');
function bookerville_set_query_var($vars) {
    array_push($vars, 'searchresults');
    return $vars;
}

add_filter('template_include', 'bookerville_load_templates', 1000, 1);
function bookerville_load_templates($template){
    if(get_query_var('searchresults')){
        $new_template = BOOKERVILLE_DIR.'/views/search-results.php';
        if(file_exists($new_template))
            $template = $new_template;
    }
    return $template;
}