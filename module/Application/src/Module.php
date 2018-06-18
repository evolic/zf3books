<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Doctrine\Service\BookService;
use Application\Entity\Book;
use Doctrine\ORM\EntityManager;
use Zend\Log\Logger;
use Zend\Log\Writer as LogWriter;

class Module
{
    const VERSION = '3.0.3-dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                'Zend\Log' => function ($sm) {
                    $log = new Logger();

                    $streamWriter = new LogWriter\Stream('./data/logs/application.log');
                    $log->addWriter($streamWriter);

//                    $chromePhpWriter = new LogWriter\ChromePhp();
//                    $log->addWriter($chromePhpWriter);

                    return $log;
                },
                BookService::class => function ($sm) {
                    /** @var EntityManager $entityManager */
                    $entityManager  = $sm->get(EntityManager::class);
                    $bookRepository = $entityManager->getRepository(Book::class);
                    $logger  = $sm->get('Zend\Log');

                    $service = new BookService($bookRepository, $logger);

                    return $service;
                },
            ]
        ];
    }
}
