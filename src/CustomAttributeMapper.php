<?php
declare(strict_types=1);
namespace SnowIO\Akeneo2Magento2;

use Joshdifabio\Transform\FlatMapElements;
use Joshdifabio\Transform\FlatMapValues;
use Joshdifabio\Transform\Identity;
use Joshdifabio\Transform\Transform;
use Joshdifabio\Transform\WithKeys;
use SnowIO\Akeneo2DataModel\AttributeValue;
use SnowIO\Akeneo2DataModel\PriceCollection;
use SnowIO\Magento2DataModel\CustomAttribute;

final class CustomAttributeMapper extends DataMapper
{
    public static function create(): self
    {
        return new self;
    }

    public function getTransform(): Transform
    {
        return FlatMapElements::via($this);
    }

    public function getValueTransform(): Transform
    {
        return FlatMapValues::via($this);
    }

    public function getKvTransform(): Transform
    {
        return WithKeys::of(Identity::fn())->then(FlatMapValues::via($this));
    }

    public function __invoke(AttributeValue $akeneoAttributeValue)
    {
        $value = $akeneoAttributeValue->getValue();
        if ($value instanceof PriceCollection) {
            if ($this->currency === null) {
                return;
            }
            $value = $value->getAmount($this->currency);
        }
        yield CustomAttribute::of($akeneoAttributeValue->getAttributeCode(), $value);
    }

    public function withCurrency(string $currency): self
    {
        $result = clone $this;
        $result->currency = $currency;
        return $result;
    }

    private $currency;

    private function __construct()
    {

    }
}
