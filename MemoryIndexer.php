<?php

declare(strict_types=1);

/*
 * This file is part of the Schranz Search package.
 *
 * (c) Alexander Schranz <alexander@sulu.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Schranz\Search\SEAL\Adapter\Memory;

use Schranz\Search\SEAL\Adapter\IndexerInterface;
use Schranz\Search\SEAL\Marshaller\Marshaller;
use Schranz\Search\SEAL\Schema\Index;
use Schranz\Search\SEAL\Task\SyncTask;
use Schranz\Search\SEAL\Task\TaskInterface;

final class MemoryIndexer implements IndexerInterface
{
    private readonly Marshaller $marshaller;

    public function __construct()
    {
        $this->marshaller = new Marshaller();
    }

    public function save(Index $index, array $document, array $options = []): ?TaskInterface
    {
        $document = MemoryStorage::save($index, $this->marshaller->marshall($index->fields, $document));

        if (!($options['return_slow_promise_result'] ?? false)) {
            return null;
        }

        return new SyncTask($document);
    }

    public function delete(Index $index, string $identifier, array $options = []): ?TaskInterface
    {
        MemoryStorage::delete($index, $identifier);

        if (!($options['return_slow_promise_result'] ?? false)) {
            return null;
        }

        return new SyncTask(null);
    }
}
