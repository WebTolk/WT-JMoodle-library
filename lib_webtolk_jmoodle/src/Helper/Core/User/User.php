<?php
/**
 * @package       WT JMoodle Library
 * @version       1.0.2
 * @Author        Sergey Tolkachyov, https://web-tolk.ru
 * @сopyright (c) January 2024 Sergey Tolkachyov. All rights reserved.
 * @license       GNU/GPL3 http://www.gnu.org/licenses/gpl-3.0.html
 * @since         1.0.0
 */
namespace Webtolk\JMoodle\Helper\Core\User;

defined('_JEXEC') or die;

use Webtolk\JMoodle\Interfaces\MethodHelperInterface;

class User implements MethodhelperInterface
{
	public function checkData(string $method, array $data = []): array
	{
		return $this->$method($data);
	}

	/**
	 * Check data for core_user_create_users Moodle REST API method
	 *
	 * @param   array  $data  Users data for create in Moodle
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.0.0
	 */
	private function core_user_create_users(array $data = []): array
	{
		if (!array_key_exists('users', $data))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Empty users array specified'
			];
		}

		$users = $data['users'];

		if (count($users) < 1)
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Empty users array specified'
			];
		}

		foreach ($users as $user)
		{
			if (!array_key_exists('createpassword', $user) || $user['createpassword'] != 1)
			{
				if (!array_key_exists('password', $user) || empty($user['password']))
				{
					return [
						'error_code'    => 400,
						'error_message' => 'Invalid password: you must provide a password, or set createpassword.'
					];
				}
			}

			if (!array_key_exists('username', $user) ||
				!array_key_exists('email', $user) ||
				!array_key_exists('firstname', $user) ||
				!array_key_exists('lastname', $user) ||
				empty($user['username']) ||
				empty($user['email']) ||
				empty($user['firstname']) ||
				empty($user['lastname'])
			)
			{
				return [
					'error_code'    => 400,
					'error_message' => 'One of the required fields (username, email, firstname, lastname) for user which you are creating are not specified or empty'
				];
			}
		}

		return $data;
	}


	/**
	 * Check data for core_user_update_users Moodle REST API method
	 *
	 * @param   array  $data  Users data for update in Moodle
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.0.0
	 */
	private function core_user_update_users(array $data = []): array
	{
		if (!array_key_exists('users', $data))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Empty users array specified'
			];
		}

		$users = $data['users'];

		if (count($users) < 1)
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Empty users array specified'
			];
		}

		foreach ($users as $user)
		{
			if (!array_key_exists('id', $user) || empty($user['id']))
			{
				return [
					'error_code'    => 400,
					'error_message' => 'There is no user id specified for one of users you are updating'
				];
			}
		}

		return $data;
	}


	/**
	 * Check data for core_user_delete_users Moodle REST API method.
	 * <strong>intval() for each array item</strong>
	 *
	 * @param   array  $data  Plain array with Moodle users ids like [1, 2, 3, 4,...] etc.
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.0.0
	 */
	private function core_user_delete_users(array $data = []): array
	{
		if (!array_key_exists('userids', $data))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Empty userids array specified'
			];
		}

		$userids = $data['userids'];

		if (count($userids) < 1)
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Empty userids array specified'
			];
		}
		$data['userids'] = array_map('intval', $userids);

		return $data;
	}

	/**
	 * Check data and array structure for core_user_get_users Moodle REST API method.
	 *
	 * @param   array  $data  Array of arrays with like
	 *                        criteria[0][key]= 'firstname'
	 *                        criteria[0][value]= 'Name'
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.0.0
	 */
	private function core_user_get_users(array $data = []): array
	{
		if (!array_key_exists('criteria', $data))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Empty criteria array specified'
			];
		}

		$criterias = $data['criteria'];

		if (count($criterias) < 1)
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
					'error_message' => 'Erong criteria array structure or criteria is empty. It could be like criteria[0][key]= string, criteria[0][value]= string'
				];
			}
		}

		return $data;
	}

	/**
	 * Check data and array structure for core_user_get_users_by_field Moodle REST API method.
	 *
	 * @param   array  $data  Array of arrays with like
	 *                        field = 'firstname'
	 *                        values[0] = 'Name'
	 *                        values[1] = 'Name2'
	 *                        values[2] = 3
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.0.0
	 */
	private function core_user_get_users_by_field(array $data = []): array
	{
		if (!array_key_exists('field', $data))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Empty field parameter specified'
			];
		}

		if (!is_string($data['field']))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Field parameter is not type string. $data["field"] = (string) $field_name'
			];
		}

		if (!array_key_exists('values', $data))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Empty values parameter specified'
			];
		}

		if (!is_array($data['values']))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Values parameter is not array. $data["values"] = ["param_1", "param_2"]'
			];
		}

		$values = $data['values'];

		if (count($values) < 1)
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Empty values array specified'
			];
		}

		foreach ($values as $value)
		{
			if (
				!is_string($value) ||
				!is_int($value) ||
				!is_float($value)
			)
			{
				return [
					'error_code'    => 400,
					'error_message' => 'Wrong one of value type in values array. It could be string, integer or float'
				];
			}
		}

		return $data;
	}


	/**
	 * Check data for core_user_add_user_device Moodle REST API method
	 *
	 * @param   array  $data  Users data for create in Moodle
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 * @todo Add checking request data
	 * @since 1.0.0
	 */
	private function core_user_add_user_device(array $data = []): array
	{
		return $data;
	}

	/**
	 * Check data for core_user_add_user_private_files Moodle REST API method
	 *
	 * @param   array  $data  Users data for create in Moodle
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 * @todo Add checking request data
	 * @since 1.0.0
	 */
	private function core_user_add_user_private_files(array $data = []): array
	{
		return $data;
	}

	/**
	 * Check data for core_user_agree_site_policy Moodle REST API method
	 *
	 * @param   array  $data  Users data for create in Moodle
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 * @todo Add checking request data
	 * @since 1.0.0
	 */
	private function core_user_agree_site_policy(array $data = []): array
	{
		return $data;
	}

	/**
	 * Check data for core_user_get_course_user_profiles Moodle REST API method
	 *
	 * @param   array  $data  Users data for create in Moodle
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 * @todo Add checking request data
	 * @since 1.0.0
	 */
	private function core_user_get_course_user_profiles(array $data = []): array
	{
		return $data;
	}

	/**
	 * Check data for core_user_get_private_files_info Moodle REST API method
	 *
	 * @param   array  $data  Users data for create in Moodle
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 * @todo Add checking request data
	 * @since 1.0.0
	 */
	private function core_user_get_private_files_info(array $data = []): array
	{
		return $data;
	}

	/**
	 * Check data for core_user_get_user_preferences Moodle REST API method
	 *
	 * @param   array  $data  Users data for create in Moodle
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 * @todo Add checking request data
	 * @since 1.0.0
	 */
	private function core_user_get_user_preferences(array $data = []): array
	{
		return $data;
	}

	/**
	 * Check data for core_user_remove_user_device Moodle REST API method
	 *
	 * @param   array  $data  Users data for create in Moodle
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 * @todo Add checking request data
	 * @since 1.0.0
	 */
	private function core_user_remove_user_device(array $data = []): array
	{
		return $data;
	}

	/**
	 * Check data for core_user_search_identity Moodle REST API method
	 *
	 * @param   array  $data  Users data for create in Moodle
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.0.0
	 */
	private function core_user_search_identity(array $data = []): array
	{
		if(array_key_exists('query', $data)){
			return [
				'error_code'    => 400,
				'error_message' => 'There is no required query param'
			];
		}

		if(empty($data['query'])){
			return [
				'error_code'    => 400,
				'error_message' => 'There is empty query param'
			];
		}

		return $data;
	}

	/**
	 * Check data for core_user_set_user_preferences Moodle REST API method
	 *
	 * @param   array  $data  Users data for create in Moodle
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 * @todo Add checking request data
	 * @since 1.0.0
	 */
	private function core_user_set_user_preferences(array $data = []): array
	{
		return $data;
	}

	/**
	 * Check data for core_user_update_picture Moodle REST API method
	 *
	 * @param   array  $data  Users data for create in Moodle
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 * @todo Add checking request data
	 * @since 1.0.0
	 */
	private function core_user_update_picture(array $data = []): array
	{
		return $data;
	}

	/**
	 * Check data for core_user_update_user_device_public_key Moodle REST API method
	 *
	 * @param   array  $data  Users data for create in Moodle
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 * @todo Add checking request data
	 * @since 1.0.0
	 */
	private function core_user_update_user_device_public_key(array $data = []): array
	{
		return $data;
	}


	/**
	 * Check data for core_user_update_user_preferences Moodle REST API method
	 *
	 * @param   array  $data  Users data for create in Moodle
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 * @todo Add checking request data
	 * @since 1.0.0
	 */
	private function core_user_update_user_preferences(array $data = []): array
	{
		return $data;
	}

	/**
	 * Check data for core_user_view_user_list Moodle REST API method
	 *
	 * @param   array  $data  Users data for create in Moodle
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 * @todo Add checking request data
	 * @since 1.0.0
	 */
	private function core_user_view_user_list(array $data = []): array
	{
		return $data;
	}

	/**
	 * Check data for core_user_view_user_profile Moodle REST API method
	 *
	 * @param   array  $data  Users data for create in Moodle
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.0.0
	 */
	private function core_user_view_user_profile(array $data = []): array
	{
		if(array_key_exists('userid', $data)){
			return [
				'error_code'    => 400,
				'error_message' => 'There is no required userid param'
			];
		}

		if(empty($data['userid'])){
			return [
				'error_code'    => 400,
				'error_message' => 'There is empty userid param'
			];
		}

		if(is_int($data['userid'])){
			return [
				'error_code'    => 400,
				'error_message' => 'The userid param is not integer'
			];
		}

		return $data;
	}

}
