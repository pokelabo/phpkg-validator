<?php // -*- coding: utf-8 -*-

//--------------------------------------------------------------
// 単純比較(1)
//--------------------------------------------------------------
$steps->Then('/^NULLである$/i', function($world) {
        assertNull($world->output);
    });

$steps->Then('/^NULLではない$/i', function($world) {
        assertTrue($world->output !== null);
    });

$steps->Then('/^FALSEである$/i', function($world) {
        assertFalse($world->output);
    });
$steps->Then('/^FALSEではない$/i', function($world) {
        assertTrue($world->output !== false);
    });

$steps->Then('/^TRUEである$/i', function($world) {
        assertTrue($world->output);
    });

$steps->Then('/^TRUEではない$/i', function($world) {
        assertTrue($world->output !== true);
    });

$steps->Then('/^空の配列である$/', function($world) {
        assertEquals(array(), $world->output);
    });

$steps->Then('/^空である$/', function($world) {
        assertEquals('', $world->output);
    });

//--------------------------------------------------------------
// 単純比較(2)
//--------------------------------------------------------------
$steps->Then('/^"([^"]*)"である$/', function($world, $arg1) {
        assertEquals($arg1, $world->output);
    });

$steps->Then("/^'([^']*)'である$/", function($world, $arg1) {
        assertEquals($arg1, $world->output);
    });

$steps->Then("/^以下の内容と同じである\$/", function($world, $string) {
        assertEquals($string, $world->output);
    });

$steps->Then('/^"([^"]*)"で始まる$/', function($world, $arg1) {
        assertEquals($arg1, substr($world->output, 0, strlen($arg1)));
    });

$steps->Then('/^(?:戻り値は)?(\d+)である$/', function($world, $arg1) {
        if (!is_int($world->output)) {
            assertRegExp('/^\d+$/', $world->output);
        }
        assertSame((int)$arg1, (int)$world->output);
    });

$steps->Then('/^セットされている$/', function($world) {
        assertTrue(isset($world->output));
    });

$steps->Then('/^正規表現"([^"]*)"に合致する$/', function($world, $arg1) {
        assertGreaterThan(0, preg_match($arg1, $world->output));
});

$steps->Then('/^正規表現"([^"]*)"とは合致しない$/', function($world, $arg1) {
        assertEquals(0, preg_match($arg1, $world->output));
});

//--------------------------------------------------------------
// ファイルパス
//--------------------------------------------------------------
$steps->Given('/^ファイルパスが"([^"]*)"のとき$/', function($world, $arg1) {
        $world->filepath = preg_replace('!APP_ROOT?!', APP_ROOT, $arg1);
    });

$steps->Given('/^ファイルパスは存在する$/', function($world) {
        assertTrue(file_exists($world->filepath));
    });

$steps->Given('/^ファイルパスは存在しない$/', function($world) {
        assertFalse(file_exists($world->filepath));
    });

//--------------------------------------------------------------
// 汎用設定
//--------------------------------------------------------------
$steps->Given('/^"([^"]*)"が"([^"]*)"のとき$/', function($world, $arg1, $arg2) {
        switch (strtolower($arg2)) {
        case 'null':
            $arg2 = null;
            break;
        case 'true':
            $arg2 = true;
            break;
        case 'false':
            $arg2 = false;
            break;
        }

        $world->$arg1 = $arg2;
    });

//--------------------------------------------------------------
// URL
//--------------------------------------------------------------
$steps->Given('/^urlが"([^"]*)"のとき$/', function($world, $arg1) {
        $parts = parse_url($arg1);
        if ($parts === false) {
            unset($world->url);
            unset($_SERVER["REQUEST_URI"]);
            return;
        }

        $_SERVER["REQUEST_URI"] = $world->url = $arg1;
    });

//--------------------------------------------------------------
// インスタンス
//--------------------------------------------------------------
$steps->When('/^"([^"]*)"のクラスインスタンスを生成する$/', function($world, $arg1) {
        $world->output = new $arg1;
    });

$steps->Then('/^生成したインスタンスは"([^"]*)"クラスである$/', function($world, $arg1) {
        assertInstanceOf($arg1, $world->output);
    });

$steps->Then('/^戻り値のクラスは"([^"]*)"クラスである$/', function($world, $arg1) {
           assertInstanceOf($arg1, $world->output);
});

//--------------------------------------------------------------
// yaml
//--------------------------------------------------------------
$steps->Then('/^下記yamlの内容と同じである$/', function($world, $string) {
        $expected = yaml_parse($string);
        assertSame($expected, $world->output);
});

$steps->Then('/^下記yamlの内容が含まれている$/', function($world, $string) {
        $expected = yaml_parse($string);
        assertArrayContainsArray($expected, $world->output);
});

$steps->Given('/^下記yamlの内容を読み込む -> "([^"]*)"$/', function($world, $arg1, $string) {
        $world->set($arg1, yaml_parse($string));
});

//--------------------------------------------------------------
// 日付・時刻
//--------------------------------------------------------------
$steps->Given('/^現在日時を記録する$/', function($world) {
        $world->now = date('Y-m-d H:i:s');
    });

$steps->Then('/^デバッグ開始時刻と同じか、より新しい$/', function($world) {
        assertGreaterThanOrEqual(strtotime($world->debug_start_time), strtotime($world->output));
});

$steps->Then('/^今日である$/', function($world) {
        $today = strtotime(date('Y-m-d'));
        $tomorrow = $today + 86400;
        $subject_time = strtotime($world->output);
        assertTrue($today <= $subject_time && $subject_time < $tomorrow);
});

$steps->When('/^(\d+)秒待機する$/', function($world, $arg1) {
        sleep($arg1);
    });

//--------------------------------------------------------------
// その他変換
//--------------------------------------------------------------
$steps->Given('/^"([^"]*)"をbase64エンコードする$/', function($world, $arg1) {
        $world->$arg1 = base64_encode($world->$arg1);
    });
$steps->Given('/^"([^"]*)"をbase64デコードする$/', function($world, $arg1) {
        $world->$arg1 = base64_decode($world->$arg1);
    });
