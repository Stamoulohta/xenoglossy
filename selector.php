<?php /* vim: set fileencoding=utf-8: */

/********************************************************************
 *                                                                  *
 *    Copyright © George Stamoulis - 2013 - All Rights Reserved.    *
 *    This file is part of the Xenoglossy Wordpress plugin.         *
 *                                                                  *
 ********************************************************************/

/* Triggers when the header ready. */
add_action('wp_head', 'apply_xenoglossy');

/**
 * Prints the language selector if it is requested.
 */
function apply_xenoglossy(){
    if(selector_is_wanted()){
        load_style('selector', 'selector.css');
        echo('<div id="xg_selector">');
        selector_ctl();
        echo('</div>');
    }
}

/**
 * Checks if the language selector is requested for this page.
 *
 * @return bool true if language selector is requested.
 */
function selector_is_wanted(){
    $desires = get_option(XG_SELECT);
    if(count($desires) == 3){
        return True;
    }
    if(is_home() && in_array('home', $desires)){
        return true;
    }
    $post_type = get_post_type(get_the_id());
    if(in_array($post_type, $desires)){
        return true;
    }
    return false;
}

/**
 * Prints the language selector control for all active languages.
 */
function selector_ctl(){
    $uri = get_appendable_uri();
    if($langs = get_active_languages(True)){
        $format = '<a href="%s=%s"><div class="selector_ctl">[%s]</div></a>';
        foreach($langs as $lang){
            $display = explode('_', $lang['locale'])[0];
            if($lang['locale'] == get_default_locale()){
                $lang['locale'] = 'reset';
            }
            printf($format, $uri . XG_GET, $lang['locale'], $display);
        }
    }
}

?>
