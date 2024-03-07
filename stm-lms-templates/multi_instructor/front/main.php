<?php

$course_id          = get_the_ID();
$author_id          = get_post_meta( $course_id, 'co_instructor', true );
$co_instructor_name = get_post_meta( $course_id, 'co_instructor_name', true );
?>
<?php if (!empty($co_instructor_name)) : ?>
<div class="meta-unit teacher clearfix">
	<div class="meta_values">
		<div class="label h6"><?php esc_html_e( 'Co-instructor', 'masterstudy-lms-learning-management-system-pro' ); ?></div>
		<div class="value heading_font h6"><?php echo $co_instructor_name ?></div>
	</div>
</div>

<style>
	.meta-unit.teacher.clearfix {
		display: inline-block;
	}
</style>

<?php endif; ?>