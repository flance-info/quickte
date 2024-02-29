<?php
add_action('wp_enqueue_scripts', 'theme_enqueue_styles');
function theme_enqueue_styles()
{

    wp_enqueue_style('theme-style', get_stylesheet_uri(), null, STM_THEME_VERSION, 'all');
}

/* Clone amministrator user role */
add_action('init', 'cloneRole');

function cloneRole() {
 $adm = get_role('administrator');
 $adm_cap= array_keys( $adm->capabilities ); //get administator capabilities
 add_role('new_role', 'QuiXte Amministrazione'); //create new role
 $new_role = get_role('new_role');
  foreach ( $adm_cap as $cap ) {
   $new_role->add_cap( $cap ); //clone administrator capabilities to new role
  }
}

/*TRADUZIONE STRINGHE*/
add_filter('gettext', 'translate_reply');
add_filter('ngettext', 'translate_reply');
function translate_reply($translated)
{
    $translated = str_ireplace('lectures', 'lezioni', $translated);
    $translated = str_ireplace('submit and checkout', 'abbonati ora', $translated);
    $translated = str_ireplace('Lesson starts in', 'La lezione inizia tra', $translated);
    $translated = str_ireplace('Curriculum', 'Lezioni', $translated);
    $translated = str_ireplace('Continue', 'Continua', $translated);
    $translated = str_ireplace('Registrati', 'Iscriviti', $translated);
    $translated = str_ireplace('Have account', 'Hai già un account?', $translated);
    $translated = str_ireplace('The password must have a minimum of 8 characters of numbers and letters, contain at least 1 capital letter, and should not exceed 20 characters ', 'La password deve contenere almeno 8 caratteri tra numeri e lettere, contenere almeno 1 lettera maiuscola e non deve superare i 20 caratteri', $translated);
    $translated = str_ireplace('Send Reset Link', 'Invia link di reset', $translated);
    $translated = str_ireplace('Password reset link sent', 'Link cambio password inviato', $translated);
    $translated = str_ireplace('to your email', 'alla tua mail', $translated);
	$translated = str_ireplace('Certificates', 'Certificati', $translated);
	$translated = str_ireplace('Insegnante', 'Docente', $translated);
	$translated = str_ireplace('Are you sure you want to cancel your', 'Sei sicuro di voler cancellare il tuo abbonamento', $translated);
	$translated = str_ireplace('Yes, cancel this membership', 'Si, cancella abbonamento', $translated);
	$translated = str_ireplace('No, keep this membership', 'No, mantieni abbonamento', $translated);
	$translated = str_ireplace('Click here to go to the home page.', 'Clicca qui per tornare alla homepage', $translated);
    return $translated;
}

// Custom certification
add_action('admin_post_custom_form_action', 'handle_custom_form_action');

function handle_custom_form_action()
{
    include('custom-certification/certificate-form.php');
}


// Custom logo for course category
// Funzione per ottenere l'URL del logo della categoria corrente
function get_category_logo_url() {
    // Ottieni l'ID della categoria corrente
    $category = get_queried_object();
    $category_id = $category->term_id;

    // Definisci un array associativo che mappa ID categoria a URL del logo
    $category_logo_urls = array(
        '70' => 'https://quixte.it/wp-content/uploads/2024/02/QXT_BiblioAcademy.png',
		'73' => 'https://quixte.it/wp-content/uploads/2024/02/QXT_BiblioAcademy.png',
		'69' => 'https://quixte.it/wp-content/uploads/2024/02/QXT_FederCoordinatori.png',
		'74' => 'https://quixte.it/wp-content/uploads/2024/02/QXT_FederCoordinatori.png',
		'71' => 'https://quixte.it/wp-content/uploads/2024/02/QXT_TeleCert.png',
		'75' => 'https://quixte.it/wp-content/uploads/2024/02/QXT_TeleCert.png'
        // Aggiungi altre categorie con i relativi URL dei loghi
    );

    // Verifica se l'ID della categoria corrente è presente nell'array
    if (array_key_exists($category_id, $category_logo_urls)) {
        return $category_logo_urls[$category_id];
    } else {
        // Se non è presente un logo specifico per la categoria, restituisci il logo predefinito
        return 'https://quixte.it/wp-content/uploads/2024/02/QXT.png';
    }
}

// Funzione per generare lo shortcode per visualizzare il logo della categoria corrente
function display_category_logo_shortcode() {
    $logo_url = get_category_logo_url();
    // Genera il markup HTML per visualizzare il logo utilizzando l'URL ottenuto
    $output = '<a href="' . esc_url(home_url('/')) . '"><img src="' . esc_url($logo_url) . '" alt="Logo della categoria"></a>';
    return $output;
}

// Registra lo shortcode
add_shortcode('category_logo', 'display_category_logo_shortcode');

// rusty code
add_action( 'init', 'stm_send_certificate_oncompletion' );
function stm_send_certificate_oncompletion() {
	include_once 'inc/STM_LMS_User_Manager_Course_User_Child.php';
	include_once 'inc/STM_LMS_Update_settings.php';
	include_once 'inc/STM_LMS_addons.php';
}

?>