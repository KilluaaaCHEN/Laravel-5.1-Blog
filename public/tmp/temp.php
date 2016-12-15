<?php

/**
 * Created by Killua Chen
 * User: killua
 * Date: 16/12/15
 * Time: 15:15
 */
class Data_BannerController extends iWebsite_Controller_Action
{
    /**
     * 首页
     * @author Killua Chen
     */
    public function indexAction()
    {
        try{
            $page_size = $this->get('page_size', 10);
            $page_index = $this->get('page_index', 1);
            $begin_time = $this->get('begin_time');
            $theme = $this->get('theme');
		$photo = $this->get('photo');
		$province = $this->get('province');
		$city = $this->get('city');
		$area = $this->get('area');
		$stores = $this->get('stores');
		$offers = $this->get('offers');
		$url = $this->get('url');
		$weight = intval($this->get('weight'));
		$is_enable = intval($this->get('is_enable'));
		$is_delete = intval($this->get('is_delete'));
		$brand_id = $this->get('brand_id');
		$desc = $this->get('desc');
		$start_time = $this->get('start_time');
		$end_time = $this->get('end_time');
		$offer_detail = $this->get('offer_detail');
            $query = [];
            $this->likeQuery($query, compact('theme', 'photo', 'province', 'city', 'area', 'stores', 'offers', 'url', 'is_enable', 'is_delete', 'brand_id', 'desc', 'start_time', 'end_time', 'offer_detail'));
            $this->equalQuery($query, compact('weight'));
            if ($begin_time) {
            $query['begin_time'] = ['$get' => new MongoDate(strtotime($begin_time))];
        }
        if ($end_time) {
            $query['end_time'] = ['$lte' => new MongoDate(strtotime($end_time))];
        }
            $db_service = new Data_Model_Banner();
            $data = $db_service->find(
                $query, ['__MODIFY_TIME__' => -1],
                ($page_index - 1) * $page_size, $page_size,
                ['theme' => 1, 'photo' => 1, 'province' => 1, 'city' => 1, 'area' => 1, 'stores' => 1, 'offers' => 1, 'url' => 1, 'weight' => 1, 'is_enable' => 1, 'is_delete' => 1, 'brand_id' => 1, 'desc' => 1, 'start_time' => 1, 'end_time' => 1, 'offer_detail' => 1, '_id' => 0, '__CREATE_TIME__' => 1]
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
        } catch (Exception $e) {
            $this->error(500, $e->getMessage());
        }
    }
    
            
    /**
     * 编辑数据
     * @author Killua Chen
     */
    public function editAction()
    {
        try {
            $id = $this->get('id');
            $db_service = new Data_Model_Banner();
            $fields = ['theme' => 1, 'photo' => 1, 'province' => 1, 'city' => 1, 'area' => 1, 'stores' => 1, 'offers' => 1, 'url' => 1, 'weight' => 1, 'is_enable' => 1, 'is_delete' => 1, 'brand_id' => 1, 'desc' => 1, 'start_time' => 1, 'end_time' => 1, 'offer_detail' => 1, '_id' => 0, '__CREATE_TIME__' => 1];
            $data = $db_service->getInfo($id, $fields);
            if (!$data) {
                $this->error(-1, '数据不存在');
            }
            $this->result('',$data);
        } catch (Exception $e) {
            $this->error(500, $e->getMessage());
        }
    }
    
            
    /**
     * 保存数据
     * @author Killua Chen
     */
    public function storeAction()
    {
        try{
            $theme = $this->get('theme');
		$photo = $this->get('photo');
		$province = $this->get('province');
		$city = $this->get('city');
		$area = $this->get('area');
		$stores = $this->get('stores');
		$offers = $this->get('offers');
		$url = $this->get('url');
		$weight = intval($this->get('weight'));
		$is_enable = intval($this->get('is_enable'));
		$is_delete = intval($this->get('is_delete'));
		$brand_id = $this->get('brand_id');
		$desc = $this->get('desc');
		$start_time = new MongoDate(strtotime($this->get('start_time')));
		$end_time = new MongoDate(strtotime($this->get('end_time')));
		$offer_detail = $this->get('offer_detail');
            $id = $this->get('id');
            $db_service = new Data_Model_Banner();
            
            $data = compact('theme', 'photo', 'province', 'city', 'area', 'stores', 'offers', 'url', 'weight', 'is_enable', 'is_delete', 'brand_id', 'desc', 'start_time', 'end_time', 'offer_detail');
            $rst = $db_service->saveData($data, $id);
            if ($rst) {
                $this->result('OK');
            } else {
                $this->error(-1, '保存失败');
            }
        } catch (Exception $e) {
            $this->error(500, $e->getMessage());
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
            $db_service = new Data_Model_Banner();
            $rst = $db_service->delData($id)['n'];
            if ($rst) {
                $this->result();
            } else {
                $this->error(-1, '删除失败');
            }
         } catch (Exception $e) {
            $this->error(500, $e->getMessage());
         }
    }
}