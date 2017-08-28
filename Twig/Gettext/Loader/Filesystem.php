<?php

/**
 * This file is part of the Twig Gettext utility.
 *
 *  (c) Saša Stamenković <umpirsky@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Twig\Gettext\Loader;

/**
 * Loads template from the filesystem.
 *
 * @author Saša Stamenković <umpirsky@gmail.com>
 */
class Filesystem extends \Twig_Loader_Filesystem
{
    /**
     * Hacked find template to allow loading templates by absolute path.
     *
     * @param string $name template name or absolute path
     */
    protected function findTemplate($name, $throw = null)
    {
        $result = parent::findTemplate($name, false);
        if ($result === false) {
            return __DIR__.'/../Test/Fixtures/twig/empty.twig';
        }
        return $result;
    }
}
