<?php

/**
 * Created by Killua Chen
 * User: killua
 * Date: 16/12/05
 * Time: 11:20
 */
class Custom_UserInfoController extends iWebsite_Controller_Action
{
    /**
     * 首页
     * @author Killua Chen
     */
    public function indexAction()
    {
        $page_size = $this->get('page_size', 10);
        $page_index = $this->get('page_index', 1);
        
        $name = $this->get('name');
		$age = intval($this->get('age'));
		$pwd = $this->get('pwd');
		$pwd2 = $this->get('pwd2');
		$begin_time = $this->get('begin_time');
		$end_time = $this->get('end_time');
		$start_time = intval($this->get('start_time'));
		$stop_time = intval($this->get('stop_time'));
		$is_valid = intval($this->get('is_valid'));
		$type = intval($this->get('type'));
        $query = [];
        $this->likeQuery($query, compact('name', 'pwd', 'pwd2', 'begin_time', 'end_time', 'is_valid'));
        $this->equalQuery($query, compact('age', 'start_time', 'stop_time', 'type'));
        if ($begin_time) {
            $query['begin_time'] = ['$get' => new MongoDate(strtotime($begin_time))];
        }
        if ($end_time) {
            $query['end_time'] = ['$lte' => new MongoDate(strtotime($end_time))];
        }
        $cui_service = new Custom_Model_UserInfo();
        $data = $cui_service->find(
            $query, ['__MODIFY_TIME__' => -1],
            ($page_index - 1) * $page_size, $page_size,
            ['name' => 1, 'age' => 1, 'pwd' => 1, 'pwd2' => 1, 'begin_time' => 1, 'end_time' => 1, 'start_time' => 1, 'stop_time' => 1, 'is_valid' => 1, 'type' => 1, '_id' => 0, '__CREATE_TIME__' => 1]
        );
        foreach ($data['datas'] as &$item) {
            $item['__CREATE_TIME__'] = date('Y-m-d H:i:s', $item['__CREATE_TIME__']->sec);
        }
        $page_total = floor($data['total'] / $page_size);
        if ($data['total'] % $page_size != 0) {
            $page_total++;
        }
        $data['page_total'] = $page_total;
        $this->result('', $data);
    }
    
            
    /**
     * 编辑数据
     * @author Killua Chen
     */
    public function editAction()
    {
        $id = $this->get('id');
        $cui_service = new Custom_Model_UserInfo();
        $fields = ['name' => 1, 'age' => 1, 'pwd' => 1, 'pwd2' => 1, 'begin_time' => 1, 'end_time' => 1, 'start_time' => 1, 'stop_time' => 1, 'is_valid' => 1, 'type' => 1, '_id' => 0, '__CREATE_TIME__' => 1];
        $data = $cui_service->getInfo($id, $fields);
        if (!$data) {
            $this->error(-1, '数据不存在');
        }
        $this->result('',$data);
    }
    
            
    /**
     * 保存数据
     * @author Killua Chen
     */
    public function storeAction()
    {
        $name = $this->get('name');
		$age = intval($this->get('age'));
		$pwd = $this->get('pwd');
		$pwd2 = $this->get('pwd2');
		$begin_time = new MongoDate(strtotime($this->get('begin_time')));
		$end_time = new MongoDate(strtotime($this->get('end_time')));
		$start_time = intval($this->get('start_time'));
		$stop_time = intval($this->get('stop_time'));
		$is_valid = intval($this->get('is_valid'));
		$type = intval($this->get('type'));
        $id = $this->get('id');
        $cui_service = new Custom_Model_UserInfo();
        if ($cui_service->validateUnique('name', $name, $id)) {
    		$this->error(-1, '名称已被使用');
		}

        $data = compact('name', 'age', 'pwd', 'pwd2', 'begin_time', 'end_time', 'start_time', 'stop_time', 'is_valid', 'type');
        $rst = $cui_service->saveData($data, $id);
        if ($rst) {
            $this->result('OK');
        } else {
            $this->error(-1, '保存失败');
        }
    }
    
            
    /**
     * 删除
     * @author Killua Chen
     */
    public function delAction()
    {
        $id = $this->get('id');
        $cui_service = new Custom_Model_UserInfo();
        $rst = $cui_service->delData($id)['n'];
        if ($rst) {
            $this->result();
        } else {
            $this->error(-1, '删除失败');
        }
    }
}