<?php

namespace Twig\Tests\Node;

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Twig\Node\BlockNode;
use Twig\Node\Node;
use Twig\Node\TextNode;
use Twig\Node\YieldTextNode;
use Twig\Test\NodeTestCase;

class BlockTest extends NodeTestCase
{
    public function testConstructor()
    {
        $body = new TextNode('foo', 1);
        $node = new BlockNode('foo', $body, 1);

        $this->assertEquals($body, $node->getNode('body'));
        $this->assertEquals('foo', $node->getAttribute('name'));
    }

    public function getTests()
    {
        $tests = [];

        $tests[] = [new BlockNode('foo', new YieldTextNode('foo', 1), 1), <<<EOF
// line 1
public function block_foo(\$context, array \$blocks = [])
{
    \$macros = \$this->macros;
    yield "foo";
}
EOF
            , new Environment(new ArrayLoader(), ['use_yield' => true])
        ];

        $tests[] = [new BlockNode('foo', new TextNode('foo', 1), 1), <<<EOF
// line 1
public function block_foo(\$context, array \$blocks = [])
{
    \$macros = \$this->macros;
    echo "foo";
}
EOF
            , new Environment(new ArrayLoader(), ['use_yield' => false])
        ];

        $tests[] = [new BlockNode('foo', new Node(), 1), <<<EOF
// line 1
public function block_foo(\$context, array \$blocks = [])
{
    \$macros = \$this->macros;
    yield;
}
EOF
            , new Environment(new ArrayLoader(), ['use_yield' => true])
        ];

        return $tests;
    }
}
