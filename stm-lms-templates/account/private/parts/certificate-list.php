<?php
/**
 * @var $current_user
 */

stm_lms_register_style('user-quizzes');
stm_lms_register_style('user-certificates');
$completed = stm_lms_get_user_completed_courses($current_user['id'], array(), -1);

stm_lms_register_script('affiliate_points');

stm_lms_register_style('affiliate_points');


$customer_orders = wc_get_orders(array(
    'customer' => $current_user['id'],
    'status' => 'completed'
));

if (!empty($completed)) { ?>
    <?php
    if (class_exists('STM_LMS_Certificate_Builder')) {
        wp_register_script('jspdf', STM_LMS_URL . '/assets/vendors/jspdf.umd.js', array(), stm_lms_custom_styles_v());
        wp_enqueue_script('stm_generate_certificate', get_stylesheet_directory_uri() . '/assets/js/generate_certificate.js', array('jspdf', 'stm_certificate_fonts'), stm_lms_custom_styles_v());
    }
    ?>
    <div class="stm-lms-user-quizzes stm-lms-user-certificates">

    <h2 class="stm-lms-account-title">
        <?php esc_html_e('My Certificates', 'masterstudy-lms-learning-management-system'); ?>
    </h2>

    <div class="multiseparator"></div>

    <div class="stm-lms-user-quiz__head heading_font">
        <div class="stm-lms-user-quiz__head_title">
            <?php esc_html_e('Course', 'masterstudy-lms-learning-management-system'); ?>
        </div>
        <div class="stm-lms-user-quiz__head_status">
            <?php esc_html_e('Certificate', 'masterstudy-lms-learning-management-system'); ?>
        </div>
    </div>

    <?php foreach ($completed as $key => $course) : ?>
        <?php
        $order_id = $customer_orders[$key] ? $customer_orders[$key]->get_id() : '';
        $user_id = $current_user['id'];
        $data_id = sanitize_text_field($course['user_course_id']);
        $course_id = sanitize_text_field($course['course_id']);
        $nonce = sanitize_text_field($course['nonce']);
        $started = sanitize_text_field($course['start_time']);
        $user_certificate_code = "$user_id$started-$course_id$data_id";

        global $wpdb;


$table_name = $wpdb->prefix . 'usermeta';
        $query = $wpdb->prepare("
            SELECT * FROM $table_name
            WHERE user_id = %d
            AND meta_key IN ('rw1pmpsu91', 'he7zifr8vw')",
            $user_id);
        $results = $wpdb->get_results($query);
        ?>

        <?php if ($order_id || !$order_id): ?>
            <div class="stm-lms-user-quizzes stm-lms-user-certificates">

                <div class="stm-lms-user-quiz">
                    <div class="stm-lms-user-quiz__title">
                        <a href="<?php echo esc_url(get_the_permalink($course['course_id'])); ?>">
                            <?php echo wp_kses_post(get_the_title($course['course_id'])); ?>
                        </a>
                    </div>

                    <a href="#"
                       data-id="<?= esc_attr($data_id) ?>"
                       data-course_id="<?= esc_attr($course_id) ?>"
                       fiscal-code="<?= esc_attr($results[0]->meta_value) ?>"
                       data-student-name="<?= esc_attr($results[1]->meta_value) ?>"
                       certificate-code="<?= esc_attr($user_certificate_code) ?>"
                       started="<?= esc_attr($course['start_time']) ?>"
                       class="stm-lms-user-quiz__name stm_preview_certificate get-certificate">
                        <?php esc_html_e('Download', 'masterstudy-lms-learning-management-system'); ?>
                    </a>
                </div>

                <div style="display: flex; gap: 10px; flex-wrap: wrap">

                    <div class="affiliate_points heading_font"
                         data-copy="<?php echo esc_attr($results[0]->meta_value); ?>">
                        <span class="hidden" id="<?php echo esc_attr($results[0]->meta_value); ?>">
                            <?php echo esc_html($results[0]->meta_value); ?>
                        </span>
                        <span class="affiliate_points__btn">
                            <i class="fa fa-link"></i>
                            <span class="text">
                                <?php esc_html_e('Copy fiscale code', 'masterstudy-lms-learning-management-system'); ?>
                            </span>
                        </span>
                    </div>

                    <div class="affiliate_points heading_font"
                         data-copy="<?php echo esc_attr($user_certificate_code); ?>">
                        <span class="hidden"
                              id="<?php echo esc_attr($user_certificate_code); ?>"><?php echo esc_html($user_certificate_code); ?></span>
                        <span class="affiliate_points__btn">
                        <i class="fa fa-code"></i>
                        <span class="text"><?php esc_html_e('Copy certificato N°', 'masterstudy-lms-learning-management-system'); ?></span>
                        </span>
                    </div>

                </div>

            </div>
        <?php else: ?>

            <h2 class="stm-lms-account-title">
                <?php esc_html_e('My Certificates', 'masterstudy-lms-learning-management-system'); ?>
            </h2>

            <div class="multiseparator"></div>
            <h4 class="no-certificates-notice"><?php esc_html_e('You do not have a certificate yet.', 'masterstudy-lms-learning-management-system'); ?></h4>
            <h4 class="no-certificates-notice"><?php esc_html_e('You should be registred by woocomerce orders, please ask your instructor add your credentials to get certificate', 'masterstudy-lms-learning-management-system'); ?></h4>

        <?php endif ?>
    <?php endforeach; ?>


    <script defer>
        const certificateLinks = document.querySelectorAll('.get-certificate');
        certificateLinks.forEach(function (link) {
            link.setAttribute('nonce', stm_lms_nonces.stm_get_certificate);
        });
    </script> <? }