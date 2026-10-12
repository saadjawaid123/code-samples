# Cards with Header (ACF block)

A section with a heading and six animated cards. Each card has a coloured blob background, a logo, an image, a description and an optional call-to-action button.

- **Desktop:** hovering a card animates the blob, logo, image, text and button into a new layout.
- **Mobile (≤ 768px):** the same animation is tied to scroll position, so it plays forward as the card scrolls up the screen and reverses when scrolling back.

How the mobile version works:
- JS writes each card's progress (0 to 1) to a CSS custom property, `--chwh-p`.
- The SCSS turns that into a negative `animation-delay` on paused keyframes, which jumps the animation to that point.

## Files

| File | Purpose |
|---|---|
| `cards-with-header.php` | Block template (PHP/HTML) |
| `cards-with-header-fields.php` | ACF field group (header fields, 6-card repeater, CTA group) |
| `cards-with-header.scss` | Hover styles, scroll-driven keyframes, gradient map |
| `cards-with-header.js` | Mobile scroll-progress driver |
| `cards-with-header.json` | Block metadata read by the registration code |
| `helpers.php` | `get_cta_link()` and `get_cta_classes()` from the theme's `functions.php` |
| `scss/_breakpoints.scss` | Breakpoint map and `responsive()` mixin |
| `scss/_variables.scss` | Colour tokens and font sizes |
| `scss/_mixinsAndFunctions.scss` | `rem()`, `clamped()`, `color()`, `img-init()` and other mixins |
| `scss/style.scss` | Entry file so the sample compiles on its own |

## Dependencies

- **ACF Pro**: field group, `get_field()`, `have_rows()`, `acf_register_block_type()`
- **Bootstrap**: grid (`container`, `row`, `col-*`) and utilities (`d-flex`, `gap-2`, `mb-*`)
- **AOS**: `data-aos` fade animations on the header and cards
- **Icon font** (IcoMoon): `icon-arrow-right` on the button. Not included.
- **Theme styles not included:**
  - `bg-*` / `color-*` utility classes, which are generated from the `$colors` map
  - `.c-btn` button styles and `.heading-pill`
  - the inline SVG `<clipPath id="blob-shape">` used by `clip-path: url(#blob-shape)`. The client's SVG was removed. Without it the blob shows as a plain rectangle.

Swiper is not used in this block.

## What I wrote vs. libraries

**I wrote:**
- the template, field group, SCSS, JS and helpers
- the hover animation and the scroll-driven mobile version
- the gradient map with its `@each` loop

The JS:
- reads every card's position before writing any styles, to avoid layout thrashing
- throttles updates with `requestAnimationFrame`
- uses a passive scroll listener
- switches on and off with `matchMedia`

**From libraries:**
- ACF provides the field UI and data access.
- Bootstrap provides the grid and utility classes.
- AOS provides the entrance fades.

## Registering the block

1. Copy the folder to `your-theme/blocks/cards-with-header/`.
2. Load `helpers.php` from `functions.php`.
3. Compile the SCSS into the theme's stylesheet.
4. Enqueue `cards-with-header.js`.
5. Register the block on `acf/init`:

```php
require_once get_template_directory() . '/blocks/cards-with-header/helpers.php';

add_action('acf/init', function () {
    $dir  = get_template_directory() . '/blocks/cards-with-header';
    $meta = json_decode(file_get_contents($dir . '/cards-with-header.json'), true);

    acf_register_block_type([
        'name'            => $meta['name'],
        'title'           => $meta['title'],
        'render_template' => $dir . '/cards-with-header.php',
        'category'        => $meta['category'],
        'icon'            => $meta['icon'],
        'supports'        => $meta['supports'],
    ]);

    include_once $dir . '/cards-with-header-fields.php';
});
```

In the original project, a small plugin I wrote does this for every folder under `blocks/`. It also enqueues each block's JS only on pages that use the block.

## Notes

- The colour token names (`primary1`, `secondary1`, …) and font names are placeholders.
- The field group expects exactly six cards.
