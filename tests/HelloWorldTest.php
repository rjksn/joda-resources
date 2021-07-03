<?php

namespace Ahmedjoda\JodaResources\Tests;

use Illuminate\Support\Str;

class HelloWorldTest extends TestCase
{
    public function testPascalToKebab()
    {
        $str = 'PascaleCaseExamples';
        $this->assertEquals(Str::kebab($str), $this->fromPascalCaseToKebabCase($str));
    }

    public function testPascalToSnake()
    {
        $str = 'PascaleCaseExamples';
        $this->assertEquals(Str::snake($str), $this->fromPascalCaseToSnakeCase($str));
    }

    public function fromPascalCaseToKebabCase($input)
    {
        return implode('-', $this->takePascalApart($input));
    }

    public function fromPascalCaseToSnakeCase($input)
    {
        return implode('_', $this->takePascalApart($input));
    }

    public function takePascalApart($input)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return $ret;
    }
}