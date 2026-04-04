jQuery(document).ready(function($) {
    'use strict';

    var mediaFrame;

    // Gestione click sul pulsante "Carica Logo"
    $('#owm_upload_logo_button').on('click', function(e) {
        e.preventDefault();
        if (mediaFrame) {
            mediaFrame.open();
            return;
        }
        mediaFrame = wp.media({
            // --- TRADUZIONE ---
            // Queste stringhe ora vengono da wp_localize_script
            title: owm_js_vars.title,       // ex: 'Scegli un logo'
            button: {
                text: owm_js_vars.button_text // ex: 'Usa questo logo'
            },
            multiple: false
        });
        mediaFrame.on('select', function() {
            var attachment = mediaFrame.state().get('selection').first().toJSON();
            $('#owm_logo_id').val(attachment.id);
            $('#owm_logo_preview').html('<img src="' + attachment.url + '" style="max-width:300px; height:auto; border:1px solid #ddd;">');
            $('#owm_remove_logo_button').show();
        });
        mediaFrame.open();
    });

    // Gestione click sul pulsante "Rimuovi Logo"
    $('#owm_remove_logo_button').on('click', function(e) {
        e.preventDefault();
        $('#owm_logo_id').val('');
        $('#owm_logo_preview').html('');
        $(this).hide();
    });
    
    // Inizializza tutti i campi con la classe 'owm-color-picker'
    $('.owm-color-picker').wpColorPicker();
    
});