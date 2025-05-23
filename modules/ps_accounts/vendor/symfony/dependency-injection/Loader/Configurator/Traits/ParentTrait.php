<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrestaShop\Module\PsAccounts\Vendor\Symfony\Component\DependencyInjection\Loader\Configurator\Traits;

use PrestaShop\Module\PsAccounts\Vendor\Symfony\Component\DependencyInjection\ChildDefinition;
use PrestaShop\Module\PsAccounts\Vendor\Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
/**
 * @method $this parent(string $parent)
 */
trait ParentTrait
{
    /**
     * Sets the Definition to inherit from.
     *
     * @param string $parent
     *
     * @return $this
     *
     * @throws InvalidArgumentException when parent cannot be set
     */
    protected final function setParent($parent)
    {
        if (!$this->allowParent) {
            throw new InvalidArgumentException(\sprintf('A parent cannot be defined when either "_instanceof" or "_defaults" are also defined for service prototype "%s".', $this->id));
        }
        if ($this->definition instanceof ChildDefinition) {
            $this->definition->setParent($parent);
        } elseif ($this->definition->isAutoconfigured()) {
            throw new InvalidArgumentException(\sprintf('The service "%s" cannot have a "parent" and also have "autoconfigure". Try disabling autoconfiguration for the service.', $this->id));
        } elseif ($this->definition->getBindings()) {
            throw new InvalidArgumentException(\sprintf('The service "%s" cannot have a "parent" and also "bind" arguments.', $this->id));
        } else {
            // cast Definition to ChildDefinition
            $definition = \serialize($this->definition);
            $definition = \substr_replace($definition, '89', 2, 2);
            $definition = \substr_replace($definition, 'Child', 80, 0);
            $definition = \unserialize($definition);
            $this->definition = $definition->setParent($parent);
        }
        return $this;
    }
}
