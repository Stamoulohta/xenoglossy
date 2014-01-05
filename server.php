<?php /* vim: set fileencoding=utf-8: */

/********************************************************************
 *                                                                  *
 *    Copyright © George Stamoulis - 2013 - All Rights Reserved.    *
 *    This file is part of the Xenoglossy Wordpress plugin.         *
 *                                                                  *
 ********************************************************************/

/* triggers when posts are ready. */
add_filter('the_posts', 'translate_posts', 0);

/**
 * Translates the posts if requested by the locale and if translations are found.
 *
 * @return array the translated posts.
 */
function translate_posts($posts){
    global $locale;
    if($locale != get_default_locale()){
        foreach($posts as $post){
            if($translation = get_translation($post->ID, $locale)){
                $post->post_content = $translation->post_content ? $translation->post_content : $post->post_content;
                $post->post_title = $translation->post_title ? $translation->post_title : $post->post_title;
                $post->post_excerpt = $translation->post_excerpt ? $translation->post_excerpt : $post->post_excerpt;
            }
        }
    }
    return $posts;
}

?>
