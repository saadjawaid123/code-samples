<?php
if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group([
        'key'    => 'group_block_hero_slider',
        'title'  => 'Block – Hero Slider',
        'fields' => [
            [
                'key'          => 'field_hero_slider_slides',
                'label'        => 'Slides',
                'name'         => 'slides',
                'type'         => 'repeater',
                'layout'       => 'block',
                'button_label' => 'Add Slide',
                'min'          => 1,
                'sub_fields'   => [
                    [
                        'key'           => 'field_hero_slider_media_type',
                        'label'         => 'Slide Type',
                        'name'          => 'media_type',
                        'type'          => 'select',
                        'choices'       => [
                            'image' => 'Image',
                            'video' => 'Video',
                        ],
                        'default_value' => 'image',
                        'ui'            => 1,
                    ],
                    [
                        'key'               => 'field_hero_slider_image',
                        'label'             => 'Image',
                        'name'              => 'image',
                        'type'              => 'image',
                        'instructions'      => 'Recommended: a wide, full-bleed image.',
                        'return_format'     => 'array',
                        'preview_size'      => 'medium',
                        'conditional_logic' => [
                            [[
                                'field'    => 'field_hero_slider_media_type',
                                'operator' => '==',
                                'value'    => 'image',
                            ]],
                        ],
                    ],
                    [
                        'key'               => 'field_hero_slider_video_source',
                        'label'             => 'Video Source',
                        'name'              => 'video_source',
                        'type'              => 'select',
                        'choices'           => [
                            'attachment' => 'Attachment (Upload)',
                            'link'       => 'External Link',
                        ],
                        'default_value'     => 'attachment',
                        'ui'                => 1,
                        'conditional_logic' => [
                            [[
                                'field'    => 'field_hero_slider_media_type',
                                'operator' => '==',
                                'value'    => 'video',
                            ]],
                        ],
                    ],
                    [
                        'key'               => 'field_hero_slider_video_file',
                        'label'             => 'Upload Video',
                        'name'              => 'video_file',
                        'type'              => 'file',
                        'instructions'      => 'Upload an MP4 or WebM video file.',
                        'required'          => 0,
                        'return_format'     => 'url',
                        'mime_types'        => 'mp4,webm',
                        'library'           => 'all',
                        'conditional_logic' => [
                            [
                                [
                                    'field'    => 'field_hero_slider_media_type',
                                    'operator' => '==',
                                    'value'    => 'video',
                                ],
                                [
                                    'field'    => 'field_hero_slider_video_source',
                                    'operator' => '==',
                                    'value'    => 'attachment',
                                ],
                            ],
                        ],
                    ],
                    [
                        'key'               => 'field_hero_slider_video_link',
                        'label'             => 'Video URL',
                        'name'              => 'video_link',
                        'type'              => 'url',
                        'instructions'      => 'Enter a YouTube, Vimeo, or direct video URL.',
                        'required'          => 0,
                        'conditional_logic' => [
                            [
                                [
                                    'field'    => 'field_hero_slider_media_type',
                                    'operator' => '==',
                                    'value'    => 'video',
                                ],
                                [
                                    'field'    => 'field_hero_slider_video_source',
                                    'operator' => '==',
                                    'value'    => 'link',
                                ],
                            ],
                        ],
                    ],
                    [
                        'key'               => 'field_hero_slider_video_poster',
                        'label'             => 'Poster Image',
                        'name'              => 'video_poster',
                        'type'              => 'image',
                        'instructions'      => 'Shown before the video loads.',
                        'required'          => 0,
                        'return_format'     => 'url',
                        'preview_size'      => 'medium',
                        'conditional_logic' => [
                            [[
                                'field'    => 'field_hero_slider_media_type',
                                'operator' => '==',
                                'value'    => 'video',
                            ]],
                        ],
                    ],
                ],
            ],
            [
                'key'           => 'field_hero_slider_autoplay',
                'label'         => 'Autoplay Slides',
                'name'          => 'autoplay',
                'type'          => 'true_false',
                'instructions'  => 'Video slides always wait for the video to finish before advancing.',
                'default_value' => 1,
                'ui'            => 1,
            ],
            [
                'key'               => 'field_hero_slider_autoplay_delay',
                'label'             => 'Slide Duration (seconds)',
                'name'              => 'autoplay_delay',
                'type'              => 'number',
                'instructions'      => 'How long an image slide stays on screen.',
                'default_value'     => 6,
                'min'               => 1,
                'step'              => 0.5,
                'conditional_logic' => [
                    [[
                        'field'    => 'field_hero_slider_autoplay',
                        'operator' => '==',
                        'value'    => '1',
                    ]],
                ],
            ],
        ],
        'location' => [
            [[
                'param'    => 'block',
                'operator' => '==',
                'value'    => 'acf/hero-slider',
            ]],
        ],
    ]);
}
