# godmodelabs/segony-kernel
[![Code Climate](https://codeclimate.com/github/godmodelabs/segony-kernel/badges/gpa.svg)](https://codeclimate.com/github/godmodelabs/segony-kernel) [![Test Coverage](https://codeclimate.com/github/godmodelabs/segony-kernel/badges/coverage.svg)](https://codeclimate.com/github/godmodelabs/segony-kernel) [![Build Status](https://travis-ci.org/godmodelabs/segony-kernel.svg?branch=master)](https://travis-ci.org/godmodelabs/segony-kernel)

> Segony is a simple framework used for sites with recurring content segments. You can configure every segment separately and combine them with different views.

The framework consists of "sites", "layouts" and "segments". These can be configured separately. This is how it works:

- Kernel `initialize`
    - Register necessary services
    - Initialize configuration
- Kernel `dispatch`
    - Spy the `site`
    - Spy the `layout`
    - Initialize the Segment Worker
        - Register `layout` and `site` segments
    - Initialize the `site`
    - Initialize the `layout`
    - Dispatch the `site`
    - Dispatch the `layout`
    - Dispatch the Segment Worker (runs `initialize`, `dispatch` as well as `render`)
    - Render the `site`
    - Append the client dispatcher to the content block
    - Render the `layout`
- Kernel `terminate`
    - Send response
    - Store debug information (environment `dev` only)

"Sites", "layouts" and "segments" can access some events (they can’t access their own events, like `layout.pre_initialize` from `LayoutController`) via Component
[symfony/eventer-dispatcher](http://symfony.com/doc/current/components/event_dispatcher/introduction.html). Check out this list of possible events:

- `segment.pre_initialize`
- `segment.post_initialize`
- `segment.pre_dispatch`
- `segment.post_dispatch`
- `segment.pre_render`
- `segment.post_render`
- `layout.pre_initialize`
- `layout.post_initialize`
- `layout.pre_dispatch`
- `layout.post_dispatch`
- `layout.pre_render`
- `layout.post_render`
- `site.pre_initialize`
- `site.post_initialize`
- `site.pre_dispatch`
- `site.post_dispatch`
- `site.pre_render`
- `site.post_render`

```php
class BaseLayoutController extends LayoutController
{

    // use callback
    public function initialize()
    {
        $this->on('segment.post_initialize', function (SegmentInitializeEvent $event) {
            // ...
        });
    }

    // use the predefined method
    public function onSegmentPostInitialize(SegmentInitializeEvent $event)
    {
        $config = $event->getController()->getConfig();

        if ($config->has('something')) {
            $event->stopPropagation();

            // ...
        }
    }

}
```

# Contributors
- Marc Binder <marcandrebinder@gmail.com>