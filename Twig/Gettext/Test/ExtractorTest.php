<?php

/**
 * This file is part of the Twig Gettext utility.
 *
 *  (c) Саша Стаменковић <umpirsky@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Twig\Gettext\Test;

use Twig\Gettext\Extractor;
use Twig\Gettext\Loader\Filesystem;

/**
 * @author Саша Стаменковић <umpirsky@gmail.com>
 */
class ExtractorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var type \Twig_Environment
     */
    protected $twig;
    
    protected function setUp()
    {
        $this->twig = new \Twig_Environment(new Filesystem('/'), array(
            'cache'       => '/tmp/cache/'.uniqid(),
            'auto_reload' => true
        ));
        $this->twig->addExtension(new \Twig_Extensions_Extension_I18n());
    }
    
    /**
     * @dataProvider testExtractDataProvider
     * @runInSeparateProcess
     */
    public function testExtract(array $templates, array $parameters)
    {
        $extractor = new Extractor($this->twig);
        
        foreach ($templates as $template) {
            $extractor->addTemplate($template);
        }
        foreach ($parameters as $parameter) {
            $extractor->addGettextParameter($parameter);
        }
        
        $extractor->extract();
    }
    
    public function testExtractDataProvider()
    {
        return array(
            array(
                array(
                    __DIR__.'/Fixtures/twig/foo.twig',
                    __DIR__.'/Fixtures/twig/bar.twig',
                ),
                array(
                    '--sort-output',
                    '--omit-header',
                    '--force-po',
                    '-o',
                    __DIR__.'/Fixtures/gettext/messages.pot',
                    '--from-code=utf-8',
                    '-k_',
                    '-kgettext',
                    '-kgettext_noop',
                    '-L',
                    'PHP',
                )
            ),
        );
    }
}
