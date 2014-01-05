<?php /* vim: set fileencoding=utf-8: */

/********************************************************************
 *                                                                  *
 *    Copyright © George Stamoulis - 2013 - All Rights Reserved.    *
 *    This file is part of the Xenoglossy Wordpress plugin.         *
 *                                                                  *
 ********************************************************************/

/**
 * Start of Editor page.
 */
function xenoglossy_editor_page(){
    load_style('editor', 'editor.css');
    manage_trans_input();
    $posts = get_all_posts();
    $original_id = get_original_id();
    $selected_post = get_post($original_id);
    $selected_locale = get_selected_locale();
    $active_languages = get_active_languages(True);
    $translation = get_translation($original_id, $selected_locale);
    include(MAINDIR . '/html/editor.html');
}

/**
 * Returns the selected locale.
 *
 * @return mixed string (the locale) or Null.
 */
function get_selected_locale(){
    return isset($_GET['locale_selector']) ? $_GET['locale_selector'] : Null;
}

/**
 * Returns the id of the selected original post.
 *
 * @return mixed int (the id) or Null.
 */
function get_original_id(){
    return isset($_GET['post_selector']) ? $_GET['post_selector'] : Null;
}

/**
 * Prints 'disabled' if the original post's id is Null.
 *
 * @param int $original_id the id.
 */
function is_disabled($original_id){
    echo($original_id ? '' : 'disabled');
}

/**
 * Prints 'void' if the selected post_type is not 'post' or 'page'.
 *
 * @param int $original_id the id.
 */
function default_editor_class($original_id){
    if($original_id){
        if(!in_array(get_post_type($original_id), array('post', 'page'))){
            echo('void');
        }
    }
}

/**
 * Prints 'void'. ...and that's all!
 */
function toolbar_class(){
    echo('void');
}

/**
 * Saves or updates the translation.
 */
function manage_trans_input(){
    if(isset($_POST['submit']) || isset($_POST['save'])){
        if(isset($_POST['translation_id'])){
            $post['ID'] = $_POST['translation_id'];
        }
        $taxonomies = get_taxonomies(array('object_type' => array(TRANSLAT)));
        $post['post_type'] = TRANSLAT;
        $post['comment_status'] = 'closed';
        $post['tax_input'] = array( $taxonomies[LANGTAX] => $_POST['locale'],
                                    $taxonomies[PARENTAX] => $_POST['original_id']);
        $post['post_content'] = $_POST['post_content'];
        $post['post_title'] = $_POST['post_title'];
        $post['post_excerpt'] = 'TODO handle excerpts!!';
        $post['post_status'] = isset($_POST['save'])? 'draft' : 'publish';
        wp_insert_post($post);
    }
}

/**
 * Prints an option for every post.
 *
 * @param array $posts the posts.
 */
function populate_post_selector($posts){
    $options = get_post_list($posts);
    $optfrmt = '<option value="%1$s" %3$s>(%4$s)%1$5s: %2$s</option>';
    foreach($options as $opt){
        vprintf($optfrmt, $opt);
    }
}

/**
 * Prints an option for every active language and
 * selects the requested one.
 *
 * @param array  $active_languages the active languages.
 * @param string $selected_locale  the requested locale.
 */
function populate_locale_selector($active_languages, $selected_locale){
    $locale = get_default_locale();
    $optfrmt = '<option value="%1$s" %3$s>%1$s : %2$s</option>';
    foreach($active_languages as $lang){
        if($lang['locale'] != $locale){
            $selected = $lang['locale'] == $selected_locale? 'selected' : '';
            printf($optfrmt, $lang['locale'], $lang['description'], $selected);
        }
    }
}

/**
 * Prints the selected post's title.
 *
 * @param WP_Post $selected_post the post.
 */
function selected_title($selected_post){
    if($selected_post){ echo($selected_post->post_title); }
}

/**
 * Prints the selected post's content.
 *
 * @param WP_Post $selected_post the post.
 */
function selected_content($selected_post){
    if($selected_post){ echo($selected_post->post_content); }
}

/**
 * Prints the id of the translation.
 *
 * @param WP_Post $transtation the translation.
 */
function translation_id($translation){
    if($translation){
        echo($translation->ID);
    }
}

/**
 * Extracts the fields: id, post_title, and post_type from every post
 * and returns the in an array.
 *
 * @param  array $posts the posts.
 * @return array        the extracted fields for each post.
 */
function get_post_list($posts){
    $options = array();
    if($posts){
        foreach($posts as $post){
            $post_data = array($post->ID, $post->post_title, '', $post->post_type);
            if($post->ID == $_GET['post_selector']){
                $post_data[2] = 'selected';
            }
            $options[] = $post_data;
        }
        return $options;
    }else{
        return array(array('n/a', 'No Posts Found!', '', ''));
    }
}

/**
 * Prints the description of the selected language.
 *
 * @param array  $active_languages the active languages.
 * @param string $selected_locale  the selected locale.
 */
function translation_header($active_languages, $selected_locale){
    foreach($active_languages as $lang){
        if($lang['locale'] == $selected_locale){
            printf('%s :', $lang['description']);
        }
    }
}

?>
