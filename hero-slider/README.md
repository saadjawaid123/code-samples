# Hero Slider (ACF block)

A full-width hero slider for WordPress. Each slide is an image, an uploaded video, or a YouTube/Vimeo link.

- Image slides advance on a timer.
- Video slides play to the end before moving on.
- Each video slide has a play/pause button.
- With a single slide, the slider is skipped and the video just loops.
- The first image loads eagerly with high priority (it is the likely LCP element). The rest are lazy-loaded.
- The first slide has a one-time zoom-out, which is turned off under `prefers-reduced-motion`.

## Files

| File | Purpose |
|---|---|
| `hero-slider.php` | Block template (PHP/HTML) |
| `hero-slider-fields.php` | ACF field group (slides repeater, autoplay, delay) |
| `hero-slider.scss` | Block styles |
| `hero-slider.js` | Slider setup and video/autoplay handling |
| `hero-slider.json` | Block metadata read by the registration code |
| `scss/_breakpoints.scss` | Breakpoint map and `responsive()` mixin |
| `scss/_variables.scss` | Colour tokens and font sizes |
| `scss/_mixinsAndFunctions.scss` | `rem()`, `clamped()`, `color()` and other mixins |
| `scss/style.scss` | Entry file so the sample compiles on its own |

## Dependencies

- **ACF Pro**: field group, `get_field()`, `acf_register_block_type()`
- **Swiper.js**: slider engine (fade effect, pagination, navigation). Must load before `hero-slider.js`.
- **Bootstrap utilities**: `p-0`, `m-0`, `d-none`
- **AOS**: `data-aos` attributes on the play/pause button
- **Icon font** (IcoMoon): `icon-pause`, `icon-play`, `icon-arrow-left`, `icon-arrow-right`. Not included.

## What I wrote vs. libraries

**I wrote:**
- the template, field group, SCSS and JS
- the SCSS helpers (`rem`, `clamped`, `color`, `responsive`, pagination-dot hit-area mixins)
- the logic that coordinates video playback with Swiper autoplay
- the image `sizes`/loading strategy

**From libraries:**
- Swiper provides the slide transitions, pagination dots and arrow navigation.
- ACF provides the field UI and data access.
- Bootstrap, AOS and IcoMoon provide utility classes, scroll animations and icons.

## Registering the block

1. Copy the folder to `your-theme/blocks/hero-slider/`.
2. Compile the SCSS into the theme's stylesheet.
3. Enqueue Swiper, then `hero-slider.js`.
4. Register the block on `acf/init`:

```php
add_action('acf/init', function () {
    $dir  = get_template_directory() . '/blocks/hero-slider';
    $meta = json_decode(file_get_contents($dir . '/hero-slider.json'), true);

    acf_register_block_type([
        'name'            => $meta['name'],
        'title'           => $meta['title'],
        'render_template' => $dir . '/hero-slider.php',
        'category'        => $meta['category'],
        'icon'            => $meta['icon'],
        'supports'        => $meta['supports'],
    ]);

    include_once $dir . '/hero-slider-fields.php';
});
```

In the original project, a small plugin I wrote does this for every folder under `blocks/`. It also enqueues each block's JS only on pages that use the block.

## Notes

- The colour token names (`primary1`, …) and font names are placeholders.
- `sample_optimize_output()`, mentioned in a comment, is a theme-level output filter. It is not included.
