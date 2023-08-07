<?php declare(strict_types=1);

namespace Tests\BoardStorage;

use App\BoardStorage\BoardStorage;
use App\BoardStorage\InMemoryBoardStorage;

/**
 * @covers \App\BoardStorage\InMemoryBoardStorage
 */
final class InMemoryBoardStorageTest extends BoardStorageTestCase
{
    public function getBoardStorage(): BoardStorage
    {
        return new InMemoryBoardStorage();
    }
}
