<?php

/**
 * 通过接口类实现多继承
 */




interface TestA{
    public function A1():bool;
    public function A2():static;
}

interface TestB{
    public function B1():bool;
    public function B2():object;
}


class Handle implements TestA,TestB
{
    public function A1(): bool
    {
        // TODO: Implement A1() method.
        return true;
    }

    public function A2(): static
    {
        // TODO: Implement A2() method.
        return static::A2();
    }

    public function B1(): bool
    {
        // TODO: Implement B1() method.
        return true;
    }

    public function B2(): object
    {
        // TODO: Implement B2() method.
        return $this;
    }
}