<?php
$block_id = esc_attr($block['id']);
$class_name = 'block-hero-slider';
if (!empty($block['className'])) {
    $class_name .= ' ' . esc_attr($block['className']);
}
if (!empty($block['align'])) {
    $class_name .= ' align' . esc_attr($block['align']);
}

$slides         = get_field('slides') ?: [];
$autoplay       = get_field('autoplay');
$autoplay_delay = (float) (get_field('autoplay_delay') ?: 6);

// A lone slide behaves like the hero video block: it just loops on itself.
$is_single_slide = count($slides) < 2;

// Rendered width of a 1920x700 image under object-fit: cover in the wrapper
// (400px tall up to md, clamp(525px, 325px + 19.53vw, 700px) above), so the
// browser picks the right srcset candidate at every breakpoint.
$slide_sizes = '(max-width: 768px) 1097px, (max-width: 1024px) 1440px, (max-width: 1920px) calc(891px + 53.57vw), 100vw';

// Only the first rendered slide is an LCP candidate; the rest load lazily.
$is_first_slide = true;
?>
<section
        id="<?php echo $block_id; ?>"
        class="<?php echo $class_name; ?> p-0"
>

    <div class="hero-slider__wrapper">

        <?php if (!empty($slides)) : ?>

            <div class="swiper hero-slider__swiper"
                 data-autoplay="<?php echo $autoplay ? '1' : '0'; ?>"
                 data-autoplay-delay="<?php echo esc_attr((int) round($autoplay_delay * 1000)); ?>"
                 data-loop="<?php echo $is_single_slide ? '0' : '1'; ?>"
            >
                <div class="swiper-wrapper">

                    <?php foreach ($slides as $slide) :
                        $media_type   = $slide['media_type'] ?? 'image';
                        $image        = $slide['image'] ?? null;
                        $video_source = $slide['video_source'] ?? 'attachment';
                        $video_file   = $slide['video_file'] ?? '';
                        $video_poster = $slide['video_poster'] ?? '';
                        $video_link   = $slide['video_link'] ?? '';

                        $is_iframe = ($media_type === 'video' && $video_source === 'link' && $video_link &&
                            (strpos($video_link, 'youtube') !== false ||
                                strpos($video_link, 'youtu.be') !== false ||
                                strpos($video_link, 'vimeo') !== false));

                        // Nothing chosen for this row — skip it rather than render an empty slide.
                        if ($media_type === 'image' && empty($image)) continue;
                        if ($media_type === 'video' && empty($video_file) && empty($video_link)) continue;

                        $slide_class    = $is_first_slide ? ' hero-slider__slide--intro' : '';
                        $is_lcp_slide   = $is_first_slide;
                        $is_first_slide = false;
                        ?>

                        <div class="swiper-slide hero-slider__slide hero-slider__slide--<?php echo esc_attr($media_type); ?><?php echo $slide_class; ?>"
                             data-type="<?php echo $is_iframe ? 'iframe' : esc_attr($media_type); ?>">

                            <?php if ($media_type === 'image') : ?>

                                <?php
                                $image_attrs = [
                                    'class' => 'hero-slider__image',
                                    'sizes' => $slide_sizes,
                                ];
                                if ($is_lcp_slide) {
                                    $image_attrs['loading']       = 'eager';
                                    $image_attrs['fetchpriority'] = 'high';
                                } else {
                                    $image_attrs['loading']  = 'lazy';
                                    $image_attrs['decoding'] = 'async';
                                }

                                // sample_optimize_output() strips core's "sizes=auto" from these again.
                                $image_id = is_array($image) ? ($image['ID'] ?? 0) : (int) $image;
                                echo wp_get_attachment_image($image_id, 'full', false, $image_attrs);
                                ?>

                            <?php elseif ($is_iframe) : ?>

                                <?php
                                $embed_url = $video_link;

                                if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $video_link, $matches)) {
                                    $embed_url = 'https://www.youtube.com/embed/' . $matches[1] . '?autoplay=1&mute=1&loop=1&playlist=' . $matches[1] . '&controls=0&playsinline=1';
                                } elseif (preg_match('/vimeo\.com\/(\d+)/', $video_link, $matches)) {
                                    $embed_url = 'https://player.vimeo.com/video/' . $matches[1] . '?autoplay=1&muted=1&loop=1&background=1';
                                }
                                ?>

                                <iframe
                                        class="hero-slider__player hero-slider__player--iframe"
                                        src="<?php echo esc_url($embed_url); ?>"
                                        frameborder="0"
                                        allow="autoplay; fullscreen"
                                        allowfullscreen
                                        aria-hidden="true"
                                        role="presentation"
                                ></iframe>

                            <?php else : ?>

                                <?php $video_url = ($video_source === 'link') ? $video_link : $video_file; ?>

                                <video
                                        class="hero-slider__player"
                                        muted
                                        playsinline
                                        preload="metadata"
                                        <?php if ($is_single_slide) : ?>autoplay loop<?php endif; ?>
                                        <?php if ($video_poster) : ?>poster="<?php echo esc_url($video_poster); ?>"<?php endif; ?>
                                        src="<?php echo esc_url($video_url); ?>"
                                        aria-hidden="true"
                                        role="presentation"
                                ></video>

                                <button
                                        class="hero-slider__toggle"
                                        type="button"
                                        aria-label="Pause video"
                                        data-state="playing"
                                        data-aos="fade-in"
                                        data-aos-delay="800"
                                >
                                    <span class="hero-slider__toggle-icon hero-slider__toggle-icon--pause" aria-hidden="true">
                                        <i class="icon-pause"></i>
                                    </span>
                                    <span class="hero-slider__toggle-icon hero-slider__toggle-icon--play d-none" aria-hidden="true">
                                        <i class="icon-play"></i>
                                    </span>
                                </button>

                            <?php endif; ?>

                        </div>

                    <?php endforeach; ?>

                </div><!-- /.swiper-wrapper -->
            </div><!-- /.swiper -->

            <?php if (!$is_single_slide) : ?>
                <div class="hero-slider__pagination"></div>

                <button class="hero-slider__btn hero-slider__btn--prev" type="button" aria-label="Previous slide">
                    <i class="icon-arrow-left" aria-hidden="true"></i>
                </button>
                <button class="hero-slider__btn hero-slider__btn--next" type="button" aria-label="Next slide">
                    <i class="icon-arrow-right" aria-hidden="true"></i>
                </button>
            <?php endif; ?>

        <?php else : ?>

            <div class="hero-slider__placeholder">
                <p class="m-0">No slides added yet.</p>
            </div>

        <?php endif; ?>

    </div>

</section>
