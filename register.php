<?php /* vim: set fileencoding=utf-8: */

/********************************************************************
 *                                                                  *
 *    Copyright © George Stamoulis - 2013 - All Rights Reserved.    *
 *    This file is part of the Xenoglossy Wordpress plugin.         *
 *                                                                  *
 ********************************************************************/

/* Triggers when wp if fully loaded. */
add_action('init', 'register_translation_type');

/**
 * Creates and registers the TRANSLAT post_type.
 */
function register_translation_type(){
    $trans = get_trans_taxonomy();
    $parent = get_parent_taxonomy();
    $post_type = TRANSLAT;
    $args['label'] = 'translations';
    $args['labels'] = array('singular_name' => TRANSLAT);
    $args['description'] = 'Translated post created by the xenoglossy plugin';
    $args['public'] = False;
    $args['exclude_from_search'] = True;
    $args['publicly_queryable'] = False;
    $args['show_ui'] = False;
    $args['show_in_nav_menus'] = False;
    $args['show_in_menu'] = False;
    $args['show_in_admin_bar'] = False;
    $args['taxonomies'] = array($trans, $parent);
    register_post_type($post_type, $args);
}

/**
 * Creates and registers the LANGTAX taxonomy.
 *
 * @return string the LANGTAX taxonomy's name.
 */
function get_trans_taxonomy(){
    $taxonomy = LANGTAX;
    $object_type = array(TRANSLAT);
    $args['label'] = 'languages';
    $args['labels'] = array('singular_name' => 'language');
    $args['description'] = 'Language taxonomy created by the xenoglossy plugin';
    $args['public'] = False;
    $args['show_ui'] = False;
    $args['show_in_nav_menus'] = False;
    register_taxonomy($taxonomy, $object_type, $args);
    return $taxonomy;
}

/**
 * Creates and registers the PARENTAX taxonomy.
 *
 * @return string the PARENTAX taxonomy's name.
 */
function get_parent_taxonomy(){
    $taxonomy = PARENTAX;
    $object_type = array(TRANSLAT);
    $args['label'] = 'parent';
    $args['labels'] = array('singular_name' => 'parents');
    $args['description'] = 'Parent id taxonomy created by the xenoglossy plugin';
    $args['public'] = False;
    $args['show_ui'] = False;
    $args['show_in_nav_menus'] = False;
    register_taxonomy($taxonomy, $object_type, $args);
    return $taxonomy;
}

?>
