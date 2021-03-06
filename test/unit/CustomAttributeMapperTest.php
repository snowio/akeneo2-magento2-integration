<?php
declare(strict_types=1);

namespace SnowIO\Akeneo2Magento2\Test;

use PHPUnit\Framework\TestCase;
use SnowIO\Akeneo2DataModel\AttributeValueSet;
use SnowIO\Akeneo2Magento2\CustomAttributeMapper;
use SnowIO\Magento2DataModel\CustomAttribute;
use SnowIO\Magento2DataModel\CustomAttributeSet;

class CustomAttributeMapperTest extends TestCase
{
    private $akeneoAttributes;

    public function setUp()
    {
        $this->akeneoAttributes = AttributeValueSet::fromJson('main', [
            'attribute_values' => [
                'size' => 'Large',
                'price' => [
                    'gbp' => '30',
                    'eur' => '37.45',
                ],
                'weight' => '30',
            ],
        ]);
    }

    public function testMap()
    {
        $customAttributeMapper = CustomAttributeMapper::create()
            ->withCurrency('eur')
            ->getTransform();
        $expected = CustomAttributeSet::of([
            CustomAttribute::of('size', 'Large'),
            CustomAttribute::of('price', '37.45'),
            CustomAttribute::of('weight', '30'),
        ]);
        $transformOutput = $customAttributeMapper->applyTo($this->akeneoAttributes);
        $actual = CustomAttributeSet::of(\iterator_to_array($transformOutput));
        self::assertTrue($expected->equals($actual));
    }

    public function testMapWithoutCurrency()
    {
        $customAttributeMapper = CustomAttributeMapper::create()
            ->getTransform();
        $expected = CustomAttributeSet::of([
            CustomAttribute::of('size', 'Large'),
            CustomAttribute::of('weight', '30'),
        ]);
        $transformOutput = $customAttributeMapper->applyTo($this->akeneoAttributes);
        $actual = CustomAttributeSet::of(\iterator_to_array($transformOutput));
        self::assertTrue($expected->equals($actual));
    }
}
