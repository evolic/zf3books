<?php
/**
 * Created by PhpStorm.
 * User: tomasz
 * Date: 14.06.18
 * Time: 08:58
 */

namespace Application\Resolver;


use Application\Form\FindBooksForm;

class QueryResolver
{
    public function __construct()
    {
    }

    public function resolve(string $query)
    {
        preg_match(FindBooksForm::QUERY_PATTERN, $query, $matches);

        $bookName   = $matches[1];
        $columnName = $matches[3];

        $compareOperator = $matches[4];
        $age             = (int) $matches[5];

        return [$bookName, $compareOperator, $age];
    }
}