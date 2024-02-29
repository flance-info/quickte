<?php

use MasterStudy\Lms\Repositories\CurriculumMaterialRepository;
use MasterStudy\Lms\Repositories\CurriculumRepository;

new STM_LMS_User_Manager_Course_User_Child;

class STM_LMS_User_Manager_Course_User_Child {

	public function __construct() {
		remove_all_actions( 'wp_ajax_stm_lms_dashboard_set_student_item_progress' );
		add_action( 'wp_ajax_stm_lms_dashboard_set_student_item_progress', array( $this, 'set_student_progress' ) );

		$this->send_generated_certificate();
	}


	public function set_student_progress() {
		check_ajax_referer( 'stm_lms_dashboard_set_student_item_progress', 'nonce' );
		if ( ! STM_LMS_User_Manager_Interface::isInstructor() ) {
			die;
		}
		$request_body = file_get_contents( 'php://input' );
		$data         = json_decode( $request_body, true );
		if ( empty( $data['user_id'] ) || empty( $data['course_id'] ) || empty( $data['item_id'] ) ) {
			die;
		}
		$course_id  = intval( $data['course_id'] );
		$student_id = intval( $data['user_id'] );
		$item_id    = intval( $data['item_id'] );
		$completed  = boolval( $data['completed'] );
		/*For various item types*/
		/*Check item in curriculum*/
		$course_materials = ( new CurriculumMaterialRepository() )->get_course_materials( $course_id );
		if ( empty( $course_materials ) ) {
			die;
		}
		if ( ! in_array( $item_id, $course_materials, true ) ) {
			die;
		}
		switch ( get_post_type( $item_id ) ) {
			case 'stm-lessons':
				STM_LMS_User_Manager_Course_User::complete_lesson( $student_id, $course_id, $item_id );
				break;
			case 'stm-assignments':
				STM_LMS_User_Manager_Course_User::complete_assignment( $student_id, $course_id, $item_id, $completed );
				break;
			case 'stm-quizzes':
				STM_LMS_User_Manager_Course_User::complete_quiz( $student_id, $course_id, $item_id, $completed );
				break;
		}
		STM_LMS_Course::update_course_progress( $student_id, $course_id );
		$response = STM_LMS_User_Manager_Course_User::_student_progress( $course_id, $student_id );
		STM_LMS_User_Manager_Course_User_Child::send_student_certificate_oncompletion( $response, $course_id, $student_id );
		wp_send_json( $response );
	}

	public static function send_student_certificate_oncompletion( $response, $course_id, $user_id ) {

		if ( class_exists( 'STM_LMS_Mails' ) ) {

			$passing_grade = intval( STM_LMS_Options::get_option( 'certificate_threshold', 70 ) );
			$user_grade    = intval( $response['progress_percent'] );
			$transient_key = 'certificate_email_sent_' . $user_id . '_' . $course_id;

			if ( $user_grade < $passing_grade ) {
				delete_transient( $transient_key );
			} else {

				$user         = STM_LMS_User::get_current_user( $user_id );
				$course_title = get_the_title( $course_id );
				$name         = $user['login'];
				$message      = sprintf(
				/* translators: %1$s Course Title, %2$s User Login */
					esc_html__( 'Dear  %1$s, Your Certificate for Course %2$s is now available to download from your Personal Area.', 'masterstudy-child' ),
					$name,
					$course_title,
				);
				// Check if the email has already been sent
				if ( false === get_transient( $transient_key ) ) {


					STM_LMS_Helpers::send_email( $user['email'], "Your Certificate for Course {$course_title}", $message, 'stm_lms_certificate_available_for_user', compact( 'name', 'course_title' ) );
					// Set transient to mark that the email has been sent
					set_transient( $transient_key, 'sent', YEAR_IN_SECONDS ); // Adjust the expiration time as needed
				}
			}
		}
	}

	public function send_generated_certificate() {
		add_filter(
			'masterstudy_lms_certificate_fields_data',
			function ( $fields, $certificate ) {

				return $fields;
			},
			10,
			2
		);
	}
}