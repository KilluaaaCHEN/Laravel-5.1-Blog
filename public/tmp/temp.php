<?php

/**
 * Created by Killua Chen
 * User: killua
 * Date: 17/02/20
 * Time: 18:14
 */
class Backend_SigninRecordController extends iWebsite_Controller_Action
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
		$staff_name = $this->get('staff_name');
		$status = intval($this->get('status'));
		$course = $this->get('course');
		$coach_id = $this->get('coach_id');
		$coach_name = $this->get('coach_name');
		$begin_time = intval($this->get('begin_time'));
		$end_time = intval($this->get('end_time'));
		$floor = $this->get('floor');
		$duration = intval($this->get('duration'));
		$course_type = intval($this->get('course_type'));
		$course_id = $this->get('course_id');
		$is_delete = intval($this->get('is_delete'));
		$date = intval($this->get('date'));
            
            $query = ['is_delete' => ['$ne'=>true]];
            $this->likeQuery($query, compact('open_id', 'staff_name', 'course', 'coach_id', 'coach_name', 'floor', 'course_id', 'is_delete'));
            $this->equalQuery($query, compact('status', 'begin_time', 'end_time', 'duration', 'course_type', 'date'));
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

            $bsr_service = new Backend_Model_SigninRecord();
            $is_export = $this->get('is_export');
            if ($is_export) {
                $page_size = MAX_VALUE;
                $page_index = 1;
            }
            $data = $bsr_service->find(
                $query, ['__MODIFY_TIME__' => -1],
                ($page_index - 1) * $page_size, $page_size,
                ['open_id' => 1, 'staff_name' => 1, 'status' => 1, 'course' => 1, 'coach_id' => 1, 'coach_name' => 1, 'begin_time' => 1, 'end_time' => 1, 'floor' => 1, 'duration' => 1, 'course_type' => 1, 'course_id' => 1, 'is_delete' => 1, 'date' => 1, '_id' => 0, '__CREATE_TIME__' => 1]
            );
            foreach ($data['datas'] as &$item) {
            }
            if ($is_export) {
                arrayToCVS2('', [
                    'title' => [
                        'open_id' => '学员编号',
                        'staff_name' => '学员姓名',
                        'status' => '签到状态',
                        'course' => '课程',
                        'coach_id' => '教练编号',
                        'coach_name' => '教练名称',
                        'begin_time' => '开始时间',
                        'end_time' => '结束时间',
                        'floor' => '楼层',
                        'duration' => '持续时间(m)',
                        'course_type' => '课程类型',
                        'course_id' => '课程编号',
                        'is_delete' => '是否删除',
                        'date' => '日期'],
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
            $bsr_service = new Backend_Model_SigninRecord();
            $fields = ['open_id' => 1, 'staff_name' => 1, 'status' => 1, 'course' => 1, 'coach_id' => 1, 'coach_name' => 1, 'begin_time' => 1, 'end_time' => 1, 'floor' => 1, 'duration' => 1, 'course_type' => 1, 'course_id' => 1, 'is_delete' => 1, 'date' => 1, '_id' => 0, '__CREATE_TIME__' => 1];
            $data = $bsr_service->getInfo($id, $fields);
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
		$staff_name = $this->get('staff_name');
		$status = intval($this->get('status'));
		$course = $this->get('course');
		$coach_id = $this->get('coach_id');
		$coach_name = $this->get('coach_name');
		$begin_time = intval($this->get('begin_time'));
		$end_time = intval($this->get('end_time'));
		$floor = $this->get('floor');
		$duration = intval($this->get('duration'));
		$course_type = intval($this->get('course_type'));
		$course_id = $this->get('course_id');
		$is_delete = intval($this->get('is_delete'));
		$date = intval($this->get('date'));
            $id = $this->get('id');
            $bsr_service = new Backend_Model_SigninRecord();
            
            $data = compact('open_id', 'staff_name', 'status', 'course', 'coach_id', 'coach_name', 'begin_time', 'end_time', 'floor', 'duration', 'course_type', 'course_id', 'is_delete', 'date');
            $rst = $bsr_service->saveData($data, $id);
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
            $bsr_service = new Backend_Model_SigninRecord();
            $rst = $bsr_service->delData($id)['n'];
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