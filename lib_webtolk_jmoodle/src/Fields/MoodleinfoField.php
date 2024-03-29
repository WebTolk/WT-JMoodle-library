<?php
/**
 * @package       WT JMoodle Library
 * @version       1.1.0
 * @Author        Sergey Tolkachyov, https://web-tolk.ru
 * @сopyright (c) March 2024 Sergey Tolkachyov. All rights reserved.
 * @license       GNU/GPL3 http://www.gnu.org/licenses/gpl-3.0.html
 * @since         1.0.0
 */

namespace Webtolk\JMoodle\Fields;
defined('_JEXEC') or die;

use Joomla\CMS\Date\Date;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\NoteField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\PluginHelper;
use Webtolk\JMoodle\JMoodle;

class MoodleinfoField extends NoteField
{

	protected $type = 'Moodleinfo';

	protected function getInput()
	{

		$moodle = new JMoodle();
		if (!$moodle::canDoRequest())
		{
			return '';
		}

		$result_jmoodle = $moodle->request('core_webservice_get_site_info');
		if (count($result_jmoodle) == 0)
		{
			return '<div class="alert alert-danger row">
						<div class="col-2 h1">400</div>
						<div class="col-10">There is no Moodle host response</div>
					</div>';
		}
		if (isset($result_jmoodle['error_code']) && !empty($result_jmoodle['error_code']))
		{
			return '<div class="alert alert-danger row">
						<div class="col-2 h1">' . $result_jmoodle['error_code'] . '</div>
						<div class="col-10">' . $result_jmoodle['error_message'] . '</div>
					</div>';
		}

		if (!array_key_exists('sitename', $result_jmoodle) || empty($result_jmoodle['sitename']))
		{
			return '<div class="alert alert-danger row">
						<div class="col-2 h1">400</div>
						<div class="col-10">Moodle return wrong response</div>
					</div>';
		}

		$html   = [];
		$html[] = '<div class="row mx-0 shadow p-4">';
		$html[] = '<div class="col-4 col-md-2 col-xl-2">
						<svg id="Layer_1" width="180" class="img-fluid" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 4531.67 1110.96"><defs><style>.cls-1{fill:#f27f22;}.cls-2{fill:#333;}</style></defs><path class="cls-1" d="M1092,1134.2V753.1q0-119.51-98.72-119.52T894.51,753.1v381.1h-194V753.1q0-119.51-97-119.52-98.75,0-98.71,119.52v381.1h-194V730.61q0-124.73,86.61-188.79,76.23-57.18,206.13-57.21,131.68,0,194,67.57,53.68-67.57,195.76-67.57,129.91,0,206.11,57.21,86.6,64,86.61,188.79V1134.2Z" transform="translate(-72.51 -43.75)"/><path class="cls-1" d="M3469.79,1132.43V43.75H3664V1132.43Z" transform="translate(-72.51 -43.75)"/><path class="cls-1" d="M3222.33,1132.43v-64.14q-26.05,34.65-88.41,55.47-55.53,19-105.77,19.06-138.69,0-222.74-95.36t-84.1-235.75c0-92.42,27.29-170,82.37-232.29,48.74-55.11,128-93.61,219.28-93.61,102.83,0,162.9,38.61,199.37,83.2V43.75h189V1132.43Zm0-362.31q0-52-49.43-99.7t-101.4-47.69q-74.53,0-117.88,60.69-38.14,53.8-38.13,131.74,0,76.32,38.13,130,43.34,62.46,117.88,62.41,45.06,0,97.94-42.46t52.89-87.54Z" transform="translate(-72.51 -43.75)"/><path class="cls-1" d="M2355.58,1149.76q-147.39,0-243.57-93.63t-96.24-241q0-147.28,96.24-240.94t243.57-93.62q147.3,0,244.42,93.62t97.09,240.94q0,147.42-97.09,241T2355.58,1149.76Zm0-513.14q-70.22,0-107.93,53.15T2210,816.07q0,73.14,35.06,123.68,40.35,57.5,110.54,57.44t110.51-57.44q36.82-50.5,36.82-123.68t-35.06-123.68Q2427.46,636.62,2355.58,636.62Z" transform="translate(-72.51 -43.75)"/><path class="cls-1" d="M1653.5,1149.76q-147.35,0-243.57-93.63t-96.21-241q0-147.28,96.21-240.94T1653.5,480.6q147.3,0,244.45,93.62T1995,815.16q0,147.42-97.06,241T1653.5,1149.76Zm0-513.14q-70.17,0-107.93,53.15t-37.7,126.3q0,73.14,35.06,123.68,40.35,57.5,110.57,57.44T1764,939.75q36.83-50.5,36.85-123.68t-35.09-123.68Q1725.42,636.62,1653.5,636.62Z" transform="translate(-72.51 -43.75)"/><path class="cls-1" d="M3893.91,874.13c4.15,46.23,64.23,145.6,163,145.6,96.14,0,141.61-55.5,143.87-78L4405.34,940c-22.31,68.24-113,213.25-352,213.25-99.4,0-190.35-30.94-255.68-92.75s-97.93-142.42-97.93-241.84q0-154.27,97.93-245.28t254-91q169.87,0,265.21,112.68,88.44,104,88.44,279.1ZM4211.16,754.5q-12.14-62.43-52-102.28-45.15-43.31-104-43.34-60.72,0-101.43,41.6t-54.58,104Z" transform="translate(-72.51 -43.75)"/><path class="cls-2" d="M687.6,455.27l192.72-140.7-2.48-8.6C530.2,348.49,372,378.7,72.51,552.3l2.77,7.9,23.82.22c-2.21,24-6,83.24-1.12,172.33-33.24,96.18-.85,161.52,29.56,232.56,4.8-74,4.3-154.91-18.4-235.46-4.72-88.47-.82-146.68,1.31-169.32L309,562.43s-1.31,60,5.88,116.41c177.43,62.34,355.85-.22,450.51-153.9C739.17,495.47,687.6,455.27,687.6,455.27Z" transform="translate(-72.51 -43.75)"/><path class="cls-2" d="M4457.41,1154.71h-17.29v-86.46h-29.39v-14.9h76.34v14.9h-29.66Zm84.36,0-28.27-78.58h-1.12l.85,18.13v60.45h-16.74V1053.35h26.3l27.55,78.59,28.53-78.59h25.31v101.36h-17.85v-59l.85-19.27h-1.13l-29.66,78.31Z" transform="translate(-72.51 -43.75)"/></svg>
					</div>';
		$html[] = '<div class="col">';
		$html[] = '<h4>' . $result_jmoodle['sitename'] . '</h4>';
		$html[] = '<p>Moodle ' . $result_jmoodle['release'] . '</p>';
		$html[] = '<p><a href="' . $result_jmoodle['siteurl'] . '" target="_blank">' . $result_jmoodle['siteurl'] . '</a></p>';
		$html[] = '</div>';
		$html[] = '<div class="col-4 col-md-2 col-xl-2">';

		$html[] = '<img src="' . $result_jmoodle['userpictureurl'] . '" class="img-fluid img-thumbnail" alt="' . $result_jmoodle['firstname'] . ' ' . $result_jmoodle['lastname'] . '"/>';
		$html[] = '</div>';
		$html[] = '<div class="col">';
		$html[] = '<hgroup>';
		$html[] = '<h4>' . $result_jmoodle['firstname'] . ' ' . $result_jmoodle['lastname'] . '</h4>';
		$html[] = '</hgroup>';
		$html[] = '<span class="badge bg-dark">User id</span><span class="badge bg-success">' . $result_jmoodle['userid'] . '</span> ';
		$html[] = '<span class="badge bg-dark">Username</span><span class="badge bg-success">' . $result_jmoodle['username'] . '</span>';
		$html[] = '</div></div>';

		return implode('', $html);
	}
}

?>