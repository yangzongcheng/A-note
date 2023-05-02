<?php

/**
 * 基类异常处理
 * DateTime:  2021/12/20 14:05:28
 * ClassName: HandleError
 */
class HandleError extends \ErrorException {
    /** @var int 成功 */
    const ERROR_SUCCESS = 0;

    /** @var int 失败 */
    const ERROR_FAILED = 1;

    /** @var int 参数错误 */
    const ERRNO_ARGS = 2;

    /** @var int 对象不存在 */
    const ERRNO_NOT_EXISTS = 3;

    /**
     * 错误数据
     *  可通过API接口data属性返回给客户端
     * @var array|null
     */
    protected array $data = [];
    public function setData(array $data): self {
        $this->data = $data;
        return $this;
    }
    public function getData():array {
        return $this->data;
    }


}

/**
 * 参数错误
 * DateTime:  2021/12/20 14:15:56
 * ClassName: ArgumentError
 */
class ArgumentError extends HandleError
{
    public  $code = HandleError::ERRNO_ARGS;
}


//try{

//    throw new ArgumentError('参数错误');

//}catch (\Exception $handleError)
//{
//    echo $handleError->getMessage();
//}


//捕获异常
set_exception_handler(function (HandleError $exception){
   $data =[
       'code'=>$exception->getCode(),
       'msg'=>$exception->getMessage(),
       'data'=>$exception->getData()
   ];
});

throw new ArgumentError('参数错误');


throw new ArgumentError('参数错误');


