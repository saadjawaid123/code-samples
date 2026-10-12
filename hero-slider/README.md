# Hero Slider (ACF block)

A full-width hero slider for WordPress. It's anonymized from a real client project and shared with permission. Client names, colours, fonts and assets have been replaced or removed.

## What it does

Each slide can be an image, an uploaded video, or a YouTube or Vimeo link. Editors set slides up in ACF fields.

- Image slides move on after a set delay.
- Video slides pause the autoplay timer and move on when the video ends.
- If the browser blocks autoplay, the slide falls back to the timer.
- Uploaded videos get a play/pause button.
- With only one slide, Swiper isn't started at all. The video just loops.
- The first image loads right away with high fetch priority. The rest load lazily.
- The first slide zooms out once on load. That's turned off for people who prefer reduced motion.

## Files

| File | What it is |
|---|---|
| `hero-slider.php` | Block template |
| `hero-slider-fields.php` | ACF field group (slides, autoplay, delay) |
| `hero-slider.scss` | Block styles |
| `hero-slider.js` | Slider setup and video handling |
| `hero-slider.json` | Block settings read by the registration code |
| `scss/_breakpoints.scss` | Breakpoints and the `responsive()` mixin |
| `scss/_variables.scss` | Colour tokens and font sizes (placeholder values) |
| `scss/_mixinsAndFunctions.scss` | `rem()`, `clamped()`, `color()` and other helpers |
| `scss/style.scss` | Entry file so the SCSS compiles on its own |

## Dependencies

- **ACF Pro** for the fields and block registration
- **Swiper.js** for the slider. Load it before `hero-slider.js`.
- **Bootstrap** utility classes (`p-0`, `m-0`, `d-none`)
- **AOS** for the fade-in on the play/pause button
- **An icon font** for the arrow and play/pause icons. Not included.

## What I wrote and what came from libraries

I wrote the template, the field group, the SCSS, the JavaScript and the SCSS helpers.

Swiper handles the fade transition, the dots and the arrows. My code decides when Swiper should move. It stops autoplay for video slides, starts it again for image slides, and keeps the play/pause button in sync.

ACF Pro gives the editing fields. Bootstrap and AOS add a few classes and a fade.

## How to register it

1. Copy the folder to `your-theme/blocks/hero-slider/`.
2. Compile the SCSS into your theme's stylesheet.
3. Enqueue Swiper, then `hero-slider.js`.
4. Register the block:

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

On the original project, a small plugin I wrote does this for every folder in `blocks/`. It also loads each block's JS only on pages that use the block.

## What I'd improve

- The arrow and play/pause buttons remove their outline on `:focus-visible`. Keyboard users can't see which button is focused. I'd put a visible focus style back.
- YouTube and Vimeo slides have no play/pause button. Only uploaded videos do.
- The YouTube embed uses `youtube.com`. I'd switch it to `youtube-nocookie.com`.
