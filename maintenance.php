<?php
// --- Inizio Blocco PHP ---
$options = get_option( 'owm_options' );

$site_name = get_bloginfo('name');
$ragione_sociale = $site_name;

// Colori
$color_bg     = !empty($options['color_bg']) ? $options['color_bg'] : '#f4f7f6';
$color_border = !empty($options['color_border']) ? $options['color_border'] : '#00b3ea';

// Logica Logo
$logo_id = !empty( $options['logo_id'] ) ? $options['logo_id'] : 0;
$logo_html = '';
$logo_alt_text = $site_name . ' ' . __( 'Logo', 'operweb-maintenance-mode' ); // Testo alt di fallback

if ( $logo_id ) {
    $logo_html = wp_get_attachment_image( $logo_id, 'full', false, array('class' => 'custom-maintenance-logo') );
} else {
    $logo_html = '<div class="logo-icon"><span class="dashicons dashicons-admin-tools"></span></div>';
}

// Testo
$custom_text = !empty( $options['text'] ) ? $options['text'] : '';
$contatto_tel = "";
// --- Fine Blocco PHP ---
?>
<!DOCTYPE html>
<html lang="<?php echo esc_attr( get_locale() ); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title><?php printf( '%s - %s', esc_html__( 'Sito in Manutenzione', 'operweb-maintenance-mode' ), esc_html( $site_name ) ); ?></title>
    
    <?php
    // ERRORE #41 RISOLTO: wp_head() stamperà lo stile Dashicons
    wp_head();
    ?>
    
    <style>
        /* Aggiunto per nascondere l'admin bar se per caso viene mostrata */
        html { margin-top: 0 !important; }
        * html body { margin-top: 0 !important; }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: <?php echo esc_attr( $color_bg ); ?>; 
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            text-align: center;
            padding: 20px 0;
        }
        .container {
            background-color: #ffffff;
            padding: 40px 50px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            max-width: 600px;
            width: 90%;
            border-top: 5px solid <?php echo esc_attr( $color_border ); ?>; 
        }
        .logo .custom-maintenance-logo {
            max-width: 400px;
            height: auto;
            margin-bottom: 25px;
        }
        .logo .logo-icon .dashicons {
            font-size: 80px;
            width: 80px;
            height: 80px;
            line-height: 1;
            color: <?php echo esc_attr( $color_border ); ?>;
            margin-bottom: 25px;
        }
        .maintenance-content {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #555;
            text-align: left;
        }
        .maintenance-content h1 {
             font-size: 1.8rem;
             color: #222;
             margin-bottom: 15px;
             text-align: center;
        }
        .maintenance-content p {
            margin-bottom: 1.2em;
        }
        .separator {
            border: 0;
            height: 1px;
            background-color: #eee;
            margin: 30px 0 25px 0;
        }
        .contact-info {
            font-size: 0.9rem;
            color: #777;
            line-height: 1.5;
        }
        .contact-info strong { color: #333; }
    </style>
</head>
<body <?php body_class(); ?>> <div class="container">
        <div class="logo">
            <?php 
            // ERRORE 112 RISOLTO: Aggiunto wp_kses_post()
            echo wp_kses_post( $logo_html ); 
            ?>
        </div>
        <div class="maintenance-content">
            <?php 
            // ERRORE 15 RISOLTO: Aggiunto wp_kses_post()
            echo wp_kses_post( wpautop( $custom_text ) ); 
            ?>
        </div>
        <footer>
            <hr class="separator">
            <div class="contact-info">
                <strong><?php echo esc_html( $ragione_sociale ); ?></strong>
                <?php if ( !empty($contatto_tel) ) : ?>
                    <br><?php echo esc_html( $contatto_tel ); ?>
                <?php endif; ?>
            </div>
        </footer>
    </div>
    
    <?php 
    // Aggiunto per compatibilità plugin
    wp_footer(); 
    ?>
    
</body>
</html>
</html>