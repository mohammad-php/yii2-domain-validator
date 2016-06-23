<?php

namespace kdn\yii2\validators;

use kdn\yii2\validators\mocks\ModelMock;
use stdClass;

/**
 * Class DomainValidatorTest.
 * @package kdn\yii2\validators
 * @covers kdn\yii2\validators\DomainValidator::init
 */
class DomainValidatorTest extends TestCase
{
    /**
     * @var DomainValidator
     */
    protected $validator;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();
        $this->validator = new DomainValidator;
    }

    public static function validDomainProvider()
    {
        return [
            'one domain name label' => ['localhost'],
            'two domain name labels' => ['example.com'],
            'domain name with trailing dot' => ['example.com.'],
            '127 levels, 253 characters and trailing dot' => [
                'a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.' .
                'a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.' .
                'a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.' .
                'a.a.a.a.',
            ],
            'domain name labels with 63 characters' => [
                'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa.' .
                'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa.' .
                'example.com',
            ],
            'domain name with various symbols' => ['1.a-B.c2'],
            'Punycode, mixed domain name' => ['xn--e1afmkfd.test.xn--80akhbyknj4f'],
        ];
    }

    /**
     * @param string $value
     * @covers       kdn\yii2\validators\DomainValidator::validateValue
     * @dataProvider validDomainProvider
     * @small
     */
    public function testValidDomain($value)
    {
        $this->assertTrue($this->validator->validate($value));
    }

    public static function validDomainInUrlProvider()
    {
        return [
            'HTTP, one domain name label' => ['http://localhost'],
            'HTTP, two domain name labels' => ['http://example.com/index.html'],
            'FTP, domain name with trailing dot' => ['ftp://example.com./img/dir/'],
            'HTTPS, 127 levels, 253 characters and trailing dot' => [
                'https://a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.' .
                'a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.' .
                'a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.a.' .
                'a.a.a.a./index.html',
            ],
            'missing scheme, domain name labels with 63 characters' => [
                '//aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa.' .
                'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa.' .
                'example.com',
            ],
            'complex URL, domain name with various symbols' => [
                'http://username:password@1.a-B.c2:9090/path?a=b&c=d#anchor',
            ],
            'Punycode, FTP, mixed domain name' => ['ftp://xn--e1afmkfd.test.xn--80akhbyknj4f/img/dir/'],
        ];
    }

    /**
     * @param string $value
     * @covers       kdn\yii2\validators\DomainValidator::validateValue
     * @dataProvider validDomainInUrlProvider
     * @small
     */
    public function testValidDomainInUrl($value)
    {
        $this->assertTrue($this->validator->validate($value));
    }

    public static function validDomainIdnProvider()
    {
        return [
            'IDN, one domain name label' => ['пример'],
            'IDN, two domain name labels' => ['пример.испытание'],
            'IDN, domain name with trailing dot' => ['пример.испытание.'],
            'IDN, mixed domain name' => ['пример.test.испытание'],
            'IDN, 34 levels, 253 characters (ф. == xn--t1a.) and trailing dot' => [
                'ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.s.s.s.',
            ],
            'IDN, domain name labels with 63 characters' => [
                'ффффффффффффффффффффффффффффффффффффффффффффффффффффффффф.' .
                'ффффффффффффффффффффффффффффффффффффффффффффффффффффффффф.' .
                'испытание'
            ],
            'IDN, domain name with various symbols' => ['1.a-B.cф2'],
            'IDN, cup' => ['☕.us'],
            'IDN, full-width characters' => ['日本語。ＪＰ'],

            'IDN, HTTP, one domain name label' => ['http://пример'],
            'IDN, HTTP, two domain name labels' => ['http://пример.испытание/index.html'],
            'IDN, FTP, domain name with trailing dot' => ['ftp://пример.испытание./img/dir/'],
            'IDN, FTP, mixed domain name' => ['ftp://пример.test.испытание/img/dir/'],
            'IDN, HTTPS, 34 levels, 253 characters (ф. == xn--t1a.) and trailing dot' => [
                'https://ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.s.s.s./index.html',
            ],
            'IDN, missing scheme, domain name labels with 63 characters' => [
                '//ффффффффффффффффффффффффффффффффффффффффффффффффффффффффф.' .
                'ффффффффффффффффффффффффффффффффффффффффффффффффффффффффф.' .
                'испытание'
            ],
            'IDN, complex URL, domain name with various symbols' => [
                'http://username:password@1.a-B.cф2:9090/path?a=ф&c=d#-пример',
            ],
            'IDN, HTTP, cup' => ['http://☕.us/index.html'],
            'IDN, HTTP, full-width characters' => ['http://日本語。ＪＰ/index.html'],
        ];
    }

    /**
     * @param string $value
     * @covers       kdn\yii2\validators\DomainValidator::validateValue
     * @uses         kdn\yii2\validators\DomainValidator::getDefaultErrorMessages
     * @uses         kdn\yii2\validators\DomainValidator::getErrorMessage
     * @dataProvider validDomainIdnProvider
     * @small
     */
    public function testInvalidDomainWithDisabledIdn($value)
    {
        $this->assertFalse($this->validator->validate($value));
    }

    /**
     * @covers kdn\yii2\validators\DomainValidator::getDefaultErrorMessages
     * @covers kdn\yii2\validators\DomainValidator::getErrorMessage
     * @covers kdn\yii2\validators\DomainValidator::validateValue
     * @small
     */
    public function testInvalidDomainWithDisabledIdnErrorMessage()
    {
        $value = 'ффффффффффффффффффффффффффффффффффффффффффффффффффффффффф.' .
            'ффффффффффффффффффффффффффффффффффффффффффффффффффффффффф.' .
            'испытание';
        $this->assertFalse($this->validator->validate($value, $errorMessage));
        $this->assertEquals(
            'Each label of the input value can consist of only latin letters, numbers and hyphens.',
            $errorMessage
        );
    }

    public static function validDomainAllProvider()
    {
        return array_merge(
            static::validDomainProvider(),
            static::validDomainInUrlProvider(),
            static::validDomainIdnProvider()
        );
    }

    /**
     * @param string $value
     * @covers       kdn\yii2\validators\DomainValidator::validateValue
     * @dataProvider validDomainAllProvider
     * @small
     */
    public function testValidIdnDomain($value)
    {
        if (!function_exists('idn_to_ascii')) {
            $this->markTestSkipped('intl extension required.');
            return;
        }

        $validator = $this->validator;
        $validator->enableIDN = true;
        $this->assertTrue($validator->validate($value));
    }

    /**
     * @covers kdn\yii2\validators\DomainValidator::getDefaultErrorMessages
     * @covers kdn\yii2\validators\DomainValidator::getErrorMessage
     * @covers kdn\yii2\validators\DomainValidator::validateValue
     * @medium
     */
    public function testDns()
    {
        $validator = $this->validator;
        $nonExistingDomain = 'non-existing-subdomain.example.com';
        $this->assertTrue($validator->validate($nonExistingDomain));
        $validator->checkDNS = true;
        $this->assertFalse($validator->validate($nonExistingDomain, $errorMessage));
        $this->assertEquals('DNS record corresponding to the input value not found.', $errorMessage);

        $data = [
            'google.com',
            'http://username:password@google.com:9090/path?a=b&c=d#anchor',
        ];
        foreach ($data as $value) {
            $this->assertTrue(
                $validator->validate($value),
                "Failed to validate \"$value\" (checkDNS = true)."
            );
        }
    }

    /**
     * @covers kdn\yii2\validators\DomainValidator::getDefaultErrorMessages
     * @covers kdn\yii2\validators\DomainValidator::getErrorMessage
     * @covers kdn\yii2\validators\DomainValidator::validateValue
     * @medium
     */
    public function testDnsWithEnabledIdn()
    {
        if (!function_exists('idn_to_ascii')) {
            $this->markTestSkipped('intl extension required.');
            return;
        }

        $validator = $this->validator;
        $validator->checkDNS = true;
        // enabling of IDN should not affect error message
        $validator->enableIDN = true;
        $this->assertFalse($validator->validate('non-existing-subdomain.example.com', $errorMessage));
        $this->assertEquals('DNS record corresponding to the input value not found.', $errorMessage);

        $data = [
            'google.com',
            'http://username:password@google.com:9090/path?a=b&c=d#anchor',
            'яндекс.рф',
            'http://username:password@яндекс.рф:9090/path?a=ф&c=d#-пример',
        ];
        foreach ($data as $value) {
            $this->assertTrue(
                $validator->validate($value),
                "Failed to validate \"$value\" (checkDNS = true, enableIDN = true)."
            );
        }
    }

    /**
     * @covers kdn\yii2\validators\DomainValidator::getDefaultErrorMessages
     * @covers kdn\yii2\validators\DomainValidator::getErrorMessage
     * @covers kdn\yii2\validators\DomainValidator::validateValue
     * @small
     */
    public function testUnderscore()
    {
        $validator = $this->validator;
        $validator->allowUnderscore = true;
        $data = [
            'ex_ample.com',
            'http://username:password@ex_ample.com:9090/path?a=b&c=d#anchor',
        ];
        foreach ($data as $value) {
            $this->assertTrue(
                $validator->validate($value),
                "Failed to validate \"$value\" (allowUnderscore = true)."
            );
        }
    }

    public static function urlNotAllowedProvider()
    {
        return array_merge(
            static::arrayAddColumn(static::validDomainProvider(), true),
            static::arrayAddColumn(static::validDomainInUrlProvider(), false)
        );
    }

    /**
     * @param string $value
     * @param boolean $expectedResult
     * @covers       kdn\yii2\validators\DomainValidator::validateValue
     * @uses         kdn\yii2\validators\DomainValidator::getDefaultErrorMessages
     * @uses         kdn\yii2\validators\DomainValidator::getErrorMessage
     * @dataProvider urlNotAllowedProvider
     * @small
     */
    public function testUrlNotAllowed($value, $expectedResult)
    {
        $validator = $this->validator;
        $validator->allowURL = false;
        $this->assertEquals($expectedResult, $validator->validate($value));
    }

    /**
     * @covers kdn\yii2\validators\DomainValidator::getDefaultErrorMessages
     * @covers kdn\yii2\validators\DomainValidator::getErrorMessage
     * @covers kdn\yii2\validators\DomainValidator::validateValue
     * @small
     */
    public function testLabelNumberMin()
    {
        $validator = $this->validator;
        $validator->labelNumberMin = 2;
        $this->assertFalse($validator->validate('localhost', $errorMessage));
        $this->assertEquals('the input value should consist of at least 2 labels separated by dot.', $errorMessage);
        $this->assertTrue($validator->validate('example.com'));
        $this->assertTrue($validator->validate('test.example.com'));
        $validator->labelNumberMin = 3;
        $this->assertFalse($validator->validate('example.com', $errorMessage));
        $this->assertEquals('the input value should consist of at least 3 labels separated by dots.', $errorMessage);
        $this->assertTrue($validator->validate('test.example.com'));
    }

    /**
     * @covers kdn\yii2\validators\DomainValidator::getDefaultErrorMessages
     * @covers kdn\yii2\validators\DomainValidator::getErrorMessage
     * @covers kdn\yii2\validators\DomainValidator::validateValue
     * @small
     */
    public function testLabelNumberMinWithEnabledIdn()
    {
        if (!function_exists('idn_to_ascii')) {
            $this->markTestSkipped('intl extension required.');
            return;
        }

        // enabling of IDN should not affect error message
        $this->validator->enableIDN = true;
        $this->testLabelNumberMin();
    }

    /**
     * @covers kdn\yii2\validators\DomainValidator::getDefaultErrorMessages
     * @covers kdn\yii2\validators\DomainValidator::getErrorMessage
     * @covers kdn\yii2\validators\DomainValidator::validateValue
     * @small
     */
    public function testInvalidDomainIdnToAscii()
    {
        if (!function_exists('idn_to_ascii')) {
            $this->markTestSkipped('intl extension required.');
            return;
        }

        $validator = $this->validator;
        $validator->enableIDN = true;
        $this->assertFalse($validator->validate('a⒈com', $errorMessage));
        $this->assertEquals('the input value contains invalid characters.', $errorMessage);
    }

    public static function invalidDomainProvider($testName)
    {
        if ($testName == 'testInvalidDomain') {
            $messageInvalidCharacter =
                'Each label of the input value can consist of only latin letters, numbers and hyphens.';
            $messageLabelStartEnd = 'Each label of the input value should start and end with latin letter or number.' .
                ' The rightmost label of the input value should start with latin letter.';
        } else {
            $messageInvalidCharacter =
                'Each label of the input value can consist of only letters, numbers and hyphens.';
            $messageLabelStartEnd = 'Each label of the input value should start and end with letter or number.' .
                ' The rightmost label of the input value should start with letter.';
        }
        $messageLabelTooLong = 'Each label of the input value should contain at most 63 characters.';
        $messageLabelTooShort = 'Each label of the input value should contain at least 1 character.';
        $messageNotString = 'the input value must be a string.';
        $messageTooLong = 'the input value should contain at most 253 characters.';
        $messageTooShort = 'the input value should contain at least 1 character.';
        return [
            'null' => [null, $messageNotString],
            'boolean' => [true, $messageNotString],
            'integer' => [1, $messageNotString],
            'float' => [1.2, $messageNotString],
            'array' => [[], $messageNotString],
            'object' => [new stdClass, $messageNotString],

            'domain name too long' => [str_repeat('a.', 126) . 'aa', $messageTooLong],

            'domain name too short' => ['', $messageTooShort],

            'first domain name label starts with hyphen' => ['-example.com', $messageLabelStartEnd],
            'first domain name label ends with hyphen' => ['example-.com', $messageLabelStartEnd],
            'last domain name label starts with hyphen' => ['example.-com', $messageLabelStartEnd],
            'last domain name label ends with hyphen' => ['example.com-', $messageLabelStartEnd],

            'IDN, first domain name label starts with hyphen' => ['-пример.испытание', $messageInvalidCharacter],
            'IDN, first domain name label ends with hyphen' => ['пример-.испытание', $messageInvalidCharacter],
            'IDN, last domain name label starts with hyphen' => ['пример.-испытание', $messageInvalidCharacter],
            'IDN, last domain name label ends with hyphen' => ['пример.испытание-', $messageInvalidCharacter],

            'IDN, HTTP, first domain name label starts with hyphen' => [
                'http://-пример.испытание/index.html',
                $messageInvalidCharacter,
            ],
            'IDN, HTTP, first domain name label ends with hyphen' => [
                'http://пример-.испытание/index.html',
                $messageInvalidCharacter,
            ],
            'IDN, HTTP, last domain name label starts with hyphen' => [
                'http://пример.-испытание/index.html',
                $messageInvalidCharacter,
            ],
            'IDN, HTTP, last domain name label ends with hyphen' => [
                'http://пример.испытание-/index.html',
                $messageInvalidCharacter,
            ],

            'last domain name label starts with number' => ['example.4om', $messageLabelStartEnd],

            'domain name label too long' => [str_repeat('a', 64), $messageLabelTooLong],

            'dot' => ['.', $messageLabelTooShort],
            'domain name starts with dot' => ['.example.com', $messageLabelTooShort],
            'domain name ends with two dots' => ['example.com..', $messageLabelTooShort],
            'domain name contains two dots in a row' => ['example..com', $messageLabelTooShort],

            'domain name contains underscore' => ['ex_ample.com', $messageInvalidCharacter],

            'IDN, domain name too long' => [
                'ффффффффффффффффффффффффффффффффффффффффффффффффффффффффф.' .
                'ффффффффффффффффффффффффффффффффффффффффффффффффффффффффф.' .
                'ффффффффффффффффффффффффффффффффффффффффффффффффффффффффф.' .
                'ффффффффффффффффффффффффффффффффффффффффффффффффффффффффф.',
                $messageInvalidCharacter,
            ],
            'IDN, domain name label too long' => [
                'фффффффффффффффффффффффффффффффффффффффффффффффффффффффффs',
                $messageInvalidCharacter,
            ],
        ];
    }

    /**
     * @param string $value
     * @param string $expectedErrorMessage
     * @covers       kdn\yii2\validators\DomainValidator::getDefaultErrorMessages
     * @covers       kdn\yii2\validators\DomainValidator::getErrorMessage
     * @covers       kdn\yii2\validators\DomainValidator::validateValue
     * @dataProvider invalidDomainProvider
     * @small
     */
    public function testInvalidDomain($value, $expectedErrorMessage)
    {
        $this->assertFalse($this->validator->validate($value, $errorMessage));
        $this->assertEquals($expectedErrorMessage, $errorMessage);
    }

    public static function invalidDomainWithEnabledIdnProvider()
    {
        $messageLabelStartEnd = 'Each label of the input value should start and end with letter or number.' .
            ' The rightmost label of the input value should start with letter.';
        $messageLabelTooLong = 'Label of the input value is too long.';
        $messageTooLong = 'the input value is too long.';
        return array_merge(
            static::invalidDomainProvider('testInvalidDomainWithEnabledIdn'),
            [
                /* todo fatal error
                'IDN, domain name too long, fatal' => [
                    'ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.ф.s.s.s.s',
                    $messageTooLong,
                ],
                //*/
                /* todo fatal error
                'IDN, domain name too long, fatal' => [
                    'ффффффффффффффффффффффффффффффффффффффффффффффффффффффффф.' .
                    'ффффффффффффффффффффффффффффффффффффффффффффффффффффффффф.' .
                    'ффффффффффффффффффффффффффффффффффффффффффффффффффффффффф.' .
                    'фффффффффффффффффффффффффффффффффффффффффффффффффффффффф.',
                    $messageTooLong,
                ],
                //*/

                'domain name too long' => [str_repeat('a.', 126) . 'aa', $messageTooLong],

                'IDN, first domain name label starts with hyphen' => ['-пример.испытание', $messageLabelStartEnd],
                'IDN, first domain name label ends with hyphen' => ['пример-.испытание', $messageLabelStartEnd],
                'IDN, last domain name label starts with hyphen' => ['пример.-испытание', $messageLabelStartEnd],
                'IDN, last domain name label ends with hyphen' => ['пример.испытание-', $messageLabelStartEnd],

                'IDN, HTTP, first domain name label starts with hyphen' => [
                    'http://-пример.испытание/index.html',
                    $messageLabelStartEnd,
                ],
                'IDN, HTTP, first domain name label ends with hyphen' => [
                    'http://пример-.испытание/index.html',
                    $messageLabelStartEnd,
                ],
                'IDN, HTTP, last domain name label starts with hyphen' => [
                    'http://пример.-испытание/index.html',
                    $messageLabelStartEnd,
                ],
                'IDN, HTTP, last domain name label ends with hyphen' => [
                    'http://пример.испытание-/index.html',
                    $messageLabelStartEnd,
                ],

                'domain name label too long' => [str_repeat('a', 64), $messageLabelTooLong],

                'IDN, domain name too long' => [
                    'ффффффффффффффффффффффффффффффффффффффффффффффффффффффффф.' .
                    'ффффффффффффффффффффффффффффффффффффффффффффффффффффффффф.' .
                    'ффффффффффффффффффффффффффффффффффффффффффффффффффффффффф.' .
                    'ффффффффффффффффффффффффффффффффффффффффффффффффффффффффф.',
                    $messageTooLong,
                ],
                'IDN, domain name label too long' => [
                    'фффффффффффффффффффффффффффффффффффффффффффффффффффффффффs',
                    $messageLabelTooLong,
                ],
            ]
        );
    }

    /**
     * Enabling of IDN should not affect error message (for most cases).
     * @param string $value
     * @param string $expectedErrorMessage
     * @covers       kdn\yii2\validators\DomainValidator::getDefaultErrorMessages
     * @covers       kdn\yii2\validators\DomainValidator::getErrorMessage
     * @covers       kdn\yii2\validators\DomainValidator::validateValue
     * @dataProvider invalidDomainWithEnabledIdnProvider
     * @small
     */
    public function testInvalidDomainWithEnabledIdn($value, $expectedErrorMessage)
    {
        if (!function_exists('idn_to_ascii')) {
            $this->markTestSkipped('intl extension required.');
            return;
        }

        $this->validator->enableIDN = true;
        $this->testInvalidDomain($value, $expectedErrorMessage);
    }

    /**
     * @covers kdn\yii2\validators\DomainValidator::getDefaultErrorMessages
     * @covers kdn\yii2\validators\DomainValidator::getErrorMessage
     * @covers kdn\yii2\validators\DomainValidator::validateValue
     * @small
     */
    public function testInvalidDomainWithAllowUnderscore()
    {
        $validator = $this->validator;
        $validator->allowUnderscore = true;
        $this->assertFalse($validator->validate('a_@_a', $errorMessage));
        if ($validator->enableIDN) {
            $expectedErrorMessage =
                'Each label of the input value can consist of only letters, numbers, hyphens and underscores.';
        } else {
            $expectedErrorMessage =
                'Each label of the input value can consist of only latin letters, numbers, hyphens and underscores.';
        }
        $this->assertEquals($expectedErrorMessage, $errorMessage);
    }

    /**
     * @covers kdn\yii2\validators\DomainValidator::getDefaultErrorMessages
     * @covers kdn\yii2\validators\DomainValidator::getErrorMessage
     * @covers kdn\yii2\validators\DomainValidator::validateValue
     * @small
     */
    public function testInvalidDomainWithAllowUnderscoreWithEnabledIdn()
    {
        if (!function_exists('idn_to_ascii')) {
            $this->markTestSkipped('intl extension required.');
            return;
        }

        // enabling of IDN should not affect error message
        $this->validator->enableIDN = true;
        $this->testInvalidDomainWithAllowUnderscore();
    }

    /**
     * @covers kdn\yii2\validators\DomainValidator::getErrorMessage
     * @covers kdn\yii2\validators\DomainValidator::validateValue
     * @small
     */
    public function testCustomErrorMessage()
    {
        $validator = $this->validator;
        $messageNotString = 'test';
        $validator->messageNotString = $messageNotString;
        $this->assertFalse($validator->validate(null, $errorMessage));
        $this->assertEquals($messageNotString, $errorMessage);
    }

    /**
     * @covers kdn\yii2\validators\DomainValidator::validateValue
     * @uses   kdn\yii2\validators\DomainValidator::getDefaultErrorMessages
     * @uses   kdn\yii2\validators\DomainValidator::getErrorMessage
     * @small
     */
    public function testValidateAttribute()
    {
        $model = new ModelMock();
        $validator = $this->validator;
        $model->domain = 'google.com';
        $validator->validateAttribute($model, 'domain');
        $this->assertFalse($model->hasErrors('domain'));
        $model->domain = '';
        $validator->validateAttribute($model, 'domain');
        $this->assertTrue($model->hasErrors('domain'));
    }

    /**
     * Important: this test should be executed last, because it can remove function "idn_to_ascii".
     * @covers kdn\yii2\validators\DomainValidator::init
     * @expectedException \yii\base\InvalidConfigException
     * @expectedExceptionMessage In order to use IDN validation intl extension must be installed and enabled.
     * @small
     */
    public function testInitIdnIntlException()
    {
        if (!function_exists('runkit_function_remove') || !ini_get('runkit.internal_override')) {
            $this->markTestSkipped('runkit extension required. runkit.internal_override should be set to "On".');
            return;
        }

        runkit_function_remove('idn_to_ascii');
        new DomainValidator(['enableIDN' => true]);
    }

    /**
     * Add column to array.
     * @param array $array
     * @param mixed $value
     * @return array
     */
    protected static function arrayAddColumn($array, $value)
    {
        return array_map(
            function ($data) use ($value) {
                $data[] = $value;
                return $data;
            },
            $array
        );
    }
}