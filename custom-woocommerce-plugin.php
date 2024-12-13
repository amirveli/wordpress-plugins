<?php
/*
Plugin Name: Custom WooCommerce Plugin
Description: Plugin personalizat care adaugă funcționalități suplimentare pentru WooCommerce.
Version: 1.0
Author: Veli Amir
*/

// Prevenim accesul direct la fișier
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Funcție pentru afișarea unui mesaj personalizat pe pagina produsului
function cwp_display_custom_message() {
    if ( is_product() ) {
        echo '<p style="background: #f9f9f9; padding: 10px; border: 1px solid #ddd;">Acesta este un mesaj personalizat pentru acest produs!</p>';
    }
}
add_action( 'woocommerce_single_product_summary', 'cwp_display_custom_message', 20 );
// Adaugă un câmp personalizat în pagina de editare a produsului
function cwp_add_custom_field() {
    global $post;

    echo '<div class="options_group">';
    woocommerce_wp_text_input( array(
        'id'          => '_custom_product_message',
        'label'       => __( 'Mesaj Personalizat', 'cwp' ),
        'placeholder' => 'Introduceți un mesaj personalizat',
        'desc_tip'    => 'true',
        'description' => __( 'Acest mesaj va fi afișat pe pagina produsului.', 'cwp' ),
    ));
    echo '</div>';
}
add_action( 'woocommerce_product_options_general_product_data', 'cwp_add_custom_field' );
// Salvarea valorii câmpului personalizat
function cwp_save_custom_field( $post_id ) {
    $custom_message = isset( $_POST['_custom_product_message'] ) ? sanitize_text_field( $_POST['_custom_product_message'] ) : '';
    update_post_meta( $post_id, '_custom_product_message', $custom_message );
}
add_action( 'woocommerce_process_product_meta', 'cwp_save_custom_field' );
// Afișarea valorii câmpului personalizat pe pagina produsului
function cwp_display_custom_field() {
    global $post;

    $custom_message = get_post_meta( $post->ID, '_custom_product_message', true );
    if ( !empty( $custom_message ) ) {
        echo '<p style="background: #e7f3fe; padding: 10px; border: 1px solid #007cba; margin-top: 15px;">' . esc_html( $custom_message ) . '</p>';
    }
}
add_action( 'woocommerce_before_add_to_cart_form', 'cwp_display_custom_field' );
// Adăugăm o pagină de setări pentru plugin
function cwp_register_settings_page() {
    add_options_page(
        'Custom WooCommerce Settings', 
        'WooCommerce Settings', 
        'manage_options', 
        'cwp-woocommerce-settings', 
        'cwp_settings_page_html'
    );
}
add_action( 'admin_menu', 'cwp_register_settings_page' );

// HTML pentru pagina de setări
function cwp_settings_page_html() {
    if ( !current_user_can( 'manage_options' ) ) {
        return;
    }

    if ( isset( $_POST['cwp_global_message'] ) ) {
        update_option( 'cwp_global_message', sanitize_text_field( $_POST['cwp_global_message'] ) );
        echo '<div class="updated"><p>Setările au fost salvate.</p></div>';
    }

    $global_message = get_option( 'cwp_global_message', '' );

    ?>
    <div class="wrap">
        <h1>Setări WooCommerce Personalizate</h1>
        <form method="post">
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="cwp_global_message">Mesaj Global pentru Produse</label>
                    </th>
                    <td>
                        <input type="text" id="cwp_global_message" name="cwp_global_message" value="<?php echo esc_attr( $global_message ); ?>" class="regular-text">
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
// Afișează mesajul global pe pagina produsului
function cwp_display_global_message() {
    $global_message = get_option( 'cwp_global_message', '' );

    if ( !empty( $global_message ) ) {
        echo '<p style="background: #fff8e1; padding: 10px; border: 1px solid #ffe58a;">' . esc_html( $global_message ) . '</p>';
    }
}
add_action( 'woocommerce_before_single_product', 'cwp_display_global_message' );
// Shortcode pentru afișarea mesajelor personalizate
function cwp_product_message_shortcode( $atts ) {
    global $post;

    $custom_message = get_post_meta( $post->ID, '_custom_product_message', true );

    if ( !empty( $custom_message ) ) {
        return '<p style="background: #e0f7fa; padding: 10px; border: 1px solid #4dd0e1;">' . esc_html( $custom_message ) . '</p>';
    }

    return '';
}
add_shortcode( 'product_message', 'cwp_product_message_shortcode' );
woocommerce_wp_text_input( array(
    'id'          => '_custom_discount_code',
    'label'       => __( 'Cod Reducere', 'cwp' ),
    'placeholder' => 'Introduceți un cod de reducere',
    'desc_tip'    => 'true',
    'description' => __( 'Acest cod va fi afișat pe pagina produsului.', 'cwp' ),
));
$custom_discount = isset( $_POST['_custom_discount_code'] ) ? sanitize_text_field( $_POST['_custom_discount_code'] ) : '';
update_post_meta( $post_id, '_custom_discount_code', $custom_discount );
$custom_discount = get_post_meta( $post->ID, '_custom_discount_code', true );

if ( !empty( $custom_discount ) ) {
    echo '<p style="background: #ffebee; padding: 10px; border: 1px solid #f44336;">Cod de Reducere: <strong>' . esc_html( $custom_discount ) . '</strong></p>';
}

