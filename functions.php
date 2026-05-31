<?php
if ( ! defined( 'ABSPATH' ) ) exit;

require_once get_template_directory() . '/inc/stats-telegram.php';

/* ===== Theme Setup ===== */
function news1_setup() {
    load_theme_textdomain( 'news-1', get_template_directory() . '/languages' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ] );
    add_theme_support( 'custom-logo', [
        'height'      => 80,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ] );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'responsive-embeds' );

    add_image_size( 'news-hero',  800, 420, true );
    add_image_size( 'news-card',  420, 250, true );
    add_image_size( 'news-thumb', 160, 100, true );
    add_image_size( 'news-wide',  1200, 500, true );

    register_nav_menus( [
        'primary' => __( 'Primary Menu', 'news-1' ),
        'footer'  => __( 'Footer Menu', 'news-1' ),
    ] );
}
add_action( 'after_setup_theme', 'news1_setup' );

/* ===== Content Width ===== */
add_action( 'after_setup_theme', function() {
    $GLOBALS['content_width'] = 1200;
}, 0 );

/* ===== Enqueue Scripts & Styles ===== */
function news1_scripts() {
    wp_enqueue_style( 'news1-style', get_stylesheet_uri(), [], '1.1.1' );
    wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Roboto:wght@400;700;900&display=swap', [], null );
    wp_enqueue_script( 'news1-main', get_template_directory_uri() . '/assets/js/main.js', [ 'jquery' ], '1.1.0', true );
    wp_enqueue_script( 'news1-priority-nav', get_template_directory_uri() . '/assets/js/priority-nav.js', [], '1.0.0', true );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }

    wp_localize_script( 'news1-main', 'news1Ajax', [
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'news1_nonce' ),
    ] );
}
add_action( 'wp_enqueue_scripts', 'news1_scripts' );

/* ===== Post View Count ===== */
function news1_count_views( $post_id ) {
    if ( is_admin() || wp_is_json_request() ) return;

    // All-time count
    $count = (int) get_post_meta( $post_id, '_post_views_count', true );
    update_post_meta( $post_id, '_post_views_count', $count + 1 );

    // Daily count — key format: _post_views_20260530
    $today_key   = '_post_views_' . date( 'Ymd' );
    $today_count = (int) get_post_meta( $post_id, $today_key, true );
    update_post_meta( $post_id, $today_key, $today_count + 1 );
}

add_action( 'wp_head', function() {
    if ( is_singular( 'post' ) ) {
        news1_count_views( get_the_ID() );
    }
} );

function news1_get_views( $post_id = null ) {
    return (int) get_post_meta( $post_id ?: get_the_ID(), '_post_views_count', true );
}

// Trả về meta key view của hôm nay
function news1_today_view_key() {
    return '_post_views_' . date( 'Ymd' );
}

/* ===== SEO Meta Tags — disabled: Rank Math handles all SEO meta ===== */
// function news1_seo_meta() { ... }
// add_action( 'wp_head', 'news1_seo_meta', 1 );

/* ===== Customizer: Social Links ===== */
add_action( 'customize_register', function( $wp_customize ) {
    $wp_customize->add_section( 'news1_social', [
        'title'    => __( 'Social Media Links', 'news-1' ),
        'priority' => 30,
    ] );

    $socials = [
        'news1_facebook_url' => 'Facebook Page URL',
        'news1_twitter_url'  => 'X (Twitter) URL',
        'news1_reddit_url'   => 'Reddit URL',
    ];

    foreach ( $socials as $key => $label ) {
        $wp_customize->add_setting( $key, [ 'default' => '', 'sanitize_callback' => 'esc_url_raw' ] );
        $wp_customize->add_control( $key, [
            'label'   => $label,
            'section' => 'news1_social',
            'type'    => 'url',
        ] );
    }
} );

function news1_get_social( $key ) {
    return get_theme_mod( $key, '' );
}

/* ===== Footer Widgets ===== */
function news1_widgets_init() {
    $defaults = [
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="footer-widget-title">',
        'after_title'   => '</h3>',
    ];

    register_sidebar( array_merge( $defaults, [
        'name'        => __( 'Footer Column 1', 'news-1' ),
        'id'          => 'footer-1',
        'description' => __( 'First footer column.', 'news-1' ),
    ] ) );

    register_sidebar( array_merge( $defaults, [
        'name'        => __( 'Footer Column 2', 'news-1' ),
        'id'          => 'footer-2',
        'description' => __( 'Second footer column.', 'news-1' ),
    ] ) );
}
add_action( 'widgets_init', 'news1_widgets_init' );

/* ===== Comments: only Name + Content, no email/login required ===== */

// No login needed to comment (overrides Settings → Discussion)
add_filter( 'option_comment_registration',        '__return_zero' );

// Auto-approve first-time commenters (no manual moderation queue)
add_filter( 'option_comment_previously_approved', '__return_zero' );

// Disable comment flood protection (too quickly error)
add_filter( 'comment_flood_filter', '__return_false' );

// Name field not required either
add_filter( 'option_require_name_email', '__return_false' );

// Enable threaded (nested) comments at depth 5 (overrides Settings → Discussion)
add_filter( 'option_thread_comments',       '__return_true' );
add_filter( 'option_thread_comments_depth', function() { return 5; } );

// Remove email, url, cookies fields from comment form
add_filter( 'comment_form_default_fields', function( $fields ) {
    unset( $fields['email'] );
    unset( $fields['url'] );
    unset( $fields['cookies'] );
    return $fields;
} );

/* ===== Helper: Thumbnail or Placeholder ===== */
function news1_get_thumbnail( $post_id = null, $size = 'news-card', $attr = [] ) {
    $attr['loading'] = 'lazy';
    if ( has_post_thumbnail( $post_id ) ) {
        return get_the_post_thumbnail( $post_id, $size, $attr );
    }
    $w = 600; $h = 360;
    if ( $size === 'news-hero' )  { $w = 800; $h = 420; }
    if ( $size === 'news-thumb' ) { $w = 160; $h = 100; }
    $alt = esc_attr( isset( $attr['alt'] ) ? $attr['alt'] : get_the_title( $post_id ) );
    return '<img src="https://placehold.co/' . $w . 'x' . $h . '/d0d0d0/888888?text=No+Image" alt="' . $alt . '" loading="lazy">';
}

/* ===== Helper: Primary Category Label ===== */
function news1_get_primary_cat( $post_id = null ) {
    $cats = get_the_category( $post_id );
    if ( $cats ) {
        return '<a href="' . esc_url( get_category_link( $cats[0]->term_id ) ) . '" class="post-card-cat">' . esc_html( $cats[0]->name ) . '</a>';
    }
    return '';
}

/* ===== Excerpt ===== */
add_filter( 'excerpt_length', function() { return 20; }, 999 );
add_filter( 'excerpt_more',   function() { return '&hellip;'; } );

/* ===== Custom Nav Walker (2-level) ===== */
class News1_Walker_Nav_Menu extends Walker_Nav_Menu {
    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $classes   = empty( $item->classes ) ? [] : (array) $item->classes;
        $class_str = implode( ' ', array_filter( $classes ) );
        $url       = $item->url ?: '#';
        $title     = apply_filters( 'the_title', $item->title, $item->ID );
        $attr      = ' href="' . esc_url( $url ) . '"';
        if ( $item->target ) $attr .= ' target="' . esc_attr( $item->target ) . '"';
        if ( $item->xfn )    $attr .= ' rel="' . esc_attr( $item->xfn ) . '"';

        $has_sub = in_array( 'menu-item-has-children', $classes );
        $output .= '<li class="' . esc_attr( $class_str ) . '">';
        $output .= '<a' . $attr . '>' . esc_html( $title );
        if ( $has_sub && $depth === 0 ) $output .= ' <span class="sub-arrow">&#9660;</span>';
        $output .= '</a>';
    }
}

/* ===== Breadcrumb ===== */
function news1_breadcrumb() {
    echo '<nav class="breadcrumb" aria-label="Breadcrumb">';
    echo '<a href="' . esc_url( home_url( '/' ) ) . '">' . __( 'Home', 'news-1' ) . '</a>';

    if ( is_category() ) {
        echo ' &rsaquo; ' . single_cat_title( '', false );
    } elseif ( is_tag() ) {
        echo ' &rsaquo; ' . __( 'Tag: ', 'news-1' ) . single_tag_title( '', false );
    } elseif ( is_single() ) {
        $cats = get_the_category();
        if ( $cats ) {
            echo ' &rsaquo; <a href="' . esc_url( get_category_link( $cats[0]->term_id ) ) . '">' . esc_html( $cats[0]->name ) . '</a>';
        }
        echo ' &rsaquo; ' . get_the_title();
    } elseif ( is_page() ) {
        echo ' &rsaquo; ' . get_the_title();
    } elseif ( is_search() ) {
        echo ' &rsaquo; ' . sprintf( __( 'Search: %s', 'news-1' ), get_search_query() );
    } elseif ( is_404() ) {
        echo ' &rsaquo; 404 Not Found';
    } elseif ( is_archive() ) {
        echo ' &rsaquo; ' . get_the_archive_title();
    }

    echo '</nav>';
}

/* ===== Related Posts (by category, exclude current) ===== */
function news1_related_posts( $post_id, $count = 6 ) {
    $cats = wp_get_post_categories( $post_id );
    if ( ! $cats ) return;

    $query = new WP_Query( [
        'category__in'   => $cats,
        'post__not_in'   => [ $post_id ],
        'posts_per_page' => $count,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'ignore_sticky_posts' => true,
    ] );

    if ( ! $query->have_posts() ) return;
    ?>
    <div class="related-posts-widget">
        <h3 class="related-title"><?php _e( 'Related Articles', 'news-1' ); ?></h3>
        <?php while ( $query->have_posts() ) : $query->the_post(); ?>
        <div class="related-item">
            <a href="<?php the_permalink(); ?>" class="related-thumb-link">
                <?php echo news1_get_thumbnail( get_the_ID(), 'news-thumb', [ 'alt' => get_the_title() ] ); ?>
            </a>
            <div class="related-body">
                <span class="related-cat"><?php echo strip_tags( news1_get_primary_cat() ); ?></span>
                <h4 class="related-post-title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h4>
                <div class="related-date"><?php echo get_the_date(); ?></div>
            </div>
        </div>
        <?php endwhile; wp_reset_postdata(); ?>
    </div>
    <?php
}

/* ===== Numeric Pagination ===== */
function news1_pagination( $query = null ) {
    global $wp_query;
    $q     = $query ?: $wp_query;
    $total = $q->max_num_pages;
    if ( $total <= 1 ) return;

    $current = max( 1, get_query_var( 'paged' ) );
    $links   = paginate_links( [
        'base'      => str_replace( PHP_INT_MAX, '%#%', esc_url( get_pagenum_link( PHP_INT_MAX ) ) ),
        'format'    => '?paged=%#%',
        'current'   => $current,
        'total'     => $total,
        'type'      => 'array',
        'prev_text' => '&laquo;',
        'next_text' => '&raquo;',
    ] );

    if ( $links ) {
        echo '<div class="pagination">';
        foreach ( $links as $link ) echo $link;
        echo '</div>';
    }
}

/* ===== Helper: US Eastern Time display ===== */
function news1_us_date() {
    try {
        $tz = new DateTimeZone( 'America/New_York' );
        $dt = new DateTime( 'now', $tz );
        return $dt->format( 'l, F j, Y' );
    } catch ( Exception $e ) {
        return date_i18n( 'l, F j, Y' );
    }
}

/* ===== Helper: Trending posts query ===== */
function news1_trending_query( $count = 10 ) {
    // Bước 1: lấy IDs post có view, sort theo view giảm dần
    $viewed_q   = new WP_Query( [
        'posts_per_page'      => $count,
        'fields'              => 'ids',
        'meta_key'            => '_post_views_count',
        'orderby'             => 'meta_value_num',
        'order'               => 'DESC',
        'ignore_sticky_posts' => true,
        'meta_query'          => [ [ 'key' => '_post_views_count', 'compare' => 'EXISTS' ] ],
    ] );
    $viewed_ids = (array) $viewed_q->posts;
    $found      = count( $viewed_ids );

    // Bước 2: nếu chưa đủ, lấy thêm IDs post mới nhất chưa có view
    $all_ids = $viewed_ids;
    if ( $found < $count ) {
        $exclude = $found > 0 ? $viewed_ids : [ 0 ];
        $fill_q  = new WP_Query( [
            'posts_per_page'      => $count - $found,
            'fields'              => 'ids',
            'orderby'             => 'date',
            'order'               => 'DESC',
            'ignore_sticky_posts' => true,
            'post__not_in'        => $exclude,
        ] );
        $all_ids = array_merge( $viewed_ids, (array) $fill_q->posts );
    }

    if ( empty( $all_ids ) ) {
        return new WP_Query( [ 'posts_per_page' => $count, 'ignore_sticky_posts' => true ] );
    }

    // Bước 3: 1 query sạch với đủ IDs, giữ nguyên thứ tự
    return new WP_Query( [
        'post__in'            => $all_ids,
        'posts_per_page'      => $count,
        'orderby'             => 'post__in',
        'ignore_sticky_posts' => true,
    ] );
}

/* ===== Admin: hide update nag for non-admins ===== */
add_action( 'admin_head', function() {
    if ( ! current_user_can( 'update_core' ) ) remove_action( 'admin_notices', 'update_nag', 3 );
} );

/* ===== Contact Messages CPT ===== */
add_action( 'init', function() {
    register_post_type( 'contact_message', [
        'labels' => [
            'name'          => 'Contact Messages',
            'singular_name' => 'Contact Message',
            'menu_name'     => 'Contact Messages',
            'all_items'     => 'All Messages',
            'view_item'     => 'View Message',
        ],
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_icon'           => 'dashicons-email-alt',
        'capability_type'     => 'post',
        'capabilities'        => [ 'create_posts' => 'do_not_allow' ],
        'map_meta_cap'        => true,
        'supports'            => [ 'title' ],
        'has_archive'         => false,
    ] );
} );

// Extra columns in admin list view
add_filter( 'manage_contact_message_posts_columns', function( $cols ) {
    return [
        'cb'      => $cols['cb'],
        'title'   => 'Subject',
        'cm_name'    => 'Name',
        'cm_email'   => 'Email',
        'cm_message' => 'Message',
        'date'    => 'Date',
    ];
} );

add_action( 'manage_contact_message_posts_custom_column', function( $col, $post_id ) {
    switch ( $col ) {
        case 'cm_name':    echo esc_html( get_post_meta( $post_id, '_cm_name', true ) );    break;
        case 'cm_email':
            $email = get_post_meta( $post_id, '_cm_email', true );
            echo '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>';
            break;
        case 'cm_message': echo esc_html( wp_trim_words( get_post_meta( $post_id, '_cm_message', true ), 12 ) ); break;
    }
}, 10, 2 );

// Metabox showing full message in edit screen
add_action( 'add_meta_boxes', function() {
    add_meta_box( 'cm_detail', 'Message Detail', function( $post ) {
        $name    = get_post_meta( $post->ID, '_cm_name', true );
        $email   = get_post_meta( $post->ID, '_cm_email', true );
        $message = get_post_meta( $post->ID, '_cm_message', true );
        echo '<p><strong>Name:</strong> ' . esc_html( $name ) . '</p>';
        echo '<p><strong>Email:</strong> <a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a></p>';
        echo '<p><strong>Message:</strong></p>';
        echo '<div style="background:#f9f9f9;padding:12px;border:1px solid #ddd;border-radius:4px;white-space:pre-wrap;">' . esc_html( $message ) . '</div>';
    }, 'contact_message', 'normal', 'high' );
} );

function news1_save_contact_message( $name, $email, $subject, $message ) {
    return wp_insert_post( [
        'post_type'   => 'contact_message',
        'post_title'  => sanitize_text_field( $subject ),
        'post_status' => 'publish',
        'meta_input'  => [
            '_cm_name'    => sanitize_text_field( $name ),
            '_cm_email'   => sanitize_email( $email ),
            '_cm_message' => sanitize_textarea_field( $message ),
        ],
    ] );
}
