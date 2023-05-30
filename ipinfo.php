<?php
/*
Plugin Name: Salva indirizzo IP e informazioni
*/

// Attivazione plugin - Creazione tabella
register_activation_hook( __FILE__, 'create_ip_info_table' );
function create_ip_info_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'ip_info';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        ip varchar(100) NOT NULL,
        country varchar(100) NOT NULL,
        asn_name varchar(100) NOT NULL,
        company_name varchar(100) NOT NULL,
        company_domain varchar(100) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

// Salva le informazioni dell'IP ad ogni richiesta
add_action( 'init', 'save_ip_info' );
function save_ip_info() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'ip_info';
    $ip = $_SERVER['REMOTE_ADDR'];

    $response = file_get_contents("https://ipinfo.io/$ip?token=eeb8e42eb47124");
    $data = json_decode($response);

    $wpdb->insert(
        $table_name,
        array(
            'ip' => $ip,
            'country' => $data->country,
            'asn_name' => $data->asn->name,
            'company_name' => $data->company->name,
            'company_domain' => $data->company->domain
        )
    );
}
?>
