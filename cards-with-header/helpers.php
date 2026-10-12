<?php
// Helper function to process the CTA group logic
function get_cta_link($group)
{
    if (empty($group['cta_title'])) return null;

    $url = '';
    $type = $group['cta_type'];

    if ($type === 'attachment') {
        $url = $group['cta_attachment'];
    } elseif ($type === 'internal') {
        $url = $group['page_link'];
    } else {
        $url = $group['external_link'];
    }

    return [
        'title' => $group['cta_title'],
        'url' => $url
    ];
}


function get_cta_classes($cta_group, $default_bg = 'primary1', $extra = '', $default_hover = 'primary2')
{
    $bg = !empty($cta_group['cta_background_color']) ? $cta_group['cta_background_color'] : $default_bg;
    $hover_color = !empty($cta_group['cta_background_hover_color']) ? $cta_group['cta_background_hover_color'] : $default_hover;
    $hover = $hover_color ? 'hover-' . $hover_color : '';
    $design = (!empty($cta_group['cta_design_type']) && $cta_group['cta_design_type'] === 'c-btn-full') ? 'c-btn-full' : 'c-btn-empty';

    $classes = [
        'c-btn',
        $extra,
        'justify-content-between',
        $design,
        'd-flex',
        'align-items-center',
        $bg,
        $hover,
        $design === 'c-btn-empty' ? 'font-xs' : '',
        'border-0',
    ];

    return implode(' ', array_filter($classes));
}
