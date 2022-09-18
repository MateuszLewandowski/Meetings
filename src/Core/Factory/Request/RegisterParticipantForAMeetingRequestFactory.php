<?php 

namespace App\Core\Factory\Request;

use App\Core\Factory\Request\RequestFactoryInterface;
use App\Http\Request\RegisterParticipantForAMeetingRequest;

final class RegisterParticipantForAMeetingRequestFactory implements RequestFactoryInterface
{
    public static function getInstance(): callable {
        return function(array $query, array $request, array $attributes, array $cookies, array $files, array $server, $content) {
            return new RegisterParticipantForAMeetingRequest($query, $request, $attributes, $cookies, $files, $server, $content);
        };
    }
}