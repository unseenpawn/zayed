<?php
use PHPUnit\Framework\TestCase;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

class RoutingTest extends TestCase
{
    public function testGermanPageRoute(): void
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $r) {
            require __DIR__ . '/../routes/web.php';
        });
        $routeInfo = $dispatcher->dispatch('GET', '/de/ueber-uns');
        $this->assertSame(FastRoute\Dispatcher::FOUND, $routeInfo[0]);
    }
}
