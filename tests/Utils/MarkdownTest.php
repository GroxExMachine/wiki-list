<?php


namespace App\Tests\Utils;


use App\Utils\Markdown;
use PHPUnit\Framework\TestCase;


class MarkdownTest extends TestCase
{
    /**
     * @dataProvider contentProvider
     */
    public function testMarkdownConvert($content, $expected)
    {
        $this->assertEquals($expected, Markdown::convert($content));
    }

    public function contentProvider()
    {
        return [
            [
                "paragraph\n\n",
                '<p>paragraph</p>',
            ],
            [
                "paragraph\r\n\r\n",
                '<p>paragraph</p>',
            ],
            [
                '**bold**',
                '<strong>bold</strong>',
            ],
            [
                '__underline__',
                '<u>underline</u>',
            ],
            [
                '((title|http://www.url.domain))',
                '<a href="http://www.url.domain">title</a>',
            ],
            [
                '__**underline**__',
                '<u><strong>underline</strong></u>',
            ],
            [
                '**((title|http://www.url.domain))**',
                '<strong><a href="http://www.url.domain">title</a></strong>',
            ],
        ];
    }

}