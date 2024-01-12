<?php
/**
 * @package       WT JMoodle Library
 * @version       1.0.2
 * @Author        Sergey Tolkachyov, https://web-tolk.ru
 * @сopyright (c) January 2024 Sergey Tolkachyov. All rights reserved.
 * @license       GNU/GPL3 http://www.gnu.org/licenses/gpl-3.0.html
 * @since         1.0.0
 */
namespace Webtolk\JMoodle\Interfaces;

defined('_JEXEC') or die;
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