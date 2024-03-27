<?php
/**
 * @package       WT JMoodle Library
 * @version       1.1.0
 * @Author        Sergey Tolkachyov, https://web-tolk.ru
 * @сopyright (c) March 2024 Sergey Tolkachyov. All rights reserved.
 * @license       GNU/GPL3 http://www.gnu.org/licenses/gpl-3.0.html
 * @since         1.0.0
 */

namespace Webtolk\JMoodle\Helper\Core\Webservice;

use Webtolk\JMoodle\Interfaces\MethodhelperInterface;

defined('_JEXEC') or die;

class Webservice implements MethodhelperInterface
{
    public function checkData(string $method, array $data = []): array
    {
        return $data;
    }
}