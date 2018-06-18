<?php
namespace Application\Form;

use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Validator\StringLength;
use Zend\Validator\Callback;
use Zend\Validator\Regex;

class FindBooksForm extends Form
{
    const FIELD_QUERY   = 'query';
    const FIELD_SUBMIT  = 'submit';
    const QUERY_PATTERN = '/^([\\p{L} ]{2,})\|((age)(>|>=|<|<=)(\d+))$/u';

    /**
     * @var InputFilter
     */
    private $inputFilter;

    public function __construct()
    {
        parent::__construct('find-books');

        $this->setAttribute('method', 'POST');

        $this->add([
            'name' => self::FIELD_QUERY,
            'type' => 'text',
            'options' => [
                'label' => 'Query:',
            ],
            'attributes' => [
                'id'    => self::FIELD_QUERY,
                'placeholder' => 'ZieLoNa MiLa|age>30'
            ]
        ]);

        $this->add([
            'name' => self::FIELD_SUBMIT,
            'type' => 'submit',
            'attributes' => [
                'value' => 'Submit',
                'id'    => self::FIELD_SUBMIT,
            ]
        ]);
    }

    /**
     * Returns InputFilter
     *
     * @return InputFilter
     */
    public function getInputFilter(): InputFilter
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        $inputFilter->add([
            'name' => self::FIELD_QUERY,
            'required' => true,
            'filters' => [
                // we cannot strip tags in this case, but we are sure that we are save with XSS
                // we have regular expression describing out query here
//                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 9,
                        'max' => 63,
                    ]
                ],
                [
                    'name' => Regex::class,
                    'options' => [
                        'pattern'   => self::QUERY_PATTERN,
                    ]
                ],
            ],
        ]);

        $this->inputFilter = $inputFilter;

        return $this->inputFilter;
    }
}
