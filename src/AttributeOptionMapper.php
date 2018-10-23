<?php
declare(strict_types=1);
namespace SnowIO\Akeneo2Magento2;

use SnowIO\Magento2DataModel\AttributeOption as Magento2AttributeOption;
use SnowIO\Akeneo2DataModel\AttributeOption as Akeneo2AttributeOption;

final class AttributeOptionMapper extends DataMapper
{
    public static function withDefaultLocale(string $defaultLocale): self
    {
        return new self($defaultLocale);
    }

    public function __invoke(Akeneo2AttributeOption $attributeOption): Magento2AttributeOption
    {
        $optionCodes = explode('-', $attributeOption->getOptionCode());
        $optionCode = count($optionCodes) >= 2 ? $optionCodes[1] : $attributeOption->getOptionCode();

        return Magento2AttributeOption::of(
            $attributeOption->getAttributeCode(),
            $optionCode,
            $attributeOption->getLabel($this->defaultLocale) ?? $attributeOption->getOptionCode()
        );
    }

    private $defaultLocale;

    private function __construct(string $defaultLocale)
    {
        $this->defaultLocale = $defaultLocale;
    }
}
