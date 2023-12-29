<?php
/**
 * @package       WT JMoodle Library
 * @version       1.0.0
 * @Author        Sergey Tolkachyov, https://web-tolk.ru
 * @сopyright (c) December 2023 Sergey Tolkachyov. All rights reserved.
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

class MoodlerestapimethodsField extends NoteField
{

	protected $type = 'Moodlerestapimethods';

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

		$html        = [];
		$collapsible = ($this->element['collapsible'] == 'true') || ($this->element['collapsible'] == '1') ? true : false;
		if ($collapsible)
		{
			$html[] = '<details>';
			$html[] = '<summary>
								Available functions <span class="badge bg-primary">' . (count($result_jmoodle['functions'])) . '</span>
							</summary>';
		}

		$html[] = '<ol class="list-group list-group-flush">';
		foreach ($result_jmoodle['functions'] as $function)
		{
			$html[] = '<li class="list-group-item d-flex"><span class="fw-bold">' . $function['name'] . '</span> <span class="ms-auto"><span class="ms-auto badge bg-info">version</span><span class="badge bg-primary">' . $function['version'] . '</span></span></li>';
		}

		$html[] = '</ol>';
		if ($collapsible)
		{
			$html[] = '</details>';
		}

		return implode('', $html);
	}
}

?>