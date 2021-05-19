<?php

declare(strict_types=1);

namespace App\Application\Product\Form\Type;

use App\Application\Product\Form\Model\ProductCreateFormModel;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductCreateTypeTest extends TypeTestCase
{
    public function testSubmitValidData(): void
    {
        $formData = [
            'title' => 'Title',
            'description' => 'Description',
            'price' => '123.12',
        ];

        $model = new ProductCreateFormModel();

        $form = $this->factory
            ->create(ProductCreateType::class, $model);
        $form->submit($formData);

        $expected = new ProductCreateFormModel();
        $expected->title = 'Title';
        $expected->description = 'Description';
        $expected->price = '12312';

        self::assertTrue($form->isSynchronized());
        self::assertEquals($expected, $model);
    }

    public function testConfigureOptions(): void
    {
        $resolver = $this->createMock(OptionsResolver::class);
        $resolver
            ->expects(self::once())
            ->method('setDefaults')
            ->with([
                'data_class' => ProductCreateFormModel::class,
                'currency' => 'string',
            ]);

        $formType = new ProductCreateType();
        $formType->configureOptions($resolver);
    }
}
