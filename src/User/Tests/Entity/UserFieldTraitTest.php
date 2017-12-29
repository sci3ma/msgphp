<?php

declare(strict_types=1);

namespace MsgPhp\User\Tests\Entity;

use MsgPhp\User\Entity\{User, UserFieldTrait};
use MsgPhp\User\UserIdInterface;
use PHPUnit\Framework\TestCase;

final class UserFieldTraitTest extends TestCase
{
    public function testField(): void
    {
        $value = $this->getMockBuilder(User::class)->disableOriginalConstructor()->getMock();
        $value->expects($this->any())
            ->method('getId')
            ->willReturn($this->getMockBuilder(UserIdInterface::class)->getMock());

        $object = $this->getObject($value);

        $this->assertSame($value, $object->getUser());
        $this->assertSame($value->getId(), $object->getUserId());
    }

    private function getObject($value)
    {
        return new class($value) {
            use UserFieldTrait;

            public function __construct($value)
            {
                $this->user = $value;
            }
        };
    }
}
