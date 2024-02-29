<?php

STM_LMS_Update_settings::init();

class STM_LMS_Update_settings {
	public const FIELDS_META_MAPPING
		= array(
			'co_instructor_name' => 'co_instructor_name',
			'course_date'        => 'course_date',
		);

	public static function init() {
		add_action( 'masterstudy_lms_course_saved', array( self::class, 'course_saved' ), 10, 2 );
	}


	public static function course_saved( $post_id, $course ) {
		foreach ( self::FIELDS_META_MAPPING as $property => $meta_key ) {
			if ( ! empty( $course[ $property ] ) ) {
				update_post_meta( $post_id, $meta_key, $course[ $property ] );
			}
		}
	}

	public static function override_course_settings_route() {

		register_rest_route(
			'masterstudy-lms/v2',
			'/courses/(?P<course_id>\d+)/settings',
			array(
				'methods'             => 'GET',
				'callback'            => array( self::class, 'custom_course_settings_callback' ),
				'permission_callback' => '__return_true'
			)
		);
	}

	public static function custom_course_settings_callback( $data ) {
		$course_id = $data['course_id'];
		echo 'ttt';
		$settings = array(
			'setting1' => 'value1',
			'setting2' => 'value2',
		);

		return rest_ensure_response( $settings );
	}


}