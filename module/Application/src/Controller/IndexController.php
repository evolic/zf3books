<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Doctrine\Service\BookService;
use Application\Entity\Book;
use Application\Form\FindBooksForm;
use Application\Resolver\QueryResolver;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    /**
     * @var BookService
     */
    private $bookService;


    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function indexAction()
    {
        $request = $this->getRequest();
        $view    = new ViewModel();
        $form    = new FindBooksForm();
        $books   = false;

        $view->setVariable('form', $form);
        $view->setVariable('books', $books);

        if (! $request->isPost()) {
            return $view;
        }

        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $view;
        }

        $values     = $form->getData();
        $query      = $values[FindBooksForm::FIELD_QUERY];
        $resolver   = new QueryResolver();

        list($bookName, $compareOperator, $age) = $resolver->resolve($query);

        $books = $this->bookService->getBooksByNameWithStats(
            $bookName,
            $compareOperator,
            $age
        );

        if (is_array($books) && empty($books)) {
            $books = $this->bookService->getBooksWithFulltextAndStats(
                $bookName,
                $compareOperator,
                $age
            );
        }

        $view->setVariable('books', $books);

        return $view;
    }

    public function allBooksAction()
    {
        $view    = new ViewModel();
        $form    = new FindBooksForm();
        $books   = $this->bookService->getAllBooks();

        $view->setVariable('form', $form);
        $view->setVariable('books', $books);

        return $view;
    }
}
