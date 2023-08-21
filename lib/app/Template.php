<?php
namespace app;
class Template {
    public $elementStack = [];
    public function isElementHTMLEmpty($element) {
        static $voidElements = [
            'area' => true,
            'base' => true,
            'br' => true,
            'col' => true,
            'embed' => true,
            'hr' => true,
            'img' => true,
            'input' => true,
            'link' => true,
            'meta' => true,
            'param' => true,
            'source' => true,
            'track' => true,
            'wbr' => true,
            'command' => true,
            'keygen' => true,
            'menuitem' => true,
        ];
        return isset($voidElements[strtolower($element)]);
    }
    public function attributeSourceCode($key, $value) {
        if (strlen($value) && (in_array($value[0], ['$', '(', '\\']))) {
            $value = sprintf('@%s', $value);
        } else {
            $value = sprintf("'%s'", strtr($value, [ '\\' => '\\\\', "'" => '\\\'' ]));
        }
        if ($key[0] == '_') {
            $key = substr($key, 1);
            $value = sprintf('__(%s)', $value);
        }
        return [ strtolower($key), $value ];
    }
    public function startElement($parser, $name, $attrs) {
        if ($name == 'T:TEMPLATE') return;
        if (FALSE === strpos($name, '.')) {
            printf('?><%s%s<?php ob_start();', implode(' ', [
                preg_replace('/^_/', '', strtolower($name)),
                \sergiosgc\ArrayAdapter::from($attrs)
                    ->mapAssociative(function ($v, $k) {
                        $k = strtolower($k);
                        $valueIsPHPLiteral = (strlen($v) && ($v[0] == '$' || $v[0] == '\\'));
                        $translate = ($k[0] == '_');
                        if ($translate) $k = substr($k, 1); // Remove leading space
                        $v = $valueIsPHPLiteral
                             ? ( $translate 
                                 ? sprintf('<?= strtr(__(@%s), [ \'&\' => \'&amp;\', \'"\' => \'&quot;\' ]) ?>', $v)
                                 : sprintf('<?= strtr(@%s, [ \'&\' => \'&amp;\', \'"\' => \'&quot;\' ]) ?>', $v)
                               )
                             : ( $translate 
                                 ? sprintf('<?= strtr(__(\'%s\'), [ \'&\' => \'&amp;\', \'"\' => \'&quot;\' ]) ?>', strtr($v, [ '\\' => '\\\\', "'" => "\\'"])) 
                                 : strtr($v, [ '&' => '&amp;', '"' => '&quot;', '<' => '&lt;', '>' => '&gt;' ]) 
                             );

                        return [ $k, $v ];
                    })->map(function($v, $k) { return sprintf('%s="%s"', $k, $v); })
                    ->implode(' ')
            ]),
                $this->isElementHTMLEmpty($name) ? '' : '>'
            );
            return;
        }
        $name = explode('.', strtolower($name));
        array_push($name, 
            (function ($localName) {
                return implode('', array_map(function($part) { 
                    if (0 == strlen($part)) return '';
                    $part[0] = strtoupper($part[0]);
                    return $part;
                },
                explode('-', $localName)));
            })(array_pop($name)));
        $name = implode('/', $name);
        printf("ob_start(); // %s\n", $name);
        array_push($this->elementStack, [
            'tag' => $name,
            'attrs' => array_reduce( 
                array_map( [ $this, 'attributeSourceCode'], array_keys($attrs), $attrs ),
                function ($acc, $v) { $acc[$v[0]] = $v[1]; return $acc; },
                [] 
            )
        ]);
        if (strpos($name, '/') !== FALSE) {
            printf(<<<EOS

\app\Template::componentPre(
    '%s',
    [
%s
    ]
);

EOS
                , $this->elementStack[count($this->elementStack) - 1]['tag']
                , implode(", \n", 
                    array_map(
                        function ($k, $v) { return sprintf("        '%s' => %s", $k, $v); },
                        array_keys($this->elementStack[count($this->elementStack) - 1]['attrs']),
                        $this->elementStack[count($this->elementStack) - 1]['attrs']
                    )
                )
            );
        }
    }
    public function endElement($parser, $name) {
        if ($name == 'T:TEMPLATE') return;
        if (strpos($name, '.') === FALSE) {
            if ($this->isElementHTMLEmpty($name)) {
                printf(<<<EOS

(function(\$content) { // /%s
    if (\$content == '') {
        print(' />');
    } else {
        print(\$content);
        print('</%s>');
    }
})(%s);
EOS
                    , strtolower(preg_replace('/^_/', '', strtolower($name)))
                    , strtolower(preg_replace('/^_/', '', strtolower($name)))
                    , $name[0] == '_' ? '__(ob_get_clean())' : 'ob_get_clean()'
                );
            } else {
                printf("%s; print('</%s>'); // %s\n"
                    , $name[0] == '_' ? 'print(__(ob_get_clean()))' : 'print(ob_get_clean())'
                    , strtolower(preg_replace('/^_/', '', strtolower($name)))
                    , strtolower(preg_replace('/^_/', '', strtolower($name)))
                );
            }
            return;
        }
        $element = array_pop($this->elementStack);
        printf(<<<EOS

\app\Template::component(
    '%s',
    [
        'content' => ob_get_clean(),
%s
    ]
);

EOS
            , $element['tag']
            , implode(", \n", 
                array_map(
                    function ($k, $v) { return sprintf("        '%s' => %s", $k, $v); },
                    array_keys($element['attrs']),
                    $element['attrs']
                )
            )
        );
    }
    public function characterData($parser, $data) {
        if (preg_match('_^\s*$_s', $data)) return;
        if (strlen($data) && $data[0] == '$') {
            printf('print(@%s);', $data);
        } else {
            printf('?>%s<?php ', $data);
        }
    }
    public function processingInstruction($parser, $target, $data) {
        if ($target == 'php') throw new Exception(sprintf('Processing instruction with php target found at %d:%d. Did you forget to CDATA quote a PHP tag?',
            xml_get_current_line_number($parser),
            xml_get_current_column_number($parser)));
        printf('<?%s %s', $target, $data);
    }
    public function parseTree($treeSource) {
        $parser = xml_parser_create();
        xml_set_element_handler($parser, [$this, 'startElement'], [$this, 'endElement']);
        xml_set_character_data_handler($parser, [ $this, 'characterData' ]);
        xml_set_processing_instruction_handler($parser, [ $this, 'processingInstruction' ]);
        if (0 === xml_parse($parser, $treeSource, true)) throw new Exception(sprintf("Error parsing element tree at %d:%d: %s",
            xml_get_current_line_number($parser),
            xml_get_current_column_number($parser),
            xml_error_string(xml_get_error_code($parser))
        ));
    }
    public static function compile($source) {
        ob_start();

        $result = preg_match('_(?<code>.*?)^----$(?<tree>.*)_ms', $source, $matches);
        if ($result === 0) throw new Exception('Template source code does not match template syntax');
        if ($result === FALSE) throw new Exception('Error matching template source regex');
        $code = preg_replace('_\?>\s*$_ms', '', $matches['code']);
        if (preg_match('_^\s*$_s', $code)) $code = '<?php ';
        $elementTree = sprintf(strtr('<?xml version="1.0" encoding="UTF-8"?>\n<t:template xmlns:t="http://xml.sergiosgc.com/templates">%s</t:template>', [ '\n' => "\n" ]), $matches['tree']);
        printf(<<<EOS
<?php
(function() {
    // Unpack variables in scope
    foreach (func_get_args()[0] as \$veryRandomString137845ToAvoidCollisionsKey => \$veryRandomString137845ToAvoidCollisionsValue) \$\$veryRandomString137845ToAvoidCollisionsKey = \$veryRandomString137845ToAvoidCollisionsValue;
    unset(\$veryRandomVariableNameForThescriptFile);
    unset(\$veryRandomString137845ToAvoidCollisionsKey);
    unset(\$veryRandomString137845ToAvoidCollisionsValue);
    // Template PHP code
?>%s
    // Template components

EOS
            , $code);
            
        (new Template())->parseTree($elementTree);
        print("})(get_defined_vars());");

        return strtr(preg_replace('_<\?php\s+\?>_ms', '', ob_get_clean()), [ '?><?asphp' => '' ]);
    }
    public static function component($uri, $args) {
        $requestCopy = $_REQUEST;
        $_REQUEST = $args;
        \app\Application::singleton()->getComponentRouter()->output($uri);
        $_REQUEST = $requestCopy;
    }
    public static function componentPre($uri, $args) {
        $requestCopy = $_REQUEST;
        $_REQUEST = $args;
        \app\Application::singleton()->getComponentRouter()->preOutput($uri);
        $_REQUEST = $requestCopy;
    }
}