<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Kntnt Auto SEO Description Generator
 * Plugin URI:        https://github.com/Kntnt/kntnt-auto-seo-description-generator
 * GitHub Plugin URI: https://github.com/Kntnt/kntnt-auto-seo-description-generator
 * Description:       Provides the excerpt as the value of the field _genesis_description, if missing.
 * Version:           1.0.0
 * Author:            Thomas Barregren
 * Author URI:        https://www.kntnt.com/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 */

defined( 'WPINC' ) || die;

add_filter( 'get_post_metadata', function ( $value, $post_id, $meta_key, $single ) {

    // Do nothing if _genesis_description isn't requested.
    if ( '' != $meta_key && '_genesis_description' != $meta_key ) {
        return $value;
    }

    // Don't filter when this filter is called indirectly by itself.
    static $dont_filter = false;
    if ( $dont_filter ) {
        return $value;
    }

    // Get the original metadata.
    $dont_filter = true;
    $meta_value = get_post_meta( $post_id, $meta_key, $single );
    $dont_filter = false;

    // Use the excerpt if  _genesis_description is empty.
    if ( is_array( $meta_value ) ) {
        if ( ! isset( $meta_value['_genesis_description'] ) || '' == $meta_value['_genesis_description'][0] ) {
            $meta_value['_genesis_description'][0] = get_the_excerpt( $post_id );
        }
    }
    else if ( '' == $meta_value ) {
        $meta_value = get_the_excerpt( $post_id );
    }

    return $meta_value;

}, 10, 4 );
