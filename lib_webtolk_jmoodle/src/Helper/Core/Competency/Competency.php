<?php
/**
 * @package       WT JMoodle Library
 * @version       1.0.3
 * @Author        Sergey Tolkachyov, https://web-tolk.ru
 * @сopyright (c) January 2024 Sergey Tolkachyov. All rights reserved.
 * @license       GNU/GPL3 http://www.gnu.org/licenses/gpl-3.0.html
 * @since         1.0.0
 */
namespace Webtolk\JMoodle\Helper\Core\Competency;

defined('_JEXEC') or die;
use Webtolk\JMoodle\Interfaces\MethodhelperInterface;

class Competency implements MethodhelperInterface
{
    public function checkData(string $method, array $data = []): array
    {
        return $data;
    }
} 
    
    
    
    