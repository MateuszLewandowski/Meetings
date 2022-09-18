<?php 

namespace App\Core\Factory\Request;

use App\Core\Factory\Request\RequestFactoryInterface;
use App\Http\Request\CreateUserRequest;

final class CreateUserRequestFactory implements RequestFactoryInterface
{
    public static function getInstance(): callable {
        return function(array $query, array $request, array $attributes, array $cookies, array $files, array $server, $content) {
            return new CreateUserRequest($query, $request, $attributes, $cookies, $files, $server, $content);
        };
    }
}