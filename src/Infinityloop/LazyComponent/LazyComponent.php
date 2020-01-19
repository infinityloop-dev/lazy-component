<?php

declare(strict_types = 1);

namespace Infinityloop\LazyComponent;

abstract class LazyComponent extends \Nette\Application\UI\Control
{
    private bool $loaded = false;

    public function handleLoadComponent() : void
    {
        $this->loaded = true;
        $this->redrawControl();
    }

    public function renderLoadComponentLink() : void
    {
        echo $this->link('loadComponent');
    }

    public function render() : void
    {
        if (!$this->loaded) {
            $this->template->setFile(__DIR__ . '/emptyComponent.latte');
        } else {
            $this->beforeRender();
            $this->template->setFile(\str_replace('.php', '.latte', static::getReflection()->getFileName()));
        }

        $this->template->render();
    }

    public function saveState(array &$params): void
    {
        parent::saveState($params);
        $params['lazyComponent_loaded'] = $this->loaded;
    }

    public function loadState(array $params): void
    {
        parent::saveState($params);
        $this->loaded = (bool) ($params['lazyComponent_loaded'] ?? $this->loaded);
    }

    protected function beforeRender() : void
    {
    }
}
