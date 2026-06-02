<?php get_header(); ?>

<div class="single-content-wrap">

    <!-- ===== ARTICLE + COMMENTS ===== -->
    <main id="primary" class="single-main" role="main">
        <?php while ( have_posts() ) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class( 'single-post-wrap' ); ?>>

            <!-- Categories -->
            <div class="single-post-cats">
                <?php
                $cats = get_the_category();
                foreach ( $cats as $cat ) :
                ?>
                <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"><?php echo esc_html( $cat->name ); ?></a>
                <?php endforeach; ?>
            </div>

            <!-- Title -->
            <h1 class="single-post-title"><?php the_title(); ?></h1>

            <!-- Meta -->
            <div class="single-post-meta">
                <?php
                $author_bio = get_the_author_meta( 'description' );
                if ( ! $author_bio ) {
                    $author_bio = sprintf(
                        __( '%s is a sports and events writer for ClutchPoints, primarily covering events and athletes from the NBA, NFL, NCAA Basketball, and MLB. The Denver native graduated from Colorado State and has previously written at three local news outlets in the area.', 'news-1' ),
                        get_the_author()
                    );
                }
                ?>
                <span class="author-trigger-wrap">
                    <?php _e( 'By', 'news-1' ); ?>
                    <button type="button" class="author-popup-trigger">
                        <?php the_author(); ?>
                    </button>
                    <div class="author-tooltip" role="tooltip">
                        <strong class="author-tooltip-name"><?php echo esc_html( get_the_author() ); ?></strong>
                        <p class="author-tooltip-bio"><?php echo esc_html( $author_bio ); ?></p>
                    </div>
                </span>
                <span><?php echo news1_time_ago(); ?></span>
                <?php
                $pub_ts = get_post_time( 'U' );
                $mod_ts = get_post_modified_time( 'U' );
                if ( $mod_ts > $pub_ts ) :
                ?>
                <span><?php _e( 'Updated:', 'news-1' ); ?> <?php echo news1_time_ago( $mod_ts ); ?></span>
                <?php endif; ?>
                <?php if ( comments_open() ) : ?>
                <span>
                    <a href="#comments">
                        <?php comments_number( __( 'No comments', 'news-1' ), __( '1 comment', 'news-1' ), __( '% comments', 'news-1' ) ); ?>
                    </a>
                </span>
                <?php endif; ?>
                <?php
                $words   = str_word_count( strip_tags( get_the_content() ) );
                $est_min = max( 1, round( $words / 200 ) );
                ?>
                <span><?php echo $est_min; ?> <?php _e( 'min read', 'news-1' ); ?></span>
                <span><?php echo number_format( news1_get_views() ); ?> <?php _e( 'views', 'news-1' ); ?></span>
            </div>

            <!-- Share (top) -->
            <div class="post-share">
                <span class="post-share-label"><?php _e( 'Share:', 'news-1' ); ?></span>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo rawurlencode( get_permalink() ); ?>"
                   class="share-btn facebook" target="_blank" rel="noopener noreferrer">
                    f
                </a>
                <a href="https://twitter.com/intent/tweet?url=<?php echo rawurlencode( get_permalink() ); ?>&text=<?php echo rawurlencode( get_the_title() ); ?>"
                   class="share-btn twitter" target="_blank" rel="noopener noreferrer">
                    &#120143;
                </a>
                <a href="https://www.reddit.com/submit?url=<?php echo rawurlencode( get_permalink() ); ?>&title=<?php echo rawurlencode( get_the_title() ); ?>"
                   class="share-btn reddit" target="_blank" rel="noopener noreferrer">
                     <?xml version="1.0" encoding="utf-8"?>
<!-- License: MIT. Made by Garuda Technology: https://github.com/garudatechnologydevelopers/sketch-icons -->
<svg width="25px" height="25px" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M16 2C8.27812 2 2 8.27812 2 16C2 23.7219 8.27812 30 16 30C23.7219 30 30 23.7219 30 16C30 8.27812 23.7219 2 16 2Z" fill="#FC471E"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M20.0193 8.90951C20.0066 8.98984 20 9.07226 20 9.15626C20 10.0043 20.6716 10.6918 21.5 10.6918C22.3284 10.6918 23 10.0043 23 9.15626C23 8.30819 22.3284 7.6207 21.5 7.6207C21.1309 7.6207 20.7929 7.7572 20.5315 7.98359L16.6362 7L15.2283 12.7651C13.3554 12.8913 11.671 13.4719 10.4003 14.3485C10.0395 13.9863 9.54524 13.7629 9 13.7629C7.89543 13.7629 7 14.6796 7 15.8103C7 16.5973 7.43366 17.2805 8.06967 17.6232C8.02372 17.8674 8 18.1166 8 18.3696C8 21.4792 11.5817 24 16 24C20.4183 24 24 21.4792 24 18.3696C24 18.1166 23.9763 17.8674 23.9303 17.6232C24.5663 17.2805 25 16.5973 25 15.8103C25 14.6796 24.1046 13.7629 23 13.7629C22.4548 13.7629 21.9605 13.9863 21.5997 14.3485C20.2153 13.3935 18.3399 12.7897 16.2647 12.7423L17.3638 8.24143L20.0193 8.90951ZM12.5 18.8815C13.3284 18.8815 14 18.194 14 17.3459C14 16.4978 13.3284 15.8103 12.5 15.8103C11.6716 15.8103 11 16.4978 11 17.3459C11 18.194 11.6716 18.8815 12.5 18.8815ZM19.5 18.8815C20.3284 18.8815 21 18.194 21 17.3459C21 16.4978 20.3284 15.8103 19.5 15.8103C18.6716 15.8103 18 16.4978 18 17.3459C18 18.194 18.6716 18.8815 19.5 18.8815ZM12.7773 20.503C12.5476 20.3462 12.2372 20.4097 12.084 20.6449C11.9308 20.8802 11.9929 21.198 12.2226 21.3548C13.3107 22.0973 14.6554 22.4686 16 22.4686C17.3446 22.4686 18.6893 22.0973 19.7773 21.3548C20.0071 21.198 20.0692 20.8802 19.916 20.6449C19.7628 20.4097 19.4524 20.3462 19.2226 20.503C18.3025 21.1309 17.1513 21.4449 16 21.4449C15.3173 21.4449 14.6345 21.3345 14 21.1137C13.5646 20.9621 13.1518 20.7585 12.7773 20.503Z" fill="white"/>
</svg>
                </a>
                <button class="share-btn copy" data-url="<?php the_permalink(); ?>">
                    &#128279; Copy Link
                </button>
            </div>

            <!-- Featured Image -->
            <?php if ( has_post_thumbnail() ) : ?>
            <div class="single-post-thumb">
                <?php the_post_thumbnail( 'news-wide', [ 'alt' => get_the_title() ] ); ?>
                <?php $cap = get_the_post_thumbnail_caption(); if ( $cap ) : ?>
                <p class="thumb-caption"><?php echo esc_html( $cap ); ?></p>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Content -->
            <div class="single-post-content">
                <?php
                the_content();
                wp_link_pages( [
                    'before' => '<div class="page-links">' . __( 'Pages:', 'news-1' ),
                    'after'  => '</div>',
                ] );
                ?>
            </div>

            <!-- Tags -->
            <?php $tags = get_the_tags(); if ( $tags ) : ?>
            <div class="post-tags">
                <span class="post-tags-label"><?php _e( 'Tags:', 'news-1' ); ?></span>
                <?php foreach ( $tags as $tag ) : ?>
                <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>"><?php echo esc_html( $tag->name ); ?></a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Share (bottom) -->
            <div class="post-share" style="margin-top:20px;">
                <span class="post-share-label"><?php _e( 'Share this article:', 'news-1' ); ?></span>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo rawurlencode( get_permalink() ); ?>"
                   class="share-btn facebook" target="_blank" rel="noopener noreferrer">f</a>
                <a href="https://twitter.com/intent/tweet?url=<?php echo rawurlencode( get_permalink() ); ?>&text=<?php echo rawurlencode( get_the_title() ); ?>"
                   class="share-btn twitter" target="_blank" rel="noopener noreferrer">&#120143;</a>
                <a href="https://www.reddit.com/submit?url=<?php echo rawurlencode( get_permalink() ); ?>&title=<?php echo rawurlencode( get_the_title() ); ?>"
                   class="share-btn reddit" target="_blank" rel="noopener noreferrer"><?xml version="1.0" encoding="utf-8"?>
<!-- License: MIT. Made by Garuda Technology: https://github.com/garudatechnologydevelopers/sketch-icons -->
<svg width="25px" height="25px" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M16 2C8.27812 2 2 8.27812 2 16C2 23.7219 8.27812 30 16 30C23.7219 30 30 23.7219 30 16C30 8.27812 23.7219 2 16 2Z" fill="#FC471E"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M20.0193 8.90951C20.0066 8.98984 20 9.07226 20 9.15626C20 10.0043 20.6716 10.6918 21.5 10.6918C22.3284 10.6918 23 10.0043 23 9.15626C23 8.30819 22.3284 7.6207 21.5 7.6207C21.1309 7.6207 20.7929 7.7572 20.5315 7.98359L16.6362 7L15.2283 12.7651C13.3554 12.8913 11.671 13.4719 10.4003 14.3485C10.0395 13.9863 9.54524 13.7629 9 13.7629C7.89543 13.7629 7 14.6796 7 15.8103C7 16.5973 7.43366 17.2805 8.06967 17.6232C8.02372 17.8674 8 18.1166 8 18.3696C8 21.4792 11.5817 24 16 24C20.4183 24 24 21.4792 24 18.3696C24 18.1166 23.9763 17.8674 23.9303 17.6232C24.5663 17.2805 25 16.5973 25 15.8103C25 14.6796 24.1046 13.7629 23 13.7629C22.4548 13.7629 21.9605 13.9863 21.5997 14.3485C20.2153 13.3935 18.3399 12.7897 16.2647 12.7423L17.3638 8.24143L20.0193 8.90951ZM12.5 18.8815C13.3284 18.8815 14 18.194 14 17.3459C14 16.4978 13.3284 15.8103 12.5 15.8103C11.6716 15.8103 11 16.4978 11 17.3459C11 18.194 11.6716 18.8815 12.5 18.8815ZM19.5 18.8815C20.3284 18.8815 21 18.194 21 17.3459C21 16.4978 20.3284 15.8103 19.5 15.8103C18.6716 15.8103 18 16.4978 18 17.3459C18 18.194 18.6716 18.8815 19.5 18.8815ZM12.7773 20.503C12.5476 20.3462 12.2372 20.4097 12.084 20.6449C11.9308 20.8802 11.9929 21.198 12.2226 21.3548C13.3107 22.0973 14.6554 22.4686 16 22.4686C17.3446 22.4686 18.6893 22.0973 19.7773 21.3548C20.0071 21.198 20.0692 20.8802 19.916 20.6449C19.7628 20.4097 19.4524 20.3462 19.2226 20.503C18.3025 21.1309 17.1513 21.4449 16 21.4449C15.3173 21.4449 14.6345 21.3345 14 21.1137C13.5646 20.9621 13.1518 20.7585 12.7773 20.503Z" fill="white"/>
</svg></a>
            </div>

        </article>

        <!-- Prev / Next post navigation -->
        <?php
        $prev = get_previous_post();
        $next = get_next_post();
        if ( $prev || $next ) :
        ?>
        <div class="post-nav-grid">
            <?php if ( $prev ) : ?>
            <a href="<?php echo esc_url( get_permalink( $prev->ID ) ); ?>" class="post-nav-item">
                <div class="post-nav-label">&laquo; <?php _e( 'Previous', 'news-1' ); ?></div>
                <div class="post-nav-title"><?php echo esc_html( get_the_title( $prev->ID ) ); ?></div>
            </a>
            <?php else : ?>
            <div></div>
            <?php endif; ?>
            <?php if ( $next ) : ?>
            <a href="<?php echo esc_url( get_permalink( $next->ID ) ); ?>" class="post-nav-item" style="text-align:right;">
                <div class="post-nav-label"><?php _e( 'Next', 'news-1' ); ?> &raquo;</div>
                <div class="post-nav-title"><?php echo esc_html( get_the_title( $next->ID ) ); ?></div>
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Comments -->
        <?php if ( comments_open() || get_comments_number() ) : ?>
            <?php comments_template(); ?>
        <?php endif; ?>

        <?php endwhile; ?>
    </main>

    <!-- ===== STICKY RELATED ARTICLES SIDEBAR ===== -->
    <aside class="single-related-sidebar" role="complementary">
        <?php news1_related_posts( get_the_ID(), 8 ); ?>
    </aside>

</div>

<?php get_footer(); ?>
