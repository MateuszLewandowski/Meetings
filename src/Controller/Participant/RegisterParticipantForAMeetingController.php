<?php

namespace App\Controller\Participant;

use App\Core\Factory\Request\RegisterParticipantForAMeetingRequestFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Http\Request\RegisterParticipantForAMeetingRequest;
use App\Controller\ActionableControllerInterface;
use App\Service\Participant\RegisterParticipantForAMeetingServiceInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use InvalidArgumentException;
use Throwable;

final class RegisterParticipantForAMeetingController extends AbstractController implements ActionableControllerInterface
{
    private RegisterParticipantForAMeetingRequest $request;

    public function __construct(
        private RegisterParticipantForAMeetingServiceInterface $RegisterParticipantForAMeetingService,
    ) {
        Request::setFactory(RegisterParticipantForAMeetingRequestFactory::getInstance());
        $this->request = Request::createFromGlobals();
    }

    #[Route('/meeting/participant/register', methods: ['POST'])]
    public function action(): Response {
        try {
            $this->request->validate();
            $this->RegisterParticipantForAMeetingService->add(
                meeting_id: $this->request->meeting_id,
                user_id: $this->request->user_id, 
            );
            return new Response(
                content: 'User signed up for an event.',
                status: Response::HTTP_OK
            );
        } catch (Throwable $e) {
            throw $e;
            return match (true) {
                $e instanceof InvalidArgumentException => new Response($e->getMessage(), $e->getCode()),
                default => new Response('Ooops, something went wrong.', Response::HTTP_INTERNAL_SERVER_ERROR)
            };
        }
    }
}
