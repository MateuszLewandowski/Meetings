<?php

namespace App\Controller\Meeting;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Core\Factory\Request\CreateMeetingRequestFactory;
use App\Controller\ActionableControllerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Http\Request\CreateMeetingRequest;
use App\Service\Meeting\CreateMeetingServiceInterface;
use InvalidArgumentException;
use Throwable;
use App\Http\DTO\MeetingDTO;

final class CreateMeetingController extends AbstractController implements ActionableControllerInterface
{
    private CreateMeetingRequest $request;

    public function __construct(
        private CreateMeetingServiceInterface $createMeetingService,
    ) {
        Request::setFactory(CreateMeetingRequestFactory::getInstance());
        $this->request = Request::createFromGlobals();
    }

    #[Route('/meeting/create', methods: ['POST'])]
    public function action(): Response {
        try {
            $this->request->validate();
            $meeting = $this->createMeetingService->add(
                owner_id: $this->request->owner_id,
                name: $this->request->name, 
                date: $this->request->date,
                participants_limit: $this->request->participants_limit,
            );
            return new Response(
                content: json_encode([
                    'message' => 'A new meeting has been created.',
                    'data' => $meeting->toDTO(),
                ]),
                status: Response::HTTP_CREATED,
                headers: ['Content-type' => 'application/json']
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
