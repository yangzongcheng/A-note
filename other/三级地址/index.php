<?php




/**
 * @param $data
 * 打印
 */
function dd($data){
    echo "<pre>".print_r($data,true);
}

/**
 * 获取所有的省
 */
function getProvinces(){
    $prov = file_get_contents('./provinces.json');
    $provArr = json_decode($prov,true);
    $arr =[];
    foreach ($provArr as $key=>$val){
        $arr[$val['code']] = $val['name'];
    }
    return $arr;
}


//dd(getProvinces());
/**
 * @param $provinCode
 * @return array
 * 根据省code 获取 市
 */
function getCity($provinCode){
    $city = file_get_contents('./city.json');
    $cityArr = json_decode($city,true);
    $arr =[];
    foreach ($cityArr as $key=>$val){
        if($val['provinceCode']==$provinCode){
            $arr[$val['code']] = $val['name'];
        }
    }
    return  $arr;
}

//dd(getCity(51));

/**
 * @param $cityCode
 * @return array
 * 根据 市code 获取县
 */
function getArea($cityCode){
    $area = file_get_contents('./area.json');
    $areaArr = json_decode($area,true);
    $arr = [];
    foreach ($areaArr as $key=>$val){
        if($val['cityCode']==$cityCode){
            $arr[$val['code']] = $val['name'];
        }
    }
    return $arr;
}
