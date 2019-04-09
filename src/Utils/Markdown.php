<?php


namespace App\Utils;


class Markdown
{
    public static function convert(string $content): string
    {
        $content = htmlentities($content);

        $rules = [
            // \n\n -> <p>
            '~(.+)(\n\n|\r\n\r\n)~mUs',
            // **bold**
            '~\*{2}(.*)\*{2}~mUs',
            // __underline__
            '~_{2}(.*)_{2}~Us',
            // ((title|url))
            '~\({2}(.*)\|(.*)\){2}~mUs',
        ];
        $replaces = [
            '<p>$1</p>',
            '<strong>$1</strong>',
            '<u>$1</u>',
            '<a href="$2">$1</a>',
        ];

        return  preg_replace($rules, $replaces, $content);
    }
}