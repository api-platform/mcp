<?php

/*
 * This file is part of the API Platform project.
 *
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ApiPlatform\Mcp\Tests\State;

use ApiPlatform\Mcp\State\ToolProvider;
use ApiPlatform\Metadata\McpResource;
use Mcp\Schema\Request\ReadResourceRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;

class ToolProviderTest extends TestCase
{
    /**
     * The handler installs this provider on every MCP operation that declares none,
     * MCP resources included, but it only fills `mcp_data` for a tool call: reading
     * a resource must not be mapped from a payload that does not exist.
     */
    public function testProvideReturnsNullWhenTheRequestCarriesNoToolPayload(): void
    {
        $objectMapper = $this->createMock(ObjectMapperInterface::class);
        $objectMapper->expects($this->never())->method('map');

        $provider = new ToolProvider($objectMapper);

        $operation = new McpResource(uri: 'dummy://docs', name: 'docs', class: \stdClass::class);

        $this->assertNull($provider->provide($operation, [], [
            'mcp_request' => new ReadResourceRequest('dummy://docs'),
        ]));
    }
}
