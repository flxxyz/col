<?php

/**
 * 示例路由
 */

function data()
{
    return [
        'code'    => 1,
        'message' => 'success',
        'data'    => [
            'title'       => 'Col',
            'description' => 'This is a simple PHP framework',
            'a'           => false,
            'b'           => 0,
            'c'           => '1',
            'd'           => [
                'x' => 1,
                'y' => 2,
                'z' => 3,
            ],
        ],
    ];
}

$route->get('/', function () {
    view('index', data());
});

$route->get('/json', function () {
    echo json(data());
});

$route->get('/xml', function () {
    echo xml(data());
});

$route->get('/404', function () {
    view('__404', data(), 404);  // 主动调用404视图
});

$route->get('/sort', function () {
    $start = t();
    echo '<pre>';
    $data = [
        [
            'name' => 'a',
            'age'  => 12,
            'sex'  => 0,
        ],
        [
            'name' => 'b',
            'age'  => 23,
            'sex'  => 0,
        ],
        [
            'name' => 'c',
            'age'  => 34,
            'sex'  => 1,
        ],
        [
            'name' => 'd',
            'age'  => 23,
            'sex'  => 1,
        ],
        [
            'name' => 'e',
            'age'  => 66,
            'sex'  => 0,
        ],
        [
            'name' => 'f',
            'age'  => 12,
            'sex'  => 0,
        ],
    ];

    // 插入排序(多条件)
    for ($i = 1; $i < count($data); ++$i) {
        $t = $data[$i];
        for ($j = $i - 1; $j >= 0; $j--) {
            $sort = $t['age'] < $data[$j]['age'];  // 排序开关
            if ($sort) {
                $data[$j + 1] = $data[$j];
                $data[$j] = $t;
            } else if ($t['age'] == $data[$j]['age']) {
                $sort = $t['sex'] < $data[$j]['sex'];  // 排序开关
                if ($sort) {
                    $data[$j + 1] = $data[$j];
                    $data[$j] = $t;
                }
            } else break;
        }
    }

    $end = t();
    $time = round(($end - $start) * 1000, 4) . 'ms';
    var_dump($time, $data);
});
