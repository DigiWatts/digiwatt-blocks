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
