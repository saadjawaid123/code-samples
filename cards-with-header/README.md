# Cards with Header (ACF block)

A WordPress block with a heading and six animated cards. It's anonymized from a real client project and shared with permission. Client names, colours, fonts and assets have been replaced or removed.

## What it does

Each card has a coloured background shape, a logo, an image, a short description and an optional button.

- **Desktop:** hovering a card moves the logo, image, text and button into a new layout.
- **Phones (768px and below):** the same animation plays as the card scrolls up the screen. It reverses when you scroll back.

How the phone version works:
- The JavaScript works out how far each card has scrolled, as a number from 0 to 1.
- It writes that number to a CSS variable, `--chwh-p`.
- The SCSS turns the number into a negative `animation-delay` on paused keyframes. That jumps the animation to the matching point.

## Files

| File | What it is |
|---|---|
| `cards-with-header.php` | Block template |
| `cards-with-header-fields.php` | ACF field group (heading, six cards, button settings) |
| `cards-with-header.scss` | Hover styles, scroll keyframes, gradient map |
| `cards-with-header.js` | Scroll progress for phones |
| `cards-with-header.json` | Block settings read by the registration code |
| `helpers.php` | `get_cta_link()` and `get_cta_classes()` from the theme |
| `scss/_breakpoints.scss` | Breakpoints and the `responsive()` mixin |
| `scss/_variables.scss` | Colour tokens and font sizes (placeholder values) |
| `scss/_mixinsAndFunctions.scss` | `rem()`, `clamped()`, `color()`, `img-init()` and other helpers |
| `scss/style.scss` | Entry file so the SCSS compiles on its own |

## Dependencies

- **ACF Pro** for the fields and block registration
- **Bootstrap** grid and utility classes
- **AOS** for the fade-ins on the heading and cards
- **An icon font** for the button arrow. Not included.
- **From the original theme, not included:**
  - the `bg-*` colour classes and the button styles (`.c-btn`, `.heading-pill`)
  - an inline SVG `<clipPath id="blob-shape">` that gives the background its shape. It was removed with the other client assets. Without it the background is a plain rectangle.

This block doesn't use Swiper.

## What I wrote and what came from libraries

I wrote the template, the field group, the SCSS, the JavaScript and the two PHP helpers. That includes the hover animation, the scroll version and the gradient map that the SCSS loops over with `@each`.

The JavaScript:
- reads every card's position first and only then writes the styles, so the browser doesn't have to redo layout between reads
- runs at most once per frame with `requestAnimationFrame`
- uses a passive scroll listener
- switches itself on and off with `matchMedia` when the screen size changes

ACF Pro gives the editing fields. Bootstrap gives the grid. AOS gives the fades.

## How to register it

1. Copy the folder to `your-theme/blocks/cards-with-header/`.
2. Load `helpers.php`.
3. Compile the SCSS into your theme's stylesheet.
4. Enqueue `cards-with-header.js`.
5. Register the block:

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

On the original project, a small plugin I wrote does this for every folder in `blocks/`. It also loads each block's JS only on pages that use the block.

## What I'd improve

- On desktop, the description and button only show on hover. There's no `:focus-within` rule, so a keyboard user can tab to a button they can't see. I'd show the same state on focus.
- The animation moves `top`, `left` and `height`. Those make the browser recalculate layout on every frame. I'd rebuild it with `transform` where I can.
- The cards use `transition: all` with a long `will-change` list. I'd narrow both to the properties that actually change.
