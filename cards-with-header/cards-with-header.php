<?php
$block_id = esc_attr($block['id']);
$class_name = 'block-cards-with-header';
if (!empty($block['className'])) {
    $class_name .= ' ' . esc_attr($block['className']);
}
if (!empty($block['align'])) {
    $class_name .= ' align' . esc_attr($block['align']);
}

// Fetch header fields
$sub_heading = get_field('sub_heading');
$sub_heading_background_color = get_field('sub_heading_background_color') ?: 'bg-primary1';
$heading = get_field('heading');
$description = get_field('description');
// cards-with-header.js (mobile scroll-driven animation) is auto-enqueued by the
// acf-blocks-registration plugin on pages that use this block.
?>

<section id="<?php echo $block_id; ?>" class="<?php echo $class_name; ?> pt-5">
    <div class="container">
        <div class="row d-flex justify-content-center mb-5">
            <div class="col-12 col-lg-10 d-flex flex-column align-items-center gap-2">

                <?php if ($sub_heading) : ?>
                    <p class="heading-pill font-700 mb-0 <?= esc_attr($sub_heading_background_color); ?> color-white"
                       data-aos="fade-down">
                        <?= wp_kses_post($sub_heading); ?>
                    </p>
                <?php endif; ?>

                <?php if ($heading) : ?>
                    <h2 class="h1 text-center"
                        data-aos="fade-down"
                        data-aos-delay="150">
                        <?= wp_kses_post($heading); ?>
                    </h2>
                <?php endif; ?>

                <?php if ($description) : ?>
                    <div class="font-300 text-center"
                         data-aos="fade-down"
                         data-aos-delay="300">
                        <?= wp_kses_post($description); ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
        <?php if (have_rows('cards')) : ?>
            <div class="row">
                <?php
                $i = 0;
                while (have_rows('cards')) : the_row();
                    $bg_color = get_sub_field('background_color');
                    $card_description = get_sub_field('description');
                    $logo = get_sub_field('logo');
                    $logo_size = get_sub_field('logo_size') ?: '';
                    $image = get_sub_field('image');
                    $cta = get_sub_field('cta_group') ?: [];

                    // CTA Link
                    $cta_title = $cta['cta_title'] ?? '';
                    $cta_type = $cta['cta_type'] ?? '';
                    $link_url = '';
                    $target = '';

                    if ($cta_title) {
                        if ($cta_type === 'attachment') {
                            $link_url = $cta['cta_attachment'] ?? '';
                            $target = ' target="_blank" rel="noopener"';
                        } elseif ($cta_type === 'internal') {
                            $link_url = $cta['page_link'] ?? '';
                        } elseif ($cta_type === 'external') {
                            $link_url = $cta['external_link'] ?? '';
                            $target = ' target="_blank" rel="noopener noreferrer"';
                        }
                    }

                    $cta_classes = get_cta_classes($cta, 'secondary1');

                    // Stagger the cards: 0ms, 250ms, 500ms...
                    $delay = $i * 250;
                    ?>
                    <div class="col-12 col-lg-4 mb-4"
                         data-aos="fade-up"
                         data-aos-delay="<?php echo esc_attr($delay); ?>">

                        <div class="card-item">
                            <div class="blob <?php echo esc_attr($bg_color); ?>"></div>
                            <div class="card-details parent-<?php echo esc_attr($bg_color); ?>">

                                <?php if (!empty($logo['url'])) : ?>
                                    <div class="card-logo <?php echo esc_attr($logo_size); ?>">
                                        <img src="<?php echo esc_url($logo['url']); ?>"
                                             alt="<?php echo esc_attr($logo['alt']); ?>">
                                    </div>
                                <?php endif; ?>

                                <?php if ($card_description) : ?>
                                    <div class="card-description">
                                        <h4 class="font-400"><?php echo esc_html($card_description); ?></h4>
                                    </div>
                                <?php endif; ?>

                                <?php if ($cta_title && $link_url) : ?>
                                    <div class="card-cta">
                                        <a href="<?php echo esc_url($link_url); ?>"
                                           title="<?php echo esc_attr($cta_title); ?>"
                                           class="<?php echo esc_attr($cta_classes); ?>"<?php echo $target; ?>>
                                            <span><?php echo esc_html($cta_title); ?></span>
                                            <i class="icon-arrow-right"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($image['url'])) : ?>
                                    <div class="card-image">
                                        <img src="<?php echo esc_url($image['url']); ?>"
                                             class="img-fluid"
                                             alt="<?php echo esc_attr($image['alt']); ?>">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php
                    $i++;
                endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</section>