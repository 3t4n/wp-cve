<?php

function smart_blocks_attributes_news_module_one() {
    $attrs = [
        "id" => [
            "type" => "string"
        ],
        "style" => [
            "type" => "string"
        ],
        "headerTitle" => [
            "type" => "string"
        ],
        "headerStyle" => [
            "type" => "string",
            "default" => "sb-title-style1"
        ],
        "headerColor" => [
            "type" => "string"
        ],
        "headerShortBorderColor" => [
            "type" => "string"
        ],
        "headerLongBorderColor" => [
            "type" => "string"
        ],
        "headerTitleTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "featuredTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "sideTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "excerptTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "metasTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryBackgroundColor" => [
            "type" => "string"
        ],
        "categoryTextColor" => [
            "type" => "string"
        ],
        "categoryBackgroundHoverColor" => [
            "type" => "string"
        ],
        "categoryTextHoverColor" => [
            "type" => "string"
        ],
        "titleColor" => [
            "type" => "string"
        ],
        "titleHoverColor" => [
            "type" => "string"
        ],
        "excerptColor" => [
            "type" => "string"
        ],
        "postMetasColor" => [
            "type" => "string"
        ],
        "postsPostType" => [
            "type" => "string",
            "default" => "post"
        ],
        "excludePosts" => [
            "type" => "array"
        ],
        "order" => [
            "type" => "string",
            "default" => "desc"
        ],
        "orderBy" => [
            "type" => "string",
            "default" => "date"
        ],
        "categories" => [
            "type" => "object"
        ],
        "offset" => [
            "type" => "number",
            "default" => 0
        ],
        "featuredTitleMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "featuredImageSize" => [
            "type" => "string",
            "default" => "large"
        ],
        "featuredImageHeight" => [
            "type" => "number",
            "default" => 100
        ],
        "featuredExcerptLength" => [
            "type" => "number",
            "default" => 0
        ],
        "featuredPostAuthor" => [
            "type" => "boolean",
            "default" => true
        ],
        "featuredPostDate" => [
            "type" => "boolean",
            "default" => true
        ],
        "featuredPostComments" => [
            "type" => "boolean",
            "default" => true
        ],
        "featuredPostCategory" => [
            "type" => "boolean",
            "default" => true
        ],
        "sideImageSize" => [
            "type" => "string",
            "default" => "large"
        ],
        "sideImageHeight" => [
            "type" => "number",
            "default" => 100
        ],
        "sideExcerptLength" => [
            "type" => "number",
            "default" => 100
        ],
        "sidePostAuthor" => [
            "type" => "boolean",
            "default" => true
        ],
        "sidePostDate" => [
            "type" => "boolean",
            "default" => true
        ],
        "sidePostComments" => [
            "type" => "boolean",
            "default" => true
        ],
        "sidePostCategory" => [
            "type" => "boolean",
            "default" => true
        ],
        "sideTitleMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "dateFormat" => [
            "type" => "string",
            "default" => "default"
        ],
        "customDateFormat" => [
            "type" => "string",
            "default" => "F j, Y"
        ],
        "imageBorderRadius" => [
            "type" => "number",
            "default" => 0
        ]
    ];
    return array_merge($attrs, smart_blocks_global_attributes());
}

function smart_blocks_attributes_news_module_two() {
    $attrs = [
        "id" => [
            "type" => "string"
        ],
        "style" => [
            "type" => "string"
        ],
        "headerTitle" => [
            "type" => "string"
        ],
        "headerStyle" => [
            "type" => "string",
            "default" => "sb-title-style1"
        ],
        "headerColor" => [
            "type" => "string"
        ],
        "headerShortBorderColor" => [
            "type" => "string"
        ],
        "headerLongBorderColor" => [
            "type" => "string"
        ],
        "headerTitleTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "featuredTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "sideTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "excerptTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "metasTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryBackgroundColor" => [
            "type" => "string"
        ],
        "categoryTextColor" => [
            "type" => "string"
        ],
        "categoryBackgroundHoverColor" => [
            "type" => "string"
        ],
        "categoryTextHoverColor" => [
            "type" => "string"
        ],
        "titleColor" => [
            "type" => "string"
        ],
        "titleHoverColor" => [
            "type" => "string"
        ],
        "excerptColor" => [
            "type" => "string"
        ],
        "postMetasColor" => [
            "type" => "string"
        ],
        "postsPostType" => [
            "type" => "string",
            "default" => "post"
        ],
        "excludePosts" => [
            "type" => "array"
        ],
        "order" => [
            "type" => "string",
            "default" => "desc"
        ],
        "orderBy" => [
            "type" => "string",
            "default" => "date"
        ],
        "categories" => [
            "type" => "object"
        ],
        "offset" => [
            "type" => "number"
        ],
        "featuredTitleMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "featuredImageSize" => [
            "type" => "string",
            "default" => "large"
        ],
        "featuredImageHeight" => [
            "type" => "number",
            "default" => 80
        ],
        "featuredExcerptLength" => [
            "type" => "number",
            "default" => 200
        ],
        "featuredPostAuthor" => [
            "type" => "boolean",
            "default" => true
        ],
        "featuredPostDate" => [
            "type" => "boolean",
            "default" => true
        ],
        "featuredPostComments" => [
            "type" => "boolean",
            "default" => true
        ],
        "featuredPostCategory" => [
            "type" => "boolean",
            "default" => true
        ],
        "sideImageSize" => [
            "type" => "string",
            "default" => "large"
        ],
        "sideImageHeight" => [
            "type" => "number",
            "default" => 86
        ],
        "sideExcerptLength" => [
            "type" => "number",
            "default" => 0
        ],
        "sidePostAuthor" => [
            "type" => "boolean",
            "default" => true
        ],
        "sidePostDate" => [
            "type" => "boolean",
            "default" => true
        ],
        "sidePostComments" => [
            "type" => "boolean",
            "default" => true
        ],
        "sidePostCategory" => [
            "type" => "boolean",
            "default" => true
        ],
        "sideTitleMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "dateFormat" => [
            "type" => "string",
            "default" => "default"
        ],
        "customDateFormat" => [
            "type" => "string",
            "default" => "F j, Y"
        ],
        "imageBorderRadius" => [
            "type" => "number",
            "default" => 0
        ]
    ];
    return array_merge($attrs, smart_blocks_global_attributes());
}

function smart_blocks_attributes_news_module_three() {
    $attrs = [
        "id" => [
            "type" => "string"
        ],
        "style" => [
            "type" => "string"
        ],
        "headerTitle" => [
            "type" => "string"
        ],
        "headerStyle" => [
            "type" => "string",
            "default" => "sb-title-style1"
        ],
        "headerColor" => [
            "type" => "string"
        ],
        "headerShortBorderColor" => [
            "type" => "string"
        ],
        "headerLongBorderColor" => [
            "type" => "string"
        ],
        "headerTitleTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "featuredTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "sideTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "excerptTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "metasTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryBackgroundColor" => [
            "type" => "string"
        ],
        "categoryTextColor" => [
            "type" => "string"
        ],
        "categoryBackgroundHoverColor" => [
            "type" => "string"
        ],
        "categoryTextHoverColor" => [
            "type" => "string"
        ],
        "featuredTitleColor" => [
            "type" => "string"
        ],
        "sideTitleColor" => [
            "type" => "string"
        ],
        "sideTitleHoverColor" => [
            "type" => "string"
        ],
        "titleHoverColor" => [
            "type" => "string"
        ],
        "excerptColor" => [
            "type" => "string"
        ],
        "postMetasColor" => [
            "type" => "string"
        ],
        "postsPostType" => [
            "type" => "string",
            "default" => "post"
        ],
        "excludePosts" => [
            "type" => "array"
        ],
        "order" => [
            "type" => "string",
            "default" => "desc"
        ],
        "orderBy" => [
            "type" => "string",
            "default" => "date"
        ],
        "categories" => [
            "type" => "object"
        ],
        "offset" => [
            "type" => "number"
        ],
        "featuredTitleMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "featuredImageSize" => [
            "type" => "string",
            "default" => "large"
        ],
        "featuredPostAuthor" => [
            "type" => "boolean",
            "default" => true
        ],
        "featuredPostDate" => [
            "type" => "boolean",
            "default" => true
        ],
        "featuredPostComments" => [
            "type" => "boolean",
            "default" => true
        ],
        "featuredPostCategory" => [
            "type" => "boolean",
            "default" => true
        ],
        "sideImageSize" => [
            "type" => "string",
            "default" => "large"
        ],
        "sideImageHeight" => [
            "type" => "number",
            "default" => 70
        ],
        "sidePostAuthor" => [
            "type" => "boolean",
            "default" => true
        ],
        "sidePostDate" => [
            "type" => "boolean",
            "default" => true
        ],
        "sidePostComments" => [
            "type" => "boolean",
            "default" => true
        ],
        "sidePostCategory" => [
            "type" => "boolean",
            "default" => true
        ],
        "sideTitleMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "dateFormat" => [
            "type" => "string",
            "default" => "default"
        ],
        "customDateFormat" => [
            "type" => "string",
            "default" => "F j, Y"
        ],
        "imageBorderRadius" => [
            "type" => "number",
            "default" => 0
        ]
    ];
    return array_merge($attrs, smart_blocks_global_attributes());
}

function smart_blocks_attributes_news_module_four() {
    $attrs = [
        "id" => [
            "type" => "string"
        ],
        "style" => [
            "type" => "string"
        ],
        "headerTitle" => [
            "type" => "string"
        ],
        "headerStyle" => [
            "type" => "string",
            "default" => "sb-title-style1"
        ],
        "headerColor" => [
            "type" => "string"
        ],
        "headerShortBorderColor" => [
            "type" => "string"
        ],
        "headerLongBorderColor" => [
            "type" => "string"
        ],
        "headerTitleTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "topTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "bottomTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "excerptTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "metasTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryBackgroundColor" => [
            "type" => "string"
        ],
        "categoryTextColor" => [
            "type" => "string"
        ],
        "categoryBackgroundHoverColor" => [
            "type" => "string"
        ],
        "categoryTextHoverColor" => [
            "type" => "string"
        ],
        "titleColor" => [
            "type" => "string"
        ],
        "titleHoverColor" => [
            "type" => "string"
        ],
        "excerptColor" => [
            "type" => "string"
        ],
        "postMetasColor" => [
            "type" => "string"
        ],
        "postsPostType" => [
            "type" => "string",
            "default" => "post"
        ],
        "excludePosts" => [
            "type" => "array"
        ],
        "order" => [
            "type" => "string",
            "default" => "desc"
        ],
        "orderBy" => [
            "type" => "string",
            "default" => "date"
        ],
        "categories" => [
            "type" => "object"
        ],
        "offset" => [
            "type" => "number"
        ],
        "topTitleMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "topImageSize" => [
            "type" => "string",
            "default" => "large"
        ],
        "topImageHeight" => [
            "type" => "number",
            "default" => 60
        ],
        "topPostAuthor" => [
            "type" => "boolean",
            "default" => true
        ],
        "topPostDate" => [
            "type" => "boolean",
            "default" => true
        ],
        "topPostComments" => [
            "type" => "boolean",
            "default" => true
        ],
        "topPostCategory" => [
            "type" => "boolean",
            "default" => true
        ],
        "bottomImageSize" => [
            "type" => "string",
            "default" => "large"
        ],
        "bottomImageHeight" => [
            "type" => "number",
            "default" => 70
        ],
        "bottomPostAuthor" => [
            "type" => "boolean",
            "default" => true
        ],
        "bottomPostDate" => [
            "type" => "boolean",
            "default" => true
        ],
        "bottomPostComments" => [
            "type" => "boolean",
            "default" => true
        ],
        "bottomPostCategory" => [
            "type" => "boolean",
            "default" => true
        ],
        "bottomTitleMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "dateFormat" => [
            "type" => "string",
            "default" => "default"
        ],
        "customDateFormat" => [
            "type" => "string",
            "default" => "F j, Y"
        ],
        "imageBorderRadius" => [
            "type" => "number",
            "default" => 0
        ]
    ];
    return array_merge($attrs, smart_blocks_global_attributes());
}

function smart_blocks_attributes_news_module_five() {
    $attrs = [
        "id" => [
            "type" => "string"
        ],
        "style" => [
            "type" => "string"
        ],
        "headerTitle" => [
            "type" => "string"
        ],
        "headerStyle" => [
            "type" => "string",
            "default" => "sb-title-style1"
        ],
        "headerColor" => [
            "type" => "string"
        ],
        "headerShortBorderColor" => [
            "type" => "string"
        ],
        "headerLongBorderColor" => [
            "type" => "string"
        ],
        "headerTitleTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "featuredTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "listingTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "excerptTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "metasTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryBackgroundColor" => [
            "type" => "string"
        ],
        "categoryTextColor" => [
            "type" => "string"
        ],
        "categoryBackgroundHoverColor" => [
            "type" => "string"
        ],
        "categoryTextHoverColor" => [
            "type" => "string"
        ],
        "titleColor" => [
            "type" => "string"
        ],
        "titleHoverColor" => [
            "type" => "string"
        ],
        "excerptColor" => [
            "type" => "string"
        ],
        "postMetasColor" => [
            "type" => "string"
        ],
        "postsPostType" => [
            "type" => "string",
            "default" => "post"
        ],
        "excludePosts" => [
            "type" => "array"
        ],
        "order" => [
            "type" => "string",
            "default" => "desc"
        ],
        "orderBy" => [
            "type" => "string",
            "default" => "date"
        ],
        "categories" => [
            "type" => "object"
        ],
        "offset" => [
            "type" => "number"
        ],
        "featuredTitleMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "featuredImageSize" => [
            "type" => "string",
            "default" => "large"
        ],
        "featuredImageHeight" => [
            "type" => "number",
            "default" => 70
        ],
        "featuredExcerptLength" => [
            "type" => "number",
            "default" => 200
        ],
        "featuredPostAuthor" => [
            "type" => "boolean",
            "default" => true
        ],
        "featuredPostDate" => [
            "type" => "boolean",
            "default" => true
        ],
        "featuredPostComments" => [
            "type" => "boolean",
            "default" => true
        ],
        "featuredPostCategory" => [
            "type" => "boolean",
            "default" => true
        ],
        "listingImageSize" => [
            "type" => "string",
            "default" => "large"
        ],
        "listingImageHeight" => [
            "type" => "number",
            "default" => 80
        ],
        "listingPostAuthor" => [
            "type" => "boolean",
            "default" => true
        ],
        "listingPostDate" => [
            "type" => "boolean",
            "default" => true
        ],
        "listingPostComments" => [
            "type" => "boolean",
            "default" => true
        ],
        "listingPostCategory" => [
            "type" => "boolean",
            "default" => true
        ],
        "listingTitleMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "dateFormat" => [
            "type" => "string",
            "default" => "default"
        ],
        "customDateFormat" => [
            "type" => "string",
            "default" => "F j, Y"
        ],
        "imageBorderRadius" => [
            "type" => "number",
            "default" => 0
        ]
    ];
    return array_merge($attrs, smart_blocks_global_attributes());
}

function smart_blocks_attributes_news_module_six() {
    $attrs = [
        "id" => [
            "type" => "string"
        ],
        "style" => [
            "type" => "string"
        ],
        "headerTitle" => [
            "type" => "string"
        ],
        "headerStyle" => [
            "type" => "string",
            "default" => "sb-title-style1"
        ],
        "headerColor" => [
            "type" => "string"
        ],
        "headerShortBorderColor" => [
            "type" => "string"
        ],
        "headerLongBorderColor" => [
            "type" => "string"
        ],
        "headerTitleTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "topTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "bottomTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "excerptTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "metasTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryBackgroundColor" => [
            "type" => "string"
        ],
        "categoryTextColor" => [
            "type" => "string"
        ],
        "categoryBackgroundHoverColor" => [
            "type" => "string"
        ],
        "categoryTextHoverColor" => [
            "type" => "string"
        ],
        "titleColor" => [
            "type" => "string"
        ],
        "titleHoverColor" => [
            "type" => "string"
        ],
        "bottomExcerptLength" => [
            "type" => "number",
            "default" => 0
        ],
        "excerptColor" => [
            "type" => "string"
        ],
        "postMetasColor" => [
            "type" => "string"
        ],
        "postsPostType" => [
            "type" => "string",
            "default" => "post"
        ],
        "excludePosts" => [
            "type" => "array"
        ],
        "order" => [
            "type" => "string",
            "default" => "desc"
        ],
        "orderBy" => [
            "type" => "string",
            "default" => "date"
        ],
        "categories" => [
            "type" => "object"
        ],
        "offset" => [
            "type" => "number"
        ],
        "topTitleMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "topImageSize" => [
            "type" => "string",
            "default" => "large"
        ],
        "topImageHeight" => [
            "type" => "number",
            "default" => 70
        ],
        "topPostAuthor" => [
            "type" => "boolean",
            "default" => true
        ],
        "topPostDate" => [
            "type" => "boolean",
            "default" => true
        ],
        "topPostComments" => [
            "type" => "boolean",
            "default" => true
        ],
        "topPostCategory" => [
            "type" => "boolean",
            "default" => true
        ],
        "bottomImageSize" => [
            "type" => "string",
            "default" => "large"
        ],
        "bottomImageHeight" => [
            "type" => "number",
            "default" => 70
        ],
        "bottomPostAuthor" => [
            "type" => "boolean",
            "default" => true
        ],
        "bottomPostDate" => [
            "type" => "boolean",
            "default" => true
        ],
        "bottomPostComments" => [
            "type" => "boolean",
            "default" => true
        ],
        "bottomPostCategory" => [
            "type" => "boolean",
            "default" => true
        ],
        "bottomTitleMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "dateFormat" => [
            "type" => "string",
            "default" => "default"
        ],
        "customDateFormat" => [
            "type" => "string",
            "default" => "F j, Y"
        ],
        "imageBorderRadius" => [
            "type" => "number",
            "default" => 0
        ]
    ];
    return array_merge($attrs, smart_blocks_global_attributes());
}

function smart_blocks_attributes_news_module_seven() {
    $attrs = [
        "id" => [
            "type" => "string"
        ],
        "style" => [
            "type" => "string"
        ],
        "headerTitle" => [
            "type" => "string"
        ],
        "headerStyle" => [
            "type" => "string",
            "default" => "sb-title-style1"
        ],
        "headerColor" => [
            "type" => "string"
        ],
        "headerShortBorderColor" => [
            "type" => "string"
        ],
        "headerLongBorderColor" => [
            "type" => "string"
        ],
        "headerTitleTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "featuredTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "listingTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "excerptTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "metasTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryBackgroundColor" => [
            "type" => "string"
        ],
        "categoryTextColor" => [
            "type" => "string"
        ],
        "categoryBackgroundHoverColor" => [
            "type" => "string"
        ],
        "categoryTextHoverColor" => [
            "type" => "string"
        ],
        "titleColor" => [
            "type" => "string"
        ],
        "titleHoverColor" => [
            "type" => "string"
        ],
        "excerptColor" => [
            "type" => "string"
        ],
        "postMetasColor" => [
            "type" => "string"
        ],
        "postsPostType" => [
            "type" => "string",
            "default" => "post"
        ],
        "excludePosts" => [
            "type" => "array"
        ],
        "order" => [
            "type" => "string",
            "default" => "desc"
        ],
        "orderBy" => [
            "type" => "string",
            "default" => "date"
        ],
        "categories" => [
            "type" => "object"
        ],
        "offset" => [
            "type" => "number"
        ],
        "featuredTitleMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "featuredImageSize" => [
            "type" => "string",
            "default" => "large"
        ],
        "featuredImageHeight" => [
            "type" => "number",
            "default" => 70
        ],
        "featuredExcerptLength" => [
            "type" => "number",
            "default" => 250
        ],
        "featuredPostAuthor" => [
            "type" => "boolean",
            "default" => true
        ],
        "featuredPostDate" => [
            "type" => "boolean",
            "default" => true
        ],
        "featuredPostComments" => [
            "type" => "boolean",
            "default" => true
        ],
        "featuredPostCategory" => [
            "type" => "boolean",
            "default" => true
        ],
        "listingImageSize" => [
            "type" => "string",
            "default" => "large"
        ],
        "listingImageHeight" => [
            "type" => "number",
            "default" => 80
        ],
        "listingPostAuthor" => [
            "type" => "boolean",
            "default" => true
        ],
        "listingPostDate" => [
            "type" => "boolean",
            "default" => true
        ],
        "listingPostComments" => [
            "type" => "boolean",
            "default" => true
        ],
        "listingPostCategory" => [
            "type" => "boolean",
            "default" => true
        ],
        "listingTitleMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "dateFormat" => [
            "type" => "string",
            "default" => "default"
        ],
        "customDateFormat" => [
            "type" => "string",
            "default" => "F j, Y"
        ],
        "imageBorderRadius" => [
            "type" => "number",
            "default" => 0
        ]
    ];
    return array_merge($attrs, smart_blocks_global_attributes());
}

function smart_blocks_attributes_news_module_eight() {
    $attrs = [
        "id" => [
            "type" => "string"
        ],
        "style" => [
            "type" => "string"
        ],
        "headerTitle" => [
            "type" => "string"
        ],
        "headerStyle" => [
            "type" => "string",
            "default" => "sb-title-style1"
        ],
        "headerColor" => [
            "type" => "string"
        ],
        "headerShortBorderColor" => [
            "type" => "string"
        ],
        "headerLongBorderColor" => [
            "type" => "string"
        ],
        "headerTitleTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "featuredTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "sideTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "excerptTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "metasTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryBackgroundColor" => [
            "type" => "string"
        ],
        "categoryTextColor" => [
            "type" => "string"
        ],
        "categoryBackgroundHoverColor" => [
            "type" => "string"
        ],
        "categoryTextHoverColor" => [
            "type" => "string"
        ],
        "titleColor" => [
            "type" => "string"
        ],
        "titleHoverColor" => [
            "type" => "string"
        ],
        "excerptColor" => [
            "type" => "string"
        ],
        "postMetasColor" => [
            "type" => "string"
        ],
        "postsPostType" => [
            "type" => "string",
            "default" => "post"
        ],
        "excludePosts" => [
            "type" => "array"
        ],
        "order" => [
            "type" => "string",
            "default" => "desc"
        ],
        "orderBy" => [
            "type" => "string",
            "default" => "date"
        ],
        "categories" => [
            "type" => "object"
        ],
        "offset" => [
            "type" => "number"
        ],
        "featuredTitleMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "featuredImageSize" => [
            "type" => "string",
            "default" => "large"
        ],
        "featuredImageHeight" => [
            "type" => "number",
            "default" => 70
        ],
        "featuredExcerptLength" => [
            "type" => "number",
            "default" => 250
        ],
        "featuredPostAuthor" => [
            "type" => "boolean",
            "default" => true
        ],
        "featuredPostDate" => [
            "type" => "boolean",
            "default" => true
        ],
        "featuredPostComments" => [
            "type" => "boolean",
            "default" => true
        ],
        "featuredPostCategory" => [
            "type" => "boolean",
            "default" => true
        ],
        "sideImageSize" => [
            "type" => "string",
            "default" => "large"
        ],
        "sideImageHeight" => [
            "type" => "number",
            "default" => 70
        ],
        "sidePostAuthor" => [
            "type" => "boolean",
            "default" => true
        ],
        "sidePostDate" => [
            "type" => "boolean",
            "default" => true
        ],
        "sidePostComments" => [
            "type" => "boolean",
            "default" => true
        ],
        "sidePostCategory" => [
            "type" => "boolean",
            "default" => true
        ],
        "sideTitleMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "dateFormat" => [
            "type" => "string",
            "default" => "default"
        ],
        "customDateFormat" => [
            "type" => "string",
            "default" => "F j, Y"
        ],
        "imageBorderRadius" => [
            "type" => "number",
            "default" => 0
        ]
    ];
    return array_merge($attrs, smart_blocks_global_attributes());
}

function smart_blocks_attributes_news_module_nine() {
    $attrs = [
        "id" => [
            "type" => "string"
        ],
        "style" => [
            "type" => "string"
        ],
        "headerTitle" => [
            "type" => "string"
        ],
        "headerStyle" => [
            "type" => "string",
            "default" => "sb-title-style1"
        ],
        "headerColor" => [
            "type" => "string"
        ],
        "headerShortBorderColor" => [
            "type" => "string"
        ],
        "headerLongBorderColor" => [
            "type" => "string"
        ],
        "headerTitleTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "featuredTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "listingTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "excerptTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "metasTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryBackgroundColor" => [
            "type" => "string"
        ],
        "categoryTextColor" => [
            "type" => "string"
        ],
        "categoryBackgroundHoverColor" => [
            "type" => "string"
        ],
        "categoryTextHoverColor" => [
            "type" => "string"
        ],
        "titleColor" => [
            "type" => "string"
        ],
        "titleHoverColor" => [
            "type" => "string"
        ],
        "excerptColor" => [
            "type" => "string"
        ],
        "postMetasColor" => [
            "type" => "string"
        ],
        "postsPostType" => [
            "type" => "string",
            "default" => "post"
        ],
        "excludePosts" => [
            "type" => "array"
        ],
        "order" => [
            "type" => "string",
            "default" => "desc"
        ],
        "orderBy" => [
            "type" => "string",
            "default" => "date"
        ],
        "categories" => [
            "type" => "object"
        ],
        "offset" => [
            "type" => "number",
            "default" => 0
        ],
        "featuredTitleMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "featuredImageSize" => [
            "type" => "string",
            "default" => "large"
        ],
        "featuredImageHeight" => [
            "type" => "number",
            "default" => 70
        ],
        "featuredExcerptLength" => [
            "type" => "number",
            "default" => 0
        ],
        "featuredPostAuthor" => [
            "type" => "boolean",
            "default" => true
        ],
        "featuredPostDate" => [
            "type" => "boolean",
            "default" => true
        ],
        "featuredPostComments" => [
            "type" => "boolean",
            "default" => true
        ],
        "featuredPostCategory" => [
            "type" => "boolean",
            "default" => true
        ],
        "listingImageSize" => [
            "type" => "string",
            "default" => "large"
        ],
        "listingImageHeight" => [
            "type" => "number",
            "default" => 70
        ],
        "listingExcerptLength" => [
            "type" => "number",
            "default" => 100
        ],
        "listingPostAuthor" => [
            "type" => "boolean",
            "default" => true
        ],
        "listingPostDate" => [
            "type" => "boolean",
            "default" => true
        ],
        "listingPostComments" => [
            "type" => "boolean",
            "default" => true
        ],
        "listingPostCategory" => [
            "type" => "boolean",
            "default" => true
        ],
        "listingTitleMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "dateFormat" => [
            "type" => "string",
            "default" => "default"
        ],
        "customDateFormat" => [
            "type" => "string",
            "default" => "F j, Y"
        ],
        "imageBorderRadius" => [
            "type" => "number",
            "default" => 0
        ]
    ];
    return array_merge($attrs, smart_blocks_global_attributes());
}

function smart_blocks_attributes_news_module_ten() {
    $attrs = [
        "id" => [
            "type" => "string"
        ],
        "style" => [
            "type" => "string"
        ],
        "headerTitle" => [
            "type" => "string"
        ],
        "headerStyle" => [
            "type" => "string",
            "default" => "sb-title-style1"
        ],
        "headerColor" => [
            "type" => "string"
        ],
        "headerShortBorderColor" => [
            "type" => "string"
        ],
        "headerLongBorderColor" => [
            "type" => "string"
        ],
        "noOfPosts" => [
            "type" => "number",
            "default" => 5
        ],
        "headerTitleTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "listingTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "metasTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryBackgroundColor" => [
            "type" => "string"
        ],
        "categoryTextColor" => [
            "type" => "string"
        ],
        "categoryBackgroundHoverColor" => [
            "type" => "string"
        ],
        "categoryTextHoverColor" => [
            "type" => "string"
        ],
        "titleColor" => [
            "type" => "string"
        ],
        "titleHoverColor" => [
            "type" => "string"
        ],
        "postMetasColor" => [
            "type" => "string"
        ],
        "postsPostType" => [
            "type" => "string",
            "default" => "post"
        ],
        "excludePosts" => [
            "type" => "array"
        ],
        "order" => [
            "type" => "string",
            "default" => "desc"
        ],
        "orderBy" => [
            "type" => "string",
            "default" => "date"
        ],
        "categories" => [
            "type" => "object"
        ],
        "offset" => [
            "type" => "number"
        ],
        "listingImageSize" => [
            "type" => "string",
            "default" => "large"
        ],
        "listingImageWidth" => [
            "type" => "number",
            "default" => 120
        ],
        "listingImageHeight" => [
            "type" => "number",
            "default" => 100
        ],
        "listingExcerptLength" => [
            "type" => "number",
            "default" => 100
        ],
        "listingPostAuthor" => [
            "type" => "boolean",
            "default" => true
        ],
        "listingPostDate" => [
            "type" => "boolean",
            "default" => true
        ],
        "listingPostComments" => [
            "type" => "boolean",
            "default" => true
        ],
        "listingPostCategory" => [
            "type" => "boolean",
            "default" => true
        ],
        "listingTitleMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "dateFormat" => [
            "type" => "string",
            "default" => "default"
        ],
        "customDateFormat" => [
            "type" => "string",
            "default" => "F j, Y"
        ],
        "imageBorderRadius" => [
            "type" => "number",
            "default" => 0
        ]
    ];
    return array_merge($attrs, smart_blocks_global_attributes());
}

function smart_blocks_attributes_news_module_eleven() {
    $attrs = [
        "id" => [
            "type" => "string"
        ],
        "style" => [
            "type" => "string"
        ],
        "headerTitle" => [
            "type" => "string"
        ],
        "headerStyle" => [
            "type" => "string",
            "default" => "sb-title-style1"
        ],
        "headerColor" => [
            "type" => "string"
        ],
        "headerShortBorderColor" => [
            "type" => "string"
        ],
        "headerLongBorderColor" => [
            "type" => "string"
        ],
        "headerTitleTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "postTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "metasTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryBackgroundColor" => [
            "type" => "string"
        ],
        "categoryTextColor" => [
            "type" => "string"
        ],
        "categoryBackgroundHoverColor" => [
            "type" => "string"
        ],
        "categoryTextHoverColor" => [
            "type" => "string"
        ],
        "titleColor" => [
            "type" => "string"
        ],
        "titleHoverColor" => [
            "type" => "string"
        ],
        "postMetasColor" => [
            "type" => "string"
        ],
        "postsPostType" => [
            "type" => "string",
            "default" => "post"
        ],
        "excludePosts" => [
            "type" => "array"
        ],
        "order" => [
            "type" => "string",
            "default" => "desc"
        ],
        "orderBy" => [
            "type" => "string",
            "default" => "date"
        ],
        "categories" => [
            "type" => "object"
        ],
        "offset" => [
            "type" => "number"
        ],
        "postImageSize" => [
            "type" => "string",
            "default" => "large"
        ],
        "postImageHeight" => [
            "type" => "number",
            "default" => 100
        ],
        "postPostAuthor" => [
            "type" => "boolean",
            "default" => true
        ],
        "postPostDate" => [
            "type" => "boolean",
            "default" => true
        ],
        "postPostComments" => [
            "type" => "boolean",
            "default" => true
        ],
        "postPostCategory" => [
            "type" => "boolean",
            "default" => true
        ],
        "postTitleMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "dateFormat" => [
            "type" => "string",
            "default" => "default"
        ],
        "customDateFormat" => [
            "type" => "string",
            "default" => "F j, Y"
        ],
        "imageBorderRadius" => [
            "type" => "number",
            "default" => 0
        ]
    ];
    return array_merge($attrs, smart_blocks_global_attributes());
}

function smart_blocks_attributes_news_module_twelve() {
    $attrs = [
        "id" => [
            "type" => "string"
        ],
        "style" => [
            "type" => "string"
        ],
        "headerTitle" => [
            "type" => "string"
        ],
        "headerStyle" => [
            "type" => "string",
            "default" => "sb-title-style1"
        ],
        "headerColor" => [
            "type" => "string"
        ],
        "headerShortBorderColor" => [
            "type" => "string"
        ],
        "headerLongBorderColor" => [
            "type" => "string"
        ],
        "headerTitleTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "postTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "metasTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryBackgroundColor" => [
            "type" => "string"
        ],
        "categoryTextColor" => [
            "type" => "string"
        ],
        "categoryBackgroundHoverColor" => [
            "type" => "string"
        ],
        "categoryTextHoverColor" => [
            "type" => "string"
        ],
        "titleColor" => [
            "type" => "string"
        ],
        "titleHoverColor" => [
            "type" => "string"
        ],
        "postMetasColor" => [
            "type" => "string"
        ],
        "postsPostType" => [
            "type" => "string",
            "default" => "post"
        ],
        "excludePosts" => [
            "type" => "array"
        ],
        "order" => [
            "type" => "string",
            "default" => "desc"
        ],
        "orderBy" => [
            "type" => "string",
            "default" => "date"
        ],
        "categories" => [
            "type" => "object"
        ],
        "offset" => [
            "type" => "number"
        ],
        "postImageSize" => [
            "type" => "string",
            "default" => "large"
        ],
        "postImageHeight" => [
            "type" => "number",
            "default" => 80
        ],
        "postPostAuthor" => [
            "type" => "boolean",
            "default" => true
        ],
        "postPostDate" => [
            "type" => "boolean",
            "default" => true
        ],
        "postPostComments" => [
            "type" => "boolean",
            "default" => true
        ],
        "postPostCategory" => [
            "type" => "boolean",
            "default" => true
        ],
        "postTitleMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "dateFormat" => [
            "type" => "string",
            "default" => "default"
        ],
        "customDateFormat" => [
            "type" => "string",
            "default" => "F j, Y"
        ],
        "imageBorderRadius" => [
            "type" => "number",
            "default" => 0
        ]
    ];
    return array_merge($attrs, smart_blocks_global_attributes());
}

function smart_blocks_attributes_news_module_thirteen() {
    $attrs = [
        "id" => [
            "type" => "string"
        ],
        "style" => [
            "type" => "string"
        ],
        "headerTitle" => [
            "type" => "string"
        ],
        "headerStyle" => [
            "type" => "string",
            "default" => "sb-title-style1"
        ],
        "headerColor" => [
            "type" => "string"
        ],
        "headerShortBorderColor" => [
            "type" => "string"
        ],
        "headerLongBorderColor" => [
            "type" => "string"
        ],
        "headerTitleTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "postTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "excerptTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "metasTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryBackgroundColor" => [
            "type" => "string"
        ],
        "categoryTextColor" => [
            "type" => "string"
        ],
        "categoryBackgroundHoverColor" => [
            "type" => "string"
        ],
        "categoryTextHoverColor" => [
            "type" => "string"
        ],
        "titleColor" => [
            "type" => "string"
        ],
        "titleHoverColor" => [
            "type" => "string"
        ],
        "contentBgColor" => [
            "type" => "string"
        ],
        "excerptColor" => [
            "type" => "string"
        ],
        "postMetasColor" => [
            "type" => "string"
        ],
        "postsPostType" => [
            "type" => "string",
            "default" => "post"
        ],
        "excludePosts" => [
            "type" => "array"
        ],
        "order" => [
            "type" => "string",
            "default" => "desc"
        ],
        "orderBy" => [
            "type" => "string",
            "default" => "date"
        ],
        "categories" => [
            "type" => "object"
        ],
        "offset" => [
            "type" => "number"
        ],
        "postImageSize" => [
            "type" => "string",
            "default" => "large"
        ],
        "postImageHeight" => [
            "type" => "number",
            "default" => 100
        ],
        "postExcerptLength" => [
            "type" => "number",
            "default" => 200
        ],
        "postPostAuthor" => [
            "type" => "boolean",
            "default" => true
        ],
        "postPostDate" => [
            "type" => "boolean",
            "default" => true
        ],
        "postPostComments" => [
            "type" => "boolean",
            "default" => true
        ],
        "postPostCategory" => [
            "type" => "boolean",
            "default" => true
        ],
        "postTitleMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "dateFormat" => [
            "type" => "string",
            "default" => "default"
        ],
        "customDateFormat" => [
            "type" => "string",
            "default" => "F j, Y"
        ],
        "imageBorderRadius" => [
            "type" => "number",
            "default" => 0
        ]
    ];
    return array_merge($attrs, smart_blocks_global_attributes());
}

function smart_blocks_attributes_news_module_fourteen() {
    $attrs = [
        "id" => [
            "type" => "string"
        ],
        "style" => [
            "type" => "string"
        ],
        "headerTitle" => [
            "type" => "string"
        ],
        "headerStyle" => [
            "type" => "string",
            "default" => "sb-title-style1"
        ],
        "headerColor" => [
            "type" => "string"
        ],
        "headerShortBorderColor" => [
            "type" => "string"
        ],
        "headerLongBorderColor" => [
            "type" => "string"
        ],
        "noOfPosts" => [
            "type" => "number",
            "default" => 4
        ],
        "noOfCol" => [
            "type" => "number",
            "default" => 4
        ],
        "headerTitleTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "postTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "excerptTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "metasTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryBackgroundColor" => [
            "type" => "string"
        ],
        "categoryTextColor" => [
            "type" => "string"
        ],
        "categoryBackgroundHoverColor" => [
            "type" => "string"
        ],
        "categoryTextHoverColor" => [
            "type" => "string"
        ],
        "titleColor" => [
            "type" => "string"
        ],
        "titleHoverColor" => [
            "type" => "string"
        ],
        "excerptColor" => [
            "type" => "string"
        ],
        "postMetasColor" => [
            "type" => "string"
        ],
        "postsPostType" => [
            "type" => "string",
            "default" => "post"
        ],
        "excludePosts" => [
            "type" => "array"
        ],
        "order" => [
            "type" => "string",
            "default" => "desc"
        ],
        "orderBy" => [
            "type" => "string",
            "default" => "date"
        ],
        "categories" => [
            "type" => "object"
        ],
        "offset" => [
            "type" => "number"
        ],
        "postImageSize" => [
            "type" => "string",
            "default" => "large"
        ],
        "postImageHeight" => [
            "type" => "number",
            "default" => 70
        ],
        "postExcerptLength" => [
            "type" => "number",
            "default" => 130
        ],
        "postPostAuthor" => [
            "type" => "boolean",
            "default" => true
        ],
        "postPostDate" => [
            "type" => "boolean",
            "default" => true
        ],
        "postPostComments" => [
            "type" => "boolean",
            "default" => true
        ],
        "postPostCategory" => [
            "type" => "boolean",
            "default" => true
        ],
        "postTitleMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "dateFormat" => [
            "type" => "string",
            "default" => "default"
        ],
        "customDateFormat" => [
            "type" => "string",
            "default" => "F j, Y"
        ],
        "imageBorderRadius" => [
            "type" => "number",
            "default" => 0
        ]
    ];
    return array_merge($attrs, smart_blocks_global_attributes());
}

function smart_blocks_attributes_news_module_fifteen() {
    $attrs = [
        "id" => [
            "type" => "string"
        ],
        "style" => [
            "type" => "string"
        ],
        "headerTitle" => [
            "type" => "string"
        ],
        "headerStyle" => [
            "type" => "string",
            "default" => "sb-title-style1"
        ],
        "headerColor" => [
            "type" => "string"
        ],
        "headerShortBorderColor" => [
            "type" => "string"
        ],
        "headerLongBorderColor" => [
            "type" => "string"
        ],
        "headerTitleTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "postTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "metasTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryBackgroundColor" => [
            "type" => "string"
        ],
        "categoryTextColor" => [
            "type" => "string"
        ],
        "categoryBackgroundHoverColor" => [
            "type" => "string"
        ],
        "categoryTextHoverColor" => [
            "type" => "string"
        ],
        "titleColor" => [
            "type" => "string"
        ],
        "titleHoverColor" => [
            "type" => "string"
        ],
        "postMetasColor" => [
            "type" => "string"
        ],
        "postsPostType" => [
            "type" => "string",
            "default" => "post"
        ],
        "excludePosts" => [
            "type" => "array"
        ],
        "order" => [
            "type" => "string",
            "default" => "desc"
        ],
        "orderBy" => [
            "type" => "string",
            "default" => "date"
        ],
        "categories" => [
            "type" => "object"
        ],
        "offset" => [
            "type" => "number"
        ],
        "postImageSize" => [
            "type" => "string",
            "default" => "large"
        ],
        "postImageHeight" => [
            "type" => "number",
            "default" => 100
        ],
        "postPostAuthor" => [
            "type" => "boolean",
            "default" => true
        ],
        "postPostDate" => [
            "type" => "boolean",
            "default" => true
        ],
        "postPostComments" => [
            "type" => "boolean",
            "default" => true
        ],
        "postPostCategory" => [
            "type" => "boolean",
            "default" => true
        ],
        "postTitleMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "dateFormat" => [
            "type" => "string",
            "default" => "default"
        ],
        "customDateFormat" => [
            "type" => "string",
            "default" => "F j, Y"
        ],
        "imageBorderRadius" => [
            "type" => "number",
            "default" => 0
        ]
    ];
    return array_merge($attrs, smart_blocks_global_attributes());
}

function smart_blocks_attributes_tile_module_one() {
    $attrs = [
        "id" => [
            "type" => "string"
        ],
        "style" => [
            "type" => "string"
        ],
        "headerTitle" => [
            "type" => "string"
        ],
        "headerStyle" => [
            "type" => "string",
            "default" => "sb-title-style1"
        ],
        "headerColor" => [
            "type" => "string"
        ],
        "headerShortBorderColor" => [
            "type" => "string"
        ],
        "headerLongBorderColor" => [
            "type" => "string"
        ],
        "headerTitleTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "featuredTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "sideTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "metasTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryBackgroundColor" => [
            "type" => "string"
        ],
        "categoryTextColor" => [
            "type" => "string"
        ],
        "categoryBackgroundHoverColor" => [
            "type" => "string"
        ],
        "categoryTextHoverColor" => [
            "type" => "string"
        ],
        "titleColor" => [
            "type" => "string"
        ],
        "titleHoverColor" => [
            "type" => "string"
        ],
        "postMetasColor" => [
            "type" => "string"
        ],
        "postsPostType" => [
            "type" => "string",
            "default" => "post"
        ],
        "excludePosts" => [
            "type" => "array"
        ],
        "order" => [
            "type" => "string",
            "default" => "desc"
        ],
        "orderBy" => [
            "type" => "string",
            "default" => "date"
        ],
        "categories" => [
            "type" => "object"
        ],
        "offset" => [
            "type" => "number",
            "default" => 0
        ],
        "featuredTitleMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "featuredImageSize" => [
            "type" => "string",
            "default" => "large"
        ],
        "featuredPostAuthor" => [
            "type" => "boolean",
            "default" => true
        ],
        "featuredPostDate" => [
            "type" => "boolean",
            "default" => true
        ],
        "featuredPostComments" => [
            "type" => "boolean",
            "default" => true
        ],
        "featuredPostCategory" => [
            "type" => "boolean",
            "default" => true
        ],
        "largeSideImageSize" => [
            "type" => "string",
            "default" => "large"
        ],
        "sideImageSize" => [
            "type" => "string",
            "default" => "large"
        ],
        "sidePostAuthor" => [
            "type" => "boolean",
            "default" => true
        ],
        "sidePostDate" => [
            "type" => "boolean",
            "default" => true
        ],
        "sidePostComments" => [
            "type" => "boolean",
            "default" => true
        ],
        "sidePostCategory" => [
            "type" => "boolean",
            "default" => true
        ],
        "sideTitleMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "dateFormat" => [
            "type" => "string",
            "default" => "default"
        ],
        "customDateFormat" => [
            "type" => "string",
            "default" => "F j, Y"
        ],
        "moduleHeight" => [
            "type" => "number",
            "default" => 500
        ],
        "titleBorderColor" => [
            "type" => "string"
        ],
        "imageBorderRadius" => [
            "type" => "number",
            "default" => 0
        ]
    ];
    return array_merge($attrs, smart_blocks_global_attributes());
}

function smart_blocks_attributes_tile_module_two() {
    $attrs = [
        "id" => [
            "type" => "string"
        ],
        "style" => [
            "type" => "string"
        ],
        "headerTitle" => [
            "type" => "string"
        ],
        "headerStyle" => [
            "type" => "string",
            "default" => "sb-title-style1"
        ],
        "headerColor" => [
            "type" => "string"
        ],
        "headerShortBorderColor" => [
            "type" => "string"
        ],
        "headerLongBorderColor" => [
            "type" => "string"
        ],
        "headerTitleTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "featuredTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "sideTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "metasTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryBackgroundColor" => [
            "type" => "string"
        ],
        "categoryTextColor" => [
            "type" => "string"
        ],
        "categoryBackgroundHoverColor" => [
            "type" => "string"
        ],
        "categoryTextHoverColor" => [
            "type" => "string"
        ],
        "titleColor" => [
            "type" => "string"
        ],
        "titleHoverColor" => [
            "type" => "string"
        ],
        "postMetasColor" => [
            "type" => "string"
        ],
        "postsPostType" => [
            "type" => "string",
            "default" => "post"
        ],
        "excludePosts" => [
            "type" => "array"
        ],
        "order" => [
            "type" => "string",
            "default" => "desc"
        ],
        "orderBy" => [
            "type" => "string",
            "default" => "date"
        ],
        "categories" => [
            "type" => "object"
        ],
        "offset" => [
            "type" => "number",
            "default" => 0
        ],
        "featuredTitleMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "featuredImageSize" => [
            "type" => "string",
            "default" => "large"
        ],
        "featuredPostAuthor" => [
            "type" => "boolean",
            "default" => true
        ],
        "featuredPostDate" => [
            "type" => "boolean",
            "default" => true
        ],
        "featuredPostComments" => [
            "type" => "boolean",
            "default" => true
        ],
        "featuredPostCategory" => [
            "type" => "boolean",
            "default" => true
        ],
        "sideImageSize" => [
            "type" => "string",
            "default" => "large"
        ],
        "sidePostAuthor" => [
            "type" => "boolean",
            "default" => true
        ],
        "sidePostDate" => [
            "type" => "boolean",
            "default" => true
        ],
        "sidePostComments" => [
            "type" => "boolean",
            "default" => true
        ],
        "sidePostCategory" => [
            "type" => "boolean",
            "default" => true
        ],
        "sideTitleMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "dateFormat" => [
            "type" => "string",
            "default" => "default"
        ],
        "customDateFormat" => [
            "type" => "string",
            "default" => "F j, Y"
        ],
        "moduleHeight" => [
            "type" => "number",
            "default" => 500
        ],
        "titleBorderColor" => [
            "type" => "string"
        ],
        "imageBorderRadius" => [
            "type" => "number",
            "default" => 0
        ]
    ];
    return array_merge($attrs, smart_blocks_global_attributes());
}

function smart_blocks_attributes_tile_module_three() {
    $attrs = [
        "id" => [
            "type" => "string"
        ],
        "style" => [
            "type" => "string"
        ],
        "headerTitle" => [
            "type" => "string"
        ],
        "headerStyle" => [
            "type" => "string",
            "default" => "sb-title-style1"
        ],
        "headerColor" => [
            "type" => "string"
        ],
        "headerShortBorderColor" => [
            "type" => "string"
        ],
        "headerLongBorderColor" => [
            "type" => "string"
        ],
        "headerTitleTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "postTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "metasTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryBackgroundColor" => [
            "type" => "string"
        ],
        "categoryTextColor" => [
            "type" => "string"
        ],
        "categoryBackgroundHoverColor" => [
            "type" => "string"
        ],
        "categoryTextHoverColor" => [
            "type" => "string"
        ],
        "titleColor" => [
            "type" => "string"
        ],
        "titleHoverColor" => [
            "type" => "string"
        ],
        "postMetasColor" => [
            "type" => "string"
        ],
        "postsPostType" => [
            "type" => "string",
            "default" => "post"
        ],
        "excludePosts" => [
            "type" => "array"
        ],
        "order" => [
            "type" => "string",
            "default" => "desc"
        ],
        "orderBy" => [
            "type" => "string",
            "default" => "date"
        ],
        "categories" => [
            "type" => "object"
        ],
        "offset" => [
            "type" => "number",
            "default" => 0
        ],
        "postImageSize" => [
            "type" => "string",
            "default" => "large"
        ],
        "postPostAuthor" => [
            "type" => "boolean",
            "default" => true
        ],
        "postPostDate" => [
            "type" => "boolean",
            "default" => true
        ],
        "postPostComments" => [
            "type" => "boolean",
            "default" => true
        ],
        "postPostCategory" => [
            "type" => "boolean",
            "default" => true
        ],
        "postTitleMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "dateFormat" => [
            "type" => "string",
            "default" => "default"
        ],
        "customDateFormat" => [
            "type" => "string",
            "default" => "F j, Y"
        ],
        "moduleHeight" => [
            "type" => "number",
            "default" => 400
        ],
        "titleBorderColor" => [
            "type" => "string"
        ],
        "imageBorderRadius" => [
            "type" => "number",
            "default" => 0
        ]
    ];
    return array_merge($attrs, smart_blocks_global_attributes());
}

function smart_blocks_attributes_carousel_module_one() {
    $attrs = [
        "id" => [
            "type" => "string"
        ],
        "style" => [
            "type" => "string"
        ],
        "headerTitle" => [
            "type" => "string"
        ],
        "headerStyle" => [
            "type" => "string",
            "default" => "sb-title-style1"
        ],
        "headerColor" => [
            "type" => "string"
        ],
        "headerShortBorderColor" => [
            "type" => "string"
        ],
        "headerLongBorderColor" => [
            "type" => "string"
        ],
        "headerTitleTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "postTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "metasTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "excerptTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryBackgroundColor" => [
            "type" => "string"
        ],
        "categoryTextColor" => [
            "type" => "string"
        ],
        "categoryBackgroundHoverColor" => [
            "type" => "string"
        ],
        "categoryTextHoverColor" => [
            "type" => "string"
        ],
        "titleColor" => [
            "type" => "string"
        ],
        "titleHoverColor" => [
            "type" => "string"
        ],
        "postMetasColor" => [
            "type" => "string"
        ],
        "postsPostType" => [
            "type" => "string",
            "default" => "post"
        ],
        "excludePosts" => [
            "type" => "array"
        ],
        "order" => [
            "type" => "string",
            "default" => "desc"
        ],
        "orderBy" => [
            "type" => "string",
            "default" => "date"
        ],
        "categories" => [
            "type" => "object"
        ],
        "offset" => [
            "type" => "number"
        ],
        "postImageSize" => [
            "type" => "string",
            "default" => "large"
        ],
        "postImageHeight" => [
            "type" => "number",
            "default" => 100
        ],
        "postExcerptLength" => [
            "type" => "number",
            "default" => 100
        ],
        "postPostAuthor" => [
            "type" => "boolean",
            "default" => true
        ],
        "postPostDate" => [
            "type" => "boolean",
            "default" => true
        ],
        "postPostComments" => [
            "type" => "boolean",
            "default" => true
        ],
        "postPostCategory" => [
            "type" => "boolean",
            "default" => true
        ],
        "postTitleMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "dateFormat" => [
            "type" => "string",
            "default" => "default"
        ],
        "customDateFormat" => [
            "type" => "string",
            "default" => "F j, Y"
        ],
        "autoplay" => [
            "type" => "boolean",
            "default" => true
        ],
        "pauseDuration" => [
            "type" => "number",
            "default" => 5
        ],
        "noOfSlides" => [
            "type" => "object",
            "default" => [
                "sm" => 1,
                "md" => 2,
                "lg" => 3
            ]
        ],
        "slidesMargin" => [
            "type" => "object",
            "default" => [
                "sm" => 30,
                "md" => 30,
                "lg" => 30
            ]
        ],
        "slidesStagepadding" => [
            "type" => "object",
            "default" => [
                "sm" => 0,
                "md" => 0,
                "lg" => 0
            ]
        ],
        "nav" => [
            "type" => "boolean",
            "default" => true
        ],
        "dots" => [
            "type" => "boolean",
            "default" => true
        ],
        "noOfPosts" => [
            "type" => "number",
            "default" => 4
        ],
        "excerptColor" => [
            "type" => "string"
        ],
        "navNormalBgColor" => [
            "type" => "string"
        ],
        "navIconNormalColor" => [
            "type" => "string"
        ],
        "dotsBgColor" => [
            "type" => "string"
        ],
        "navHoverBgColor" => [
            "type" => "string"
        ],
        "navIconHoverColor" => [
            "type" => "string"
        ],
        "dotsBgColorHover" => [
            "type" => "string"
        ],
        "imageBorderRadius" => [
            "type" => "number",
            "default" => 0
        ]
    ];
    return array_merge($attrs, smart_blocks_global_attributes());
}

function smart_blocks_attributes_single_news_one() {
    $attrs = [
        "id" => [
            "type" => "string"
        ],
        "style" => [
            "type" => "string"
        ],
        "postTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "metasTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "excerptTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "contentBgColor" => [
            "type" => "string"
        ],
        "contentPadding" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "contentMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "titleColor" => [
            "type" => "string"
        ],
        "titleHoverColor" => [
            "type" => "string"
        ],
        "postMetasColor" => [
            "type" => "string"
        ],
        "postId" => [
            "type" => "string"
        ],
        "categories" => [
            "type" => "array"
        ],
        "tags" => [
            "type" => "array"
        ],
        "offset" => [
            "type" => "number"
        ],
        "postImageSize" => [
            "type" => "string",
            "default" => "large"
        ],
        "postImageHeight" => [
            "type" => "number",
            "default" => 80
        ],
        "postPostAuthor" => [
            "type" => "boolean",
            "default" => true
        ],
        "postPostDate" => [
            "type" => "boolean",
            "default" => true
        ],
        "postPostComments" => [
            "type" => "boolean",
            "default" => true
        ],
        "postExcerptLength" => [
            "type" => "number",
            "default" => 200
        ],
        "excerptMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "metasMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "dateFormat" => [
            "type" => "string",
            "default" => "default"
        ],
        "customDateFormat" => [
            "type" => "string",
            "default" => "F j, Y"
        ],
        "contentAlignment" => [
            "type" => "string",
            "default" => "left"
        ],
        "excerptColor" => [
            "type" => "string"
        ],
        "postTitleMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "filterOption" => [
            "type" => "string",
            "default" => "single-post"
        ],
        "imageBorderRadius" => [
            "type" => "number",
            "default" => 0
        ]
    ];
    return array_merge($attrs, smart_blocks_global_attributes());
}

function smart_blocks_attributes_single_news_two() {
    $attrs = [
        "id" => [
            "type" => "string"
        ],
        "style" => [
            "type" => "string"
        ],
        "excerptTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "postTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "metasTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "categoryBackgroundColor" => [
            "type" => "string"
        ],
        "categoryTextColor" => [
            "type" => "string"
        ],
        "categoryBackgroundHoverColor" => [
            "type" => "string"
        ],
        "categoryTextHoverColor" => [
            "type" => "string"
        ],
        "titleColor" => [
            "type" => "string"
        ],
        "titleHoverColor" => [
            "type" => "string"
        ],
        "postMetasColor" => [
            "type" => "string"
        ],
        "postsPostType" => [
            "type" => "string",
            "default" => "post"
        ],
        "postId" => [
            "type" => "string"
        ],
        "categories" => [
            "type" => "array"
        ],
        "tags" => [
            "type" => "array"
        ],
        "offset" => [
            "type" => "number"
        ],
        "postImageSize" => [
            "type" => "string",
            "default" => "large"
        ],
        "postImageHeight" => [
            "type" => "number",
            "default" => 80
        ],
        "postPostAuthor" => [
            "type" => "boolean",
            "default" => true
        ],
        "postExcerptLength" => [
            "type" => "number",
            "default" => 0
        ],
        "postPostDate" => [
            "type" => "boolean",
            "default" => true
        ],
        "postPostComments" => [
            "type" => "boolean",
            "default" => true
        ],
        "postPostCategory" => [
            "type" => "boolean",
            "default" => true
        ],
        "postTitleMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "dateFormat" => [
            "type" => "string",
            "default" => "default"
        ],
        "customDateFormat" => [
            "type" => "string",
            "default" => "F j, Y"
        ],
        "contentPadding" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "contentMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "metasMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "excerptMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "excerptColor" => [
            "type" => "string"
        ],
        "contentAlignment" => [
            "type" => "string",
            "default" => "left"
        ],
        "contentOverlayBackground" => [
            "type" => "string"
        ],
        "filterOption" => [
            "type" => "string",
            "default" => "single-post"
        ],
        "imageBorderRadius" => [
            "type" => "number",
            "default" => 0
        ]
    ];
    return array_merge($attrs, smart_blocks_global_attributes());
}

function smart_blocks_attributes_ticker_module() {
    $attrs = [
        "id" => [
            "type" => "string"
        ],
        "style" => [
            "type" => "string"
        ],
        "tickerTitleTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "tickerContentTypography" => [
            "type" => "object",
            "default" => [
                "family" => 'inherit',
                "weight" => 'inherit',
                "textTransform" => 'inherit',
                "textDecoration" => 'inherit',
                "fontSize" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "letterSpacing" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ],
                "lineHeight" => [
                    "sm" => null,
                    "md" => null,
                    "lg" => null,
                    "unit" => "px"
                ]
            ]
        ],
        "postsPostType" => [
            "type" => "string",
            "default" => "post"
        ],
        "excludePosts" => [
            "type" => "array"
        ],
        "order" => [
            "type" => "string",
            "default" => "desc"
        ],
        "orderBy" => [
            "type" => "string",
            "default" => "date"
        ],
        "categories" => [
            "type" => "object"
        ],
        "offset" => [
            "type" => "number"
        ],
        "autoplay" => [
            "type" => "boolean",
            "default" => true
        ],
        "pause" => [
            "type" => "number",
            "default" => 5
        ],
        "postExcerptLength" => [
            "type" => "number",
            "default" => 100
        ],
        "noOfPosts" => [
            "type" => "number",
            "default" => 4
        ],
        "tickerTitle" => [
            "type" => "string",
            "default" => "Latest Posts"
        ],
        "tickerTitleBgColor" => [
            "type" => "string"
        ],
        "tickerTitleColor" => [
            "type" => "string"
        ],
        "tickerContentBgColor" => [
            "type" => "string"
        ],
        "tickerContentColor" => [
            "type" => "string"
        ],
        "navNormalBgColor" => [
            "type" => "string"
        ],
        "navIconNormalColor" => [
            "type" => "string"
        ],
        "navHoverBgColor" => [
            "type" => "string"
        ],
        "navIconHoverColor" => [
            "type" => "string"
        ]
    ];
    return array_merge($attrs, smart_blocks_global_attributes());
}

function smart_blocks_global_attributes() {
    $attrs = [
        "borderNormal" => [
            "type" => "string"
        ],
        "borderHover" => [
            "type" => "string"
        ],
        "borderNormalColor" => [
            "type" => "string"
        ],
        "borderHoverColor" => [
            "type" => "string"
        ],
        "blockBgColor" => [
            "type" => "string"
        ],
        "borderNormalWidth" => [
            "type" => "object",
            "default" => [
                "top" => null,
                "left" => null,
                "right" => null,
                "bottom" => null,
                "unit" => "px"
            ]
        ],
        "borderHoverWidth" => [
            "type" => "object",
            "default" => [
                "top" => null,
                "left" => null,
                "right" => null,
                "bottom" => null,
                "unit" => "px"
            ]
        ],
        "borderNormalRadius" => [
            "type" => "object",
            "default" => [
                "top" => null,
                "left" => null,
                "right" => null,
                "bottom" => null,
                "unit" => "px"
            ]
        ],
        "borderHoverRadius" => [
            "type" => "object",
            "default" => [
                "top" => null,
                "left" => null,
                "right" => null,
                "bottom" => null,
                "unit" => "px"
            ]
        ],
        "borderNormalBoxShadow" => [
            "type" => "object",
            "default" => [
                "horizontal" => null,
                "vertical" => null,
                "blur" => null,
                "spread" => null,
                "color" => null,
                "inset" => null
            ]
        ],
        "borderHoverBoxShadow" => [
            "type" => "object",
            "default" => [
                "horizontal" => null,
                "vertical" => null,
                "blur" => null,
                "spread" => null,
                "color" => null,
                "inset" => null
            ]
        ],
        "blockMargin" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ],
        "blockPadding" => [
            "type" => "object",
            "default" => [
                "sm" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "md" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "lg" => [
                    "top" => null,
                    "left" => null,
                    "right" => null,
                    "bottom" => null
                ],
                "unit" => "px"
            ]
        ]
    ];
    return $attrs;
}
