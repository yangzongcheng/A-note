###php执行 过程

```text
1、php初始化执行环节，启动Zend引擎，加载注册的扩展模块

2、初始化后读取脚本文件，Zend引擎对脚本文件进行词法分析(lex)，语法分析(bison)，生成语法树

3、Zend 引擎编译语法树，生成opcode，

4、Zend 引擎执行opcode，返回执行结果


在PHP cli模式下，每次执行PHP脚本，四个步骤都会依次执行一遍；

在PHP-FPM模式下，步骤1)在PHP-FPM启动时执行一次，后续的请求中不再执行；步骤2)~4)每个请求都要执行一遍；

其实步骤2)、3)生成的语法树和opcode，同一个PHP脚本每次运行的结果都是一样的
```

###OPCache
[传送门](https://www.cnblogs.com/zhangzhijian/p/11175955.html)

```text


```