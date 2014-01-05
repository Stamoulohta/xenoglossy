<?php /* vim: set fileencoding=utf-8: */

/********************************************************************
 *                                                                  *
 *    Copyright © George Stamoulis - 2013 - All Rights Reserved.    *
 *    This file is part of the Xenoglossy Wordpress plugin.         *
 *                                                                  *
 ********************************************************************/

/**
 * Registers and enqueues stylesheets.
 *
 * @param string $handle a unique name.
 * @param $css   the stylesheet basename.
 */
function load_style($handle, $css){
    wp_register_style($handle, CSSURL . $css);
    wp_enqueue_style($handle);
}

/**
 * Returns the active languages.
 *
 * @param  bool  $complete true if default language should be included.
 * @return array           the active languages.
 */
function get_active_languages($complete){
    $active_languages = array();
    if($complete){
        $locale = get_default_locale();
        $def = 'Default';
        $active_languages[] = array('description' => "$def ($locale)", 'locale' => $locale,
                       'font_name' => $def, 'font_size' => $def);
    }
    if($langs = get_option(XG_LANGS)){
        $active_languages = array_merge($active_languages, $langs);
    }
    return $active_languages;
}

/**
 * Returns the site's default locale.
 *
 * @return string the locale.
 */
function get_default_locale(){
    return WPLANG ? WPLANG : 'en_US';
}

/**
 * Enquires the database for all public posts.
 *
 * @return array the posts.
 */
function get_all_posts(){
    $args = array('numberposts' => -1, 'orderby' => 'post_date', 'order' => 'DESC', 'post_status' => 'publish');
    $args['post_type'] = get_post_types(array('public' => True));
    return get_posts($args);
}

/**
 * Enquires the database for all the translations.
 *
 * @return array the translations.
 */
function get_all_translations(){
    $args['post_type'] = TRANSLAT;
    $args['post_status'] = array('draft', 'publish');
    $args['numberposts'] = -1;
    return get_posts($args);
}

/**
 * Filters the translations by their locale.
 *
 * @param  array  $translations the translations.
 * @param  string the locale.
 * @return array  the filtered translations.
 */
function filter_translations_by_locale($translations, $locale){
    $res= array();
    $fields = array('fields' => 'names');
    foreach($translations as $translation){
        if(wp_get_post_terms($translation->ID, LANGTAX, $fields)[0] === $locale){
            $res[] = $translation;
        }
    }
    return $res;
}

/**
 * Filters the translations by their parent.
 *
 * @param  array  $translations the translations.
 * @param  string the locale.
 * @return array  the filtered translations.
 */
function filter_translations_by_parent($translations, $parent_id){
    $res= array();
    $fields = array('fields' => 'names');
    foreach($translations as $translation){
        if(wp_get_post_terms($translation->ID, PARENTAX, $fields)[0] === $parent_id){
            $res[] = $translation;
        }
    }
    return $res;
}

/**
 * Enquires the database for a specific translation.
 *
 * @param int      $original_id     the id of the original post.
 * @param string   $selected_locale the translation's locale.
 * @return mixed                    Null or WP_Post (the translation).
 */
function get_translation($original_id, $selected_locale){
    if($original_id){
        $args['post_type'] = array(TRANSLAT);
        $args['post_status'] = array('publish', 'draft');
        $args[PARENTAX] = $original_id;
        $args[LANGTAX] = $selected_locale;
        if($translations = get_posts($args)){
            return $translations[0];
        }
    }
    return Null;
}

/**
 * Prints the post_content of the translation.
 *
 * @param WP_Post $translation the translation.
 */
function translated_content($translation){
    if($translation){
        echo($translation->post_content);
    }
}

/**
 * Prints the post_title of the translation.
 *
 * @param WP_Post $translation the translation.
 */
function translated_title($translation){
    if($translation){
        echo($translation->post_title);
    }
}

/**
 * Appends either '?' or '&' to the uri 
 *
 * @return string the appendable uri.
 */
function get_appendable_uri(){
    if(null !== ($uri = $_SERVER['REQUEST_URI'])){
        $uri = clean_uri($uri);
        $query = parse_url($uri, PHP_URL_QUERY);
        if($query){
            $uri .= '&';
        }else{
            $uri .= '?';
        }
        return $uri;
    }
    return '';
}

/**
 * Erases the XG_GET part of the uri if found.
 *
 * @param  string $uri the uri.
 * @return string      the 'clean' uri.
 */
function clean_uri($uri){
    if(preg_match('/[?&]+' . XG_GET . '=\w+(&|$|#)/', $uri, $match)){
        $uri = str_replace($match[0], '', $uri);
    }
    return $uri;
}

/**
 * Deletes <b>permanently</b> all the translations of a specific locale.
 *
 * @param string $erased_locale the locale of the translations to be
 *                              <b>permanently</b> deleted.
 */
function erase_all_translations($erased_locale){
    $args['post_type'] = array(TRANSLAT);
    $args[LANGTAX] = $locale;
    $args['numberposts'] = -1;
    $args['fields'] = 'ids';
    $translations = get_posts($args);
    foreach($translations as $translation){
        wp_delete_post($translation->ID, True);
    }
}

?>
