<?php
class RedisSession{
    var $expire=86400;//过期时间
    var $sso_session;//session id
    var $session_folder;//session目录
    var $cookie_name;//cookie的名字
    var $redis;//redis连接
    var $cache;//缓存session
    var $expireAt;//过期时间
    /*
     *初始化
     *参数
     *$redis:php_redis的类实例
     *$cookie_name:cookie的名字
     *$session_id_prefix:sesion id的前缀
    **/
    function RedisSession($redis,$expire=86400,$cookie_name="sso_session",$session_id_prefix=""){
        $this->redis=$redis;
        $this->cookie_name=$cookie_name;
        $this->session_folder="sso_session:";
        //若是cookie已经存在则以它为session的id
        if(isset($_COOKIE[$this->cookie_name])){
            $this->sso_session=$_COOKIE[$this->cookie_name];
        }else{
            $this->expire=$expire;
            $this->expireAt=time()+$this->expire;
            //在IE6下的iframe无法获取到cookie,于是我使用了get方式传递了cookie的名字
            if(isset($_GET[$this->cookie_name])){
                $this->sso_session=$_GET[$this->cookie_name];
            }else{
                $this->sso_session=$this->session_folder.$session_prefix.md5(uniqid(rand(), true));
            }
            setcookie($this->cookie_name,$this->sso_session,$this->expireAt,"/");
        }
    }

    /*
     *设置过期时间
     *参数
    **/
    function expire($expire=86400){
        $this->expire=$expire;
        $this->expireAt=time()+$this->expire;
        //设置session过期时间
        setcookie($this->cookie_name,$this->sso_session,$this->expireAt,"/",".greatwallwine.com.cn");
        $this->redis->expireAt($this->sso_session, $this->expireAt);
    }

    /*
     *设置多个session的值
     *参数
     *$array:值
    **/
    function setMutil($array){
        $this->redis->hMset($this->sso_session,$array);
    }
    /*
     *设置session的值
     *参数
     *$key:session的key
     *$value:值
    **/
    function set($key,$value){
        $this->redis->hSet($this->sso_session,$key,$value);
    }
    /*
     *设置session的值为对象
     *参数
     *$key:session的key
     *$object:对象
    **/
    function setObject($key,$object){
        $this->redis->hSet($this->sso_session,$key,serialize($object));
    }

    /*
     *获取全部session的key和value
     @return: array
    **/
    function getAll(){
        return $this->redis->hGetAll($this->sso_session);
    }



    /*
     *获取一个session的key和value
     @return: array
    **/
    function get($key){
        return $this->redis->hGet($this->sso_session,$key);
    }

    /*
       *获取session的值为对象
       *参数
       *$key:session的key
       *$value:cookie的名字
      **/
    function getObject($key){
        return unserialize($this->redis->hGet($this->sso_session,$key));
    }
    /*
     *从缓存中获取一个session的key和value
     @return: array
    **/
    function getFromCache($key){
        if(!isset($this->cache)){
            $this->cache=$this->getAll();
        }
        return $this->cache[$key];
    }

    /*
     *删除一个session的key和value
     @return: array
    **/
    function del($key){
        return $this->redis->hDel($this->sso_session,$key);
    }
    /*
     *删除所有session的key和value
     @return: array
    **/
    function delAll(){
        return $this->redis->delete($this->sso_session);
    }
}