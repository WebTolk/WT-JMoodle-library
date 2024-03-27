<?php
/**
 * @package       WT JMoodle Library
 * @version       1.1.0
 * @Author        Sergey Tolkachyov, https://web-tolk.ru
 * @сopyright (c) March 2024 Sergey Tolkachyov. All rights reserved.
 * @license       GNU/GPL3 http://www.gnu.org/licenses/gpl-3.0.html
 * @since         1.0.0
 */

namespace Joomla\Plugin\System\Wtjmoodle\Extension;
// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\LibraryHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Version;
use Joomla\Event\SubscriberInterface;
use Joomla\Registry\Registry;

class Wtjmoodle extends CMSPlugin implements SubscriberInterface
{
	protected $allowLegacyListeners = false;

	/**
	 * Returns an array of events this subscriber will listen to.
	 *
	 * @return  array
	 *
	 * @since   4.0.0
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			'onAfterInitialise' => 'onAfterInitialise',
			'onAjaxWtjmoodle'   => 'onAjaxWtjmoodle',
		];
	}

	public function onAfterInitialise(): void
	{
		/**
		 * @link https://github.com/joomla/joomla-cms/pull/39348
		 */
		if (!(new Version())->isCompatible('4.2.7'))
		{
			\JLoader::registerNamespace('Webtolk\JMoodle', JPATH_LIBRARIES . '/Webtolk/JMoodle/src');
		}

	}


	public function onAjaxWtjmoodle($event): void
	{

	}

}
