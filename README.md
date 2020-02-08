# Lazy component

:wrench: Component for Nette framwork which helps with creation of lazy ajax loaded components.

## Introduction

Component which renders empty in the beggining and is populated by signal request.

## Installation

Install package using composer

```
composer require infinityloop-dev/lazy-component
```

## Dependencies

- PHP >= 7.4
- [nette/application](https://github.com/nette/application)
- [nette/utils](https://github.com/nette/utils)

## How to use

- Create new component and extend `\Infinityloop\LazyComponent\LazyComponent` instead of `\Nette\Application\UI\Control`.
- Trigger `handleLoadComponent` to load and redraw component.
- Use macro `{control componentName}` to render your component as you would normally.
- Additionaly use `{control componentName:loadComponentLink}` to get URL for `loadComponent` signal.
    - Useful for rendering URL (eg. into data attributes) so you can easily trigger signal from javascript.
- Make sure `LazyCompoennt::render()` method is called if you choose to override it.
    - Preferably use `beforeRender()` to pass variables into template. 
    - `beforeRender()` method is not called when empty template is used (when component is not loaded).
- Template for your component is automaticaly deduced to be have same name (and path) as your `.php` file.
    - File extension is simply swapped to `.latte`.
- By default the FontAwesome 5 spinner is used as placeholder. Override `EMPTY_TEMPLATE` constant to use different empty template file.

### Example lazy tab content

Tab header:
```
<li class"tab" data-load-component="{control myComponent:loadComponentLink}">Tab name</li>
```
Tab content:
```
<div class="tab-content">
    {control myComponent}
</div>
```
JQuery ajax request on click:
```
$(".tab[data-load-component]").one('click',function (event) {
    const link = $(this).data("load-component");
    $.nette.ajax({
        method: 'GET',
        url: link,
    });
});
```

- Load event is executed only once ([jqery.one](https://api.jquery.com/one/)).
