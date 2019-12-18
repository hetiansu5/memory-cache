<?php

use MemoryCache\Cache;
use PHPUnit\Framework\TestCase;
use MemoryCache\InvalidArgumentException;

class CacheTest extends TestCase
{

    public function testGetInstance()
    {
        $cache = Cache::getInstance();
        $cache1 = Cache::getInstance();
        $cache2 = new Cache();
        $this->assertTrue($cache === $cache1);
        $this->assertFalse($cache === $cache2);
    }

    public function testSet()
    {
        $cache = new Cache();

        $key = "test";
        $value = 1;
        $cache->set($key, 1, 10);
        $res = $cache->get($key);
        $this->assertSame($value, $res);

        \Carbon\Carbon::setTestNow(\Carbon\Carbon::now()->addMinute());
        $res = $cache->get($key);
        $this->assertSame(null, $res);
        \Carbon\Carbon::setTestNow();

        $cache->set($key, 2, -1);
        $res = $cache->get($key);
        $this->assertSame(null, $res);
    }

    public function testSet_Exception()
    {
        $cache = new Cache();
        $this->expectException(InvalidArgumentException::class);
        $cache->set(["sdfdf"], 1, 1);
    }

    public function testGet()
    {
        $cache = new Cache();

        $key = "test1";
        $value = ["id" => 22];

        $res = $cache->get($key);
        $this->assertSame(null, $res);

        $cache->set($key, $value, 10);
        $res = $cache->get($key);
        $this->assertSame($value, $res);
    }

    public function testGet_Exception()
    {
        $cache = new Cache();
        $this->expectException(InvalidArgumentException::class);
        $cache->get(["sdfdf"]);
    }

    public function testHas()
    {
        $cache = new Cache();

        $key = "test2";
        $value = new \StdClass();
        $value->name = "who";

        $res = $cache->has($key);
        $this->assertSame(false, $res);

        $cache->set($key, $value, -10);
        $res = $cache->has($key);
        $this->assertSame(false, $res);

        $cache->set($key, $value, 10);
        $res = $cache->has($key);
        $this->assertSame(true, $res);
    }

    public function testHas_Exception()
    {
        $cache = new Cache();
        $this->expectException(InvalidArgumentException::class);
        $cache->has(1);
    }


    public function testDelete()
    {
        $cache = new Cache();
        $key = "test3";
        $value = 1;

        $cache->set($key, $value, 10);
        $cache->delete($key);

        $res = $cache->has($key);
        $this->assertSame(false, $res);
    }

    public function testSetMultiple()
    {
        $cache = new Cache();

        $arr = [
            "t1" => 1,
            "t2" => "2",
        ];
        $cache->setMultiple($arr);

        $res1 = $cache->get("t1");
        $res2 = $cache->get("t2");

        $this->assertSame(1, $res1);
        $this->assertSame("2", $res2);

        $cache->setMultiple([
            "t2" => 3
        ], 10);

        $res2 = $cache->get("t2");
        $this->assertSame(3, $res2);

        $cache->setMultiple([
            "t2" => 3
        ], -10);

        $res2 = $cache->get("t2");
        $this->assertSame(null, $res2);
    }

    public function testSetMultiple_Exception()
    {
        $cache = new Cache();
        $this->expectException(InvalidArgumentException::class);
        $arr = "test1";
        $cache->setMultiple($arr);
    }

    public function testGetMultiple()
    {
        $cache = new Cache();

        $arr = [
            "t1" => 1,
            "t2" => "2",
        ];
        $cache->setMultiple($arr);

        $res = $cache->getMultiple(["t1", "t2", "t3"]);
        $this->assertSame([
            "t1" => 1,
            "t2" => "2",
            "t3" => null
        ], $res);
    }


    public function testDeleteMultiple()
    {
        $cache = new Cache();

        $arr = [
            "t1" => 1,
            "t2" => "2",
            "t3" => false
        ];
        $cache->setMultiple($arr);

        $cache->deleteMultiple(["t1", "t2"]);

        $res = $cache->getMultiple(["t1", "t2", "t3"]);
        $this->assertSame([
            "t1" => null,
            "t2" => null,
            "t3" => false
        ], $res);
    }

}