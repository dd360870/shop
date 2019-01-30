<?php
return [
    'delivery_method' => [
        'D' => '宅配',
        'S' => '超商取貨',
    ],
    'payment_method' => [
        'D' => '貨到付款',
        'S' => '超商取貨付款',
        'L' => 'LINE PAY',
        'C' => '信用卡',
    ],
    'payment_status' => [
        'Y' => '已付款',
        'N' => '尚未付款',
        'E' => '錯誤',
        'S' => '超商取貨付款'
    ],
    'status' => [
        'P' => '處理中',
        'C' => '取消',
        'O' => '已出貨',
    ],
    'color' => [
        0 => [
            'name' => '紅',
            'hex' => 'f44242',
        ],
        1 => [
            'name' => '橙',
            'hex' => 'ed5e1c',
        ],
        2 => [
            'name' => '黃',
            'hex' => 'ffc816',
        ],
        3 => [
            'name' => '綠',
            'hex' => '5adb36',
        ],
        4 => [
            'name' => '藍',
            'hex' => '2d89ed',
        ],
        5 => [
            'name' => '紫',
            'hex' => 'a32ced',
        ],
        6 => [
            'name' => '白',
            'hex' => 'ffffff',
            'font-color' => 'black',
        ],
        7 => [
            'name' => '黑',
            'hex' => '000000',
        ],
        8 => [
            'name' => '灰',
            'hex' => '9a9a9a'
        ]
    ],
    'size' => [
        0 => 'F', // One size
        1 => 'XS',
        2 => 'S',
        3 => 'M',
        4 => 'L',
        5 => 'XL',
        6 => 'XXL',
    ],
    'type' => [
        1 => 'men',
        2 => 'women',
        3 => 'baby',
    ],
];