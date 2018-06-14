<?php
namespace Application\Resolver;

use Application\Form\FindBooksForm;

/**
 * Class QueryResolver
 *
 * @package Application\Resolver
 */
class QueryResolver
{
    public function __construct()
    {
    }

    /**
     * Method returns book name, compare operator and an age from given query
     *
     * @param string $query
     * @return array
     */
    public function resolve(string $query): array
    {
        preg_match(FindBooksForm::QUERY_PATTERN, $query, $matches);

        $bookName   = $matches[1];
        $columnName = $matches[3];

        $compareOperator = $matches[4];
        $age             = (int) $matches[5];

        return [$bookName, $compareOperator, $age];
    }
}