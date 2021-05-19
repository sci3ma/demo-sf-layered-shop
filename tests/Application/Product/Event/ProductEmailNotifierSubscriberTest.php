<?php

declare(strict_types=1);

namespace App\Tests\Application\Product\Event;

use App\Application\Product\Event\ProductEmailNotifierSubscriber;
use App\Application\Product\Model\ProductViewModel;
use App\Domain\Entity\Product\Event\ProductAddedEvent;
use App\Domain\Entity\Product\Product;
use App\Infrastructure\Transport\EmailChannel;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductEmailNotifierSubscriberTest extends TestCase
{
    /**
     * @var EmailChannel|MockObject
     */
    private $emailChannel;

    protected function setUp(): void
    {
        $this->emailChannel = $this->createMock(EmailChannel::class);
    }

    public function testGetSubscriberEvents(): void
    {
        $actual = ProductEmailNotifierSubscriber::getSubscribedEvents();

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

        $this->emailChannel
            ->expects(self::once())
            ->method('send')
            ->with(
                'New product added.',
                ['product' => new ProductViewModel()],
                '@App/Product/email/added.html.twig'
            );

        $subscriber = new ProductEmailNotifierSubscriber($this->emailChannel);
        $subscriber->onProductAdded($event);
    }
}
