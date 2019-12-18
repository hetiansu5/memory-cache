## Introduction
PHP Memory Cache Library implements CacheInterface [PSR-16](https://learnku.com/docs/psr/psr-16-simple-cache/1628)

## Quick Start
```
    # New an cache instance
    # 构建一个实例
    $cache = new \MemoryCache\Cache();
        
    $cache->set($key, $value, $ttle);
    $cache->get($key);

```