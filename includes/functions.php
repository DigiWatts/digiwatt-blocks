<?php
/**
 * DigitWatt Blocks functions
 *
 * @package dwb
 * @since 0.2.0
 */

/**
 * Calculate post read time.
 *
 * @access public
 * @param mixed $attributes (array).
 * @return html
 */
function dwb_reading_time( $attributes ) {
    global $post;

    $content             = get_post_field( 'post_content', $post->ID );
    $word_count          = str_word_count( wp_strip_all_tags( $content ) );
    $reading_time_number = ceil( $word_count / 200 );
    $reading_time        = '';

    if ( 'before' === $attributes['timePosition'] ) {
        $reading_time = $attributes['readTimeText'] . ' ' . $reading_time_number;
    } else {
        $reading_time = $reading_time_number . ' ' . $attributes['readTimeText'];
    }

    return $reading_time;
}

/**
 * Get a trimmed post excerpt.
 *
 * @access public
 * @param int    $post_id (post id).
 * @param int    $length (excerpt length).
 * @param string $extra (after excerpt).
 * @return html
 */
function dwb_post_excerpt( $post_id = 0, $length = 10, $extra = '...' ) {
    if ( is_int( $post_id ) ) {
        $post = get_post( $post_id );
    } else {
        return '';
    }

    $the_excerpt   = $post->post_content;
    $the_excerpt   = strip_shortcodes( wp_strip_all_tags( $the_excerpt ) );
    $the_excerpt   = preg_split( '/\b/', $the_excerpt, $length * 2 + 1 );
    $excerpt_waste = array_pop( $the_excerpt );
    $the_excerpt   = implode( $the_excerpt );
    $the_excerpt  .= $extra;

    return $the_excerpt;
}
