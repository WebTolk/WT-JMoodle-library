<?php
/**
 * @package       WT JMoodle Library
 * @version       1.0.3
 * @Author        Sergey Tolkachyov, https://web-tolk.ru
 * @сopyright (c) January 2024 Sergey Tolkachyov. All rights reserved.
 * @license       GNU/GPL3 http://www.gnu.org/licenses/gpl-3.0.html
 * @since         1.0.0
 */

namespace Webtolk\JMoodle\Helper\Core\Course;

defined('_JEXEC') or die;

use Webtolk\JMoodle\Interfaces\MethodhelperInterface;

class Course implements MethodhelperInterface
{
	public function checkData(string $method, array $data = []): array
	{
		return $this->$method($data);
	}

	private function core_course_add_content_item_to_user_favourites(array $data = []): array
	{
		return $data;
	}

	private function core_course_check_updates(array $data = []): array
	{
		return $data;
	}

	private function core_course_create_categories(array $data = []): array
	{
		return $data;
	}

	private function core_course_create_courses(array $data = []): array
	{
		return $data;
	}

	private function core_course_delete_categories(array $data = []): array
	{
		return $data;
	}

	private function core_course_delete_courses(array $data = []): array
	{
		return $data;
	}

	private function core_course_delete_modules(array $data = []): array
	{
		return $data;
	}

	private function core_course_duplicate_course(array $data = []): array
	{
		return $data;
	}

	private function core_course_edit_module(array $data = []): array
	{
		return $data;
	}

	private function core_course_edit_section(array $data = []): array
	{
		return $data;
	}

	private function core_course_get_activity_chooser_footer(array $data = []): array
	{
		return $data;
	}

	private function core_course_get_categories(array $data = []): array
	{
		return $data;
	}

	private function core_course_get_contents(array $data = []): array
	{
		return $data;
	}

	private function core_course_get_course_content_items(array $data = []): array
	{
		return $data;
	}

	private function core_course_get_course_module(array $data = []): array
	{
		return $data;
	}

	private function core_course_get_course_module_by_instance(array $data = []): array
	{
		return $data;
	}

	/**
	 * Check data for core_course_get_courses Moodle REST API method
	 *
	 * @param   array  $data  Optional course ids list
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.0.0
	 */
	private function core_course_get_courses(array $data = []): array
	{
		if (array_key_exists('options', $data))
		{
			if (!array_key_exists('ids', $data['options']))
			{
				return [
					'error_code'    => 400,
					'error_message' => 'There is no ids key in options array specified for method core_course_get_courses'
				];
			}
		}

		return $data;
	}

	private function core_course_get_courses_by_field(array $data = []): array
	{
		return $data;
	}

	private function core_course_get_enrolled_courses_by_timeline_classification(array $data = []): array
	{
		return $data;
	}

	private function core_course_get_enrolled_courses_with_action_events_by_timeline_classification(array $data = []): array
	{
		return $data;
	}

	private function core_course_get_enrolled_users_by_cmid(array $data = []): array
	{
		return $data;
	}

	private function core_course_get_module(array $data = []): array
	{
		return $data;
	}

	private function core_course_get_recent_courses(array $data = []): array
	{
		return $data;
	}

	private function core_course_get_updates_since(array $data = []): array
	{
		return $data;
	}

	private function core_course_get_user_administration_options(array $data = []): array
	{
		return $data;
	}

	private function core_course_get_user_navigation_options(array $data = []): array
	{
		return $data;
	}

	private function core_course_import_course(array $data = []): array
	{
		return $data;
	}

	private function core_course_remove_content_item_from_user_favourites(array $data = []): array
	{
		return $data;
	}

	private function core_course_search_courses(array $data = []): array
	{
		return $data;
	}

	private function core_course_set_favourite_courses(array $data = []): array
	{
		return $data;
	}

	private function core_course_toggle_activity_recommendation(array $data = []): array
	{
		return $data;
	}

	private function core_course_update_categories(array $data = []): array
	{
		return $data;
	}

	private function core_course_update_courses(array $data = []): array
	{
		return $data;
	}

	private function core_course_view_course(array $data = []): array
	{
		return $data;
	}

	private function core_courseformat_file_handlers(array $data = []): array
	{
		return $data;
	}

	private function core_courseformat_get_state(array $data = []): array
	{
		return $data;
	}

	private function core_courseformat_update_course(array $data = []): array
	{
		return $data;
	}

	private function core_create_userfeedback_action_record(array $data = []): array
	{
		return $data;
	}
}