<?php
namespace ApplicationTest\Resolver;

use Application\Resolver\QueryResolver;
use PHPUnit\Framework\TestCase;

class QueryResolverTest extends TestCase
{
    /**
     * @var QueryResolver
     */
    private $queryResolver;

    public function setUp()
    {
        $this->queryResolver = new QueryResolver();

        parent::setUp();
    }

    public function provideDataForQueryResolver()
    {
        return [
            'ZieLoNa MiLa|age>30' => [
                'ZieLoNa MiLa|age>30',
                [
                    'ZieLoNa MiLa',
                    '>',
                    30,
                ]
            ],
            'ZieLoNa|age>=15' => [
                'ZieLoNa|age>=15',
                [
                    'ZieLoNa',
                    '>=',
                    15,
                ]
            ],
            'ZieLoNa MiLa|age<25' => [
                'ZieLoNa MiLa|age<25',
                [
                    'ZieLoNa MiLa',
                    '<',
                    25,
                ]
            ],
            'Harry|age<=30' => [
                'Harry|age<=20',
                [
                    'Harry',
                    '<=',
                    20,
                ]
            ],
        ];
    }

    /**
     * @test
     * @dataProvider provideDataForQueryResolver
     */
    public function testQueryResolver(string $query, array $output)
    {
        list($bookName, $compareOperator, $age) = $this->queryResolver->resolve($query);

        $this->assertEquals(
            $output[0],
            $bookName,
            'Extracted book name is not as expected'
        );

        $this->assertEquals(
            $output[1],
            $compareOperator,
            'Extracted compare operator is not as expected'
        );

        $this->assertEquals(
            $output[2],
            $age,
            'Extracted age is not as expected'
        );
    }
}