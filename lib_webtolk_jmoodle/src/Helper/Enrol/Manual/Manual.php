<?php
/**
 * @package       WT JMoodle Library
 * @version       1.0.0
 * @Author        Sergey Tolkachyov, https://web-tolk.ru
 * @сopyright (c) December 2023 Sergey Tolkachyov. All rights reserved.
 * @license       GNU/GPL3 http://www.gnu.org/licenses/gpl-3.0.html
 * @since         1.0.0
 */
namespace Webtolk\JMoodle\Helper\Enrol\Manual;

defined('_JEXEC') or die;
use Webtolk\JMoodle\Interfaces\MethodHelperInterface;

class Manual implements MethodhelperInterface
{
	public function checkData(string $method, array $data = []): array
	{
		return $this->$method($data);
	}

	/**
	 * Check data for enrol_manual_enrol_users Moodle REST API method
	 *
	 * @param   array  $data  Array of enrolments for Manual enrol users data
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.0.0
	 */
	private function enrol_manual_enrol_users (array $data) : array
	{
		if (!array_key_exists('enrolments', $data))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'There is no enrolments'
			];
		}

		$enrolments = $data['enrolments'];

		if (count($enrolments) < 1)
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Empty enrolments array specified'
			];
		}

		foreach ($enrolments as $enrolment)
		{
			if (!array_key_exists('roleid', $enrolment) ||
				!array_key_exists('userid', $enrolment) ||
				!array_key_exists('courseid', $enrolment) ||
				empty($enrolment['roleid']) ||
				empty($enrolment['userid']) ||
				empty($enrolment['courseid'])
			)
			{
				return [
					'error_code'    => 400,
					'error_message' => 'One of the required fields (roleid, userid, courseid) for enrolment are not specified or empty'
				];
			}
		}

		return $data;
	}

	/**
	 * Check data for enrol_manual_unenrol_users Moodle REST API method
	 *
	 * @param   array  $data  Array of unenrolments for Manual unenrol users
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.0.0
	 */
	private function enrol_manual_unenrol_users (array $data) : array
	{
		if (!array_key_exists('enrolments', $data))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'There is no unenrolments'
			];
		}

		$enrolments = $data['enrolments'];

		if (count($enrolments) < 1)
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Empty unenrolments array specified'
			];
		}

		foreach ($enrolments as $enrolment)
		{
			if (!array_key_exists('roleid', $enrolment) ||
				!array_key_exists('userid', $enrolment) ||
				empty($enrolment['roleid']) ||
				empty($enrolment['userid'])
			)
			{
				return [
					'error_code'    => 400,
					'error_message' => 'One of the required fields (roleid, userid) for unenrolment are not specified or empty'
				];
			}
		}

		return $data;
	}
} 
    
    
    
    