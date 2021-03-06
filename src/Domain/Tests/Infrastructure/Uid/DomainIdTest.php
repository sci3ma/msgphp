<?php

declare(strict_types=1);

namespace MsgPhp\Domain\Tests\Infrastructure\Uid;

use MsgPhp\Domain\Tests\Fixtures\TestDomainUuid;
use MsgPhp\Domain\Tests\Fixtures\TestOtherDomainUuid;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @internal
 */
final class DomainIdTest extends TestCase
{
    public function testFromValue(): void
    {
        $uuid = Uuid::fromString(Uuid::NIL);

        self::assertInstanceOf(TestDomainUuid::class, TestDomainUuid::fromValue(null));
        self::assertInstanceOf(TestOtherDomainUuid::class, TestOtherDomainUuid::fromValue(Uuid::NIL));
        self::assertSame((array) new TestDomainUuid($uuid), (array) TestDomainUuid::fromValue($uuid));
        self::assertNotSame((array) new TestDomainUuid(), (array) TestDomainUuid::fromValue(null));
        self::assertNotSame((array) new TestDomainUuid(null), (array) TestDomainUuid::fromValue(null));
        self::assertNotSame((array) new TestDomainUuid($uuid), (array) TestDomainUuid::fromValue(null));
    }

    public function testFromValueWithInvalidUuid(): void
    {
        $this->expectException(\LogicException::class);

        TestDomainUuid::fromValue('foo');
    }

    public function testFromInvalidValue(): void
    {
        $this->expectException(\LogicException::class);

        TestDomainUuid::fromValue(true);
    }

    public function testIsNil(): void
    {
        self::assertFalse((new TestDomainUuid())->isNil());
        self::assertTrue((new TestDomainUuid(Uuid::fromString(Uuid::NIL)))->isNil());
    }

    public function testEquals(): void
    {
        $id = new TestDomainUuid(Uuid::fromString(Uuid::NIL));

        self::assertTrue($id->equals($id));
        self::assertTrue($id->equals(new TestDomainUuid(Uuid::fromString(Uuid::NIL))));
        self::assertFalse($id->equals(new TestDomainUuid()));
        self::assertFalse($id->equals(new TestOtherDomainUuid(Uuid::fromString(Uuid::NIL))));
        self::assertFalse($id->equals(Uuid::NIL));
        self::assertFalse($id->equals(new \stdClass()));
    }

    public function testToString(): void
    {
        $id = new TestDomainUuid(Uuid::fromString($uuid = '00000000-0000-0000-0000-000000000000'));

        self::assertSame($uuid, $id->toString());
        self::assertSame($uuid, (string) $id);
        self::assertNotSame((new TestDomainUuid())->toString(), (new TestDomainUuid())->toString());
        self::assertNotSame((string) new TestDomainUuid(), (string) new TestDomainUuid());
    }

    public function testSerialize(): void
    {
        $id = new TestDomainUuid(Uuid::fromString('00000000-0000-0000-0000-000000000000'));

        self::assertSame($id->toString(), (string) unserialize(serialize($id)));
    }
}
