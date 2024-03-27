<?php
/**
 * @package       WT JMoodle Library
 * @version       1.1.0
 * @Author        Sergey Tolkachyov, https://web-tolk.ru
 * @сopyright (c) March 2024 Sergey Tolkachyov. All rights reserved.
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

	/**
	 * Check data for core_course_get_courses Moodle REST API method
	 *
	 * @param   array  $data  componentname or contentitemid data for core_course_add_content_item_to_user_favourites method
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.1.0
	 */
	private function core_course_add_content_item_to_user_favourites(array $data = []): array
	{
		if (empty($data)
			|| !in_array('componentname', $data)
			|| !in_array('contentitemid', $data))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'There is no required componentname or contentitemid keys in options array specified'
			];
		}

		foreach ([
			         'componentname',
			         'contentitemid',
		         ] as $option)
		{
			if (empty($data[$option]))
			{
				return [
					'error_code'    => 400,
					'error_message' => $option . ' is empty'
				];
			}
		}

		return $data;
	}

	/**
	 * Check data for core_course_get_courses Moodle REST API method
	 *
	 * @param   array  $data  componentname or contentitemid data for core_course_add_content_item_to_user_favourites method
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.1.0
	 */
	private function core_course_check_updates(array $data = []): array
	{
		if (empty($data))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'There is no courseid key in options array specified'
			];
		}

		if (!in_array('courseid', $data))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'There is no required courseid key'
			];
		}

		if (!is_int($data['courseid']))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'courseid params must be an integer'
			];
		}

		if (!in_array('tocheck', $data))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'There is no required tocheck array data'
			];
		}

		if (empty($data['tocheck']))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'There is empty required tocheck array data'
			];
		}

		if (array_key_exists('filter', $data) && !is_array($data['filter']))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Filter param must be an array'
			];
		}

		return $data;
	}

	/**
	 * Check data for core_course_create_categories Moodle REST API method
	 *
	 * @param   array  $data  Data for core_course_create_categories method
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.1.0
	 */
	private function core_course_create_categories(array $data = []): array
	{
		if (empty($data) || !in_array('categories', $data))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'There is no categories key in options array specified'
			];
		}

		if (!is_array($data['categories']))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Categories must be an array'
			];
		}

		if (empty($data['categories']))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Categories array is empty'
			];
		}

		foreach ($data['categories'] as $category)
		{
			if (!array_key_exists('name', $category) || !array_key_exists('parent', $category))
			{
				return [
					'error_code'    => 400,
					'error_message' => 'Category array must contain a name and parent param'
				];
			}
		}

		return $data;
	}

	/**
	 * Check data for core_course_create_courses Moodle REST API method
	 *
	 * @param   array  $data  Data for core_course_create_courses method
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.1.0
	 */
	private function core_course_create_courses(array $data = []): array
	{
		if (empty($data) || !in_array('courses', $data))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'There is no courses key in options array specified'
			];
		}

		if (!is_array($data['courses']))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Courses must be an array'
			];
		}

		if (empty($data['courses']))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Courses array is empty'
			];
		}

		if (!is_array($data['courses']))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Courses must be an array'
			];
		}

		return $data;
	}

	/**
	 * Check data for core_course_delete_categories Moodle REST API method
	 *
	 * @param   array  $data  Data for core_course_delete_categories method
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.1.0
	 */
	private function core_course_delete_categories(array $data = []): array
	{
		if (empty($data) || !in_array('categories', $data))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'There is no categories key in options array specified'
			];
		}

		if (!is_array($data['categories']))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Categories must be an array'
			];
		}

		if (empty($data['categories']))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Categories array is empty'
			];
		}

		foreach ($data['categories'] as $category)
		{
			if (!array_key_exists('id', $category) || empty($category['id']))
			{
				return [
					'error_code'    => 400,
					'error_message' => 'There is no category ids specified'
				];
			}
		}

		return $data;
	}

	/**
	 * Check data for core_course_delete_courses Moodle REST API method
	 *
	 * @param   array  $data  Data for core_course_delete_courses method
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.1.0
	 */
	private function core_course_delete_courses(array $data = []): array
	{
		if (empty($data) || !in_array('courseids', $data))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'There is no courseids key in options array specified'
			];
		}

		if (!is_array($data['courseids']))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Courseids must be an array with integers'
			];
		}

		if (empty($data['courseids']))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Courseids array is empty'
			];
		}

		return $data;
	}

	/**
	 * Check data for core_course_delete_modules Moodle REST API method
	 *
	 * @param   array  $data  Data for core_course_delete_modules method
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.1.0
	 */
	private function core_course_delete_modules(array $data = []): array
	{
		if (empty($data) || !in_array('cmids', $data))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'There is no cmids key in options array specified'
			];
		}

		if (!is_array($data['cmids']))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Cmids must be an array with integers'
			];
		}

		if (empty($data['cmids']))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Cmids array is empty'
			];
		}

		return $data;
	}

	/**
	 * Check data for core_course_duplicate_course Moodle REST API method
	 *
	 * @param   array  $data  Data for core_course_duplicate_course method
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.1.0
	 */
	private function core_course_duplicate_course(array $data = []): array
	{
		if (empty($data)
			|| !in_array('courseid', $data)
			|| !in_array('fullname', $data)
			|| !in_array('shortname', $data)
			|| !in_array('categoryid', $data))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'There is no courseid, fullname, shortname or categoryid key in options array specified'
			];
		}

		foreach ([
			         'courseid',
			         'fullname',
			         'shortname',
			         'categoryid'
		         ] as $option)
		{
			if (empty($data[$option]))
			{
				return [
					'error_code'    => 400,
					'error_message' => $option . ' is empty'
				];
			}
		}

		return $data;
	}

	/**
	 * Check data for core_course_edit_module Moodle REST API method
	 *
	 * @param   array  $data  Data for core_course_edit_module method
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.1.0
	 */
	private function core_course_edit_module(array $data = []): array
	{
		if (empty($data)
			|| !in_array('action', $data)
			|| !in_array('id', $data)
		)
		{
			return [
				'error_code'    => 400,
				'error_message' => 'There is no action or id key in options array specified'
			];
		}

		foreach ([
			         'action',
			         'id',
		         ] as $option)
		{
			if (empty($data[$option]))
			{
				return [
					'error_code'    => 400,
					'error_message' => $option . ' is empty'
				];
			}
		}

		return $data;
	}

	/**
	 * Check data for core_course_edit_section Moodle REST API method
	 *
	 * @param   array  $data  Data for core_course_edit_section method
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.1.0
	 */
	private function core_course_edit_section(array $data = []): array
	{
		if (empty($data)
			|| !in_array('action', $data)
			|| !in_array('id', $data)
		)
		{
			return [
				'error_code'    => 400,
				'error_message' => 'There is no action or id key in options array specified'
			];
		}

		foreach ([
			         'action',
			         'id',
		         ] as $option)
		{
			if (empty($data[$option]))
			{
				return [
					'error_code'    => 400,
					'error_message' => $option . ' is empty'
				];
			}
		}

		return $data;
	}

	/**
	 * Check data for core_course_get_activity_chooser_footer Moodle REST API method
	 *
	 * @param   array  $data  Data for core_course_get_activity_chooser_footer method
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.1.0
	 */
	private function core_course_get_activity_chooser_footer(array $data = []): array
	{
		if (empty($data)
			|| !in_array('courseid', $data)
			|| !in_array('sectionid', $data)
		)
		{
			return [
				'error_code'    => 400,
				'error_message' => 'There is no courseid or sectionid key in options array specified'
			];
		}

		foreach ([
			         'courseid',
			         'sectionid',
		         ] as $option)
		{
			if (empty($data[$option]))
			{
				return [
					'error_code'    => 400,
					'error_message' => $option . ' is empty'
				];
			}
		}

		return $data;
	}

	/**
	 * Check data for core_course_get_categories Moodle REST API method
	 *
	 * @param   array  $data  Data for core_course_get_categories method
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.1.0
	 */
	private function core_course_get_categories(array $data = []): array
	{
		if (!array_key_exists('criteria', $data))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Empty criteria array specified'
			];
		}

		$criterias = $data['criteria'];

		if (empty($criterias))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Empty criteria array specified'
			];
		}

		foreach ($criterias as $criteria)
		{
			if (
				(!array_key_exists('key', $criteria) || empty($criteria['key'])) ||
				(!array_key_exists('value', $criteria) || empty($criteria['value']))
			)
			{
				return [
					'error_code'    => 400,
					'error_message' => 'Wrong criteria array structure or criteria is empty. It could be like criteria[0][key]= string, criteria[0][value]= string'
				];
			}
		}

		return $data;
	}

	/**
	 * Check data for core_course_get_contents Moodle REST API method
	 *
	 * @param   array  $data  Data for core_course_get_contents method
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.1.0
	 */
	private function core_course_get_contents(array $data = []): array
	{
		if (empty($data)
			|| !in_array('courseid', $data)
		)
		{
			return [
				'error_code'    => 400,
				'error_message' => 'There is no courseid key in options array specified'
			];
		}

		if (empty($data['courseid']))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Courseid is empty'
			];
		}

		return $data;
	}

	/**
	 * Check data for core_course_get_course_content_items Moodle REST API method
	 *
	 * @param   array  $data  Data for core_course_get_course_content_items method
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.1.0
	 */
	private function core_course_get_course_content_items(array $data = []): array
	{
		if (empty($data)
			|| !in_array('courseid', $data)
		)
		{
			return [
				'error_code'    => 400,
				'error_message' => 'There is no courseid key in options array specified'
			];
		}

		if (empty($data['courseid']))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Courseid is empty'
			];
		}

		return $data;
	}

	/**
	 * Check data for core_course_get_course_module Moodle REST API method
	 *
	 * @param   array  $data  Data for core_course_get_course_module method
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.1.0
	 */
	private function core_course_get_course_module(array $data = []): array
	{
		if (empty($data)
			|| !in_array('cmid', $data)
		)
		{
			return [
				'error_code'    => 400,
				'error_message' => 'There is no cmid key in options array specified'
			];
		}

		if (empty($data['cmid']))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Cmid is empty'
			];
		}

		return $data;
	}

	/**
	 * Check data for core_course_get_course_module_by_instance Moodle REST API method
	 *
	 * @param   array  $data  Data for core_course_get_course_module_by_instance method
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.1.0
	 */
	private function core_course_get_course_module_by_instance(array $data = []): array
	{
		if (empty($data)
			|| !in_array('module', $data)
			|| !in_array('instance', $data)
		)
		{
			return [
				'error_code'    => 400,
				'error_message' => 'There is no module or instance key in options array specified'
			];
		}

		foreach ([
			         'module',
			         'instance',
		         ] as $option)
		{
			if (empty($data[$option]))
			{
				return [
					'error_code'    => 400,
					'error_message' => $option . ' is empty'
				];
			}
		}

		return $data;
	}

	/**
	 * Check data for core_course_get_courses Moodle REST API method
	 *
	 * @param   array  $data  Users data for create in Moodle
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
					'error_message' => 'There is no ids key in options array specified'
				];
			}
		}

		return $data;
	}

	/**
	 * Check data for core_course_get_courses_by_field Moodle REST API method
	 *
	 * @param   array  $data  Data for core_course_get_courses_by_field method
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.1.0
	 */
	private function core_course_get_courses_by_field(array $data = []): array
	{
		// There is no required params
		return $data;
	}

	/**
	 * Check data for core_course_get_enrolled_courses_by_timeline_classification Moodle REST API method
	 *
	 * @param   array  $data  Data for core_course_get_enrolled_courses_by_timeline_classification method
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.1.0
	 */
	private function core_course_get_enrolled_courses_by_timeline_classification(array $data = []): array
	{
		if (empty($data)
			|| !in_array('classification', $data)
		)
		{
			return [
				'error_code'    => 400,
				'error_message' => 'There is no classification key in options array specified'
			];
		}

		if (empty($data['classification']))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Classification is empty'
			];
		}

		return $data;
	}

	/**
	 * Check data for core_course_get_enrolled_courses_with_action_events_by_timeline_classification Moodle REST API method
	 *
	 * @param   array  $data  Data for core_course_get_enrolled_courses_with_action_events_by_timeline_classification method
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.1.0
	 */
	private function core_course_get_enrolled_courses_with_action_events_by_timeline_classification(array $data = []): array
	{
		if (empty($data)
			|| !in_array('classification', $data)
		)
		{
			return [
				'error_code'    => 400,
				'error_message' => 'There is no classification key in options array specified'
			];
		}

		if (empty($data['classification']))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Classification is empty'
			];
		}

		return $data;
	}

	/**
	 * Check data for core_course_get_enrolled_users_by_cmid Moodle REST API method
	 *
	 * @param   array  $data  Data for core_course_get_enrolled_users_by_cmid method
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.1.0
	 */
	private function core_course_get_enrolled_users_by_cmid(array $data = []): array
	{
		if (empty($data)
			|| !in_array('cmid', $data)
		)
		{
			return [
				'error_code'    => 400,
				'error_message' => 'There is no cmid key in options array specified'
			];
		}

		if (empty($data['cmid']))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Cmid is empty'
			];
		}

		return $data;
	}

	/**
	 * Check data for core_course_get_module Moodle REST API method
	 *
	 * @param   array  $data  Data for core_course_get_module method
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.1.0
	 */
	private function core_course_get_module(array $data = []): array
	{
		if (empty($data)
			|| !in_array('id', $data)
		)
		{
			return [
				'error_code'    => 400,
				'error_message' => 'There is no id key in options array specified'
			];
		}

		if (empty($data['id']))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Id is empty'
			];
		}

		return $data;
	}

	/**
	 * Check data for core_course_get_recent_courses Moodle REST API method
	 *
	 * @param   array  $data  Data for core_course_get_recent_courses method
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.1.0
	 */
	private function core_course_get_recent_courses(array $data = []): array
	{
		// There is no required params
		return $data;
	}

	/**
	 * Check data for core_course_get_updates_since Moodle REST API method
	 *
	 * @param   array  $data  Data for core_course_get_updates_since method
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.1.0
	 */
	private function core_course_get_updates_since(array $data = []): array
	{
		if (empty($data)
			|| !in_array('courseid', $data)
			|| !in_array('since', $data)
		)
		{
			return [
				'error_code'    => 400,
				'error_message' => 'There is no courseid or since key in options array specified'
			];
		}

		foreach ([
			         'courseid',
			         'since',
		         ] as $option)
		{
			if (empty($data[$option]))
			{
				return [
					'error_code'    => 400,
					'error_message' => $option . ' is empty'
				];
			}
		}

		return $data;
	}

	/**
	 * Check data for core_course_get_user_administration_options Moodle REST API method
	 *
	 * @param   array  $data  Data for core_course_get_user_administration_options method
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.1.0
	 */
	private function core_course_get_user_administration_options(array $data = []): array
	{
		if (empty($data)
			|| !in_array('courseids', $data)
		)
		{
			return [
				'error_code'    => 400,
				'error_message' => 'There is no id key in options array specified'
			];
		}

		if (empty($data['courseids']) || is_array($data['courseids']))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Courseids param is empty or not an array'
			];
		}

		return $data;
	}

	/**
	 * Check data for core_course_get_user_navigation_options Moodle REST API method
	 *
	 * @param   array  $data  Data for core_course_get_user_navigation_options method
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.1.0
	 */
	private function core_course_get_user_navigation_options(array $data = []): array
	{
		if (empty($data)
			|| !in_array('courseids', $data)
		)
		{
			return [
				'error_code'    => 400,
				'error_message' => 'There is no id key in options array specified'
			];
		}

		if (empty($data['courseids']) || is_array($data['courseids']))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Courseids param is empty or not an array'
			];
		}

		return $data;
	}

	/**
	 * Check data for core_course_import_course Moodle REST API method
	 *
	 * @param   array  $data  Data for core_course_import_course method
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.1.0
	 */
	private function core_course_import_course(array $data = []): array
	{
		if (empty($data)
			|| !in_array('importfrom', $data)
			|| !in_array('importto', $data)
		)
		{
			return [
				'error_code'    => 400,
				'error_message' => 'There is no importfrom or importto key in options array specified'
			];
		}

		foreach ([
			         'importfrom',
			         'importto',
		         ] as $option)
		{
			if (empty($data[$option]))
			{
				return [
					'error_code'    => 400,
					'error_message' => $option . ' is empty'
				];
			}
		}

		return $data;
	}

	/**
	 * Check data for core_course_remove_content_item_from_user_favourites Moodle REST API method
	 *
	 * @param   array  $data  Data for core_course_remove_content_item_from_user_favourites method
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.1.0
	 */
	private function core_course_remove_content_item_from_user_favourites(array $data = []): array
	{
		if (empty($data)
			|| !in_array('componentname', $data)
			|| !in_array('contentitemid', $data))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'There is no required componentname or contentitemid keys in options array specified'
			];
		}

		foreach ([
			         'componentname',
			         'contentitemid',
		         ] as $option)
		{
			if (empty($data[$option]))
			{
				return [
					'error_code'    => 400,
					'error_message' => $option . ' is empty'
				];
			}
		}

		return $data;
	}

	/**
	 * Check data for core_course_search_courses Moodle REST API method
	 *
	 * @param   array  $data  Data for core_course_search_courses method
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.1.0
	 */
	private function core_course_search_courses(array $data = []): array
	{
		if (empty($data)
			|| !in_array('criterianame', $data)
			|| !in_array('criteriavalue', $data))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'There is no required criterianame or criteriavalue keys in options array specified'
			];
		}

		foreach ([
			         'criterianame',
			         'criteriavalue',
		         ] as $option)
		{
			if (empty($data[$option]))
			{
				return [
					'error_code'    => 400,
					'error_message' => $option . ' is empty'
				];
			}
		}

		return $data;
	}

	/**
	 * Check data for core_course_set_favourite_courses Moodle REST API method
	 *
	 * @param   array  $data  Data for core_course_set_favourite_courses method
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.1.0
	 */
	private function core_course_set_favourite_courses(array $data = []): array
	{
		if (empty($data)
			|| !in_array('courses', $data))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'There is no required courses key in options array specified'
			];
		}

		foreach ($data['courses'] as $course)
		{
			if (
				(!array_key_exists('id', $course) || empty($course['id'])) ||
				(!array_key_exists('favourite', $course) || empty($course['favourite']))
			)
			{
				return [
					'error_code'    => 400,
					'error_message' => 'Wrong course array structure or course is empty. It could be like courses[0][id]= string, courses[0][favourite]= string'
				];
			}
		}

		return $data;
	}

	/**
	 * Check data for core_course_toggle_activity_recommendation Moodle REST API method
	 *
	 * @param   array  $data  Data for core_course_toggle_activity_recommendation method
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.1.0
	 */
	private function core_course_toggle_activity_recommendation(array $data = []): array
	{
		if (empty($data)
			|| !in_array('area', $data)
			|| !in_array('id', $data))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'There is no required area or id keys in options array specified'
			];
		}

		foreach ([
			         'area',
			         'id',
		         ] as $option)
		{
			if (empty($data[$option]))
			{
				return [
					'error_code'    => 400,
					'error_message' => $option . ' is empty'
				];
			}
		}

		return $data;
	}

	/**
	 * Check data for core_course_update_categories Moodle REST API method
	 *
	 * @param   array  $data  Data for core_course_update_categories method
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.1.0
	 */
	private function core_course_update_categories(array $data = []): array
	{
		if (empty($data) || !in_array('categories', $data))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'There is no categories key in options array specified'
			];
		}

		if (!is_array($data['categories']))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Categories must be an array'
			];
		}

		if (empty($data['categories']))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Categories array is empty'
			];
		}

		foreach ($data['categories'] as $category)
		{
			if (!array_key_exists('id', $category) || empty($category['id']))
			{
				return [
					'error_code'    => 400,
					'error_message' => 'There is no category id in array or it is empty'
				];
			}
		}

		return $data;
	}

	/**
	 * Check data for core_course_update_courses Moodle REST API method
	 *
	 * @param   array  $data  Data for core_course_update_courses method
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.1.0
	 */
	private function core_course_update_courses(array $data = []): array
	{
		if (empty($data) || !in_array('courses', $data))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'There is no courses key in options array specified'
			];
		}

		if (!is_array($data['courses']))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Categories must be an array'
			];
		}

		if (empty($data['courses']))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Categories array is empty'
			];
		}

		foreach ($data['courses'] as $course)
		{
			if (!array_key_exists('id', $course) || empty($course['id']))
			{
				return [
					'error_code'    => 400,
					'error_message' => 'There is no course id in array or it is empty'
				];
			}
		}

		return $data;
	}

	/**
	 * Check data for core_course_view_course Moodle REST API method
	 *
	 * @param   array  $data  Data for core_course_view_course method
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.1.0
	 */
	private function core_course_view_course(array $data = []): array
	{
		if (empty($data)
			|| !in_array('courseid', $data)
		)
		{
			return [
				'error_code'    => 400,
				'error_message' => 'There is no required courseid keys in options array specified'
			];
		}

		if (empty($data['courseid']))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Courseid is empty'
			];
		}

		return $data;
	}
}