<?php //if ($order_id): ?>
    <h1 class="success-certificate">
        <?php esc_html_e('Click button below in order to emit certificate', 'masterstudy-lms-learning-management-system') ?>
    </h1>
    <h3 style="color: #457992" class="success-certificate">
        <?php echo wp_kses_post(get_the_title($course['course_id'])); ?>
    </h3>

    <?php
    global $wpdb;
    $table_name = $wpdb->prefix . 'usermeta';
    $query = $wpdb->prepare("
        SELECT * FROM $table_name
        WHERE user_id = %d
        AND meta_key IN ('rw1pmpsu91', 'he7zifr8vw')",
        $user_id);
    $results = $wpdb->get_results($query);
    ?>

    <div class="button-download">
        <button
                class="get-certificate"
                data-id="<?= esc_attr($course['user_course_id']) ?>"
                data-course_id="<?= esc_attr($course['course_id']) ?>"
                fiscal-code="<?= esc_attr($results[0]->meta_value) ?>"
                data-student-name="<?= esc_attr($results[1]->meta_value) ?>"
                certificate-code="<?= esc_attr($user_certificate_code) ?>"
                nonce="<?= esc_attr($nonce) ?>"
                started="<?= esc_attr($course['start_time']) ?>"
                style="padding: 10px 20px;
                cursor: pointer;
                width: 200px;
                background-color: #000;
                color: #fff;
                text-decoration: none;
                border-radius: 30px;
                ">
            <?php esc_html_e('Download', 'masterstudy-lms-learning-management-system'); ?>
        </button>
    </div>
<?php // endif; ?>

<style>
    .success-certificate {
        text-align: center;
        font-size: 30px;
        font-weight: bold;
        margin-top: 30px;
    }

    .failed-certificate {
        text-align: center;
        font-size: 22px;
        font-weight: bold;
        margin-top: 30px;
    }

    .button-download {
        display: grid;
        place-content: center;
        margin-top: 30px;
    }
</style>