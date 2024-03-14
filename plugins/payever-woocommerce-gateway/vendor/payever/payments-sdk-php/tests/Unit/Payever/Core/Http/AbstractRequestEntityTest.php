<?php

namespace Payever\Tests\Unit\Payever\Core\Http;

use Payever\Sdk\Core\Base\MessageEntity;

abstract class AbstractRequestEntityTest extends AbstractMessageEntityTest
{
    /**
     * @inheritdoc
     */
    public function testEntity()
    {
        $entity = parent::testEntity();

        $this->assertRequestEntityInvalid($entity);

        return $entity;
    }

    /**
     * @param MessageEntity $entity
     */
    protected function assertRequestEntityInvalid(MessageEntity $entity)
    {
        $required = $entity->getRequired();

        if (!empty($required)) {
            foreach ($required as $field) {
                $innerEntity = clone $entity;

                $innerEntity->offsetSet($field, null);
                $this->assertFalse($innerEntity->isValid());
            }
        }
    }
}
