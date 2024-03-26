<?php
/**
 * @package       WT JMoodle Library
 * @version       1.0.3
 * @Author        Sergey Tolkachyov, https://web-tolk.ru
 * @сopyright     January 2024 Sergey Tolkachyov. All rights reserved.
 * @license       GNU/GPL3 http://www.gnu.org/licenses/gpl-3.0.html
 * @since         1.0.0
 */

namespace Webtolk\JMoodle\Fields;
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Form\Field\ListField;
use Webtolk\JMoodle\JMoodle;


class CourseslistField extends ListField
{

	protected $type = 'Courseslist';

	protected function getOptions()
	{
		$requset_options = [];
		$options         = [];

		if (isset($this->element['course_ids']) && !empty($this->element['course_ids']))
		{
			$course_ids                        = (string) $this->element['course_ids'];
			$course_ids                        = explode(',', $course_ids);
			$requset_options['options']['ids'] = $course_ids;
		}

		$moodle = new JMoodle();
		if (!$moodle::canDoRequest())
		{
			return $requset_options;
		}

		$result = $moodle->request('core_course_get_courses', $requset_options);

		if (array_key_exists('error_code', $result))
		{
			$options[] = HTMLHelper::_('select.option', '-1', 'Can\'t fetch courses from Moodle', ['disable' => true]);
		}
		elseif (count($result) == 0)
		{
			$options[] = HTMLHelper::_('select.option', '-1', 'There is no courses in Moodle', ['disable' => true]);
		}
		else
		{
			foreach ($result as $course)
			{
				$options[] = HTMLHelper::_('select.option', $course['id'], $course['fullname'] . ' (id: ' . $course['id'] . ')');
			}
		}

		return $options;
	}
}