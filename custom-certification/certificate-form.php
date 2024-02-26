<?php
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script type="text/javascript">
    var stm_lms_ajaxurl = '<?php echo esc_url(admin_url('admin-ajax.php')); ?>';
    var stm_lms_resturl = '<?php echo rest_url('stm-lms/v1', 'json'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>';
    var ms_lms_resturl = '<?php echo rest_url('masterstudy-lms/v2', 'json'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>';
    var ms_lms_nonce = '<?php echo wp_create_nonce('wp_rest'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>';
    var stm_ajax_add_pear_hb = '<?php echo wp_create_nonce('stm_ajax_add_pear_hb'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>';
</script>

<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/jspdf.umd.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/certificates-fonts.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/generate_certificate.js"></script>

<?php
$user_id = sanitize_text_field($_POST['userID']);
$nonce = sanitize_text_field($_POST['nonce']);
$customer_orders = wc_get_orders(array(
    'customer' => $user_id,
    'status' => 'completed'
));

if (!$customer_orders) {
    require 'fallback-template.php';
    exit;
}
$completed = stm_lms_get_user_completed_courses($user_id, array(), -1);

if (!empty($completed)) {
    foreach ($completed as $key => $course) {
        $data_id = sanitize_text_field($course['user_course_id']);
        $course_id = sanitize_text_field($course['course_id']);
        $started = sanitize_text_field($course['start_time']);
        $user_certificate_code = "$user_id$started-$course_id$data_id";
        $order_id = $customer_orders[$key] ? $customer_orders[$key]->get_id() : '';

        global $wpdb;

        $table_name = $wpdb->prefix . 'usermeta';
        $query = $wpdb->prepare("
            SELECT * FROM $table_name
            WHERE user_id = %d
            AND meta_key IN ('rw1pmpsu91', 'he7zifr8vw')",
            $user_id);
        $results = $wpdb->get_results($query);
        require 'success-template.php';
    }
}
?>






