<?php
namespace Common\Controller;
use Think\Controller;
/**
 * Redis操作
 * 
 */
class RedisoptController extends Controller
{
    private $serverConf ;
    private $expiretime;

    private $redisObj;

    public function __construct($servernm,$dbnm=0,$exptime=false)
    {

        $this->serverConf = $this->getRedisConf($servernm);
        $servercon = $this->serverConf;
        if($exptime){
            $this->expiretime = $exptime;
        }else if($exptime == -1){
            //

        }else{
            $this->expiretime = $servercon['expire_time'];
        }

        $this->createRedisObj($servercon,$dbnm);

        parent::__construct();
    }
    
    
    //get redis config 
    private function getRedisConf($servernm='server1'){
        $redisAllConf = C('REDIS_SERVER');
        $serverConf = $redisAllConf[$servernm];
        return $serverConf;
    }

    //获取redis操作对象
    private function createRedisObj($serverConf,$dbnm=0){
        $redis = new \Redis();

        $redis->connect($serverConf['host'], $serverConf['port']);//1秒超时
        $redis->auth($serverConf['auth']);
        //$redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
        $redis->select($dbnm);

        $this->redisObj = $redis;

    }

    //设置set集合
    public function addSet($key,$list,$dbnm=0){
        
        $redisOb = $this->redisObj;
        $redisOb->select($dbnm);
        
        foreach ($list as $k => $v) {
            $redisOb->Sadd($key,$v);
        }
        $redisOb->expire($key,$this->expiretime);
    }

    //设置set集合
    public function addSetExpire($key,$list,$dbnm=0){
        $servercon = $this->getRedisConf($servernm);
        $expiretime = $servercon['expire_time'];

        $redisOb = $this->redisObj;
        $redisOb->select($dbnm);
        

        foreach ($list as $k => $v) {
            $redisOb->Sadd($key,$v);
        }
        $redisOb->expire($key,$expiretime);
    }

    //移除set集合的某个元素
    public function sremSet($key,$list,$dbnm=0){
        
        $redisOb = $this->redisObj;
        $redisOb->select($dbnm);
        
        foreach ($list as $k => $v) {
            $redisOb->Srem($key,$v);
        }
    }

    //重新设置set集合
    public function reAddSet($key,$list,$dbnm=0){
        
        $redisOb = $this->redisObj;
        $redisOb->select($dbnm);
        $redisOb->del($key);
        
        foreach ($list as $k => $v) {
            $redisOb->Sadd($key,$v);
        }
        $redisOb->expire($key,$this->expiretime);
    }

    //获取set集合
    public function getSet($key,$dbnm=0){
        $redisOb = $this->redisObj;
        $redisOb->select($dbnm);
        $result = $redisOb->Smembers($key);
        return $result;
    }

    //随机获取指定数量set集合
    public function srandmemberSet($key,$count,$dbnm=0){
        $redisOb = $this->redisObj;
        $redisOb->select($dbnm);
        $result = $redisOb->srandmember($key,$count);
        return $result;
    }

    //获取set集合数量
    public function getSetNums($key,$dbnm=0){
        $redisOb = $this->redisObj;
        $redisOb->select($dbnm);
        $result = $redisOb->scard($key);
        return $result;
    }


    //get redis key缓存
    public function getRedisKeyCache($key,$dbnm=0){

        $redisOb = $this->redisObj;
        $redisOb->select($dbnm);
        $result = $redisOb->get($key);
        return json_decode($result, true);
    }

    //set redis key缓存
    public function setRedisKeyCache($key,$value,$dbnm=0){
        $redisOb = $this->redisObj;
        $redisOb->select($dbnm);
        $redisOb->setex($key,$this->expiretime, json_encode($value));
    }



    //get redis hash缓存
    public function getRedisHashCache($key,$sql,$dbnm=0){
        $hashkey = md5($sql);
        
        $redisOb = $this->redisObj;
        $redisOb->select($dbnm);

        $result = $redisOb->hGet($key,$hashkey);
        if($result){
            $result_arr = json_decode($result,true);
            if(count($result_arr) > 0){
                return $result_arr;
            }
        }
        
        $rows = M()->query($sql);
        if(count($rows) > 0){
            $rows_json = json_encode($rows);
            $this->SetRedisHashCache($key,$hashkey,$rows_json,$dbnm);
        }
        
        return $rows;
        
    }

    public function getNoRedisHashCache($key,$sql,$dbnm=0){
        $hashkey = md5($sql);
        
        $rows = M()->query($sql);
        if(count($rows) > 0){
            $rows_json = json_encode($rows);
            $this->SetRedisHashCache($key,$hashkey,$rows_json,$dbnm);
        }
        
        return $rows;
        
    }


    //删除key缓存
    public function delKeyCache($key){
        $redisOb = $this->redisObj;
        $keys = $redisOb->keys($key);
        $redisOb->delete($keys);
    }

    //set redis hash缓存
    public function SetRedisHashCache($key,$hashkey,$value,$dbnm=0){

        $redisOb = $this->redisObj;
        $redisOb->select($dbnm);

        $redisOb->hSet($key,$hashkey,$value);
        $redisOb->expire($key,$this->expiretime);
    }

    //get redis hash缓存
    public function GetBaseRedisHashCache($key,$hashkey,$dbnum){
        $redisOb = $this->redisObj;
        $redisOb->select($dbnum);

        $khval = $redisOb->hGet($key,$hashkey);
        return $khval;
    }
    
}
