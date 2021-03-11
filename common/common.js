/**
 * Created by yangzc on 2017/7/28.
 * 公用js库
 */

/***
 * 获取sessionid
 * @returns {string}
 */
function getSessionId() {
    var c_name = 'PHPSESSID';
    if (document.cookie.length > 0) {
        var c_start = document.cookie.indexOf(c_name + "=")
        if (c_start != -1) {
            c_start = c_start + c_name.length + 1
            var c_end = document.cookie.indexOf(";", c_start)
            if (c_end == -1) c_end = document.cookie.length
            return unescape(document.cookie.substring(c_start, c_end));
        }
    }

    /**
     * 获取当前域名
     * @returns {string}
     */
    function getHttp() {
        return window.location.protocol + "//" + window.location.host + '/';
    }

    /**
     * h5 缓存 localStorage
     * @param key
     * @param val 最好是对象
     */
    function cache(key, val) {
        if (key && val) {
            var get = localStorage.setItem(key, val);
            if (get) {
                return JSON.parse(get);
            }
        } else if (key) {
            return localStorage.getItem(JSON.stringify(key));
        }
    }

    /**
     * h5 缓存 localStorage
     * 删除缓存
     * @param key
     */
    function cache_remove(key) {
        return localStorage.removeItem(key);
    }

    /**
     * h5 缓存 localStorage
     * 清楚缓存
     */
    function cache_clear() {
        return localStorage.clear();
    }


    /**
     * 拼接url  参数
     * param array
     * 如
     *  param['id'] = id;
     *  param['student_name'] = student_name;
     *  param['course_number'] = course_number;
     */
    function url_field(param) {
        var url = '';
        for (var key in param) {
            if (param[key]) {
                url += "&" + key + '=' + param[key];
            }
        }
        return url;
    }

    /**
     * Created by yangzc
     * 获取 name值相同的 input 的值 返回 arr
     * @param $name
     * @returns {Array} 2018年03月08日09:53:41
     */
    function eq_input_val($name) {
        var obj = $("input[name=" + $name + "]");
        var arr = [];
        for (var i = 0; i < obj.length; i++) {
            var value = $(obj[i]).val();
            arr.push(value);
        }
        return arr;
    }

    /*
     * @param num
     * @returns {*}
     * 判断是否是正整数
     */
    function is_int(num) {
        //判断是否是数字
        var r = /^\+?[1-9][0-9]*$/;//正整数
        if (!r.test(num)) {
            return false;
        } else {
            return num;
        }
    }


    /**
     * 验证ajax 返回参数
     * @param $data
     */
    function check_data($data) {
        if ($data.errcode != 200) {
            layer.msg($data.msg);
            return false;//返回false 便于判断
        } else {
            return $data.data;
        }
    }

    /***
     * jq ajaxpost
     * @param url
     * @param data
     * @param call_function
     */
    function ajax_post(url, data, call_function) {
        $.ajax({
            url: url,
            type: 'post',
            data: data,
            dataType: 'json',
            success: call_function,
            error: function () {
                return layer.msg('请求失败');
            }
        });
    }

    /**
     * 对象转数组
     */
    function object_to_array($obj) {
        var arr = [];
        for (var val in $obj) {
            arr.push($obj[val]);
        }
        return arr;
    }

    /**
     *数组转对象
     */
    function array_to_object($arr) {
        var object = new Object();
        for (var key in $arr) {
            object[key] = $arr[key];
        }
        return object;
    }

    /**
     * 获取url 参数
     * @param variable
     * @returns {*}
     */
    function get_url_param(paraName) {
        var url = document.location.toString();
        var arrObj = url.split("?");

        if (arrObj.length > 1) {
            var arrPara = arrObj[1].split("&");
            var arr;

            for (var i = 0; i < arrPara.length; i++) {
                arr = arrPara[i].split("=");

                if (arr != null && arr[0] == paraName) {
                    return arr[1];
                }
            }
            return "";
        } else {
            return "";
        }
    }


    /**
     * 时间戳转日期
     * @param timestamp
     * @returns {string}
     */
    function strtotime(timestamp) {
        var date = new Date(timestamp * 1000);//时间戳为10位需*1000，时间戳为13位的话不需乘1000
        var Y = date.getFullYear() + '-';
        var M = (date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1) + '-';
        var D = date.getDate() > 9 ? date.getDate() + ' ' : '0' + date.getDate() + ' ';
        var h = date.getHours();

        var m = date.getMinutes();
        var s = date.getSeconds();

        h = h < 10 ? '0' + h : h;
        m = m < 10 ? '0' + m : m;
        s = s < 10 ? '0' + s : s;
        return Y + M + D + h + ':' + m + ':' + s;
    }

    /**
     * 获取当前时间戳
     * @returns {number}
     */
    function totime() {
        return Date.parse(new Date()) / 1000;
    }

    /**
     * 合并josn对象
     */
    function json_merge(target, old) {
        var arr = object_to_array(old);
        for (var obj in target) {
            arr.push(target[obj]);
        }
        return array_to_object(arr);
    }

    $(function () {

        //加载本地图片
        var fileList = [];
        var imageFile = document.getElementById('imageFile');// input id
        var changeImg = function () {
            var image = document.getElementById('image'); //img id
            var file = document.getElementById("imageFile").files[0];
            fileList = file;
            //imageData.append('imgFile1', file);
            var reader = new FileReader();
            reader.onload = function () {
                image.src = this.result;
            };
            reader.onerror = function () {
                alert('上传文件错误');
            };
            reader.readAsDataURL(file);
        };
        imageFile.addEventListener('change', changeImg);
    })


    /**
     *   // <input hidden="hidden" id="name" onchange="update_info(6,$(this))" name="head" class="head-img" type="file" accept="image/*"  size="30">
     *    var files = $('#upload-tx')[0].files[0];
     var formData = new FormData();
     formData.append('val', files);
     formData.append('_token', _token);
     formData.append('type',3);
     * @param Url
     * @param formData
     * @param func
     */
    function ajax_upload_file(Url, formData, func) {
        $.ajax({
            url: Url,
            type: 'POST',
            data: formData,
            // 告诉jQuery不要去处理发送的数据
            processData: false,
            // 告诉jQuery不要去设置Content-Type请求头
            contentType: false,
            beforeSend: function () {
                console.log("正在进行，请稍候");
            },
            success: func,
            error: function (responseStr) {
                return msg('请求失败~', 201);
            }
        });
    }

    /**
     * ajax 上传文件
     *  var formData = new FormData();
     *  var name = $("input").val();
     *  formData.append("file",$("#upload")[0].files[0]);
     *  formData.append("name",name);
     *  name input 的id 和上传文件的name 值
     *  param 额外的参数 默认post   json格式 如:{a:1,b:2}
     */
    function ajax_upload_files(Url, name, param, func, load) {
        // <input hidden="hidden" id="name" onchange="update_info(6,$(this))" name="head" class="head-img" type="file" accept="image/*"  size="30">
        var files = $('#name').prop('files');
        var formData = new FormData();
        formData.append('head', files[0]);
        formData.append('type', 1);

        for (var k in param) {
            formData.append(k, param[k]);
        }
        $.ajax({
            url: Url,
            type: 'POST',
            data: formData,
            // 告诉jQuery不要去处理发送的数据
            processData: false,
            // 告诉jQuery不要去设置Content-Type请求头
            contentType: false,
            beforeSend: load,//正在进行，请稍候
            success: func,
            error: function (responseStr) {
                return msg('请求失败~', 201);
            }
        });
    }
}

