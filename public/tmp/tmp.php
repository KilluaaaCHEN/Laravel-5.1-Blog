<?php

/**
 * Created by Killua Chen
 * User: killua
 * Date: 17/05/12
 * Time: 12:12
 */
class Frontend_UsersController extends iWebsite_Controller_Action
{
    /**
     * 首页
     * @author Killua Chen
     */
    public function indexAction()
    {
        try {
            $rules = [
                'open_id' => '',
                'nick_name' => '',
                'head_img' => 'url',
                'mobile' => 'mobile',
                'total_count' => 'numeric',
                'surplus_count' => 'numeric',
                'mobile_auth' => 'mobile',
                'img_list' => '',
                'praise_list' => '',
                'share_list' => '',
                'page_index' => 'required:1|integer',
                'page_size' => 'required:20|integer',
                'begin_time' => 'date',
                'end_time' => 'date',
                'begin_date' => 'dateFormat:Ymd',
                'end_date' => 'dateFormat:Ymd',
            ];
            $fu_service = new Frontend_Model_Users();
            $v = new iValidator($_REQUEST, $rules, $fu_service);
            if (!$v->validate()) {
                $this->error(-1, $v->msg());
            }
            $d = $v->data();
            
            $query = [
                'is_delete' => ['$ne' => true],
                'begin_time' => ['$lte' => new MongoDate()],
                'end_time' => ['$gte' => new MongoDate()],
            ];
            $this->likeQuery($query, [
                'open_id' => $d->open_id, 
'nick_name' => $d->nick_name, 
'head_img' => $d->head_img, 
'mobile' => $d->mobile, 
'mobile_auth' => $d->mobile_auth, 
'img_list' => $d->img_list, 
'praise_list' => $d->praise_list, 
'share_list' => $d->share_list]
            );
            $this->equalQuery($query, [
                'total_count' => $d->total_count, 
'surplus_count' => $d->surplus_count]
            );
            
                    
            //按时间精确到秒查询
            if ($d->begin_time) {
                $query['begin_time'] = ['$gte' => new MongoDate(strtotime($d->begin_time))];
            }
            if ($d->end_time) {
                $query['end_time'] = ['$lte' => new MongoDate(strtotime($d->end_time))];
            }
            
            //按日期按天查询
            if ($d->begin_date) {
                $query['time']['$gte'] = strtotime($d->begin_date);
            }
            if ($d->end_date) {
                $query['time']['$lte'] = strtotime('+1 days', strtotime($$d->end_date));
            }

            
            if ($d->is_export) {
                $d->page_size = MAX_VALUE;
                $d->page_index = 1;
            }
            
            $data = $fu_service->find(
                $query, ['sort' => 1],
                ($d->page_index - 1) * $d->page_size, $d->page_size,
                ['open_id' => 1, 'nick_name' => 1, 'head_img' => 1, 'mobile' => 1, 'total_count' => 1, 'surplus_count' => 1, 'mobile_auth' => 1, 'img_list' => 1, 'praise_list' => 1, 'share_list' => 1, '_id' => 1]
            );
            foreach ($data['datas'] as &$item) {
            }
            if ($d->is_export) {
                arrayToCVSPlus('', [
                    'title' => [
                        'open_id' => 'OpenId',
                        'nick_name' => '昵称',
                        'head_img' => '头像',
                        'mobile' => '手机号码',
                        'total_count' => '累计抽奖次数',
                        'surplus_count' => '剩余抽奖次数',
                        'mobile_auth' => '手机是否认证',
                        'img_list' => '照片列表',
                        'praise_list' => '点赞列表',
                        'share_list' => '分享列表'],
                    'result' => $data['datas']
                ]);
            }
            calcPager($data, $d->page_size);
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
            $rules = [
                'id' => 'required|exists' 
            ];
            $fu_service = new Frontend_Model_Users();
            $v = new iValidator($_REQUEST, $rules, $fu_service);
            if (!$v->validate()) {
                $this->error(-1, $v->msg());
            }
            $d = $v->data();
            $fields = ['open_id' => 1, 'nick_name' => 1, 'head_img' => 1, 'mobile' => 1, 'total_count' => 1, 'surplus_count' => 1, 'mobile_auth' => 1, 'img_list' => 1, 'praise_list' => 1, 'share_list' => 1, '_id' => 1];
            $data = $fu_service->getInfo($d->id, $fields);
            $this->result('', $data);
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
            $rules = [
                'id' => 'exists',
                'open_id' => 'required|unique',
                'nick_name' => 'required|unique',
                'head_img' => 'required|unique|url',
                'mobile' => 'required|unique|mobile',
                'total_count' => 'required|unique|numeric',
                'surplus_count' => 'required|unique|numeric',
                'mobile_auth' => 'required|unique|mobile',
                'img_list' => 'required|unique',
                'praise_list' => 'required|unique',
                'share_list' => 'required|unique',
            ];
            $fu_service = new Frontend_Model_Users();
            $v = new iValidator($_REQUEST, $rules, $fu_service);
            if (!$v->validate()) {
                $this->error(-1, $v->msg());
            }
            $data = $v->data(false);
            $rst = $fu_service->saveData($data, $data['id']);
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
            $rules = [
                'id' => 'required|exists' 
            ];
            $fu_service = new Frontend_Model_Users();
            $v = new iValidator($_REQUEST, $rules, $fu_service);
            if (!$v->validate()) {
                $this->error(-1, $v->msg());
            }
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
    
     /**
     * 导入数据
     * @author Killua Chen
     */
    public function importAction()
    {
        try {
            $rules = [
                'file' => 'required_file',
            ];
            $v = new iValidator($_REQUEST, $rules);
            if (!$v->validate()) {
                $this->error(-1, $v->msg());
            }
            $d = $v->data();
            $data = loadExcelData($d->file['tmp_name'], 2, 10);
            $batch_dta = [];
            foreach ($data as $item) {
                $batch_dta[] = [
                    'open_id' => (string)$item[0],
                    'nick_name' => (string)$item[1],
                    'head_img' => (string)$item[2],
                    'mobile' => (string)$item[3],
                    'total_count' => floatval($item[4]),
                    'surplus_count' => floatval($item[5]),
                    'mobile_auth' => iboolval($item[6]),
                    'img_list' => json_decode($item[7]),
                    'praise_list' => json_decode($item[8]),
                    'share_list' => json_decode($item[9])
                ];
            }
            $fu_service = new Frontend_Model_Users();
            $rst = $fu_service->batchInsert($batch_dta);
            $this->result('OK', $rst);
        } catch (Exception $e) {
            $this->error($e->getCode(), $e->getMessage());
        }
    }
}