<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- ===== HEADER ===== -->
<header id="site-header">
    <!-- Top bar: date + social -->
    <div class="header-top">
        <div class="container">
            <span class="header-top-date"><?php echo esc_html( news1_us_date() ); ?> ET</span>
            <div class="header-social-top">
                <?php
                $fb  = news1_get_social( 'news1_facebook_url' );
                $tw  = news1_get_social( 'news1_twitter_url' );
                $red = news1_get_social( 'news1_reddit_url' );
                if ( $fb )  echo '<a href="' . esc_url( $fb ) . '" target="_blank" rel="noopener" aria-label="Facebook">f Facebook</a>';
                if ( $tw )  echo '<a href="' . esc_url( $tw ) . '" target="_blank" rel="noopener" aria-label="X Twitter">&#120143; X</a>';
                if ( $red ) echo '<a href="' . esc_url( $red ) . '" target="_blank" rel="noopener" aria-label="Reddit">&#128125; Reddit</a>';
                ?>
            </div>
        </div>
    </div>

    <!-- Logo + Search -->
    <div class="header-main">
        <div class="container">
            <div class="site-branding">
                <?php if ( has_custom_logo() ) : ?>
                    <div class="site-logo"><?php the_custom_logo(); ?></div>
                <?php endif; ?>
                <div>
                    <div class="site-title">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                            <?php
                            $name  = get_bloginfo( 'name' );
                            $parts = explode( ' ', $name, 2 );
                            echo esc_html( $parts[0] );
                            if ( isset( $parts[1] ) ) echo ' <span>' . esc_html( $parts[1] ) . '</span>';
                            ?>
                        </a>
                    </div>
                    <?php if ( get_bloginfo( 'description' ) ) : ?>
                        <div class="site-tagline"><?php bloginfo( 'description' ); ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="header-search">
                <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <input type="search" name="s" placeholder="<?php esc_attr_e( 'Search news...', 'news-1' ); ?>"
                           value="<?php echo esc_attr( get_search_query() ); ?>">
                    <button type="submit" aria-label="Search">&#128269;</button>
                </form>
            </div>
        </div>
    </div>
</header>

<!-- ===== PRIMARY NAV (sticky) ===== -->
<nav id="site-nav" aria-label="<?php esc_attr_e( 'Primary Navigation', 'news-1' ); ?>">
    <div class="container">
        <button class="hamburger" id="hamburger" aria-expanded="false" aria-controls="primary-menu">
            &#9776;
        </button>
        <?php wp_nav_menu( [
            'theme_location' => 'primary',
            'menu_id'        => 'primary-menu',
            'menu_class'     => 'nav-menu',
            'container'      => false,
            'depth'          => 2,
            'walker'         => new News1_Walker_Nav_Menu(),
            'fallback_cb'    => function() {
                echo '<ul class="nav-menu">';
                echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . __( 'Home', 'news-1' ) . '</a></li>';
                $cats = get_categories( [ 'number' => 7, 'hide_empty' => false ] );
                foreach ( $cats as $cat ) {
                    echo '<li><a href="' . esc_url( get_category_link( $cat->term_id ) ) . '">' . esc_html( $cat->name ) . '</a></li>';
                }
                echo '</ul>';
            },
        ] ); ?>
    </div>
</nav>

<!-- ===== SECONDARY NAV: Trending + Latest ===== -->
<div id="secondary-nav">
    <div class="container">
        <div class="sec-nav-wrap">

            <!-- Trending -->
            <div class="sec-group sec-trending">
                <span class="sec-label sec-label-trend">&#128293; Trending</span>
                <?php
                $trending_q = news1_trending_query( 10 );
                if ( $trending_q->have_posts() ) :
                ?>
                <marquee class="sec-marquee" scrollamount="3"
                         onmouseover="this.stop()" onmouseout="this.start()">
                    <?php while ( $trending_q->have_posts() ) : $trending_q->the_post(); ?>
                    <a href="<?php the_permalink(); ?>" class="sec-link"><?php the_title(); ?></a>
                    <span class="sec-sep">|</span>
                    <?php endwhile; wp_reset_postdata(); ?>
                </marquee>
                <?php endif; ?>
            </div>

            <!-- Latest -->
            <div class="sec-group sec-latest">
                <span class="sec-label sec-label-latest">Latest</span>
                <div class="sec-latest-links">
                    <?php
                    $latest_nav = new WP_Query( [ 'posts_per_page' => 5, 'orderby' => 'date', 'order' => 'DESC', 'ignore_sticky_posts' => true ] );
                    while ( $latest_nav->have_posts() ) : $latest_nav->the_post();
                    ?>
                    <a href="<?php the_permalink(); ?>" class="sec-link"><?php the_title(); ?></a>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ===== BREADCRUMB ===== -->
<?php if ( ! is_front_page() ) : ?>
<div class="breadcrumb-bar">
    <div class="container"><?php news1_breadcrumb(); ?></div>
</div>
<?php endif; ?>

<div id="main-content">
    <div class="container">
