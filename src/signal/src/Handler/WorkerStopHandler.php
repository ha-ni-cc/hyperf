<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Hyperf\Signal\Handler;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Signal\SignalHandlerInterface;
use Psr\Container\ContainerInterface;
use Swoole\Server;

class WorkerStopHandler implements SignalHandlerInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var ConfigInterface
     */
    protected $config;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->config = $container->get(ConfigInterface::class);
    }

    public function listen(): array
    {
        return [
            [self::WORKER, SIGTERM],
        ];
    }

    public function handle(int $signal): void
    {
        $time = $this->config->get('server.settings.max_wait_time', 3);
        sleep($time);
        $this->container->get(Server::class)->stop();
    }
}
