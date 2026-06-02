<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
    <div class="post-card-thumb">
        <a href="<?php the_permalink(); ?>">
            <?php echo news1_get_thumbnail( get_the_ID(), 'news-card', [ 'alt' => get_the_title() ] ); ?>
        </a>
    </div>
    <div class="post-card-body">
        <?php echo news1_get_primary_cat(); ?>
        <h2 class="post-card-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h2>
        <div class="post-card-meta">
            <span><?php the_author(); ?></span>
            <span><?php echo news1_time_ago(); ?></span>
        </div>
    </div>
</article>
