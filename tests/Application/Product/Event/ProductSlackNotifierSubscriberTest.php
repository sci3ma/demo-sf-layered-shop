<?php

declare(strict_types=1);

namespace App\Tests\Application\Product\Event;

use App\Application\Product\Event\ProductSlackNotifierSubscriber;
use App\Application\Product\Model\ProductViewModel;
use App\Domain\Entity\Product\Event\ProductAddedEvent;
use App\Domain\Entity\Product\Product;
use App\Infrastructure\Transport\SlackChannel;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductSlackNotifierSubscriberTest extends TestCase
{
    /**
     * @var MockObject|SlackChannel
     */
    private $slackChannel;

    protected function setUp(): void
    {
        $this->slackChannel = $this->createMock(SlackChannel::class);
    }

    public function testGetSubscriberEvents(): void
    {
        $actual = ProductSlackNotifierSubscriber::getSubscribedEvents();

        self::assertIsArray($actual);
        self::assertArrayHasKey(ProductAddedEvent::NAME, $actual);
    }

    public function testOnProductAdded(): void
    {
        $product = $this->createMock(Product::class);

        $event = $this->createMock(ProductAddedEvent::class);
        $event
            ->expects(self::once())
            ->method('getProduct')
            ->willReturn($product);

        $this->slackChannel
            ->expects(self::once())
            ->method('send')
            ->with(
                'New product added.',
                ['product' => new ProductViewModel()],
            );

        $subscriber = new ProductSlackNotifierSubscriber($this->slackChannel);
        $subscriber->onProductAdded($event);
    }
}
