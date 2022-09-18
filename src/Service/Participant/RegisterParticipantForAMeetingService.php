<?php 

namespace App\Service\Participant;

use App\Core\Factory\MeetingFactory;
use App\Core\Factory\UserFactory;
use App\Entity\Meeting;
use App\Entity\User;
use App\Notification\Email\SendMeetingOwnerNewParticipantSuccessEmail;
use App\Notification\Email\SendMeetingOwnerOvercrowdedFailure;
use App\Notification\Email\SendRegisteredParticipantSuccessEmail;
use App\Repository\MeetingRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class RegisterParticipantForAMeetingService implements RegisterParticipantForAMeetingServiceInterface
{
    public function __construct(
        private MeetingRepository $meetingRepository,
        private MeetingFactory $meetingFactory,
        private UserRepository $userRepository,
        private UserFactory $userFactory,
        private ManagerRegistry $doctrine,
        private SendRegisteredParticipantSuccessEmail $sendRegisteredParticipantSuccessEmail,
        private SendMeetingOwnerNewParticipantSuccessEmail $sendMeetingOwnerNewParticipantSuccessEmail,
        private SendMeetingOwnerOvercrowdedFailure $sendMeetingOwnerOvercrowdedFailure,
    ) {
    }

    public function add(int $meeting_id, int $user_id)
    {
        $this->doctrine->getConnection()->beginTransaction();
        try {
            $meeting = $this->getMeeting($meeting_id);
            $user = $this->getUser($user_id);
            $participants = $meeting->getParticipants();
            if ($participants->contains($user)) {
                throw new InvalidArgumentException('User is the event participant.');
            }
            if (count($participants) >= $meeting->getParticipantsLimit()) {
                $this->SendMeetingOwnerOvercrowdedFailure($meeting, $user);
                throw new InvalidArgumentException('The event has reached the limit of participants.');
            }
            $meeting->addParticipant(participant: $user);
            $user->addMeeting(meeting: $meeting);
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($meeting);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->sendMeetingOwnerNewParticipantSuccessEmail($meeting, $user);
            $this->SendRegisteredParticipantSuccessEmail($meeting, $user);
            $this->doctrine->getConnection()->commit();
        } catch (Throwable $e) {
            $this->doctrine->getConnection()->rollback();
            throw $e;
        }
    }

    private function sendMeetingOwnerNewParticipantSuccessEmail(Meeting $meeting, User $user): void {
        $this->sendMeetingOwnerNewParticipantSuccessEmail->send(
            meeting: $meeting,
            user: $user,
        );
    }

    private function SendRegisteredParticipantSuccessEmail(Meeting $meeting, User $user): void {
        $this->sendRegisteredParticipantSuccessEmail->send(
            meeting: $meeting,
            user: $user,
        );
    }

    private function SendMeetingOwnerOvercrowdedFailure(Meeting $meeting, User $user): void {
        $this->sendMeetingOwnerOvercrowdedFailure->send(
            meeting: $meeting,
            user: $user,
        );
    }

    

    protected function getUser(int $id): ?User {
        $user = $this->userRepository->findOneById(id: $id);
        return $user instanceof User
            ? $user
            : throw new InvalidArgumentException('User does not exists.', Response::HTTP_NOT_FOUND);
    }

    protected function getMeeting(int $id): ?Meeting {
        $meeting = $this->meetingRepository->findOneById(id: $id);
        return $meeting instanceof Meeting
            ? $meeting
            : throw new InvalidArgumentException('Meeting does not exists.', Response::HTTP_NOT_FOUND);
    }
}