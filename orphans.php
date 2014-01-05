<?php /* vim: set fileencoding=utf-8: */

/********************************************************************
 *                                                                  *
 *    Copyright © George Stamoulis - 2013 - All Rights Reserved.    *
 *    This file is part of the Xenoglossy Wordpress plugin.         *
 *                                                                  *
 ********************************************************************/

/* Triggers when a post is permanently deleted. */
add_action('before_delete_post', 'delete_orphan_translations');

/* Triggers when a post is trashed. */
add_action('wp_trash_post', 'trash_translations');

/* Triggers when a post is untrashed. */
add_action('untrashed_post', 'restore_translations');

/**
 * <b>Deletes</b> the all the translations of the post.
 *
 * @param int $parent_id the id of the post to have all it's translations <b>deleted</b>.
 */
function delete_orphan_translations($parent_id){
    $translations = filter_translations_by_parent(get_all_translations(), $parent_id);
    if($translations){
        foreach($translations as $erased){
            wp_delete_post($erased->ID, True);
        }
    }
}

/**
 * Trashes the all the translations of the post.
 *
 * @param int $parent_id the id of the post to have all it's translations trashed.
 */
function trash_translations($parent_id){
    $translations = filter_translations_by_parent(get_all_translations(), $parent_id);
    if($translations){
        foreach($translations as $trashed){
            wp_delete_post($trashed->ID, False);
        }
    }
}

/**
 * Restores the all the translations of the post.
 *
 * @param int $parent_id the id of the post to have all it's translations restored.
 */
function restore_translations($parent_id){
    $translations = filter_translations_by_parent(get_all_translations(), $parent_id);
    if($translations){
        foreach($translations as $restored){
            wp_untrash_post($restored->ID);
        }
    }
}

?>
