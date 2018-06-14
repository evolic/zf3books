<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ApplicationTest\Controller;

use Application\Controller\IndexController;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class IndexControllerTest extends AbstractHttpControllerTestCase
{
    public function setUp()
    {
        // The module configuration should still be applicable for tests.
        // You can override configuration here with test case specific values,
        // such as sample view templates, path stacks, module_listener_options,
        // etc.
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));

        parent::setUp();
    }

    public function testIndexActionCanBeAccessed()
    {
        $this->dispatch('/', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('application');
        $this->assertControllerName(IndexController::class); // as specified in router's controller name alias
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('home');
    }

    public function testIndexActionViewModelTemplateRenderedWithinLayout()
    {
        $this->dispatch('/', 'GET');
        $this->assertQuery('.container .jumbotron');
    }

    public function testInvalidRouteDoesNotCrash()
    {
        $this->dispatch('/invalid/route', 'GET');
        $this->assertResponseStatusCode(404);
    }

    public function testIndexActionViewModelWhenQueryInputIsRenderedWithinLayout()
    {
        $this->dispatch('/', 'GET');
        $this->assertQuery('.container .row form#find-books input#query');
    }

    public function testIndexActionWhenQueryIsSetWithInvalidText()
    {
        $this->dispatch('/', 'POST', [
            'query' => 'ZieLoNa',
        ]);
        $this->assertQuery('.container .row form#find-books ul');
    }

    public function testIndexActionWhenQueryIsSetWithValidText()
    {
        $this->dispatch('/', 'POST', [
            'query' => 'ZieLoNa MiLa|age>30',
        ]);
        $this->assertQuery('.container .row table');
        $this->assertNotQuery('.container .row form#find-books ul');

        $this->assertXpathQueryCount('//table/tbody/tr ', 1);
    }

    public function testIndexActionWhenQueryIsSetWithUnExistingBook()
    {
        $this->dispatch('/', 'POST', [
            'query' => 'Żółta|age>30',
        ]);
        $this->assertNotQuery('.container .row table');
    }

    public function testIndexActionWhenQueryIsSetWithPartOfTheNameOfExistingBook()
    {
        $this->dispatch('/', 'POST', [
            'query' => 'ZieLoNa|age>30',
        ]);
        $this->assertQuery('.container .row table');
        $this->assertXpathQueryCount('//table/tbody/tr ', 2);
    }
}
