<?php

declare(strict_types = 1);

namespace Infinityloop\LazyComponent;

abstract class LazyComponent extends \Nette\Application\UI\Control
{
    protected const EMPTY_TEMPLATE = __DIR__ . '/emptyTemplate.latte';

    private bool $loaded = false;

    public function setLoaded(bool $loaded = true) : void
    {
        $this->loaded = $loaded;
    }
    
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
        $this->template->setFile(__DIR__ . '/LazyComponent.latte');
        $this->template->loaded = $this->loaded;
        $this->template->emptyTemplate = static::EMPTY_TEMPLATE;

        if ($this->loaded) {
            $this->beforeRender();
            $this->template->lazyTemplate = \str_replace('.php', '.latte', static::getReflection()->getFileName());
        }

        $this->template->render();
    }
    
    public function redrawControl(string $snippet = null, bool $redraw = true): void
    {
        if (\is_string($snippet) && !\Nette\Utils\Strings::startsWith($snippet, 'lazyComponent_')) {
            $this->redrawControl('lazyComponent_snippetArea');
        }

        parent::redrawControl($snippet, $redraw);
    }

    public function saveState(array &$params): void
    {
        parent::saveState($params);
        $params['lazyComponent_loaded'] = $this->loaded;
    }

    public function loadState(array $params): void
    {
        parent::loadState($params);
        $this->loaded = (bool) ($params['lazyComponent_loaded'] ?? $this->loaded);
    }

    protected function beforeRender() : void
    {
    }
}
