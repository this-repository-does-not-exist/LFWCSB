<?php declare(strict_types=1);

namespace Tests\BoardStorage;

use App\BoardStorage\BoardStorage;
use App\BoardStorage\JsonFileBoardStorage;

/**
 * @covers \App\BoardStorage\JsonFileBoardStorage
 */
final class JsonFileBoardStorageTest extends BoardStorageTestCase
{
    private string $path;

    protected function setUp(): void
    {
        $path = \tempnam(\sys_get_temp_dir(), 'cs_fixer_tmp_');
        \assert(\is_string($path));
        $this->path = $path;

        if (\file_exists($this->path)) {
            \unlink($this->path);
        }
    }

    protected function tearDown(): void
    {
        \unlink($this->path);
    }

    public function getBoardStorage(): BoardStorage
    {
        return new JsonFileBoardStorage($this->path);
    }
}
