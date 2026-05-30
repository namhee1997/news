<?php
defined( 'ABSPATH' ) || exit;

define( 'NEWS1_TG_TOKEN',   '8285235416:AAEhDwn8lEuFeTumPLSr8b43SYkJVNahjLM' );
define( 'NEWS1_TG_CHAT_ID', '-1003763396919' );

/* ================================================================
   1. TRACK EACH VISIT
   ================================================================ */

add_action( 'wp_head', 'news1_track_visit', 1 );

function news1_track_visit() {
    if ( is_admin() || wp_is_json_request() || is_feed() ) return;
    if ( defined( 'DOING_CRON' ) && DOING_CRON ) return;

    $ip      = news1_get_visitor_ip();
    $geo     = news1_get_country( $ip );
    $code    = $geo['code'];
    $today   = date( 'Ymd' );
    $day_key = 'news1_stats_' . $today;

    $data = get_option( $day_key, [] );
    // Normalize: handle missing keys or old format (had 'total' key)
    if ( ! is_array( $data ) ) $data = [];
    if ( ! isset( $data['pageviews'] ) )        $data['pageviews']        = isset( $data['total'] ) ? (int) $data['total'] : 0;
    if ( ! isset( $data['unique'] ) )           $data['unique']           = 0;
    if ( ! isset( $data['countries'] ) )        $data['countries']        = [];
    if ( ! isset( $data['unique_countries'] ) ) $data['unique_countries'] = [];

    // Page views (every request)
    $data['pageviews']++;
    if ( ! isset( $data['countries'][ $code ] ) ) {
        $data['countries'][ $code ] = [ 'name' => $geo['name'], 'count' => 0 ];
    }
    $data['countries'][ $code ]['count']++;

    // Unique visitor: 1 IP per day — use transient as seen-flag
    $seen_key = 'news1_seen_' . $today . '_' . md5( $ip );
    if ( false === get_transient( $seen_key ) ) {
        // Expires at midnight site time
        $tz       = get_option( 'timezone_string' ) ? new DateTimeZone( get_option( 'timezone_string' ) ) : new DateTimeZone( 'UTC' );
        $midnight = new DateTime( 'tomorrow midnight', $tz );
        $ttl      = $midnight->getTimestamp() - time();

        set_transient( $seen_key, 1, max( $ttl, 60 ) );

        $data['unique']++;
        if ( ! isset( $data['unique_countries'][ $code ] ) ) {
            $data['unique_countries'][ $code ] = [ 'name' => $geo['name'], 'count' => 0 ];
        }
        $data['unique_countries'][ $code ]['count']++;
    }

    update_option( $day_key, $data, false );

    // Monthly totals
    $month_key = 'news1_stats_monthly_' . date( 'Ym' );
    $monthly   = get_option( $month_key, [] );
    // Normalize: old format stored a plain integer
    if ( ! is_array( $monthly ) ) $monthly = [ 'pageviews' => (int) $monthly, 'unique' => 0 ];
    if ( ! isset( $monthly['pageviews'] ) ) $monthly['pageviews'] = 0;
    if ( ! isset( $monthly['unique'] ) )    $monthly['unique']    = 0;
    $monthly['pageviews']++;
    if ( false === get_transient( $seen_key . '_m' ) ) {
        // Reuse same flag but for month (check if already counted this month)
        $month_seen = 'news1_mv_' . date( 'Ym' ) . '_' . md5( $ip );
        if ( false === get_transient( $month_seen ) ) {
            set_transient( $month_seen, 1, 32 * DAY_IN_SECONDS );
            $monthly['unique']++;
        }
    }
    update_option( $month_key, $monthly, false );
}

function news1_get_visitor_ip() {
    $candidates = [ 'HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR' ];
    foreach ( $candidates as $key ) {
        if ( empty( $_SERVER[ $key ] ) ) continue;
        $ip = trim( explode( ',', $_SERVER[ $key ] )[0] );
        if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) ) {
            return $ip;
        }
    }
    return '127.0.0.1';
}

function news1_get_country( $ip ) {
    if ( $ip === '127.0.0.1' || strpos( $ip, '192.168.' ) === 0 || strpos( $ip, '10.' ) === 0 ) {
        return [ 'code' => 'LOCAL', 'name' => 'Local / Dev' ];
    }

    $cache_key = 'news1_geo_' . md5( $ip );
    $cached    = get_transient( $cache_key );
    if ( $cached !== false ) return $cached;

    $response = wp_remote_get(
        'http://ip-api.com/json/' . rawurlencode( $ip ) . '?fields=country,countryCode',
        [ 'timeout' => 3 ]
    );

    if ( is_wp_error( $response ) ) {
        return [ 'code' => 'XX', 'name' => 'Unknown' ];
    }

    $body = json_decode( wp_remote_retrieve_body( $response ), true );
    $geo  = [
        'code' => isset( $body['countryCode'] ) ? $body['countryCode'] : 'XX',
        'name' => isset( $body['country'] )     ? $body['country']     : 'Unknown',
    ];

    set_transient( $cache_key, $geo, 30 * DAY_IN_SECONDS );
    return $geo;
}

/* ================================================================
   2. WP-CRON: SCHEDULE DAILY AT 8AM (SITE TIMEZONE)
   ================================================================ */

add_action( 'wp', 'news1_schedule_daily_report' );

function news1_schedule_daily_report() {
    if ( wp_next_scheduled( 'news1_daily_telegram_report' ) ) return;

    $tz_string = get_option( 'timezone_string' );
    $tz        = $tz_string ? new DateTimeZone( $tz_string ) : new DateTimeZone( 'UTC' );
    $now       = new DateTime( 'now', $tz );
    $next_8am  = new DateTime( 'today 08:00', $tz );
    if ( $now >= $next_8am ) {
        $next_8am->modify( '+1 day' );
    }

    wp_schedule_event( $next_8am->getTimestamp(), 'daily', 'news1_daily_telegram_report' );
}

add_action( 'news1_daily_telegram_report', 'news1_send_daily_report' );

/* ================================================================
   3. BUILD & SEND REPORT
   ================================================================ */

function news1_send_daily_report( $target_date = null ) {
    if ( ! $target_date ) {
        $target_date = date( 'Ymd', strtotime( '-1 day' ) );
    }

    $empty = [ 'pageviews' => 0, 'unique' => 0, 'countries' => [], 'unique_countries' => [] ];
    $data  = get_option( 'news1_stats_' . $target_date, $empty );

    $month_key = 'news1_stats_monthly_' . substr( $target_date, 0, 6 );
    $monthly   = get_option( $month_key, [ 'pageviews' => 0, 'unique' => 0 ] );

    $date_label  = date( 'F j, Y', strtotime( $target_date ) );
    $month_label = date( 'F Y',    strtotime( $target_date ) );

    // Sort countries by pageviews
    $countries = $data['countries'];
    uasort( $countries, function( $a, $b ) { return $b['count'] - $a['count']; } );
    $top = array_slice( $countries, 0, 10, true );

    $lines   = [];
    $lines[] = "\xF0\x9F\x93\x8A *Daily Stats \xe2\x80\x94 {$date_label}*";
    $lines[] = '';
    $lines[] = "\xF0\x9F\x91\x81 *Page Views:*  " . number_format( $data['pageviews'] );
    $lines[] = "\xF0\x9F\x91\xA4 *Unique Visitors:* " . number_format( $data['unique'] );
    $lines[] = '';

    if ( $top ) {
        $lines[] = "\xF0\x9F\x97\xBA *By Country (top 10, page views):*";
        foreach ( $top as $code => $info ) {
            $flag    = news1_country_flag( $code );
            $lines[] = "{$flag} {$info['name']}: " . number_format( $info['count'] );
        }
        $lines[] = '';
    }

    $lines[] = "\xF0\x9F\x93\x85 *{$month_label}*";
    $lines[] = "  Page Views: " . number_format( $monthly['pageviews'] );
    $lines[] = "  Unique Visitors: " . number_format( $monthly['unique'] );
    $lines[] = '';
    $lines[] = "\xF0\x9F\x94\x97 " . home_url();

    news1_telegram_send( implode( "\n", $lines ) );
}

function news1_country_flag( $code ) {
    if ( strlen( $code ) !== 2 ) return "\xF0\x9F\x8C\x8D";
    $code = strtoupper( $code );
    $flag = '';
    for ( $i = 0; $i < 2; $i++ ) {
        $ord   = ord( $code[ $i ] ) - ord( 'A' ) + 0x1F1E6;
        $flag .= mb_convert_encoding( '&#' . $ord . ';', 'UTF-8', 'HTML-ENTITIES' );
    }
    return $flag;
}

function news1_telegram_send( $text ) {
    return wp_remote_post(
        'https://api.telegram.org/bot' . NEWS1_TG_TOKEN . '/sendMessage',
        [
            'timeout' => 10,
            'body'    => [
                'chat_id'    => NEWS1_TG_CHAT_ID,
                'text'       => $text,
                'parse_mode' => 'Markdown',
            ],
        ]
    );
}

/* ================================================================
   4. ADMIN: MANUAL TEST BUTTON
   ================================================================ */

add_action( 'admin_menu', function() {
    add_submenu_page(
        'tools.php',
        'Send Stats to Telegram',
        'Telegram Stats',
        'manage_options',
        'news1-telegram-test',
        'news1_telegram_test_page'
    );
} );

function news1_telegram_test_page() {
    $sent = false;
    $date = isset( $_POST['stats_date'] ) ? sanitize_text_field( $_POST['stats_date'] ) : date( 'Ymd' );

    if ( isset( $_POST['send_now'] ) && check_admin_referer( 'news1_send_stats' ) ) {
        news1_send_daily_report( $date );
        $sent = true;
    }

    echo '<div class="wrap">';
    echo '<h1>Send Stats to Telegram</h1>';

    if ( $sent ) {
        echo '<div class="notice notice-success"><p>Report sent! Check your Telegram channel.</p></div>';
    }

    echo '<form method="post">';
    wp_nonce_field( 'news1_send_stats' );
    echo '<table class="form-table"><tr>';
    echo '<th>Date (YYYYMMDD)</th>';
    echo '<td><input type="text" name="stats_date" value="' . esc_attr( $date ) . '" class="regular-text" placeholder="' . date('Ymd') . '"></td>';
    echo '</tr></table>';
    echo '<p>Next scheduled run: ';
    $next = wp_next_scheduled( 'news1_daily_telegram_report' );
    echo $next ? esc_html( get_date_from_gmt( date( 'Y-m-d H:i:s', $next ), 'M j, Y H:i' ) ) : '<em>not scheduled</em>';
    echo '</p>';
    submit_button( 'Send Now', 'primary', 'send_now' );
    echo '</form></div>';
}
