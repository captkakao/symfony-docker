<?php

namespace App\Tests\Functional\Users\Application\Command\CreateUser;

use App\Shared\Application\Command\CommandBusInterface;
use App\Tests\Tools\FakerTools;
use App\Users\Application\Command\CreateUser\CreateUserCommand;
use App\Users\Domain\Repository\UserRepositoryInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateUserCommandHandlerTest extends WebTestCase
{
    use FakerTools;

    private CommandBusInterface $commandBus;
    private UserRepositoryInterface $userRepository;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->commandBus = $this::getContainer()->get(CommandBusInterface::class);
        $this->userRepository = $this::getContainer()->get(UserRepositoryInterface::class);
    }

    public function test_user_created_successfully()
    {
        $command = new CreateUserCommand($this->getFaker()->email(), $this->getFaker()->password());

        $userUlid = $this->commandBus->execute($command);

        $user = $this->userRepository->findByUlid($userUlid);
        $this->assertNotEmpty($user);
    }
}
