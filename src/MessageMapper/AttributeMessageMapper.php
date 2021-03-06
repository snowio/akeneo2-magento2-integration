<?php
declare(strict_types=1);
namespace SnowIO\Akeneo2Magento2\MessageMapper;

use Joshdifabio\Transform\Transform;
use SnowIO\Akeneo2DataModel\Event\AttributeDeletedEvent;
use SnowIO\Akeneo2DataModel\Event\AttributeSavedEvent;
use SnowIO\Akeneo2DataModel\Event\EntityStateEvent;
use SnowIO\Magento2DataModel\AttributeData;
use SnowIO\Magento2DataModel\Command\Command;
use SnowIO\Magento2DataModel\Command\DeleteAttributeCommand;
use SnowIO\Magento2DataModel\Command\SaveAttributeCommand;

final class AttributeMessageMapper extends MessageMapperWithDeleteSupport
{
    public static function withAttributeTransform(Transform $transform): self
    {
        return new self($transform);
    }

    protected function resolveEntitySavedEvent($event): EntityStateEvent
    {
        if (\is_array($event)) {
            $event = AttributeSavedEvent::fromJson($event);
        } elseif (!$event instanceof AttributeSavedEvent) {
            throw new \InvalidArgumentException;
        }
        return $event;
    }

    protected function resolveEntityDeletedEvent($event): EntityStateEvent
    {
        if (\is_array($event)) {
            $event = AttributeDeletedEvent::fromJson($event);
        } elseif (!$event instanceof AttributeDeletedEvent) {
            throw new \InvalidArgumentException;
        }
        return $event;
    }

    /**
     * @param AttributeData $magentoEntityData
     */
    protected function getRepresentativeValueForDiff($magentoEntityData): string
    {
        return \json_encode($magentoEntityData->toJson());
    }

    /**
     * @param AttributeData $magentoEntityData
     */
    protected function getMagentoEntityIdentifier($magentoEntityData): string
    {
        return $magentoEntityData->getCode();
    }

    /**
     * @param AttributeData $magentoEntityData
     */
    protected function createSaveEntityCommand($magentoEntityData): Command
    {
        return SaveAttributeCommand::of($magentoEntityData);
    }

    protected function createDeleteEntityCommand(string $magentoEntityIdentifier): Command
    {
        return DeleteAttributeCommand::of($magentoEntityIdentifier);
    }
}
