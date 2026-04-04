<?php
/**
 * Plugin Name:       OperWEB Maintenance Mode
 * Description:       Attiva una pagina di manutenzione personalizzata con opzioni.
 * Version:           2.3.3
 * Author:            Fabrizio Ortis | OperWEB
 * Author URI:        https://www.operweb.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       operweb-maintenance-mode
 * Domain Path:       /languages
 */

// Impedisci l'accesso diretto
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// La funzione 'owm_load_textdomain' è stata rimossa (correzione 2.3.2)

// Plugin Update Checker
require_once plugin_dir_path( __FILE__ ) . 'plugin-update-checker/plugin-update-checker.php';

$owm_update_checker = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
    'https://github.com/fabrizioortis/operweb-maintenance-mode/', // L'URL della tua repository GitHub
    __FILE__, // Il file principale del plugin
    'operweb-maintenance-mode' // Lo slug del tuo plugin
);

$owm_update_checker->setBranch('main');

/**
 * Funzione principale che mostra la pagina di manutenzione
 */
function owm_maintenance_mode_check() {
    $options = get_option( 'owm_options' );
    if ( empty( $options['enable'] ) || $options['enable'] != 1 ) {
        return;
    }
    if ( current_user_can( 'manage_options' ) ) {
        return;
    }
    $maintenance_file = plugin_dir_path( __FILE__ ) . 'maintenance.php';
    if ( file_exists( $maintenance_file ) ) {
        if ( ! headers_sent() ) {
            header( 'HTTP/1.1 503 Service Unavailable', true, 503 );
            header( 'Content-Type: text/html; charset=utf-8' );
            header( 'Retry-After: 3600' );
        }
        include( $maintenance_file );
        die();
    }
}
add_action( 'template_redirect', 'owm_maintenance_mode_check' );


/**
 * Carica gli stili per la pagina di manutenzione (correzione 2.3.2)
 */
function owm_enqueue_maintenance_styles() {
    $options = get_option( 'owm_options' );
    if ( ! empty( $options['enable'] ) && $options['enable'] == 1 && ! current_user_can( 'manage_options' ) ) {
        wp_enqueue_style( 'dashicons' );
    }
}
add_action( 'wp_enqueue_scripts', 'owm_enqueue_maintenance_styles', 9 );


/*
|--------------------------------------------------------------------------
| PAGINA IMPOSTAZIONI NEL PANNELLO ADMIN
|--------------------------------------------------------------------------
*/

function owm_add_admin_menu() {
    add_options_page(
        __( 'Manutenzione', 'operweb-maintenance-mode' ), // Titolo Pagina
        __( 'Manutenzione', 'operweb-maintenance-mode' ), // Titolo Menu
        'manage_options',
        'owm_maintenance_page',
        'owm_options_page_html'
    );
}
add_action( 'admin_menu', 'owm_add_admin_menu' );

/**
 * Registra le impostazioni del plugin
 */
function owm_settings_init() {
    register_setting(
        'owm_options_group', 'owm_options', 'owm_sanitize_options'
    );

    add_settings_section(
        'owm_main_section', 
        __( 'Impostazioni Pagina Manutenzione', 'operweb-maintenance-mode' ), 
        null, 
        'owm_options_group'
    );

    add_settings_field(
        'owm_field_enable', 
        __( 'Attiva Manutenzione', 'operweb-maintenance-mode' ), 
        'owm_field_enable_cb',
        'owm_options_group', 'owm_main_section'
    );
    
    add_settings_field(
        'owm_field_logo', 
        __( 'Logo Personalizzato (Opzionale)', 'operweb-maintenance-mode' ), 
        'owm_field_logo_cb',
        'owm_options_group', 'owm_main_section'
    );
    
    add_settings_field(
        'owm_field_text', 
        __( 'Testo Pagina', 'operweb-maintenance-mode' ), 
        'owm_field_text_cb',
        'owm_options_group', 'owm_main_section'
    );
    
    add_settings_field(
        'owm_field_color_bg', 
        __( 'Colore Sfondo Pagina', 'operweb-maintenance-mode' ), 
        'owm_field_color_bg_cb',
        'owm_options_group', 'owm_main_section'
    );
    
    add_settings_field(
        'owm_field_color_border', 
        __( 'Colore Bordo Superiore', 'operweb-maintenance-mode' ), 
        'owm_field_color_border_cb',
        'owm_options_group', 'owm_main_section'
    );
}
add_action( 'admin_init', 'owm_settings_init' );

/**
 * Funzione di Sanitizzazione
 */
function owm_sanitize_options( $input ) {
    $output = array();
    $output['enable'] = isset( $input['enable'] ) ? 1 : 0;
    $output['logo_id'] = isset( $input['logo_id'] ) ? absint( $input['logo_id'] ) : 0;
    $output['text'] = isset( $input['text'] ) ? wp_kses_post( $input['text'] ) : '';
    $output['color_bg'] = isset( $input['color_bg'] ) ? sanitize_hex_color( $input['color_bg'] ) : '';
    $output['color_border'] = isset( $input['color_border'] ) ? sanitize_hex_color( $input['color_border'] ) : '';
    return $output;
} 

/*
|--------------------------------------------------------------------------
| FUNZIONI DI CALLBACK (Render HTML dei campi)
|--------------------------------------------------------------------------
*/

function owm_field_enable_cb() {
    $options = get_option( 'owm_options' );
    $checked = ( !empty($options['enable']) && $options['enable'] == 1 ) ? 'checked' : '';
    
    echo '<label><input type="checkbox" name="owm_options[enable]" value="1" ' . esc_attr( $checked ) . '> ' . esc_html__( 'Attiva la modalità manutenzione', 'operweb-maintenance-mode' ) . '</label>';
    echo '<p class="description">' . esc_html__( 'Se spuntato, solo gli amministratori vedranno il sito. Tutti gli altri visitatori vedranno la pagina di manutenzione.', 'operweb-maintenance-mode' ) . '</p>';
}

function owm_field_logo_cb() {
    $options = get_option( 'owm_options' );
    $logo_id = !empty($options['logo_id']) ? $options['logo_id'] : 0;
    $logo_url = $logo_id ? wp_get_attachment_image_url( $logo_id, 'medium' ) : '';
    
    echo '<div class="owm-logo-uploader">';
    echo '<input type="hidden" name="owm_options[logo_id]" id="owm_logo_id" value="' . esc_attr( $logo_id ) . '">';
    echo '<button type="button" class="button button-secondary" id="owm_upload_logo_button">' . esc_html__( 'Carica/Scegli Logo', 'operweb-maintenance-mode' ) . '</button> ';
    echo '<button type="button" class="button button-secondary" id="owm_remove_logo_button" style="' . ( !$logo_id ? 'display:none;' : '' ) . '">' . esc_html__( 'Rimuovi Logo', 'operweb-maintenance-mode' ) . '</button>';
    echo '<div id="owm_logo_preview" style="margin-top:15px;">';
    if ( $logo_url ) {
        echo '<img src="' . esc_url( $logo_url ) . '" style="max-width:300px; height:auto; border:1px solid #ddd;">';
    }
    echo '</div>';
    echo '<p class="description">' . esc_html__( "Carica un logo per sostituire l'icona di default (🛠️). Se rimosso, verrà usata l'icona di default.", 'operweb-maintenance-mode' ) . '</p>';
    echo '</div>';
}

function owm_field_text_cb() {
    $options = get_option( 'owm_options' );
    $content = !empty($options['text']) ? $options['text'] : '';
    if( empty($content) ) {
        $content = '<h1>' . esc_html__( 'Sito in Manutenzione', 'operweb-maintenance-mode' ) . '</h1>';
        $content .= '<p>' . esc_html__( 'Stiamo eseguendo alcuni aggiornamenti per migliorare la tua esperienza. Torneremo online il prima possibile. Ci scusiamo per il disagio.', 'operweb-maintenance-mode' ) . '</p>';
    }
    
    wp_editor( $content, 'owm_text_editor', array(
        'textarea_name' => 'owm_options[text]', 
        'media_buttons' => false, 
        'textarea_rows' => 10,
    ));
}

function owm_field_color_bg_cb() {
    $options = get_option( 'owm_options' );
    $color = !empty($options['color_bg']) ? $options['color_bg'] : '#f4f7f6';
    echo '<input type="text" name="owm_options[color_bg]" value="' . esc_attr( $color ) . '" class="owm-color-picker">';
}

function owm_field_color_border_cb() {
    $options = get_option( 'owm_options' );
    $color = !empty($options['color_border']) ? $options['color_border'] : '#00b3ea';
    echo '<input type="text" name="owm_options[color_border]" value="' . esc_attr( $color ) . '" class="owm-color-picker">';
}

/**
 * Renderizza l'HTML della pagina di impostazioni
 */
function owm_options_page_html() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    
    $admin_logo_url = plugin_dir_url( __FILE__ ) . 'assets/operweb-admin-logo.png';
    ?>
    <div class="wrap">
        
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        
        <form action="options.php" method="post">
            <?php
            settings_fields( 'owm_options_group' );
            do_settings_sections( 'owm_options_group' );
            submit_button( __( 'Salva Impostazioni', 'operweb-maintenance-mode' ) );
            ?>
        </form>
        
        <div class="owm-admin-footer">
            
            <?php if ( file_exists( plugin_dir_path( __FILE__ ) . 'assets/operweb-admin-logo.png' ) ) : ?>
                <img src="<?php echo esc_url( $admin_logo_url ); ?>" alt="<?php esc_attr_e( 'Logo OperWEB', 'operweb-maintenance-mode' ); ?>">
            <?php else : ?>
                <strong>OperWEB</strong>
            <?php endif; ?>
            
            <p>
                <?php
                /* translators: %1$s: Link to OperWEB website, %2$s: 'esperti WordPress' (WordPress experts) in bold */
                $footer_text_1 = __( 'Questo plugin è offerto gratuitamente da %1$s, la tua agenzia di %2$s.', 'operweb-maintenance-mode' );
                
                $allowed_html = array(
                    'strong' => array(),
                    'a'      => array(
                        'href'   => array(),
                        'target' => array(),
                    ),
                );

                $link_operweb = '<a href="https://www.operweb.com?utm_source=plugin-maint-ow&utm_medium=admin-footer" target="_blank">OperWEB</a>';
                $strong_experts = '<strong>' . esc_html__( 'esperti WordPress', 'operweb-maintenance-mode' ) . '</strong>';

                echo wp_kses( sprintf( $footer_text_1, $link_operweb, $strong_experts ), $allowed_html );
                ?>
            </p>
            
            <p>
                <?php
                /* translators: %s: 'Contattaci' (Contact us) link */
                $footer_text_2 = __( 'Hai bisogno di un sito web, un e-commerce o una consulenza? %s!', 'operweb-maintenance-mode' );
                
                $allowed_html_link_only = array(
                    'a' => array(
                        'href'   => array(),
                        'target' => array(),
                    ),
                );
                
                $link_contact = '<a href="https://www.operweb.com/contatti/?utm_source=plugin-maint-ow&utm_medium=admin-footer" target="_blank">' . esc_html__( 'Contattaci', 'operweb-maintenance-mode' ) . '</a>';

                echo wp_kses( sprintf( $footer_text_2, $link_contact ), $allowed_html_link_only );
                ?>
            </p>
        </div>
        
    </div>
    <?php
}

/**
 * Carica gli script JS e CSS per Admin
 */
function owm_enqueue_admin_scripts( $hook_suffix ) {
    if ( $hook_suffix != 'settings_page_owm_maintenance_page' ) {
        return;
    }
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_media();
    
    wp_enqueue_script(
        'owm-admin-uploader',
        plugin_dir_url( __FILE__ ) . 'js/admin-uploader.js',
        array( 'jquery', 'wp-color-picker' ), // Dipendenze
        '1.1', 
        true
    );
    
    wp_localize_script( 'owm-admin-uploader', 'owm_js_vars', array(
        'title'       => __( 'Scegli un logo', 'operweb-maintenance-mode' ),
        'button_text' => __( 'Usa questo logo', 'operweb-maintenance-mode' ),
    ) );
}
add_action( 'admin_enqueue_scripts', 'owm_enqueue_admin_scripts' );

/**
 * Aggiunge CSS personalizzato per il FOOTER della pagina admin
 */
function owm_admin_page_styles() {
    ?>
    <style>
    .owm-admin-footer {
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px dashed #ddd;
        text-align: center;
        font-size: 0.9em;
        color: #555;
    }
    .owm-admin-footer img {
        max-width: 180px;
        height: auto;
        vertical-align: middle;
        margin-bottom: 15px;
    }
    .owm-admin-footer strong {
        font-size: 1em;
    }
    .owm-admin-footer p {
        margin: 0.5em 0;
    }
    .owm-admin-footer a {
        color: #0073aa; 
        text-decoration: none;
        font-weight: 600;
    }
    .owm-admin-footer a:hover {
        text-decoration: underline;
    }
    </style>
    <?php
}
add_action('admin_head-settings_page_owm_maintenance_page', 'owm_admin_page_styles');


/**
 * Aggiunge un link diretto alla pagina di impostazioni dalla pagina dei plugin
 */
function owm_add_settings_link( $links ) {
    $settings_link = '<a href="' . esc_url( admin_url( 'options-general.php?page=owm_maintenance_page' ) ) . '">' . esc_html__( 'Impostazioni', 'operweb-maintenance-mode' ) . '</a>';
    array_unshift( $links, $settings_link );
    return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'owm_add_settings_link' );