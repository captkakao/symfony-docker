<?php

namespace App\Tests\Functional\Users\Application\Query\FindUserByEmail;

use App\Shared\Application\Query\QueryBusInterface;
use App\Tests\Resource\Fixture\UserFixture;
use App\Tests\Tools\FakerTools;
use App\Users\Application\DTO\UserDTO;
use App\Users\Application\Query\FindUserByEmail\FindUserByEmailQuery;
use App\Users\Application\Query\FindUserByEmail\FindUserByEmailQueryHandler;
use App\Users\Domain\Repository\UserRepositoryInterface;
use Exception;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FindUserByEmailQueryHandlerTest extends WebTestCase
{
    use FakerTools;

    private QueryBusInterface $queryBus;
    private UserRepositoryInterface $userRepository;
    private AbstractDatabaseTool $databaseTool;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->queryBus = static::getContainer()->get(QueryBusInterface::class);
        $this->userRepository = static::getContainer()->get(UserRepositoryInterface::class);
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function test_user_created_when_command_executed(): void
    {
        $referenceRepository = $this->databaseTool->loadFixtures([UserFixture::class])->getReferenceRepository();

        $user = $referenceRepository->getReference(UserFixture::REFERENCE);
        $query = new FindUserByEmailQuery($user->getEmail());

        $userDTO = $this->queryBus->execute($query);

        $this->assertInstanceOf(UserDTO::class, $userDTO);
    }
}
