<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Loads templates from other loaders.
 *
 * @final
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class IfwPsn_Vendor_Twig_Loader_Chain implements IfwPsn_Vendor_Twig_LoaderInterface, IfwPsn_Vendor_Twig_ExistsLoaderInterface, IfwPsn_Vendor_Twig_SourceContextLoaderInterface
{
    private $hasSourceCache = [];
    protected $loaders = [];

    /**
     * @param IfwPsn_Vendor_Twig_LoaderInterface[] $loaders
     */
    public function __construct(array $loaders = [])
    {
        foreach ($loaders as $loader) {
            $this->addLoader($loader);
        }
    }

    public function addLoader(IfwPsn_Vendor_Twig_LoaderInterface $loader)
    {
        $this->loaders[] = $loader;
        $this->hasSourceCache = [];
    }

    public function getSource($name)
    {
        @trigger_error(sprintf('Calling "getSource" on "%s" is deprecated since 1.27. Use getSourceContext() instead.', get_class($this)), E_USER_DEPRECATED);

        $exceptions = [];
        foreach ($this->loaders as $loader) {
            if ($loader instanceof IfwPsn_Vendor_Twig_ExistsLoaderInterface && !$loader->exists($name)) {
                continue;
            }

            try {
                return $loader->getSource($name);
            } catch (IfwPsn_Vendor_Twig_Error_Loader $e) {
                $exceptions[] = $e->getMessage();
            }
        }

        throw new IfwPsn_Vendor_Twig_Error_Loader(sprintf('Template "%s" is not defined%s.', $name, $exceptions ? ' ('.implode(', ', $exceptions).')' : ''));
    }

    public function getSourceContext($name)
    {
        $exceptions = [];
        foreach ($this->loaders as $loader) {
            if ($loader instanceof IfwPsn_Vendor_Twig_ExistsLoaderInterface && !$loader->exists($name)) {
                continue;
            }

            try {
                if ($loader instanceof IfwPsn_Vendor_Twig_SourceContextLoaderInterface) {
                    return $loader->getSourceContext($name);
                }

                return new IfwPsn_Vendor_Twig_Source($loader->getSource($name), $name);
            } catch (IfwPsn_Vendor_Twig_Error_Loader $e) {
                $exceptions[] = $e->getMessage();
            }
        }

        throw new IfwPsn_Vendor_Twig_Error_Loader(sprintf('Template "%s" is not defined%s.', $name, $exceptions ? ' ('.implode(', ', $exceptions).')' : ''));
    }

    public function exists($name)
    {
        $name = (string) $name;

        if (isset($this->hasSourceCache[$name])) {
            return $this->hasSourceCache[$name];
        }

        foreach ($this->loaders as $loader) {
            if ($loader instanceof IfwPsn_Vendor_Twig_ExistsLoaderInterface) {
                if ($loader->exists($name)) {
                    return $this->hasSourceCache[$name] = true;
                }

                continue;
            }

            try {
                if ($loader instanceof IfwPsn_Vendor_Twig_SourceContextLoaderInterface) {
                    $loader->getSourceContext($name);
                } else {
                    $loader->getSource($name);
                }

                return $this->hasSourceCache[$name] = true;
            } catch (IfwPsn_Vendor_Twig_Error_Loader $e) {
            }
        }

        return $this->hasSourceCache[$name] = false;
    }

    public function getCacheKey($name)
    {
        $exceptions = [];
        foreach ($this->loaders as $loader) {
            if ($loader instanceof IfwPsn_Vendor_Twig_ExistsLoaderInterface && !$loader->exists($name)) {
                continue;
            }

            try {
                return $loader->getCacheKey($name);
            } catch (IfwPsn_Vendor_Twig_Error_Loader $e) {
                $exceptions[] = get_class($loader).': '.$e->getMessage();
            }
        }

        throw new IfwPsn_Vendor_Twig_Error_Loader(sprintf('Template "%s" is not defined%s.', $name, $exceptions ? ' ('.implode(', ', $exceptions).')' : ''));
    }

    public function isFresh($name, $time)
    {
        $exceptions = [];
        foreach ($this->loaders as $loader) {
            if ($loader instanceof IfwPsn_Vendor_Twig_ExistsLoaderInterface && !$loader->exists($name)) {
                continue;
            }

            try {
                return $loader->isFresh($name, $time);
            } catch (IfwPsn_Vendor_Twig_Error_Loader $e) {
                $exceptions[] = get_class($loader).': '.$e->getMessage();
            }
        }

        throw new IfwPsn_Vendor_Twig_Error_Loader(sprintf('Template "%s" is not defined%s.', $name, $exceptions ? ' ('.implode(', ', $exceptions).')' : ''));
    }
}

//class_alias('IfwPsn_Vendor_Twig_Loader_Chain', 'Twig\Loader\ChainLoader', false);
