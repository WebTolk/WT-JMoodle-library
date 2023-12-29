<?php
/**
 * @package     Webtolk\JMoodle\Interfaces\MethodhelperInterface
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Webtolk\JMoodle\Interfaces;

interface MethodhelperInterface
{
	/**
	 * Checking the data structure before sending a request to the Moodle REST API.
	 * Returns $data if check is success or array with error info like ['error_code' => 400, 'error_message' => 'Here is an error description']
	 *
	 * @param string $method Moodle REST API method name.
	 * @param array $data All data for request combined into array
	 * @return array
	 *
	 * @since 1.0.0
	 */
	public function checkData(string $method, array $data = []) : array;
}