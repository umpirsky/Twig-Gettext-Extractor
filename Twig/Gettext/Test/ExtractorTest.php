<?php

/**
 * This file is part of the Twig Gettext utility.
 *
 *  (c) Saša Stamenković <umpirsky@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Twig\Gettext\Test;

use Twig\Gettext\Extractor;
use Twig\Gettext\Loader\Filesystem;
use Symfony\Component\Translation\Loader\PoFileLoader;

/**
 * @author Saša Stamenković <umpirsky@gmail.com>
 */
class ExtractorTest extends \PHPUnit\Framework\TestCase
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
        $filesystem = new Filesystem('/', __DIR__ . '/Fixtures/twig');
        $filesystem->prependPath(__DIR__ . '/Fixtures/twig');
        $this->twig = new \Twig_Environment($filesystem, [
            'cache' => '/tmp/cache/' . uniqid(),
            'auto_reload' => true,
        ]);
        $this->twig->addExtension(new \Twig_Extensions_Extension_I18n());

        $this->loader = new PoFileLoader();
    }

    /**
     * @dataProvider extractDataProvider
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

        $catalog = $this->loader->load($this->getPotFile(), null);

        foreach ($messages as $message) {
            $this->assertTrue(
                $catalog->has($message),
                sprintf('Message "%s" not found in catalog.', $message)
            );
        }
    }

    public function extractDataProvider()
    {
        return [
            [
                [
                    '/singular.twig',
                    '/plural.twig',
                ],
                $this->getGettextParameters(),
                [
                    'Hello %name%!',
                    'Hello World!',
                    'Hey %name%, I have one apple.',
                    'Hey %name%, I have %count% apples.',
                ],
            ],
        ];
    }

    public function testExtractNoTranslations()
    {
        $extractor = new Extractor($this->twig);

        $extractor->addTemplate('/empty.twig');
        $extractor->setGettextParameters($this->getGettextParameters());

        $extractor->extract();

        $catalog = $this->loader->load($this->getPotFile(), null);

        $this->assertEmpty($catalog->all('messages'));
    }

    private function getPotFile()
    {
        return __DIR__ . '/Fixtures/messages.pot';
    }

    private function getGettextParameters()
    {
        return [
            '--force-po',
            '-o',
            $this->getPotFile(),
        ];
    }

    public function testExtractWithCustomStubs()
    {
        $extractor = new Extractor($this->twig);
        $this->twig->addFunction(new \Twig_SimpleFunction('serverUrl', true));
        $this->twig->addFunction(new \Twig_SimpleFunction('url', true));
        $this->twig->addFunction(new \Twig_SimpleFunction('1', true));
        $extractor->addTemplate(__DIR__ . '/Fixtures/twig/customFunctions.twig');
        $extractor->setGettextParameters($this->getGettextParameters());
        $extractor->extract();
    }

    protected function tearDown()
    {
        if (file_exists($this->getPotFile())) {
            unlink($this->getPotFile());
        }
    }

}
