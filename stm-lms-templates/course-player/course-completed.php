<?php
/**
 * @var $post_id
 * @var $item_id
 * @var boolean $dark_mode
 */

$data = apply_filters( 'masterstudy_lms_course_player_completed', $post_id, $item_id );

wp_enqueue_style( 'masterstudy-course-player-course-completed' );
wp_enqueue_script( 'masterstudy-course-player-course-completed' );
wp_localize_script(
	'masterstudy-course-player-course-completed',
	'course_completed',
	array(
		'course_id' => $post_id,
		'completed' => (bool) $data['lesson_completed'],
		'nonce'     => wp_create_nonce( 'stm_lms_total_progress' ),
		'ajax_url'  => admin_url( 'admin-ajax.php' ),
	)
);
wp_enqueue_script( 'jspdf' );
wp_enqueue_script( 'masterstudy-course-player-certificate' );
wp_localize_script(
	'masterstudy-course-player-certificate',
	'course_certificate',
	array(
		'nonce'    => wp_create_nonce( 'stm_get_certificate' ),
		'ajax_url' => admin_url( 'admin-ajax.php' ),
	)
);

$failed_image  = $data['failed_image'];
$success_image = $data['success_image'];
?>
<div id="masterstudy-course-player-course-completed" class="masterstudy-course-player-course-completed" style="display: none;">
	<div class="masterstudy-course-player-course-completed__info">
		<span class="masterstudy-course-player-course-completed__info-close stmlms-close"></span>
		<div class="masterstudy-course-player-course-completed__info-loading">
			<?php echo esc_html__( 'Loading your statistics', 'masterstudy-lms-learning-management-system' ); ?>
		</div>
		<div class="masterstudy-course-player-course-completed__info-success">
			<div class="masterstudy-course-player-course-completed__opportunities">
				<?php
				if ( ! $data['disable_smile'] ) :
					if ( ! empty( $data['custom_failed_image_id'] ) ) {
						$custom_failed_image_url = wp_get_attachment_image_url( $data['custom_failed_image_id'], 'thumbnail' );
						if ( ! empty( $custom_failed_image_url ) ) {
							$failed_image = $custom_failed_image_url;
						}
					}
					if ( ! empty( $data['custom_success_image_id'] ) ) {
						$custom_success_image_url = wp_get_attachment_image_url( $data['custom_success_image_id'], 'thumbnail' );
						if ( ! empty( $custom_success_image_url ) ) {
							$success_image = $custom_success_image_url;
						}
					}
					?>
					<div class="masterstudy-course-player-course-completed__opportunities-icon">
						<?php if ( $data['passed'] ) : ?>
							<img src="<?php echo esc_url( $success_image ); ?>" width="80" height="80" alt="<?php echo esc_html__( 'You have successfully completed the course', 'masterstudy-lms-learning-management-system' ); ?>">
						<?php else : ?>
							<img src="<?php echo esc_url( $failed_image ); ?>" width="80" height="80" alt="<?php echo esc_html__( 'You have NOT completed the course', 'masterstudy-lms-learning-management-system' ); ?>">
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<div class="masterstudy-course-player-course-completed__opportunities-statistic">
					<span class="masterstudy-course-player-course-completed__opportunities-label"><?php echo esc_html__( 'Your score', 'masterstudy-lms-learning-management-system' ); ?></span>
					<span class="masterstudy-course-player-course-completed__opportunities-percent"></span>
				</div>
			</div>

			<?php if ( $data['passed'] ) : ?>
			<div class="masterstudy-course-player-course-completed__info-message"><?php echo esc_html__( 'You have successfully completed the course', 'masterstudy-lms-learning-management-system' ); ?></div>
			<?php else : ?>
			<div class="masterstudy-course-player-course-completed__info-message"><?php echo esc_html__( 'You have NOT completed the course', 'masterstudy-lms-learning-management-system' ); ?></div>
			<?php endif; ?>
			<h2 class="masterstudy-course-player-course-completed__info-title"></h2>
			<div class="masterstudy-course-player-course-completed__curiculum-statistic">
				<?php
				$curriculums = ms_plugin_curriculum_list();
				foreach ( $curriculums as $curriculum ) :
					?>
				<div class="masterstudy-course-player-course-completed__curiculum-statistic-item masterstudy-course-player-course-completed__curiculum-statistic-item_type-<?php echo esc_attr( $curriculum['type'] ); ?>">
					<img src="<?php echo esc_url( STM_LMS_URL . 'assets/icons/lessons/' . $curriculum['icon'] . '.svg' ); ?>" width="<?php echo esc_attr( $curriculum['icon_width'] ); ?>" height="<?php echo esc_attr( $curriculum['icon_height'] ); ?>">
					<span><?php echo esc_html( $curriculum['label'] ); ?> <strong><span class="masterstudy-course-player-course-completed__curiculum-statistic-item_completed"></span>/<span class="masterstudy-course-player-course-completed__curiculum-statistic-item_total"></span></strong></span>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
