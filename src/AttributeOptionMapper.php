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
        return Magento2AttributeOption::of(
            $attributeOption->getAttributeCode(),
            $this->obtainOptionCodeFromPrefixedOptionCode($attributeOption),
            $attributeOption->getLabel($this->defaultLocale) ?? $attributeOption->getOptionCode()
        );
    }

    private function obtainOptionCodeFromPrefixedOptionCode(Akeneo2AttributeOption $attributeOption)
    {
        $optionCodes = explode('-', $attributeOption->getOptionCode());
        return count($optionCodes) >= 2 && $optionCodes[0] === $attributeOption->getAttributeCode() ?
            implode('-', array_slice($optionCodes, 1)) :
            $attributeOption->getOptionCode();
    }

    private $defaultLocale;

    private function __construct(string $defaultLocale)
    {
        $this->defaultLocale = $defaultLocale;
    }
}
