<?php
declare(strict_types=1);

namespace SnowIO\Akeneo2Magento2\Test;

use PHPUnit\Framework\TestCase;

use SnowIO\Akeneo2DataModel\AttributeData as Akeneo2AttributeData;
use SnowIO\Akeneo2DataModel\AttributeType as Akeneo2AttributeType;
use SnowIO\Akeneo2Magento2\AttributeMapper;
use SnowIO\Magento2DataModel\AttributeData as Magento2AttributeData;
use SnowIO\Magento2DataModel\FrontendInput;

class AttributeMapperTest extends TestCase
{
    public function testMap()
    {
        $mapper = AttributeMapper::withDefaultLocale('en_GB')
            ->withTypeToFrontendInputMapper(function () {
                return FrontendInput::MULTISELECT;
            });
        $akeneoAttributeData = Akeneo2AttributeData::fromJson([
            'code' => 'size',
            'type' => Akeneo2AttributeType::SIMPLESELECT,
            'localizable' => true,
            'scopable' => true,
            'sort_order' => 34,
            'labels' => [
                'en_GB' => 'Size',
                'fr_FR' => 'Taille',
            ],
            'group' => 'general',
            '@timestamp' => 1508491122,
        ]);
        $expected = Magento2AttributeData::of('size', FrontendInput::MULTISELECT, 'Size');
        $actual = $mapper($akeneoAttributeData);
        self::assertTrue($expected->equals($actual));
    }
}
