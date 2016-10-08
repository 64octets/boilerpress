<?php
/**
 * The template for displaying comments.
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package BoilerPress
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
    return;
}
?>

<section id="comments" class="comments-area" aria-label="Post Comments">

    <?php
    if ( have_comments() ) : ?>
        <h2 class="comments-title">
            <?php
            printf( // WPCS: XSS OK.
                esc_html( _nx( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'boilerpress' ) ),
                number_format_i18n( get_comments_number() ),
                '<span>' . get_the_title() . '</span>'
            );
            ?>
        </h2>

        <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through. ?>
            <nav id="comment-nav-above" class="comment-navigation" role="navigation" aria-label="Comment Navigation Above">
                <span class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'boilerpress' ); ?></span>
                <div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'boilerpress' ) ); ?></div>
                <div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'boilerpress' ) ); ?></div>
            </nav><!-- #comment-nav-above -->
        <?php endif; // Check for comment navigation. ?>

        <ol class="comment-list">
            <?php
            wp_list_comments( array(
                'style'      => 'ol',
                'short_ping' => TRUE,
                'callback'   => 'boilerpress_comment',
            ) );
            ?>
        </ol><!-- .comment-list -->

        <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through. ?>
            <nav id="comment-nav-below" class="comment-navigation" role="navigation" aria-label="Comment Navigation Below">
                <span class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'boilerpress' ); ?></span>
                <div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'boilerpress' ) ); ?></div>
                <div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'boilerpress' ) ); ?></div>
            </nav><!-- #comment-nav-below -->
        <?php endif; // Check for comment navigation.

    endif;

    if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
        <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'boilerpress' ); ?></p>
    <?php endif;

    $args = apply_filters( 'boilerpress_comment_form_args', array(
        'title_reply_before' => '<span id="reply-title" class="gamma comment-reply-title">',
        'title_reply_after'  => '</span>',
    ) );

    comment_form( $args ); ?>

</section><!-- #comments -->
