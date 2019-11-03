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

namespace Kntnt\Auto_SEO_Description_Generator;

defined( 'WPINC' ) && new Plugin;

final class Plugin {

    function __construct() {
        add_filter( 'get_post_metadata', [ $this, 'get_post_metadata' ], 10, 4 );
    }

    function get_post_metadata( $value, $post_id, $meta_key, $single ) {

        // Don't filter when this filter is called indirectly by itself.
        static $dont_filter = false;
        if ( $dont_filter ) {
            return $value;
        }

        // Do nothing if _genesis_description isn't requested.
        if ( '' != $meta_key && '_genesis_description' != $meta_key ) {
            return $value;
        }

        // Get the original metadata.
        $dont_filter = true;
        $meta_value = get_post_meta( $post_id, $meta_key, $single );
        $dont_filter = false;

        if ( is_array( $meta_value ) ) {
            if ( ! isset( $meta_value['_genesis_description'] ) ) {
                $meta_value['_genesis_description'][0] = $this->description( $post_id );
            }
            $meta_value['_genesis_description'][0] = $this->description( $post_id, $meta_value['_genesis_description'][0] );
        }
        else {
            $meta_value = $this->description( $post_id, $meta_value );
        }

        return $meta_value;

    }

    private function description( $post_id, $text = '' ) {
        if ( '' == $text ) {
            $text = get_the_excerpt( $post_id );
        }
        return $text;
    }

}