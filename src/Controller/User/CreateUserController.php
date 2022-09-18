<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Core\Factory\Request\CreateUserRequestFactory;
use App\Controller\ActionableControllerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\Request;
use App\Http\Request\CreateUserRequest;
use App\Service\User\CreateUserServiceInterface;
use InvalidArgumentException;
use Throwable;

final class CreateUserController extends AbstractController implements ActionableControllerInterface
{
    private CreateUserRequest $request;

    public function __construct(
        private CreateUserServiceInterface $createUserService,
    ) {
        Request::setFactory(CreateUserRequestFactory::getInstance());
        $this->request = Request::createFromGlobals();
    }

    #[Route('/user/create', methods: ['POST'])]
    public function action(): Response {
        try {
            $this->request->validate();
            $user = $this->createUserService->add(
                name: $this->request->name, 
                email: $this->request->email
            );
            return new Response(
                content: json_encode([
                    'message' => 'A new user has been created.',
                    'data' => $user->toDTO(),
                ]),
                status: Response::HTTP_CREATED,
                headers: ['Content-type' => 'application/json']
            );
        } catch (Throwable $e) {
            throw $e;
            return match (true) {
                $e instanceof InvalidArgumentException => new Response($e->getMessage(), $e->getCode()),
                $e instanceof UniqueConstraintViolationException => new Response('User already exists.', Response::HTTP_UNPROCESSABLE_ENTITY),
                default => new Response('Ooops, something went wrong.', Response::HTTP_INTERNAL_SERVER_ERROR)
            };
        }
    }
}
