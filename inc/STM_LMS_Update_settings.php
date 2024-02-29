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


}