<?php

declare(strict_types=1);

namespace TijmenWierenga\Tests\Commenting\Models;

use PHPUnit\Framework\TestCase;
use TijmenWierenga\Commenting\Models\CommentableId;

final class CommentableIdTest extends TestCase
{
    /**
     * @dataProvider validTypeProvider
     */
    public function testItCreatesAnIdForAValidType(string $type): void
    {
        $id = CommentableId::new($type);

        static::assertEquals($type, $id->getResourceType());
    }

    /**
     * @dataProvider validTypeProvider
     */
    public function testItInstantiatesAnIdFromScalarTypes(string $type): void
    {
        $uuid = '7c14a5be-55ce-4822-b48d-527e8e967da2';
        $id = CommentableId::fromScalar($type, $uuid);

        static::assertEquals($type, $id->getResourceType());
        static::assertEquals($uuid, $id->toString());
        static::assertEquals($uuid, $id->getUuid()->toString());
    }

    public function validTypeProvider(): array
    {
        return array_map(fn ($type): array => [$type], CommentableId::RESOURCE_TYPES);
    }
}
