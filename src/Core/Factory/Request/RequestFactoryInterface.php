<?php 

namespace App\Core\Factory\Request;

interface RequestFactoryInterface
{
    public static function getInstance(): callable;
}