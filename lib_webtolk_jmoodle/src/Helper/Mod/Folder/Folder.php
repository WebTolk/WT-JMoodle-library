<?php
namespace Webtolk\JMoodle\Helper\Mod\Folder;

defined('_JEXEC') or die;
use Webtolk\JMoodle\Interfaces\MethodHelperInterface;

class Folder implements MethodhelperInterface
{
    public function checkData(string $method, array $data = []): array
    {
        return $data;
    }
} 
    
    
    
    