<?php
if ( post_password_required() ) return;
?>
<div id="comments" class="comments-wrap">

    <?php if ( have_comments() ) : ?>
    <h3 class="comments-title">
        <?php
        $count = get_comments_number();
        printf(
            _n( '%s Comment', '%s Comments', $count, 'news-1' ),
            number_format_i18n( $count )
        );
        ?>
    </h3>

    <ol class="comment-list">
        <?php
        wp_list_comments( [
            'style'       => 'ol',
            'short_ping'  => true,
            'avatar_size' => 0,
            'callback'    => function( $comment, $args, $depth ) {
                $GLOBALS['comment'] = $comment;
                ?>
                <li id="comment-<?php comment_ID(); ?>" <?php comment_class( 'comment' ); ?>>
                    <?php
                    $author_name   = get_comment_author();
                    $avatar_letter = strtoupper( mb_substr( $author_name, 0, 1 ) );
                    ?>
                    <div class="comment-inner">
                    <div class="comment-avatar" aria-hidden="true"><?php echo esc_html( $avatar_letter ); ?></div>
                    <div class="comment-body">
                        <div class="comment-meta-line">
                            <span class="comment-author-name"><?php comment_author(); ?></span>
                            <span class="comment-date-info"><?php comment_date( 'M j, Y' ); ?> at <?php comment_time( 'g:i a' ); ?></span>
                        </div>

                        <?php if ( '0' === $comment->comment_approved ) : ?>
                        <div class="comment-pending">&#9679; Awaiting moderation</div>
                        <?php endif; ?>

                        <div class="comment-text"><?php comment_text(); ?></div>

                        <div class="comment-actions">
                        <?php
                        comment_reply_link( array_merge( $args, [
                            'depth'      => $depth,
                            'max_depth'  => $args['max_depth'],
                            'before'     => '',
                            'after'      => '',
                            'reply_text' => 'Reply',
                        ] ) );
                        ?>
                        </div>
                    </div>
                    </div><!-- .comment-inner -->
                <?php
            },
            'end-callback' => function( $comment, $args, $depth ) {
                echo '</li>';
            },
        ] );
        ?>
    </ol>

    <button id="load-more-comments" type="button">
        <?php _e( 'Load More Comments', 'news-1' ); ?>
    </button>

    <?php endif; // have_comments() ?>

    <?php if ( ! comments_open() && get_comments_number() ) : ?>
    <p style="color:#888;font-size:14px;text-align:center;padding:14px 0;">
        <?php _e( 'Comments are closed.', 'news-1' ); ?>
    </p>
    <?php endif; ?>

    <!-- ===== Comment Form ===== -->
    <?php if ( comments_open() ) : ?>
    <div class="comment-form-wrap">
        <h3><?php _e( 'Leave a Comment', 'news-1' ); ?></h3>
        <?php
        comment_form( [
            'class_form'           => 'comment-form',
            'title_reply'          => '',
            'title_reply_before'   => '',
            'title_reply_after'    => '',
            'cancel_reply_before'  => '<span class="cancel-reply">',
            'cancel_reply_after'   => '</span>',
            'label_submit'         => __( 'Post Comment', 'news-1' ),
            'class_submit'         => 'submit-btn',
            'comment_notes_before' => '',
            'comment_notes_after'  => '',
            'fields' => [
                'author' => '<div class="form-row"><label for="author">'
                    . __( 'Name', 'news-1' )
                    . ' <span aria-hidden="true">*</span></label>'
                    . '<input id="author" name="author" type="text" required autocomplete="name" maxlength="245"></div>',
            ],
            'comment_field' => '<div class="form-row"><label for="comment">'
                . __( 'Comment', 'news-1' )
                . ' <span aria-hidden="true">*</span></label>'
                . '<textarea id="comment" name="comment" rows="5" required></textarea></div>',
        ] );
        ?>
    </div>
    <?php endif; ?>

</div>
