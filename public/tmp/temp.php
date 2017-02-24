<?php

/**
 * Created by Killua Chen
 * User: killua
 * Date: 17/02/24
 * Time: 10:13
 */
class Frontend_UserController extends iWebsite_Controller_Action
{
    /**
     * 首页
     * @author Killua Chen
     */
    public function indexAction()
    {
        try {
            $page_size = $this->get('page_size', 10);
            $page_index = $this->get('page_index', 1);
            
            $open_id = $this->get('open_id');
		$name = $this->get('name');
		$mobile = intval($this->get('mobile'));
		$gender = intval($this->get('gender'));
		$birthday = intval($this->get('birthday'));
		$stature = intval($this->get('stature'));
		$floor = $this->get('floor');
		$identity = $this->get('identity');
		$head_img = $this->get('head_img');
		$is_delete = intval($this->get('is_delete'));
            
            $query = ['is_delete' => ['$ne'=>true]];
            $this->likeQuery($query, compact('open_id', 'name', 'floor', 'identity', 'head_img', 'is_delete'));
            $this->equalQuery($query, compact('mobile', 'gender', 'birthday', 'stature'));
                    //按时间精确到秒查询
        $begin_time = $this->get('begin_time');
        $end_time = $this->get('end_time');
        if ($begin_time) {
            $query['begin_time'] = ['$gte' => new MongoDate(strtotime($begin_time))];
        }
        if ($end_time) {
            $query['end_time'] = ['$lte' => new MongoDate(strtotime($end_time))];
        }
        
        //按日期按天查询
        $begin_date = $this->get('begin_date');
        $end_date = $this->get('end_date');
        if ($begin_date) {
            $query['time']['$gte'] = strtotime($begin_date);
        }
        if ($end_date) {
            $query['time']['$lte'] = strtotime('+1 days', strtotime($$end_date));
        }

            $fu_service = new Frontend_Model_User();
            $is_export = $this->get('is_export');
            if ($is_export) {
                $page_size = MAX_VALUE;
                $page_index = 1;
            }
            $data = $fu_service->find(
                $query, ['__MODIFY_TIME__' => -1],
                ($page_index - 1) * $page_size, $page_size,
                ['open_id' => 1, 'name' => 1, 'mobile' => 1, 'gender' => 1, 'birthday' => 1, 'stature' => 1, 'floor' => 1, 'identity' => 1, 'head_img' => 1, 'is_delete' => 1, '_id' => 1]
            );
            foreach ($data['datas'] as &$item) {
            }
            if ($is_export) {
                arrayToCVS2('', [
                    'title' => [
                        'open_id' => '用户唯一标识',
                        'name' => '姓名',
                        'mobile' => '手机',
                        'gender' => '性别',
                        'birthday' => '生日',
                        'stature' => '身高',
                        'floor' => '楼层',
                        'identity' => '身份',
                        'head_img' => '头像',
                        'is_delete' => '是否删除'],
                    'result' => $data['datas']
                ]);
            }
            $page_total = floor($data['total'] / $page_size);
            if ($data['total'] % $page_size != 0) {
                $page_total++;
            }
            $data['page_total'] = $page_total;
            $this->result('', $data);
        } catch (Exception $e) {
            $this->error($e->getCode(), $e->getMessage());
        }
    }
    
            
    /**
     * 获取信息
     * @author Killua Chen
     */
    public function getInfoAction()
    {
        try {
            $id = $this->get('id');
            $fu_service = new Frontend_Model_User();
            $fields = ['open_id' => 1, 'name' => 1, 'mobile' => 1, 'gender' => 1, 'birthday' => 1, 'stature' => 1, 'floor' => 1, 'identity' => 1, 'head_img' => 1, 'is_delete' => 1, '_id' => 1];
            $data = $fu_service->getInfo($id, $fields);
            if (!$data) {
                abort(404,'数据不存在');
            }
            $this->result('',$data);
        } catch (Exception $e) {
            $this->error($e->getCode(), $e->getMessage());
        }
    }
    
            
    /**
     * 保存数据
     * @author Killua Chen
     */
    public function storeAction()
    {
        try {
            $open_id = $this->get('open_id');
		$name = $this->get('name');
		$mobile = intval($this->get('mobile'));
		$gender = intval($this->get('gender'));
		$birthday = intval($this->get('birthday'));
		$stature = intval($this->get('stature'));
		$floor = $this->get('floor');
		$identity = $this->get('identity');
		$head_img = $this->get('head_img');
		$is_delete = intval($this->get('is_delete'));
            $id = $this->get('id');
            $fu_service = new Frontend_Model_User();
            
            $rules = [
                'open_id' => 'required|unique',
                'name' => 'required',
                'mobile' => 'required|mobile',
                'gender' => 'required|numeric',
                'birthday' => 'required|dateFormat:Ymd',
                'stature' => 'required|integer',
                'floor' => 'required|integer',
                'identity' => 'required|integer',
                'head_img' => '',
                'is_delete' => '',
            ];
            $v = new iValidator($_REQUEST, $rules, $fu_service);
            if (!$v->validate()) {
                $this->error(-1,$v->msg());
            }
            
            $data = compact('open_id', 'name', 'mobile', 'gender', 'birthday', 'stature', 'floor', 'identity', 'head_img', 'is_delete');
            $rst = $fu_service->saveData($data, $id);
            if ($rst) {
                $this->result('OK');
            } else {
                abort(-1, '保存失败');
            }
        } catch (Exception $e) {
            $this->error($e->getCode(), $e->getMessage());
        } 
    }
    
            
    /**
     * 删除
     * @author Killua Chen
     */
    public function delAction()
    {
        try {
            $id = $this->get('id');
            $fu_service = new Frontend_Model_User();
            $rst = $fu_service->delData($id)['n'];
            if ($rst) {
                $this->result();
            } else {
                abort(-1, '删除失败');
            }
         } catch (Exception $e) {
            $this->error($e->getCode(), $e->getMessage());
         }
    }
}