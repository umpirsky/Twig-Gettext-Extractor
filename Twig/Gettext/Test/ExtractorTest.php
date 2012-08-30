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
use Symfony\Component\Translation\Loader\PoFileLoader;

/**
 * @author Саша Стаменковић <umpirsky@gmail.com>
 */
class ExtractorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var PoFileLoader
     */
    protected $loader;

    protected function setUp()
    {
        $this->twig = new \Twig_Environment(new Filesystem('/'), array(
            'cache'       => '/tmp/cache/'.uniqid(),
            'auto_reload' => true
        ));
        $this->twig->addExtension(new \Twig_Extensions_Extension_I18n());

        $this->loader = new PoFileLoader();
    }

    /**
     * @dataProvider testExtractDataProvider
     */
    public function testExtract(array $templates, array $parameters, array $messages)
    {
        $extractor = new Extractor($this->twig);

        foreach ($templates as $template) {
            $extractor->addTemplate($template);
        }
        foreach ($parameters as $parameter) {
            $extractor->addGettextParameter($parameter);
        }

        $extractor->extract();

        $catalog = $this->loader->load(self::getPotFile(), null);

        foreach ($messages as $message) {
            $this->assertTrue(
                $catalog->has($message),
                sprintf('Message "%s" not found in catalog.', $message)
            );
        }
    }

    public static function testExtractDataProvider()
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
                    self::getPotFile(),
                    '--from-code=utf-8',
                    '-k_',
                    '-kgettext',
                    '-kgettext_noop',
                    '-L',
                    'PHP',
                ),
                array(
                    'Hello %name%!',
                    'Hello World!',
                    'Hey %name%, I have one apple.',
                    'Hey %name%, I have %count% apples.',
                ),
            ),
        );
    }

    private static function getPotFile()
    {
        return __DIR__.'/Fixtures/messages.pot';
    }

    protected function tearDown()
    {
        if (file_exists(self::getPotFile())) {
            unlink(self::getPotFile());
        }
    }
}
