<?php
/*
Plugin Name: Salva indirizzo IP e informazioni
Plugin URI: https://wpaper.it
Description: Salva l'indirizzo IP e le informazioni dell'utente e le invia ad un server .net
Version: 0.3
*/

add_action( 'init', 'send_ip_and_url' );

function send_ip_and_url() {
    if (!session_id()) {
        session_start();
    }

    if (!isset($_SESSION['ip_and_url_sent'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $url = home_url( add_query_arg( null, null ) );

        $response = wp_remote_post( 'http://195.32.71.172:3000/ipinfo', array(
            'method' => 'POST',
            'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
            'body' => json_encode(array('ip' => $ip, 'url' => $url)),
            'sslverify' => false,
            'data_format' => 'body'
        ));

        if ( is_wp_error( $response ) ) {
            error_log( $response->get_error_message() );
        }

        $_SESSION['ip_and_url_sent'] = true;
    }
}
?>
