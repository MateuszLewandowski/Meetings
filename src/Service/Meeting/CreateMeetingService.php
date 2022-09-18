<?php 

namespace App\Service\Meeting;

use App\Core\Factory\MeetingFactory;
use App\Entity\Meeting;
use App\Entity\User;
use App\Repository\MeetingRepository;
use App\Repository\UserRepository;
use Throwable;
use DateTime;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

final class CreateMeetingService implements CreateMeetingServiceInterface
{
    public function __construct(
        private MeetingRepository $meetingRepository,
        private MeetingFactory $meetingFactory,
        private UserRepository $userRepository,
    ) {
    }

    public function add(int $owner_id, string $name, string $date, int $participants_limit): ?Meeting
    {
        try {
            $owner = $this->getUser($owner_id);
            if ($this->meetingRepository->checkIfUserHasAnotherEventAtTheTime($owner, $date)) {
                throw new InvalidArgumentException('The user is already running another event at the given time.', Response::HTTP_BAD_REQUEST);
            }
            $meeting = $this->meetingFactory->create(
                arguments: [
                    'owner' => $owner,
                    'name' => $name,
                    'date' => new DateTime($date),
                    'participants_limit' => $participants_limit
                ],
            );
            $this->meetingRepository->add(
                entity: $meeting, flush: true
            );
            return $meeting;
        } catch (Throwable $e) {
            throw $e;
        }
    }

    protected function getUser(int $id): ?User {
        $user = $this->userRepository->findOneById(id: $id);
        return $user instanceof User
            ? $user
            : throw new InvalidArgumentException('User does not exists.', Response::HTTP_NOT_FOUND);
    }
}