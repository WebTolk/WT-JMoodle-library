<?php

/**
 * @package       WT JMoodle Library
 * @version       1.0.0
 * @Author        Sergey Tolkachyov, https://web-tolk.ru
 * @сopyright (c) December 2023 Sergey Tolkachyov. All rights reserved.
 * @license       GNU/GPL3 http://www.gnu.org/licenses/gpl-3.0.html
 * @since         1.0.0
 */

namespace Webtolk\JMoodle;
defined('_JEXEC') or die;

use Webtolk\JMoodle\Interfaces\MethodhelperInterface;
use Exception;
use Joomla\CMS\Cache\Cache;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\LibraryHelper;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Http\HttpFactory;
use Joomla\CMS\Uri\Uri;
use Joomla\Registry\Registry;

use Webtolk\JMoodle\JMoodleClientException;
use Joomla\CMS\Date\Date;
use Joomla\CMS\Log\Log;

class JMoodle
{

	/**
	 * @var string Moodle webservice token
	 * @since 1.0.0
	 */
	private static $moodle_token = '';

	/**
	 * @var string Moodle host
	 * @since 1.0.0
	 */
	private static $moodle_host = '';


	/**
	 *
	 * @param   string  $function        Moodle REST API method
	 * @param   array   $data            array of data for Moodle REST API
	 * @param   string  $request_method  HTTP method: GET or POST
	 * @param   string  $custom_path     Custom url path instead <code>/webservice/rest/server.php</code>
	 *
	 * @return mixed array if Moodle REST API method used or \Joomla\CMS\Http\Response for customRequest() method
	 *
	 * @since 1.0.0
	 */
	private static function getResponse(string $function = '', array $data = [], string $request_method = 'POST', string $custom_path = '', array $curl_options = []): mixed
	{
		/**
		 * Check if the library system plugin is enabled and credentials data are filled
		 */
		if (!self::canDoRequest())
		{
			return [
				'error_code'    => 400,
				'error_message' => 'JMoodle library can\'t do request. See logs for more details.'
			];
		}

		// Setup host
		$request_uri = new Uri;
		$request_uri->setHost(self::getMoodleHost());
		// Work only via https
//			$request_uri->setScheme('https');
		// Setup REST API url
		if (!empty($custom_path) && $function == 'wt_jmoodle_custom_request_function')
		{
			$request_uri->setPath($custom_path);
		}
		else
		{
			$request_uri->setPath('/webservice/rest/server.php');
		}

		// Set token
		$request_uri->setVar('wstoken', self::getMoodleToken());
		if (!empty($function))
		{
			// Set the Moodle REST API method
			$request_uri->setVar('wsfunction', $function);
		}

		$request_uri->setVar('moodlewsrestformat', 'json');

		$options = new Registry();
		
		if(count($curl_options) > 0)
		{
			$options->set(
				'transport.curl',$curl_options);
		}

		$http = (new HttpFactory)->getHttp($options, ['curl', 'stream']);

		if ($request_method != 'GET')
		{
			$request_method = strtolower($request_method);
			// $url, $data, $headers, $timeout

			$response = $http->$request_method($request_uri, $data);
		}
		else
		{
			if (!empty($data) && is_array($data))
			{
				$request_uri->setQuery($data);

			}

			// $url, $headers, $timeout
			$response = $http->get($request_uri);
		}

		/**
		 * Don't check errors etc for custom request. Return raw $response
		 */
		if ($function == 'wt_jmoodle_custom_request_function')
		{
			return $response;
		}
		// Check the errors and make a human friendly message if errors are exists
		$response = self::responseHandler($response, $function);

		return $response;


	}

	/**
	 * Check if JMoodle library can do a request to Moodle via REST API
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public static function canDoRequest(): bool
	{

		if (PluginHelper::isEnabled('system', 'wtjmoodle'))
		{
			if (empty(self::getMoodleHost()))
			{
				self::saveToLog('Plugin System - WT JMoodle: there is no credentials data: no Moodle host specified. You can set it in WT JMoodle plugin params or via setMoodleHost() method', 'ERROR');

				return false;
			}

			if (empty(self::getMoodleToken()))
			{
				self::saveToLog('Plugin System - WT JMoodle: there is no credentials data: no token specified. You can set it in WT JMoodle plugin params or via setMoodleToken() method', 'ERROR');

				return false;
			}

			// All OK
			return true;
		}

		self::saveToLog('Plugin System - WT JMoodle disabled', 'WARNING');

		return false;

	}

	/**
	 * Log and enqueue mesesage.
	 * Function for to log library errors in lib_webtolk_jmoodle.log.php in
	 * Joomla log path. Default Log category lib_webtolk_jmoodle
	 *
	 * @param   string  $data      error message
	 * @param   string  $priority  Joomla Log priority
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public static function saveToLog(string $data, string $priority = 'NOTICE'): void
	{
		Log::addLogger(
			array(
				// Sets file name
				'text_file' => 'lib_webtolk_jmoodle.log.php',
			),
			// Sets all but DEBUG log level messages to be sent to the file
			Log::ALL & ~Log::DEBUG,
			array('lib_webtolk_jmoodle')
		);
		Factory::getApplication()->enqueueMessage($data, $priority);
		$priority = 'Log::' . $priority;
		Log::add($data, $priority, 'lib_webtolk_jmoodle');
	}

	/**
	 * Handle the Moodle REST API errors
	 *
	 * @param $response object
	 *
	 * @return object
	 * @since 1.0.0
	 */
	public static function responseHandler($response, $calling_function = ''): array
	{
		// We need in object here
		$body = (new Registry($response->body))->toArray();

		if (array_key_exists('exception', $body) ||
			array_key_exists('errorcode', $body) ||
			array_key_exists('message', $body))
		{

			self::saveToLog('JMoodle library. ' . (!empty($calling_function) ? $calling_function . ':' : '') . $body['exception'] . ' ' . $body['errorcode'] . ' ' . $body['message'], 'ERROR');

			return [
				'error_code'    => 400,
				'error_message' => 'JMoodle. ' . (!empty($calling_function) ? $calling_function . ':' : '') . $body['exception'] . ' ' . $body['errorcode'] . ' ' . $body['message']
			];

		}

		return $body;

	}

	/**
	 * Check have we moodle user id for this joomla user id?
	 *
	 * @param   int  $joomla_user_id
	 *
	 * @return mixed (bool) false or (int) moodle user id
	 *
	 * @since 1.0.0
	 */
	public static function checkIsMoodleUser(int $joomla_user_id): mixed
	{
		$db    = Factory::getContainer()->get('DatabaseDriver');
		$query = $db->getQuery(true)
			->select($db->quoteName('moodle_user_id'))
			->from($db->quoteName('#__lib_jmoodle_users_sync'))
			->where($db->quoteName('joomla_user_id') . ' = ' . $db->quote($joomla_user_id));
		$db->setQuery($query);
		//Get single result
		$moodle_user_id = $db->loadResult();
		if (!empty($moodle_user_id))
		{
			return (int) $moodle_user_id;
		}

		return false;
	}

	/**
	 * Check have we joomla user id for this moodle user id?
	 *
	 * @param   int  $moodle_user_id
	 *
	 * @return mixed (bool) false or (int) joomla user id
	 *
	 * @since 1.0.0
	 */
	public static function checkIsJoomlaUser(int $moodle_user_id): mixed
	{
		$db    = Factory::getContainer()->get('DatabaseDriver');
		$query = $db->getQuery(true)
			->select($db->quoteName('joomla_user_id'))
			->from($db->quoteName('#__lib_jmoodle_users_sync'))
			->where($db->quoteName('moodle_user_id') . ' = ' . $db->quote($moodle_user_id));
		$db->setQuery($query);
		//Get single result
		$joomla_user_id = $db->loadResult();
		if (!empty($joomla_user_id))
		{
			return (int) $joomla_user_id;
		}

		return false;
	}

	/**
	 * Add new joomla user id to moodle user id mapping
	 *
	 * @param   int  $moodle_user_id
	 *
	 * @return bool True or false
	 *
	 * @since 1.0.0
	 */
	public static function addJoomlaMoodleUserSync(int $joomla_user_id, int $moodle_user_id): bool
	{
		$db    = Factory::getContainer()->get('DatabaseDriver');
		$query = $db->getQuery(true)
			->insert($db->quoteName('#__lib_jmoodle_users_sync'))
			->columns([$db->quoteName('joomla_user_id'), $db->quoteName('moodle_user_id')])
			->values(implode(',', [$db->quote($joomla_user_id), $db->quote($moodle_user_id)]));
		$db->setQuery($query);

		return (bool) $db->execute();
	}

	/**
	 * Delete Joomla & Moodle user ids mapping. Batch method.
	 *
	 * <b>Specify Joomla users ids OR Moodle users ids</b>
	 * <ul>
	 * <li>If Joomla user id specified - delete by Joomla user id.</li>
	 * <li>If Moodle user id specified - delete by Moodle user id.</li>
	 * <li>If both user ids specified - Reuqest will not be executed</li>
	 * </ul>
	 *
	 * @param   array  $joomla_user_ids  Plain array of Joomla users ids like [1, 2, 3, 4...]
	 * @param   array  $moodle_user_ids  Plain array of Moodle users ids like [1, 2, 3, 4...]
	 *
	 * @return bool True or false
	 *
	 * @since 1.0.0
	 */
	public static function removeJoomlaMoodleUserSync(array $joomla_user_ids = [], array $moodle_user_ids = []): bool
	{

		if (count($joomla_user_ids) > 0 && count($moodle_user_ids) > 0)
		{

			self::saveToLog(__FUNCTION__ . ': there are both Joomla user ids and Moodle user ids specified. Please, specify only one of them for correct deleting users ids mapping from database.', 'error');

			return false;
		}

		$db    = Factory::getContainer()->get('DatabaseDriver');
		$query = $db->getQuery(true);
		$query->delete($db->quoteName('#__lib_jmoodle_users_sync'));

		// delete by Joomla user id
		if (count($joomla_user_ids) > 0 && count($moodle_user_ids) < 1)
		{
			$conditions = [
				$db->quoteName('joomla_user_id') . ' IN (' . implode(',', $joomla_user_ids) . ')'
			];
		}
		elseif (count($moodle_user_ids) > 0 && count($joomla_user_ids) < 1)    // delete by Moodle user id
		{
			$conditions = [
				$db->quoteName('moodle_user_id') . ' IN (' . implode(',', $moodle_user_ids) . ')'
			];
		}

		$query->where($conditions);
		$db->setQuery($query);

		return (bool) $db->execute();
	}


	/**
	 * @param   string  $method  Moodle REST API method
	 * @param   array   $data    data for Moodle REST API method
	 *
	 * @return array
	 *
	 * @since 1.0.0
	 */
	public function request(string $method = '', array $data = [])
	{
		$method_helper = $this->getMethodHelperClass($method);
		$data          = $method_helper->checkData($method, $data);

		// If check is not passed - don't do request
		if (array_key_exists('error_code', $data) && !empty($data['error_code']))
		{
			self::saveToLog('JMoodle library. ' . (!empty($method) ? $method . ':' : '') . implode(' ', $data), 'ERROR');

			return $data;
		}

		$result = self::getResponse($method, $data, 'POST');

		return $result;
	}


	/**
	 * The method allows you to make requests to your own URL in Moodle
	 * Method returns a raw Joomla HTTP object without post-processing.
	 *
	 * <code>
	 *     $result = $moodle->customRequest(...)
	 *     $response = json_decode($result->body);
	 * </code>
	 *
	 * @param   string  $path    Path for url like <code>/auth/jmoodle/jmoodle_login.php</code>
	 * @param   array   $data    Data for your request
	 * @param   string  $method  HTTP method like POST or GET
	 * @param   array  $curl_options  Array with cURL options like $curl_options[CURLOPT_SSL_VERIFYHOST] = false;
	 *
	 * @return \Joomla\CMS\Http\Response
	 *
	 * @since 1.0.0
	 */
	public function customRequest(string $path = '', array $data = [], string $method = 'POST', array $curl_options = [])
	{

		$result = self::getResponse('wt_jmoodle_custom_request_function', $data, $method, $path, $curl_options);

		return $result;
	}

	/**
	 * Get method helper class for method
	 *
	 * @param   string  $method
	 *
	 * @return \Webtolk\JMoodle\Interfaces\MethodhelperInterface
	 *
	 * @since 1.0.0
	 */
	private function getMethodHelperClass(string $method): MethodhelperInterface
	{

        /**
         * @todo RENAME THE HELPER CLASS self because it is reserved. Do the condition here
         */
		$arr           = explode('_', $method);
		$name_1        = ucfirst($arr[0]);
		$name_2        = ucfirst($arr[1]);
		$classname     = $name_1 . "\\" . $name_2 . "\\" . $name_2;
		$method_helper = __NAMESPACE__ . "\\Helper\\" . $classname;

		return new $method_helper;
	}

	/**
	 * Get Moodle web service token from pluyin settings
	 *
	 * @return string
	 *
	 * @since 1.0.0
	 */
	public static function getMoodleToken(): string
	{
		if (empty(self::$moodle_token))
		{
			$plugin = PluginHelper::getPlugin('system', 'wtjmoodle');
			$params = json_decode($plugin->params);

			self::$moodle_token = $params->moodle_token;
		}

		return self::$moodle_token;
	}

	/**
	 * Set Moodle web service token from pluyin settings
	 *
	 * @param   string  $moodle_token
	 *
	 *
	 * @since 1.0.0
	 */
	public static function setMoodleToken(string $moodle_token): void
	{
		self::$moodle_token = $moodle_token;
	}

	/**
	 * Get Moodle host from pluyin settings
	 *
	 * @since 1.0.0
	 */
	public static function getMoodleHost(): string
	{
		if (empty(self::$moodle_host))
		{
			$plugin = PluginHelper::getPlugin('system', 'wtjmoodle');
			$params = json_decode($plugin->params);

			self::$moodle_host = $params->moodle_host;
		}

		return self::$moodle_host;
	}

	/**
	 * Set Moodle host from pluyin settings
	 *
	 * @param   string  $moodle_host
	 *
	 *
	 * @since 1.0.0
	 */
	public static function setMoodleHost(string $moodle_host): void
	{
		self::$moodle_host = $moodle_host;
	}

}