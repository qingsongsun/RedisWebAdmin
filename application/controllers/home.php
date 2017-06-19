<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller
{
    //redis连接标识
    var $redis;

    //redis连接的判断
    var $redis_flag;

    public function __construct()
    {
        parent::__construct();

        $this->load->library('session');
    }

    public function index()
    {

        global $redis;
        $data["redis"] = $redis;

        if (! empty($_POST["redis_server"])) {
            $current_redis = array("redis_server" => $_POST["redis_server"]);
            $this->session->set_userdata($current_redis);
            $this->__init_redis();
        }

        $data["redis_server"] = $this->session->userdata("redis_server");

        $this->load->view('header');
        $this->load->view('left', $data);
        $this->load->view('right', $data);

    }

/*    //首页右侧页面
    public function right()
    {
        $this->load->view("right");
    }*/

    public function get(){

        global $redis;
        $data["redis"] = $redis;
        $data["redis_server"] = $this->session->userdata("redis_server");

        $action = $this->uri->segment(3);

        if ($action == "info"){

            $this->__init_redis();
            if ($this->redis_flag){
                try{
                    $data["info"] = $this->redis->info();
                }
                catch (Exception $e){
                    echo $e->getMessage();
                    echo "<br/>";
                    echo "remote redis don't support this command!";
                    echo "<br/>";
                    echo "<br/>";
                }
            }

            $this->load->view('header');
            $this->load->view('left', $data);
            $this->load->view("info", $data);
        }

    }

    //key相关命令
    public function key()
    {

        global $redis;
        $data["redis"] = $redis;
        $data["redis_server"] = $this->session->userdata("redis_server");

        $action = $this->uri->segment(3);
        $key = $this->input->post('key');
        $data["key"] = $key;

        global $key_type;

        //查看key类型
        if ($action == "key_type") {

            if (!$key) {
                $this->load->view('header');
                $this->load->view('left', $data);
                $this->load->view("key_type");
            } else {
                $this->__init_redis();
                if ($this->redis_flag) {
                    $data["result"] = $key_type[$this->redis->type($key)];
                }

                $this->load->view('header');
                $this->load->view('left', $data);
                $this->load->view('result', $data);
            }
        }

        //查看key是否存在
        if ($action == "key_exists") {
            $key = $this->input->post('key');
            if (!$key) {
                $this->load->view('header');
                $this->load->view('left', $data);
                $this->load->view("key_exists");
            } else {
                $this->__init_redis();
                if ($this->redis_flag) {
                    if ($this->redis->exists($key)) $data["result"] = '存在'; else $data["result"] = '不存在';
                }
                $this->load->view('header');
                $this->load->view('left', $data);
                $this->load->view('result', $data);
            }
        }

        //key ttl
        if ($action == "key_ttl") {
            $key = $this->input->post('key');
            if (!$key) {
                $this->load->view('header');
                $this->load->view('left', $data);
                $this->load->view("key_ttl");
            } else {
                $this->__init_redis();
                if ($this->redis_flag) {
                    if ($this->redis->ttl($key) == '-2') $data["result"] = '不存在'; else $data["result"] = $this->redis->ttl($key);
                }
                $this->load->view('header');
                $this->load->view('left', $data);
                $this->load->view('result', $data);
            }
        }

        //随机返回一个key
        if ($action == "key_randomkey") {
            $this->__init_redis();
            if ($this->redis_flag) {
                try {
                    $data["result"] = $this->redis->randomkey();

                    $this->load->view('header');
                    $this->load->view('left', $data);
                    $this->load->view('result', $data);

                } catch (Exception $e) {
                    echo $e->getMessage();
                    echo "<br/>";
                    echo "远程redis不支持该指令";
                    echo "<br/>";
                    echo "<br/>";
                }
            }
        }

        //del key
        if ($action == "key_delete") {

            if ($this->input->post("key") !== false) {
                $key = $this->input->post("key");

                $this->__init_redis();
                if ($this->redis_flag) {
                    if ($this->redis->delete($key) == '0') $data["result"] = '不存在'; else $data["result"] = '删除成功';
                }
                $this->load->view('header');
                $this->load->view('left', $data);
                $this->load->view('result', $data);

            } else {
                $this->load->view('header');
                $this->load->view('left', $data);
                $this->load->view("key_delete");
            }
        }

    }

    //string
    public function string(){

        global $redis;
        $data["redis"] = $redis;
        $data["redis_server"] = $this->session->userdata("redis_server");

        $action = $this->uri->segment(3);
        $key = $this->input->post('key');
        $data["key"] = $key;

        if ($action == "get") {

            if (!$key){
                $this->load->view('header');
                $this->load->view('left', $data);
                $this->load->view("string_get");
            }else{
                $this->__init_redis();
                if ($this->redis_flag) {
                    $data["result"] = $this->redis->get($key);
                }
                $this->load->view('header');
                $this->load->view('left', $data);
                $this->load->view('result', $data);
            }
        }

        if ($action == "getrange"){
            $start = $this->input->post('start');
            $stop = $this->input->post('stop');

            if ( $key ){

                if ($start == ""){
                    $start = "0";
                }
                if($stop == ""){
                    $stop = "-1";
                }

                $this->__init_redis();
                if ($this->redis_flag) {
                    if ($this->input->post("select_type") == "getRange") {
                        $data["result"] = $this->redis->getRange($key,$start,$stop);
                    }

                    $this->load->view('header');
                    $this->load->view('left', $data);
                    $this->load->view('result', $data);
                }
            }else{
                $this->load->view('header');
                $this->load->view('left', $data);
                $this->load->view("string_getrange");
            }
        }

    }

    public function hash(){

        global $redis;
        $data["redis"] = $redis;
        $data["redis_server"] = $this->session->userdata("redis_server");

        $action = $this->uri->segment(3);
        $key = $this->input->post('key');
        $data["key"] = $key;

        if($action == "hgetall"){
            if (!$key){
                $this->load->view('header');
                $this->load->view('left', $data);
                $this->load->view("hash_hgetall");
            }else{
                $data["redis_server"] = $this->session->userdata("redis_server");
                $this->__init_redis();
                if ($this->redis_flag) {
                    if ($this->input->post("select_type") == "hgetall") {
                        $data["result"] = $this->redis->hGetAll($key);
                    }
                }

                $this->load->view('header');
                $this->load->view('left', $data);
                $this->load->view('result', $data);
            }
        }

        if ($action == "hkeys"){
            if (!$key){
                $this->load->view('header');
                $this->load->view('left', $data);
                $this->load->view("hash_hkeys");
            }else{
                $this->__init_redis();
                if ($this->redis_flag) {
                    $data["result"] = $this->redis->hkeys($key);
                }
                $this->load->view('header');
                $this->load->view('left', $data);
                $this->load->view('result', $data);
            }
        }

        if ($action == "hlen"){
            if (!$key){
                $this->load->view('header');
                $this->load->view('left', $data);
                $this->load->view("hash_hlen");
            }else{
                $this->__init_redis();
                if ($this->redis_flag) {
                    if ($this->redis->hlen($key) !== 0){
                        $data["result"] = $this->redis->hlen($key);
                        $this->load->view('header');
                        $this->load->view('left', $data);
                        $this->load->view('result', $data);
                    }else{
                        $data["result"] = "key不存在，或key不是hash";
                        $this->load->view('header');
                        $this->load->view('left', $data);
                        $this->load->view('result', $data);
                    }
                }
            }
        }

        if ($action == "exists"){
            $field = $this->input->post('field');
            if (!$key || !$field){
                $this->load->view('header');
                $this->load->view('left', $data);
                $this->load->view("hash_exists");
            }else{
                $this->__init_redis();
                if ($this->redis_flag) {
                    if ($this->redis->hExists($key, $field) == 1){
                        $data["result"] = "key包含该field!";
                        $this->load->view('header');
                        $this->load->view('left', $data);
                        $this->load->view('result', $data);
                    }else{
                        $data["result"] = "field或者key不存在!";
                        $this->load->view('header');
                        $this->load->view('left', $data);
                        $this->load->view('result', $data);
                    }
                }
            }
        }

        if ($action == "hget"){

            $field = $this->input->post('field');

            if (!$key || !$field){
                $this->load->view('header');
                $this->load->view('left', $data);
                $this->load->view("hash_hget");
            }else{
                $data["redis_server"] = $this->session->userdata("redis_server");
                $this->__init_redis();
                if ($this->redis_flag) {
                    $data["result"] = $this->redis->hGet($key, $field);
                    $this->load->view('header');
                    $this->load->view('left', $data);
                    $this->load->view('result', $data);
                }
            }
        }

    }

    //列表相关操作
    public function lists(){

        global $redis;
        $data["redis"] = $redis;
        $data["redis_server"] = $this->session->userdata("redis_server");

        $action = $this->uri->segment(3);
        $key = $this->input->post('key');
        $data["key"] = $key;

        //根据索引获取列表中的元素
        if ($action == "lindex"){
            $key = $this->input->post('key');
            $lindex = $this->input->post('lindex');

            if (!$key){
                $this->load->view('header');
                $this->load->view('left', $data);
                $this->load->view("lists_lindex");
            }else{
                $this->__init_redis();
                if ($this->redis_flag) {
                    if ($this->redis->lget($key, intval($lindex)) === FALSE){
                        $data['result'] = 'key或索引值不存在';
                        $this->load->view('header');
                        $this->load->view('left', $data);
                        $this->load->view('result', $data);

                    }else{
                        $data['result'] = $this->redis->lget($key, intval($lindex));
                        $this->load->view('header');
                        $this->load->view('left', $data);
                        $this->load->view('result', $data);
                    }
                }
            }
        }

        //列表的长度
        if ($action == "llen"){
            $key = $this->input->post('key');

            if (!$key){
                $this->load->view('header');
                $this->load->view('left', $data);
                $this->load->view("lists_llen");
            }else{
                $this->__init_redis();

                if ($this->redis_flag) {
                    if ($this->redis->lSize($key) == 0){
                        $data['result'] = 'key不存在、为空或不是list类型';
                        $this->load->view('header');
                        $this->load->view('left', $data);
                        $this->load->view('result', $data);
                    }else{
                        $data['result'] = $this->redis->lSize($key);
                        $this->load->view('header');
                        $this->load->view('left', $data);
                        $this->load->view('result', $data);
                    }
                }
            }
        }

        if ($action == "lrange"){
            $key = $this->input->post('key');
            $start = $this->input->post('start');
            $stop = $this->input->post('stop');

            if (!$key){
                $this->load->view('header');
                $this->load->view('left', $data);
                $this->load->view("lists_lrange");
            }else{
                $this->__init_redis();

                if ($this->redis_flag) {
                    $result = $this->redis->lRange($key, $start, $stop);
                    if (empty($result)){
                        $data['result'] = 'key不存在或不是list类型';
                        $this->load->view('header');
                        $this->load->view('left', $data);
                        $this->load->view('result', $data);
                    }else{
                        $data['result'] = $result;
                        $this->load->view('header');
                        $this->load->view('left', $data);
                        $this->load->view('result', $data);
                    }
                }
            }
        }
    }

    public function sets(){

        global $redis;
        $data["redis"] = $redis;
        $data["redis_server"] = $this->session->userdata("redis_server");

        $action = $this->uri->segment(3);

        if ($action == "scard"){

            $key = $this->input->post('key');
            $data["key"] = $key;

            if(!$key){
                $this->load->view('header');
                $this->load->view('left', $data);
                $this->load->view("sets_scard");
            }else{
                $this->__init_redis();

                if ($this->redis_flag) {
                    $result = $this->redis->sCard($key);
                    if (empty($result)){
                        $data['result'] = 'key不存在或不是set类型';
                        $this->load->view('header');
                        $this->load->view('left', $data);
                        $this->load->view('result', $data);
                    }else{
                        $data['result'] = $result;
                        $this->load->view('header');
                        $this->load->view('left', $data);
                        $this->load->view('result', $data);
                    }
                }
            }
        }

        if ($action == "sdiff"){
            $key1 = $this->input->post('key1');
            $key2 = $this->input->post('key2');

            if(!$key1 || !$key2){
                $this->load->view('header');
                $this->load->view('left', $data);
                $this->load->view("sets_sdiff");
            }else{
                $this->__init_redis();

                if ($this->redis_flag) {
                    $result = $this->redis->sdiff($key1, $key2);
                    if (empty($result)){
                        $data['result'] = '无差集，或key不存在，或不是set类型';
                        $this->load->view('header');
                        $this->load->view('left', $data);
                        $this->load->view('result', $data);
                    }else{
                        $data['result'] = $result;
                        $this->load->view('header');
                        $this->load->view('left', $data);
                        $this->load->view('result', $data);
                    }
                }
            }

        }

        if ($action == "sinter"){
            $key1 = $this->input->post('key1');
            $key2 = $this->input->post('key2');

            if(!$key1 || !$key2){
                $this->load->view('header');
                $this->load->view('left', $data);
                $this->load->view("sets_sinter");
            }else{

                $this->__init_redis();

                if ($this->redis_flag) {
                    $result = $this->redis->sinter($key1, $key2);
                    if (empty($result)){
                        $data['result'] = '无差集，或key不存在，或不是set类型';
                        $this->load->view('header');
                        $this->load->view('left', $data);
                        $this->load->view('result', $data);
                    }else{
                        $data['result'] = $result;
                        $this->load->view('header');
                        $this->load->view('left', $data);
                        $this->load->view('result', $data);
                    }
                }
            }
        }

        if ($action == "sismember"){
            $key= $this->input->post('key');
            $member = $this->input->post('member');

            $data["key"] = $key;

            if(!$key || !$member){
                $this->load->view('header');
                $this->load->view('left', $data);
                $this->load->view("sets_sismember");
            }else{
                $this->__init_redis();

                if ($this->redis_flag) {
                    $result = $this->redis->sismember($key, $member);
                    if ($result){
                        $data['result'] = '存在';
                        $this->load->view('header');
                        $this->load->view('left', $data);
                        $this->load->view('result', $data);
                    }else{
                        $data['result'] = '无差集，或key不存在，或不是set类型';
                        $this->load->view('header');
                        $this->load->view('left', $data);
                        $this->load->view('result', $data);
                    }
                }
            }
        }

        if ($action == "smembers"){
            $key= $this->input->post('key');
            $data["key"] = $key;

            if(!$key){
                $this->load->view('header');
                $this->load->view('left', $data);
                $this->load->view("sets_smembers");
            }else{
                $this->__init_redis();

                if ($this->redis_flag) {
                    $data['result'] = $this->redis->smembers($key);
                    $this->load->view('header');
                    $this->load->view('left', $data);
                    $this->load->view('result', $data);
                }
            }
        }

        if ($action == "srandmember"){
            $key= $this->input->post('key');
            $count = $this->input->post('count');

            $data["key"] = $key;

            if(!$key){
                $this->load->view('header');
                $this->load->view('left', $data);
                $this->load->view("sets_srandmember");
            }else{
                $this->__init_redis();

                if ($this->redis_flag) {
                    if (empty($count)){
                        $data['result'] = $this->redis->srandmember($key);
                        $this->load->view('header');
                        $this->load->view('left', $data);
                        $this->load->view('result', $data);
                    }else{
                        $data['result'] = $this->redis->srandmember($key, $count);
                        $this->load->view('header');
                        $this->load->view('left', $data);
                        $this->load->view('result', $data);
                    }
                }
            }
        }

        if ($action == "sunion"){
            $key1 = $this->input->post('key1');
            $key2 = $this->input->post('key2');

            $data["key"] = $key;

            if(!$key1 || !$key2){
                $this->load->view('header');
                $this->load->view('left', $data);
                $this->load->view("sets_sunion");
            }else{
                $this->__init_redis();

                if ($this->redis_flag) {
                    $data['result'] = $this->redis->sunion($key1, $key2);
                    $this->load->view('header');
                    $this->load->view('left', $data);
                    $this->load->view('result', $data);
                }
            }
        }

    }

    public function zsets(){

        global $redis;
        $data["redis"] = $redis;
        $data["redis_server"] = $this->session->userdata("redis_server");

        $action = $this->uri->segment(3);

        if ($action == "zcard"){
            $key = $this->input->post('key');
            $data["key"] = $key;

            if(!$key){
                $this->load->view('header');
                $this->load->view('left', $data);
                $this->load->view("zsets_zcard");
            }else{
                $this->__init_redis();

                if ($this->redis_flag) {
                    $data['result'] = $this->redis->zsize($key);
                    $this->load->view('header');
                    $this->load->view('left', $data);
                    $this->load->view('result', $data);
                }
            }
        }

        if ($action == "zcount"){
            $key = $this->input->post('key');
            $start = $this->input->post('start');
            $stop = $this->input->post('stop');

            $data["key"] = $key;

            if(!$key){
                $this->load->view('header');
                $this->load->view('left', $data);
                $this->load->view("zsets_zcount");
            }else{

                if ($start == ""){
                    $start = "0";
                }
                if($stop == ""){
                    $stop = "-1";
                }

                $this->__init_redis();

                if ($this->redis_flag) {
                    $data['result'] = $this->redis->zcount($key, $start, $stop);
                    $this->load->view('header');
                    $this->load->view('left', $data);
                    $this->load->view('result', $data);
                }

            }
        }

    }

    private function __init_redis(){
        if (! $this->redis_flag){
            if (empty($this->session->userdata("redis_server"))){
                echo "<script>alert('redis server is not connected!');</script>";exit;
            }
            $tmp = explode(":", $this->session->userdata("redis_server"));
            settype($tmp[1], "int");
            $this->redis = new Redis();
            $this->redis_flag = $this->redis->pconnect($tmp[0], $tmp[1]);
            if (!$this->redis_flag) echo "<script>alert('redis connect failed -_-!');</script>";
        }
    }
}