<?php

/**
 * Created by Killua Chen
 * User: killua
 * Date: 17/05/10
 * Time: 14:27
 */
class Backend_StoreController extends iWebsite_Controller_Action
{
    /**
     * 首页
     * @author Killua Chen
     */
    public function indexAction()
    {
        try {
            $rules = [
                'store_code' => '',
                'company_name' => '',
                'province' => '',
                'city' => '',
                'region' => '',
                'store_name' => '',
                'business_type' => '',
                'address' => '',
                'location' => '',
                'page_index' => 'required:1|integer',
                'page_size' => 'required:20|integer',
                'begin_time' => 'date',
                'end_time' => 'date',
                'begin_date' => 'dateFormat:Ymd',
                'end_date' => 'dateFormat:Ymd',
            ];
            $bs_service = new Backend_Model_Store();
            $v = new iValidator($_REQUEST, $rules, $bs_service);
            if (!$v->validate()) {
                $this->error(-1,$v->msg());
            }
            $d = $v->data();
            
            $query = [
                'is_delete' => ['$ne'=>true],
                'begin_time' => ['$lte' => new MongoDate()],
                'end_time' => ['$gte' => new MongoDate()],
            ];
            $this->likeQuery($query, ['store_code'=>$d->store_code, 
'company_name'=>$d->company_name, 
'province'=>$d->province, 
'city'=>$d->city, 
'region'=>$d->region, 
'store_name'=>$d->store_name, 
'business_type'=>$d->business_type, 
'address'=>$d->address, 
'location'=>$d->location]);
            $this->equalQuery($query, []);
            
                    
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
            
            $data = $bs_service->find(
                $query, ['sort' => 1],
                ($d->page_index - 1) * $d->page_size, $d->page_size,
                ['store_code' => 1, 'company_name' => 1, 'province' => 1, 'city' => 1, 'region' => 1, 'store_name' => 1, 'business_type' => 1, 'address' => 1, 'location' => 1, '_id' => 1]
            );
            foreach ($data['datas'] as &$item) {
            }
            if ($d->is_export) {
                arrayToCVSPlus('', [
                    'title' => [
                        'store_code' => '门店编号',
                        'company_name' => '公司名称',
                        'province' => '省份',
                        'city' => '城市',
                        'region' => '区县',
                        'store_name' => '门店名称',
                        'business_type' => '商圈分类',
                        'address' => '门店地址',
                        'location' => '坐标'],
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
            $bs_service = new Backend_Model_Store();
            $v = new iValidator($_REQUEST, $rules, $bs_service);
            if (!$v->validate()) {
                $this->error(-1,$v->msg());
            }
            $d = $v->data();
            $fields = ['store_code' => 1, 'company_name' => 1, 'province' => 1, 'city' => 1, 'region' => 1, 'store_name' => 1, 'business_type' => 1, 'address' => 1, 'location' => 1, '_id' => 1];
            $data = $bs_service->getInfo($d->id, $fields);
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
            $rules = [
                'id' => 'exists',
                'store_code' => 'required|unique',
                'company_name' => 'required|unique',
                'province' => 'required|unique',
                'city' => 'required|unique',
                'region' => 'required|unique',
                'store_name' => 'required|unique',
                'business_type' => 'required|unique',
                'address' => 'required|unique',
                'location' => 'required',
            ];
            $bs_service = new Backend_Model_Store();
            $v = new iValidator($_REQUEST, $rules, $bs_service);
            if (!$v->validate()) {
                $this->error(-1,$v->msg());
            }
            $data = $v->data(false);
            $rst = $bs_service->saveData($data, $data['id']);
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
            $bs_service = new Backend_Model_Store();
            $v = new iValidator($_REQUEST, $rules, $bs_service);
            if (!$v->validate()) {
                $this->error(-1,$v->msg());
            }
            $rst = $bs_service->delData($id)['n'];
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
            $data = loadExcelData($d->file['tmp_name'], 2, 9);
            $batch_dta = [];
            foreach ($data as $\item) {
                $batch_dta[] = [
                    'store_code'=>$item[0],
                    'company_name'=>$item[1],
                    'province'=>$item[2],
                    'city'=>$item[3],
                    'region'=>$item[4],
                    'store_name'=>$item[5],
                    'business_type'=>$item[6],
                    'address'=>$item[7],
                    'location'=>$item[8]
                ];
            }
            $bs_service = new Backend_Model_Store();
            $rst = bs_service->batchInsert($batch_dta);
            $this->result('OK', $rst);
        } catch (Exception $e) {
            $this->error($e->getCode(), $e->getMessage());
        }
    }
}