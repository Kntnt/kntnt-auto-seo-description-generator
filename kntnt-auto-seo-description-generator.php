<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Kntnt Auto SEO Description Generator
 * Plugin URI:        https://github.com/Kntnt/kntnt-auto-seo-description-generator
 * Description:       Provides the excerpt as the value of the field _genesis_description, if missing.
 * Version:           2.0.0
 * Author:            Thomas Barregren
 * Author URI:        https://www.kntnt.com/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 */

defined( 'ABSPATH' ) || die;

add_filter( 'get_post_metadata', function ( $meta_value, $post_id, $meta_key, $single ) {

    // Flag signaling whether or not this filter should be applied.
    static $do_filter = true;

    if ( '_genesis_description' == $meta_key && $do_filter ) {

        // Get the the value of _genesis_description.
        $do_filter = false;
        $meta_value = get_post_meta( $post_id, $meta_key, $single );

        // Use excerpt if _genesis_description is missing or empty.
        if ( ! $meta_value ) {
            $meta_value = [ get_the_excerpt( $post_id ) ];
        }

    }

    return $meta_value;

}, 10, 4 );
