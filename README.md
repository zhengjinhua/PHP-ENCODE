PHP OPCODE COMPILER
====
通过把php类源码编译编译成opcode,至少可以达到隐藏业务逻辑,增加恶意修改成本

应用场景
-------------
给项目中Controller/Model/Lib编译成opcode

apc配置注意
---------
apc.enable_cli = 1

apc.stat = 0

注意
------------
只能用于类文件

