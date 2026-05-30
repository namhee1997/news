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
                if ( $fb )  echo '<a href="' . esc_url( $fb ) . '" target="_blank" rel="noopener" aria-label="Facebook">f</a>';
                if ( $tw )  echo '<a href="' . esc_url( $tw ) . '" target="_blank" rel="noopener" aria-label="X Twitter">&#120143;</a>';
                if ( $red ) echo '<a href="' . esc_url( $red ) . '" target="_blank" rel="noopener" aria-label="Reddit"><?xml version="1.0" encoding="utf-8"?>
<!-- License: MIT. Made by Garuda Technology: https://github.com/garudatechnologydevelopers/sketch-icons -->
<svg width="15px" height="15px" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M16 2C8.27812 2 2 8.27812 2 16C2 23.7219 8.27812 30 16 30C23.7219 30 30 23.7219 30 16C30 8.27812 23.7219 2 16 2Z" fill="#FC471E"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M20.0193 8.90951C20.0066 8.98984 20 9.07226 20 9.15626C20 10.0043 20.6716 10.6918 21.5 10.6918C22.3284 10.6918 23 10.0043 23 9.15626C23 8.30819 22.3284 7.6207 21.5 7.6207C21.1309 7.6207 20.7929 7.7572 20.5315 7.98359L16.6362 7L15.2283 12.7651C13.3554 12.8913 11.671 13.4719 10.4003 14.3485C10.0395 13.9863 9.54524 13.7629 9 13.7629C7.89543 13.7629 7 14.6796 7 15.8103C7 16.5973 7.43366 17.2805 8.06967 17.6232C8.02372 17.8674 8 18.1166 8 18.3696C8 21.4792 11.5817 24 16 24C20.4183 24 24 21.4792 24 18.3696C24 18.1166 23.9763 17.8674 23.9303 17.6232C24.5663 17.2805 25 16.5973 25 15.8103C25 14.6796 24.1046 13.7629 23 13.7629C22.4548 13.7629 21.9605 13.9863 21.5997 14.3485C20.2153 13.3935 18.3399 12.7897 16.2647 12.7423L17.3638 8.24143L20.0193 8.90951ZM12.5 18.8815C13.3284 18.8815 14 18.194 14 17.3459C14 16.4978 13.3284 15.8103 12.5 15.8103C11.6716 15.8103 11 16.4978 11 17.3459C11 18.194 11.6716 18.8815 12.5 18.8815ZM19.5 18.8815C20.3284 18.8815 21 18.194 21 17.3459C21 16.4978 20.3284 15.8103 19.5 15.8103C18.6716 15.8103 18 16.4978 18 17.3459C18 18.194 18.6716 18.8815 19.5 18.8815ZM12.7773 20.503C12.5476 20.3462 12.2372 20.4097 12.084 20.6449C11.9308 20.8802 11.9929 21.198 12.2226 21.3548C13.3107 22.0973 14.6554 22.4686 16 22.4686C17.3446 22.4686 18.6893 22.0973 19.7773 21.3548C20.0071 21.198 20.0692 20.8802 19.916 20.6449C19.7628 20.4097 19.4524 20.3462 19.2226 20.503C18.3025 21.1309 17.1513 21.4449 16 21.4449C15.3173 21.4449 14.6345 21.3345 14 21.1137C13.5646 20.9621 13.1518 20.7585 12.7773 20.503Z" fill="white"/>
</svg></a>';
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
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="text-transform: uppercase;">
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
