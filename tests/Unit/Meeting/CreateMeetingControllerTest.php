<?php 

namespace Meetings\Tests\Unit\Meeting;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

final class CreateMeetingControllerTest extends WebTestCase
{
    private static function getArgs() {
        return [
            [
                'name' => 'owner_id',
                'contents' => 1,
            ],
            [
                'name' => 'name',
                'contents' => 'Example',
            ],
            [
                'name' => 'date',
                'contents' => '2022-10-05 10:10',
            ],
            [
                'name' => 'participants_limit',
                'contents' => 5,
            ],
        ];
    }

    private static function UserCreateAPICall(string $email): ResponseInterface {
        return (new Client)->post('http://127.0.0.1:42961/user/create', [
            'multipart' => 
            [
                [
                    'name' => 'email',
                    'contents' => $email,
                ],
            ],
        ]);
    }

    public function testCreateMeetingFactoryExpectsSuccess() 
    {
        $args = $this->getArgs();
        $userCreateResponse = self::UserCreateAPICall($this->generateRandomEmail());
        $userCreateContent = json_decode($userCreateResponse->getBody()->getContents());
        $args[0]['contents'] = $userCreateContent->data->id;
        $meetingCreateResponse = (new Client)->post('http://127.0.0.1:42961/meeting/create', [
            'multipart' => $args,
        ]);
        $meetingCreateContent = json_decode($meetingCreateResponse->getBody()->getContents());
        $this->assertTrue($meetingCreateContent->message === 'A new meeting has been created.');
        $meeting = &$meetingCreateContent->data;
        $this->assertIsObject($meeting);
        $this->assertIsObject($meeting->owner);
        $this->assertIsInt($meeting->id);
        $this->assertIsInt($meeting->owner->id);
        $this->assertIsArray($meeting->participants);
        $this->assertIsArray($meeting->owner->meetings);
    }

    private function generateRandomEmail($length = 15) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $len = strlen($characters);
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[rand(0, $len - 1)];
        }
        return $string . '@example.com';
    }
}