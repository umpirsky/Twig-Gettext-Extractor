<?php

/**
 * This file is part of the Twig Gettext utility.
 *
 *  (c) Саша Стаменковић <umpirsky@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Twig\Gettext\Loader;

/**
 * Loads template from the filesystem.
 *
 * @author Саша Стаменковић <umpirsky@gmail.com>
 */
class Filesystem extends \Twig_Loader_Filesystem
{
    /**
     * Hacked find template to allow loading templates by absolute path.
     * 
     * @param string $name template name or absolute path
     */
    protected function findTemplate($name)
    {
        try {
            parent::findTemplate($name);
        } catch (\Twig_Error_Loader $e) {
            if (is_file($name)) {
                return $this->cache[$name] = $name;
            }
        }
        
        if (empty($this->cache)) {
            throw new \Twig_Error_Loader(sprintf('Unable to find template "%s" (looked into: %s).', $name, implode(', ', $this->paths)));
        } else {
            return array_pop($this->cache);
        }
    }
}
