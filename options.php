<?php /* vim: set fileencoding=utf-8: */

/********************************************************************
 *                                                                  *
 *    Copyright © George Stamoulis - 2013 - All Rights Reserved.    *
 *    This file is part of the Xenoglossy Wordpress plugin.         *
 *                                                                  *
 ********************************************************************/

/**
 * Start of Options page.
 */
function xenoglossy_options_page(){
    $default_locale = get_default_locale();
    $msg = manage_input($default_locale);
    $posts = get_all_posts();
    $translations = get_all_translations();
    $options = get_option(XG_SELECT);
    $active_languages = get_active_languages(True);
    load_style('options', 'options.css');
    include(HTMLDIR . 'options.html');
}

/**
 * Initial input management.
 *
 * @param  string $default_locale the default locale.
 * @return string                 the resulting message.
 */
function manage_input($default_locale){
    $msg="";
    if( isset($_POST['delete_language'])){
        $msg = delete_language($_POST['locale_selector'], $default_locale);
    }else if(isset($_POST['add_language'])){
        $msg = add_language($_POST, $default_locale);
    }else if(isset($_POST['update_selector'])){
        $msg = update_selector($_POST);
    }else if(isset($_POST['edit_font'])){
        $msg = update_font($_POST);
    }else if(isset($_POST['hide_about'])){
        $msg = update_about(isset($_POST['hide']));
    }
    return $msg;
}

/**
 * Adds a new active language option.
 *
 * @param  array  $form_data      the post data.
 * @param  string $default_locale the default locale.
 * @return string                 the resulting message.
 */
function add_language($form_data, $default_locale){
    $newlang = sanitize($form_data);
    if($newlang['locale'] == $default_locale){
        return sprintf(__('locale %s is already the default'), $newlang['locale']);
    }
    $active_languages = get_active_languages(False);
    foreach($active_languages as $lang){
        if($lang['locale'] == $newlang['locale']){
            return sprintf(__('locale %s is already active'), $newlang['locale']);
        }
    }
    array_push($active_languages, $newlang);
    if(update_option(XG_LANGS, $active_languages)){
        return sprintf(__('language %s added successfully'), $newlang['description']);
    }
    return __('Option could not be modified');
}

/**
 * Formats the input.
 *
 * @param  array $form_data the post data.
 * @return array            the data formatted.
 */
function sanitize($form_data){
    $selected_locale = explode(' : ', $form_data['locale_selector']);
    $result = array();
    $result['locale'] = $selected_locale[0];
    $result['description'] = $selected_locale[1];
    $result = set_font($result, $form_data);
    return $result;
}

/**
 * Deletes an active language option.
 *
 * @param  string $locale_selector the selected locale.
 * @param  string $default_locale  the default locale.
 * @return string                  the resulting message.
 */
function delete_language($locale_selector, $default_locale){
    if($locale_selector == $default_locale){
        return sprintf(__('Default locale (%s) cannot be deleted'), $default_locale);
    }
    $active_languages = get_active_languages(True);
    foreach($active_languages as $key=>$lang){
        if($lang['locale'] == $locale_selector){
            unset($active_languages[$key]);
            update_option(XG_LANGS, $active_languages);
            return sprintf(__('locale %s deleted successfully'), $locale_selector);
        }
    }
    return sprintf(__('locale %s was not found active'), $locale_selector);
}

/**
 * Updates the language selector placement option.
 *
 * @param  array  $form_data the post data.
 * @return string            the resulting message.
 */
function update_selector($form_data){
    $update = array();
    $types = array('home', 'post', 'page');
    foreach($types as $type){
        if(isset($form_data[$type])){
            $update[] = $type;
        }
    }
    if(update_option(XG_SELECT, $update)){
        return __('Selector updated successfily');
    }
    return __('Selector kept it\'s state');
}

/**
 * Checks the checkbox if it's value is previously selected by the user.
 *
 * @param string $opt     the checkbox value.
 * @param array  $options the previously selected values.
 */
function is_checked($opt, $options){
    if(!$options){
        return;
    }
    if(in_array($opt, $options)){
        echo("checked");
    }
}

/**
 * Updates the font of an active language.
 *
 * @param  array  $form_data the post data.
 * @return string            the resulting message.
 */
function update_font($form_data){
    $active_languages = get_active_languages(False);
    foreach($active_languages as $key=>$lang){
        if($lang['locale'] == $form_data['locale']){
            $active_languages[$key] = set_font($lang, $form_data);
            if(update_option(XG_LANGS, $active_languages)){
                return sprintf(__('%s font updated successfuly'), $lang['locale']);
            }
            return sprintf(__('%s font kept it\'s state'), $lang['locale']);
        }
    }
}

/**
 * Replaces empty data with the 'Default' placeholder for the language.
 *
 * @param  array $lang      the language.
 * @param  array $form_data the post data.
 * @return array            the language.
 */
function set_font($lang, $form_data){
    $lang['font_url'] = empty($form_data['font_url']) ? 'Default' : $form_data['font_url'];
    $lang['font_name'] = basename($lang['font_url']);
    $lang['font_size'] = empty($form_data['font_size']) ? 'Default' : $form_data['font_size'] . $form_data['size_unit'];
    return $lang;
}

/**
 * Updates the About page state.
 *
 * @param bool $hide true if baout must be hidden.
 */
function update_about($hide){
    var_dump($hide);
    update_option(XG_HIDE, $hide);
}

/**
 * Prints the message if it exists.
 *
 * @param string $msg the message.
 */
function msgbox($msg){
    $msgbox = '<div id="message" class="updated below-h2"><p>%s</p></div>';
    if($msg != ""){
        printf($msgbox, $msg);
    }
}

/**
 * Prints the table rows for the synopsis table.
 *
 * @param array  $active_languages the active languages.
 * @param array  $translations     the translations.
 * @param string $default_locale   the default locale.
 * @param array  $posts            the posts array.
 */
function synopsis_table($active_languages, $translations, $default_locale, $posts){
    $format = get_synopsis_format();
    $post_count = count($posts);
    foreach($active_languages as $lang){
        if($default_locale != $lang['locale']){
            $stats = get_language_stats($post_count, $translations, $lang['locale']);
            echo('<tr class="with_options">');
            printf($format['language'], $lang['description'], $lang['locale']);
            printf($format['font'], $lang['font_name'], $lang['locale'], __('Edit'));
            printf($format['font'], $lang['font_size'], $lang['locale'], __('Edit'));
            printf($format['posts'], 'translated_' . $lang['locale'], $stats[0]);
            printf($format['posts'], 'remnant_' . $lang['locale'], $stats[1]);
        }else{
            echo('<tr>');
            printf($format['default'], $lang['description']);
            printf($format['default'], $lang['font_name']);
            printf($format['default'], $lang['font_size']);
            printf($format['default'], '');
            printf($format['default'], '');
        }
        echo('</tr>');
    }
}

/**
 * Creates the format string array for the synopsis table rows.
 *
 * @return array the format strings.
 */
function get_synopsis_format(){
    $form = '<form method="post", action="#">
                <input type="hidden" name="locale_selector" value="%s"/>
                <input type="submit" name="delete_language" value="'.__('Delete').'" class="table_delete"/></form>';
    $format['language'] = "<td>%s$form</td>";
    $format['font'] = '<td>%s<br/><a class="a_edit" title="Edit this font" href="#%s_edit">%s</a></td>';
    $format['posts'] = '<td><a title="See translated posts" href="#%s">%s</a></td>';
    $format['default'] = '<td>%s</td>';
    return $format;
}

/**
 * Prints the table rows for the analysis hidden tables.
 *
 * @param array  $entries the posts.
 * @param array  $format  the format strings.
 * @param string $locale  the related locale.
 */
function analysis_table($entries, $format, $locale){
    if(empty($entries)){ return; }
    $translated = ($entries[0]->post_type == TRANSLAT);
    $entries = filter_entries_by_input($entries);
    foreach($entries as $entry){
        echo('<tr>');
        $user = get_userdata($entry->post_author);
        $author = $user->user_login;
        if($translated){
            $parent_id = wp_get_post_terms($entry->ID, PARENTAX, array('fields' => 'slugs'))[0];
            printf($format['id']  , $entry->ID);
            printf($format['post'], $entry->post_title, $parent_id, $locale, $entry->post_title);
            printf($format['tget'], 'author', $entry->post_author,  $locale, $author);
            printf($format['tget'], 'status', $entry->post_status,  $locale, $entry->post_status);
        }else{
            printf($format['id']  , $entry->ID);
            printf($format['post'], $entry->post_title, $entry->ID, $locale, $entry->post_title);
            printf($format['rget'], 'author', $entry->post_author,  $locale, $author);
            printf($format['rget'], 'type', $entry->post_type,      $locale, $entry->post_type);
            printf($format['rget'], 'status', $entry->post_status,  $locale, $entry->post_status);


        }
        echo('</tr>');
    }
}

/**
 * Creates the format string array for the analysis table rows.
 *
 * @return array the format strings.
 */
function get_analysis_format(){
    $translate = __('Translate');
    $editurl = EDITURL . '&post_selector=%d&locale_selector=%s';
    $optturl = OPTSURL . '&post_%s=%s#translated_%s';
    $optrurl = OPTSURL . '&post_%s=%s#remnant_%s';
    $format['id']    = '<td><strong>%s</strong></td>';
    $format['post']  = "<td><strong><a class=\"row-title\" title=\"$translate %s\" href=$editurl>%s</a></strong></td>";
    $format['tget']  = "<td><a href=$optturl>%s</a></td>";
    $format['rget']  = "<td><a href=$optrurl>%s</a></td>";
    return $format;
}

/**
 * Applies the requested filters to the entries.
 *
 * @param  array $entries the posts.
 * @return array          the filtered $entries
 */
function filter_entries_by_input($entries){
    $clause1 = isset($_POST['reset']);
    $clause2 = ! isset($_GET['post_author']);
    $clause3 = ! isset($_GET['post_status']);
    $clause4 = ! isset($_GET['post_type']);
    if($clause1 || $clause2 && $clause3 && $clause4){ return $entries; }
    $res = array();
    $filters = array('post_author', 'post_status', 'post_type');
    foreach($filters as $filter){
        if(isset($_GET[$filter])){
            foreach($entries as $entry){
                if($entry->$filter == $_GET[$filter]){
                    $res[] = $entry;
                }
            }
        }
    }
    return $res;
}


/**
 * Subtracts the translated posts from total.
 *
 * @param  int    $total        the posts sum.
 * @param  array  $translations the translations.
 * @param  string $locale       the related locale.
 * @return array                the translated and remnant values.
 */
function get_language_stats($total, $translations, $locale){
    $translations_count = count(filter_translations_by_locale($translations, $locale));
    return array($translations_count, ($total-$translations_count));
}

/**
 * Prints an option for every active language.
 *
 * @param array $active_languages the active languages.
 */
function populate_active_locale_selector($active_languages){
    $optfrmt = '<option value="%1$s">%1$s : %2$s</option>';
    foreach($active_languages as $lang){
        printf($optfrmt, $lang['locale'], $lang['description']);
    }
}

/**
 * Prints the font edditing form for each active language.
 *
 * @param array  $active_languages the active languages.
 * @param string $default_locale   the default locale.
 */
function hidden_forms($active_languages, $default_locale){
    foreach($active_languages as $lang){
        if($lang['locale'] == $default_locale){
            continue;
        }
        $font_size = array_fill(0, 3, '');
        if($lang['font_size'] != 'Default'){
            preg_match('/([0-9]*)(.*)/', $lang['font_size'], $font_size);
        }
        include(HTMLDIR . 'font.html');
    }
}

/**
 * Prints the analysis table for every active language.
 *
 * @param array  $active_languages the active languages.
 * @param array  $translations     the translations.
 * @param string $default_locale   the default locale.
 * @param array  $posts            the posts array.
 */
function hidden_tables($active_languages, $translations, $default_locale, $posts){
    $format = get_analysis_format();
    foreach($active_languages as $lang){
        if($lang['locale'] == $default_locale){
            continue;
        }
        $translated = filter_translations_by_locale($translations, $lang['locale']);
        $remnant = get_remnant($posts, $translated);
        include(HTMLDIR . 'analysis.html');
    }
}

/**
 * Gets all the selected language's posts that remain untranslated.
 *
 * @param  array $posts      the posts array.
 * @param  array $translated the filtered translations.
 * @return array             the remnant.
 */
function get_remnant($posts, $translated){
    foreach($translated as $trans){
        $ids[] = $trans->PARENTAX;
    }
    foreach($posts as $post){
        if(!in_array($post->ID, $ids)){
            $remnant[] = $post;
        }
    }
    return $remnant;
}

?>
