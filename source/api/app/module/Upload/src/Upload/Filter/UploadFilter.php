<?php
/**
 * UploadFilter
 *
 * @package Upload\Filter
 * @author Kevin A. Padilla <kevin.padilla0717@gmail.com>
 */
namespace Upload\Filter;

use Zend\InputFilter\InputFilter;
use Zend\Validator\NotEmpty;
use Zend\Validator\Callback;

class UploadFilter extends InputFilter
{
    public function __construct()
    {
        $this->add([
            'name'     => 'username',
            'required' => true,
            'filters'  => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'NotEmpty',
                    'options' => [
                        'messages' => [
                            NotEmpty::IS_EMPTY => 'User name is required.',
                        ],
                    ],
                ]
            ],
        ]);

        $this->add([
            'name'     => 'file',
            'required' => true,
            'filters'  => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'NotEmpty',
                    'options' => [
                        'messages' => [
                            NotEmpty::IS_EMPTY => 'File is required.',
                        ],
                    ],
                ],
                [
                    'name'  => 'Callback',
                    'options'=> [
                        'messages' => [
                            Callback::INVALID_VALUE => 'Invalid file uploaded. Filename missing.'
                        ],
                        'callback' => function ($value, $context=[]) {
                            return !empty($value['name']);
                        }
                    ]
                ],
                [
                    'name'  => 'Callback',
                    'options'=> [
                        'messages' => [
                            Callback::INVALID_VALUE => 'Invalid file uploaded. Temp name is missing.'
                        ],
                        'callback' => function ($value, $context=[]) {
                            return !empty($value['tmp_name']);
                        }
                    ]
                ]
            ],
        ]);

	}
}
