<?php
require_once 'workflows.php';
function get_weather($query) {
    $workflow = new Workflows();
    $localtion = $query;
    $url = "http://api.map.baidu.com/telematics/v3/weather?location=$localtion&output=json&ak=1DULUC24MBTW3ZaeB7BBRUK9";
    $data = $workflow->request($url);
    $data = json_decode($data, true);
    if ($data["error"] != 0 || !isset($data) || empty($data)) {
        $workflow->result('', '', "没有结果,请检查你的输入或者网络状态", '', '', 'yes');
    } else {
        for ($i = 0; $i < 3; $i++) {
            $date = $data["results"][0]["weather_data"][$i]["date"]; // 日期
            $weather = $data["results"][0]["weather_data"][$i]["weather"]; // 天气
            $wind = $data["results"][0]["weather_data"][$i]["wind"]; // 风
            $temperature = $data["results"][0]["weather_data"][$i]["temperature"]; // 温度
            $dayPicture = basename($data["results"][0]["weather_data"][$i]["dayPictureUrl"]);// 天气图标
            $workflow->result('', '', $date . " $weather", $wind . ' ' . $temperature, "day/" . $dayPicture, 'yes');
        }
        $cyzs = $data["results"][0]["index"][0]["des"]; // 穿衣指数
        $zwxzs = $data["results"][0]["index"][5]["des"]; // 紫外线指数
        $cyzs = explode("。", $cyzs);
        $zwxzs = explode("。", $zwxzs);
        $workflow->result('', '', "今日穿衣指数", $cyzs[0], 'day/cyzs.png', 'yes');
        $workflow->result('', '', "今日紫外线指数", $zwxzs[0], 'day/zwxzs.png', 'yes');
    }
    echo $workflow->toxml();
}

