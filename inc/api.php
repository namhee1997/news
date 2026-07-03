<?php
/**
 * Custom REST API for the news-crawler automation tool.
 *
 * Endpoints:
 *   GET   /wp-json/news1/v1/categories   — public
 *   POST  /wp-json/news1/v1/media        — requires X-API-Key header
 *   POST  /wp-json/news1/v1/posts        — requires X-API-Key header
 *   GET   /wp-json/news1/v1/posts        — requires X-API-Key header  ?per_page=100&page=1&fields=id,title
 *   PATCH /wp-json/news1/v1/posts/{id}   — requires X-API-Key header
 *   GET   /wp-json/news1/v1/posts/pending-image-cleanup   — requires X-API-Key header  ?days=30&per_page=50&page=1
 *   DELETE /wp-json/news1/v1/posts/{id}/images   — requires X-API-Key header
 *
 * Add to wp-config.php:
 *   define( 'NEWS1_API_KEY', 'your-secret-key-here' );
 */
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! defined( 'NEWS1_API_KEY' ) ) {
    define( 'NEWS1_API_KEY', 'CHANGE_THIS_KEY_IN_WP_CONFIG' );
}

add_action( 'rest_api_init', function () {

    register_rest_route( 'news1/v1', '/categories', [
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'news1_api_categories',
        'permission_callback' => '__return_true',
    ] );

    register_rest_route( 'news1/v1', '/media', [
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'news1_api_upload_media',
        'permission_callback' => 'news1_api_auth',
    ] );

    register_rest_route( 'news1/v1', '/posts', [
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'news1_api_create_post',
        'permission_callback' => 'news1_api_auth',
    ] );

    register_rest_route( 'news1/v1', '/posts', [
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'news1_api_list_posts',
        'permission_callback' => 'news1_api_auth',
    ] );

    register_rest_route( 'news1/v1', '/posts/(?P<id>\d+)', [
        'methods'             => 'PATCH',
        'callback'            => 'news1_api_update_post',
        'permission_callback' => 'news1_api_auth',
        'args'                => [
            'id' => [ 'validate_callback' => fn( $v ) => is_numeric( $v ) ],
        ],
    ] );

    register_rest_route( 'news1/v1', '/posts/pending-image-cleanup', [
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'news1_api_pending_image_cleanup',
        'permission_callback' => 'news1_api_auth',
    ] );

    register_rest_route( 'news1/v1', '/posts/(?P<id>\d+)/images', [
        'methods'             => 'DELETE',
        'callback'            => 'news1_api_purge_post_images',
        'permission_callback' => 'news1_api_auth',
        'args'                => [
            'id' => [ 'validate_callback' => fn( $v ) => is_numeric( $v ) ],
        ],
    ] );

} );

/* ── Auth ───────────────────────────────────────────────────────────────── */

function news1_api_auth( WP_REST_Request $request ) {
    $key = $request->get_header( 'X-API-Key' );
    if ( ! $key || $key !== NEWS1_API_KEY ) {
        return new WP_Error( 'unauthorized', 'Invalid or missing API key.', [ 'status' => 401 ] );
    }
    return true;
}

/* ── GET /categories ────────────────────────────────────────────────────── */

function news1_api_categories() {
    $terms = get_terms( [
        'taxonomy'   => 'category',
        'hide_empty' => false,
        'orderby'    => 'name',
        'order'      => 'ASC',
    ] );

    if ( is_wp_error( $terms ) ) {
        return new WP_Error( 'fetch_failed', $terms->get_error_message(), [ 'status' => 500 ] );
    }

    return rest_ensure_response( array_map( function ( $t ) {
        return [
            'id'          => $t->term_id,
            'name'        => $t->name,
            'slug'        => $t->slug,
            'description' => $t->description,
            'count'       => $t->count,
        ];
    }, $terms ) );
}

/* ── POST /media ────────────────────────────────────────────────────────── */

function news1_api_upload_media( WP_REST_Request $request ) {
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $files = $request->get_file_params();
    if ( empty( $files['file'] ) ) {
        return new WP_Error( 'no_file', 'No file in request (field name: "file").', [ 'status' => 400 ] );
    }

    $file = $files['file'];
    if ( ( $file['error'] ?? UPLOAD_ERR_NO_FILE ) !== UPLOAD_ERR_OK ) {
        return new WP_Error( 'upload_error', 'File upload error code: ' . $file['error'], [ 'status' => 400 ] );
    }

    $name = sanitize_file_name( $file['name'] ?? 'upload.jpg' );
    if ( ! pathinfo( $name, PATHINFO_EXTENSION ) ) {
        $name .= '.jpg';
    }

    $upload_file = [
        'name'     => $name,
        'type'     => $file['type'] ?? 'image/jpeg',
        'tmp_name' => $file['tmp_name'],
        'error'    => $file['error'],
        'size'     => $file['size'],
    ];
    $moved = wp_handle_upload( $upload_file, [ 'test_form' => false ] );

    if ( isset( $moved['error'] ) ) {
        return new WP_Error( 'move_failed', $moved['error'], [ 'status' => 500 ] );
    }

    $attachment_id = wp_insert_attachment(
        [
            'post_title'     => pathinfo( $name, PATHINFO_FILENAME ),
            'post_mime_type' => $moved['type'],
            'post_status'    => 'inherit',
        ],
        $moved['file']
    );

    if ( is_wp_error( $attachment_id ) ) {
        return new WP_Error( 'attach_failed', $attachment_id->get_error_message(), [ 'status' => 500 ] );
    }

    wp_update_attachment_metadata(
        $attachment_id,
        wp_generate_attachment_metadata( $attachment_id, $moved['file'] )
    );

    return rest_ensure_response( [
        'id'  => $attachment_id,
        'url' => wp_get_attachment_url( $attachment_id ),
    ] );
}

/* ── POST /posts ────────────────────────────────────────────────────────── */

function news1_api_create_post( WP_REST_Request $request ) {
    $params = $request->get_json_params();

    $title       = sanitize_text_field( $params['title']            ?? '' );
    $content     = wp_kses_post( $params['content']                 ?? '' );
    $category_id = absint( $params['category_id']                   ?? 0 );
    $tags        = array_filter( array_map( 'sanitize_text_field', (array) ( $params['tags'] ?? [] ) ) );
    $featured_id = absint( $params['featured_image_id']             ?? 0 );

    if ( ! $title || ! $content ) {
        return new WP_Error( 'missing_fields', '"title" and "content" are required.', [ 'status' => 400 ] );
    }

    // Create or reuse tags
    $tag_ids = [];
    foreach ( $tags as $tag_name ) {
        $term = wp_insert_term( $tag_name, 'post_tag' );
        if ( is_wp_error( $term ) ) {
            $existing = get_term_by( 'name', $tag_name, 'post_tag' );
            if ( $existing ) {
                $tag_ids[] = $existing->term_id;
            }
        } else {
            $tag_ids[] = $term['term_id'];
        }
    }

    $post_id = wp_insert_post(
        [
            'post_title'    => $title,
            'post_content'  => $content,
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_category' => $category_id ? [ $category_id ] : [],
            'tags_input'    => $tag_ids,
        ],
        true
    );

    if ( is_wp_error( $post_id ) ) {
        return new WP_Error( 'create_failed', $post_id->get_error_message(), [ 'status' => 500 ] );
    }

    if ( $featured_id ) {
        set_post_thumbnail( $post_id, $featured_id );
    }

    return rest_ensure_response( [
        'id'       => $post_id,
        'url'      => get_permalink( $post_id ),
        'edit_url' => admin_url( 'post.php?post=' . $post_id . '&action=edit' ),
    ] );
}

/* ── GET /posts ─────────────────────────────────────────────────────────── */

function news1_api_list_posts( WP_REST_Request $request ) {
    $per_page = min( absint( $request->get_param( 'per_page' ) ?: 100 ), 200 );
    $page     = max( 1, absint( $request->get_param( 'page' ) ?: 1 ) );
    $fields   = array_filter( explode( ',', $request->get_param( 'fields' ) ?: 'id,title' ) );

    $query = new WP_Query( [
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'paged'          => $page,
        'orderby'        => 'ID',
        'order'          => 'ASC',
        'no_found_rows'  => false,
    ] );

    $results = [];
    foreach ( $query->posts as $post ) {
        $row = [];
        if ( in_array( 'id',    $fields, true ) ) $row['id']    = $post->ID;
        if ( in_array( 'title', $fields, true ) ) $row['title'] = $post->post_title;
        if ( in_array( 'slug',  $fields, true ) ) $row['slug']  = $post->post_name;
        $results[] = $row;
    }

    return rest_ensure_response( $results );
}

/* ── PATCH /posts/{id} ──────────────────────────────────────────────────── */

function news1_api_update_post( WP_REST_Request $request ) {
    $post_id = absint( $request->get_param( 'id' ) );
    $post    = get_post( $post_id );

    if ( ! $post || $post->post_type !== 'post' ) {
        return new WP_Error( 'not_found', 'Post not found.', [ 'status' => 404 ] );
    }

    $params = $request->get_json_params();
    $update = [ 'ID' => $post_id ];

    if ( isset( $params['title'] ) ) {
        $update['post_title'] = sanitize_text_field( $params['title'] );
    }
    if ( isset( $params['content'] ) ) {
        $update['post_content'] = wp_kses_post( $params['content'] );
    }

    if ( count( $update ) === 1 ) {
        return new WP_Error( 'no_fields', 'No updatable fields provided.', [ 'status' => 400 ] );
    }

    $result = wp_update_post( $update, true );
    if ( is_wp_error( $result ) ) {
        return new WP_Error( 'update_failed', $result->get_error_message(), [ 'status' => 500 ] );
    }

    return rest_ensure_response( [
        'id'    => $post_id,
        'title' => get_the_title( $post_id ),
    ] );
}

/* ── GET /posts/pending-image-cleanup ───────────────────────────────────── */
/**
 * Lists published posts older than `days` (post_date) that have not yet had
 * their images purged (no `_news1_images_purged` meta). Used by the
 * delete-post automation to find candidates without re-scanning cleaned posts.
 */
function news1_api_pending_image_cleanup( WP_REST_Request $request ) {
    $days     = max( 1, absint( $request->get_param( 'days' ) ?: 30 ) );
    $per_page = min( absint( $request->get_param( 'per_page' ) ?: 50 ), 200 );
    $page     = max( 1, absint( $request->get_param( 'page' ) ?: 1 ) );

    $cutoff = gmdate( 'Y-m-d H:i:s', strtotime( "-{$days} days", time() ) );

    $query = new WP_Query( [
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'paged'          => $page,
        'orderby'        => 'date',
        'order'          => 'ASC',
        'date_query'     => [
            [ 'column' => 'post_date_gmt', 'before' => $cutoff, 'inclusive' => true ],
        ],
        'meta_query'     => [
            [ 'key' => '_news1_images_purged', 'compare' => 'NOT EXISTS' ],
        ],
        'no_found_rows'  => true,
        'fields'         => 'ids',
    ] );

    return rest_ensure_response( array_map( function ( $id ) {
        return [ 'id' => $id, 'date' => get_post_field( 'post_date', $id ) ];
    }, $query->posts ) );
}

/* ── DELETE /posts/{id}/images ──────────────────────────────────────────── */
/**
 * Force-deletes the post's featured image attachment and any content images
 * that live in this site's media library, stripping their markup from
 * post_content so no broken <img> tags remain. Files are removed from disk
 * (force delete, no trash) to actually free host storage.
 */
function news1_api_purge_post_images( WP_REST_Request $request ) {
    $post_id = absint( $request->get_param( 'id' ) );
    $post    = get_post( $post_id );

    if ( ! $post || $post->post_type !== 'post' ) {
        return new WP_Error( 'not_found', 'Post not found.', [ 'status' => 404 ] );
    }

    $deleted_ids = [];

    // Featured image
    $thumb_id = get_post_thumbnail_id( $post_id );
    if ( $thumb_id ) {
        delete_post_thumbnail( $post_id );
        if ( wp_delete_attachment( $thumb_id, true ) ) {
            $deleted_ids[] = $thumb_id;
        }
    }

    // Content images — only ones that are actual attachments in this media library
    $content = $post->post_content;
    if ( $content && preg_match_all( '/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $content, $matches ) ) {
        foreach ( array_unique( $matches[1] ) as $src ) {
            $att_id = attachment_url_to_postid( $src );
            if ( ! $att_id ) {
                continue;
            }

            $escaped_src    = preg_quote( $src, '/' );
            $figure_pattern = '/<figure[^>]*>\s*<img[^>]+src=["\']' . $escaped_src . '["\'][^>]*>.*?<\/figure>/is';
            if ( preg_match( $figure_pattern, $content ) ) {
                $content = preg_replace( $figure_pattern, '', $content );
            } else {
                $img_pattern = '/<img[^>]+src=["\']' . $escaped_src . '["\'][^>]*>/i';
                $content     = preg_replace( $img_pattern, '', $content );
            }

            if ( wp_delete_attachment( $att_id, true ) ) {
                $deleted_ids[] = $att_id;
            }
        }

        if ( $content !== $post->post_content ) {
            wp_update_post( [ 'ID' => $post_id, 'post_content' => $content ] );
        }
    }

    update_post_meta( $post_id, '_news1_images_purged', current_time( 'mysql' ) );

    return rest_ensure_response( [
        'id'                     => $post_id,
        'deleted_attachment_ids' => $deleted_ids,
        'purged'                 => true,
    ] );
}
