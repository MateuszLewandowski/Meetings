<?php 

namespace Meetings\Tests\Unit\Meeting;

use App\Core\Factory\MeetingFactory;
use App\Core\Factory\UserFactory;
use App\Entity\User;
use DateTime;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;

final class CreateMeetingFactoryTest extends TestCase
{
    private const DATE_FORMAT = 'Y-m-d H:i';

    private static function getArgs() {
        return [
            'email' => 'example@test.com',
            'name' => 'Example meeting',
            'date' => new DateTime(),
            'participants_limit' => 50,
        ];
    }

    private static function getMeeting(array $args) {
        $userFactory = new UserFactory;
        $meetingFactory = new MeetingFactory;
        return $meetingFactory->create([
            'owner' => $userFactory->create([
                'email' => $args['email']
            ]),
            'name' => $args['name'],
            'date' => $args['date'],
            'participants_limit' => $args['participants_limit'],
        ]);
    }
    
    public function testCreateMeetingFactoryExpectsSuccess() 
    {
        $args = $this->getArgs();
        $meeting = $this->getMeeting($args);
        $this->assertInstanceOf(User::class, $meeting->getOwner());
        $this->assertInstanceOf(DateTime::class, $meeting->getDate());
        $this->assertTrue($meeting->getDate()->format(self::DATE_FORMAT) === $args['date']->format(self::DATE_FORMAT));
        $this->assertEquals($meeting->getParticipantsLimit(), $args['participants_limit']);
        $this->assertClassHasAttribute('email', $meeting->getOwner()::class);
        $this->assertIsString($meeting->getName());
        $this->assertIsInt($meeting->getParticipantsLimit());
        $this->assertInstanceOf(Collection::class, $meeting->getParticipants());
    }

    public function testCreateMeetingFactoryExpectsDateError() 
    {
        $this->expectError();
        $args = $this->getArgs();
        $args['date'] = 'err';
        $this->getMeeting($args);
    }

    public function testCreateMeetingFactoryExpectsParticipantsLimitError() 
    {
        $this->expectError();
        $args = $this->getArgs();
        $args['participants_limit'] = 'err';
        $this->getMeeting($args);
    }

    public function testCreateMeetingFactoryExpectsNameError() 
    {
        $this->expectError();
        $args = $this->getArgs();
        unset($args['name']);
        $this->getMeeting($args);
    }
}