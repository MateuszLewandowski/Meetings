<?php 

namespace App\Core\Factory\Requests;

use App\Core\Factory\Request\RequestFactoryInterface;
use App\Requests\RegisterParticipantForAMeetingRequest;

final class CreateMeetingRequestFactory implements RequestFactoryInterface
{
    public static function getInstance(): callable {
        return function(array $query, array $request, array $attributes, array $cookies, array $files, array $server, $content) {
            return new RegisterParticipantForAMeetingRequest($query, $request, $attributes, $cookies, $files, $server, $content);
        };
    }
}