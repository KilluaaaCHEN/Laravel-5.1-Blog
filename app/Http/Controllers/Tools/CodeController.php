<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CodeController extends Controller
{
    public $structure; // json
    public $delete; //is_delete
    public $model_cls; //Admin_Model_User
    public $model_short;// uc_service
    public $keys;// ['key1'=>1,'key2'=>1]
    public $keys2;// 'key1' ,'key2'
    public $keys_arr; // ['key1','key2']
    public $keys_int; // ['key1','key2']
    public $keys_ex_int; // ['key1','key2']
    public $attr_kv; //['open_id' => '学员编号','staff_name' => '学员姓名']
    public $keys_like;//['key1'=>$d->key1]

    /**
     * 生成iWebSite CRUD代码
     * @param Request $request
     * @return mixed
     */
    public function generate(Request $request)
    {
        $data = $request->all();
        if ($request->getMethod() == 'POST') {
            $this->validate($request, [
                'db' => 'required',
                'coll' => 'required',
                'module' => 'required',
                'ctrl' => 'required',
                'model' => 'required',
                'structure' => 'required',
            ]);
            $this->initVariable($data);
            if ($data['type'] == 'model') {
                return $this->generateModel($data);
            } elseif ($data['type'] == 'controller') {
                return $this->generateController($data);
            }
            return redirect()->back()->withInput($data);
        }
        return view('tools.code');
    }

    /**
     * 初始化变量
     * @param $data
     * @author Killua Chen
     */
    public function initVariable($data)
    {
        $fields = json_decode($data['structure'], true);
        $this->structure = $fields;
        $this->delete = $data['delete'];
        $this->model_cls = "{$data['module']}_Model_{$data['model']}";
        $this->setModelShort();
        $this->setKeys();
        $this->setKeys2();
        $this->setKeysArray();
        $this->setKeysInt();
        $this->setKeysLike();
        $this->setKeysAttr();
    }

    /**
     * 设置model变量名
     * @author Killua Chen
     */
    public function setModelShort()
    {
        $str = str_replace('_Model_', '', $this->model_cls);
        $model_short = '';
        for ($i = 0; $i < strlen($str); $i++) {
            $item = substr($str, $i, 1);
            if (preg_match('/^[A-Z]+$/', $item)) {
                $model_short .= strtolower($item);
            }
        }
        $model_short .= '_service';
        $this->model_short = $model_short;
    }

    /**
     * 设置变量keys
     * @author Killua Chen
     */
    public function setKeys()
    {
        $keys = '[';
        foreach ($this->structure as $item) {
            $keys .= "'{$item['key']}' => 1, ";
        }
        $keys = rtrim($keys, ' ,') . ", '_id' => 1]";
        $this->keys = $keys;
    }

    /**
     * 设置变量keys
     * @author Killua Chen
     */
    public function setKeys2()
    {
        $keys = '';
        foreach ($this->structure as $item) {
            $keys .= "'{$item['key']}', ";
        }
        $this->keys2 = rtrim($keys, ', ');
    }

    /**
     * 设置变量keys
     * @author Killua Chen
     */
    public function setKeysArray()
    {
        $keys = [];
        foreach ($this->structure as $item) {
            array_push($keys, $item['key']);
        }
        $this->keys_arr = $keys;
    }

    public function setKeysInt()
    {
        $keys = '';
        foreach ($this->structure as $item) {
            if ($item['type'] == '数字输入框') {
                $keys .= "'{$item['key']}' => \$d->{$item['key']}, \n";
            }
        }
        $this->keys_int = rtrim($keys, ", \n");
    }

    private function setKeysAttr()
    {
        $str = '[';
        foreach ($this->structure as $item) {
            $str .= "
                        '{$item['key']}' => '{$item['name']}',";
        }
        $this->attr_kv = rtrim($str, ',') . ']';
    }

    /**
     * 生成Model
     * @param $data
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @author Killua Chen
     */
    private function generateModel($data)
    {
        $date = date('y/m/d');
        $time = date('H:i');
        $unique = $this->getUniqueFun();
        $t1 = '';
        if ($this->delete) {
            $t1 = ",\n            '$this->delete' => ['\$ne' => true]";
        }
        $delete_str = $this->getDelDataStr();
        $pwd_str = $this->getPwdStr();
        $str = <<<STR
        
<?php

/**
 * Created by Killua Chen
 * User: killua
 * Date: $date
 * Time: $time
 */
class {$data['module']}_Model_{$data['model']} extends iWebsite_Plugin_Mongo
{

    protected \$name = '{$data['coll']}';
    protected \$dbName = '{$data['db']}';

    /**
     * 保存数据
     * @param \$data
     * @param string|MongoId|bool \$id
     * @return string 
     * @author Killua Chen
     */
    public function saveData(\$data, \$id = false)
    {{$pwd_str}
        if (\$id) {
            \$id = \$id instanceof MongoId ? \$id : myMongoId(\$id);
            \$rst = \$this->update(['_id' => myMongoId(\$id)], ['\$set' => \$data])['n'];
            if (!\$rst) \$id = false;
        } else {
            \$rst = \$this->insert(\$data);
            @\$id = myMongoId(\$rst['_id']);
        }
        return \$id;
    }

    $unique
    
    /**
     * 获取数据
     * @param \$condition \$id or \$query
     * @param array \$fields
     * @return mixed
     * @author Killua Chen
     */
    public function getInfo(\$condition, \$fields = [])
    {
        \$query = [];
        if (is_array(\$condition)) {
            \$query = array_merge(\$query, \$condition);
        } else {
            \$query['_id'] = \$condition instanceof MongoId ? \$condition : myMongoId(\$condition);
        }
        return \$this->findOne(\$query, \$fields);
    }
    
    /**
     * 是否存在
     * @param \$id
     * @return bool
     * @author Killua Chen
     */
    public function isExists(\$id)
    {
        \$id = \$id instanceof MongoId ? \$id : myMongoId(\$id);
        return !!\$this->count([
            '_id' => \$id$t1
        ]);
    }
    
    $delete_str
    
    
    /**
     * 原子修改数据
     * @param \$operation
     * @return mixed
     * @throws Exception
     * @author Killua Chen
     */
    public function modify(\$operation)
    {
        if (!isset(\$operation['query']['_id']) && !isset(\$operation['upsert'])) {
            \$info = \$this->getInfo(\$operation['query'], ['_id' => 1]);
             if (empty(\$info)) {
                return false;
             } else {
                \$operation['query']['_id'] = myMongoId(\$info['_id']);
             }
        }
        \$rst = \$this->findAndModify(\$operation);
        return \$rst;
    }
    
    
    /**
     * 上传文件
     * @author Killua Chen
     * @param \$data
     * @return bool|string
     */
    public function upload(\$data)
    {
        if (is_array(\$data)) {
            \$picture = \$this->uploadFile('file');
        } else {
            \$picture = \$this->uploadBytes(uniqid(), \$data);
        }
        \$rst = false;
        if (isset(\$picture['file'])) {
            \$rst = 'http://cloud.umaman.com/file/' . \$picture['file']['_id']['\$id'];
        }
        return \$rst;
    }
    
    
}
STR;
        return $this->putFile($data, $str, "{$data['model']}.php");
    }

    /**
     * 获取唯一字段函数
     * @return string
     * @author Killua Chen
     */
    private function getUniqueFun()
    {

        $str = <<<STR
        
    /**
     * 验证字段唯一,是否存在
     * @param \$field
     * @param \$val
     * @param \$id
     * @return bool
     * @author Killua Chen
     */
    public function validateUnique(\$field, \$val, \$id = false)
    {
        \$condition = ['is_delete' => ['\$ne' => true]];
        if (\$field == 'id') {
            \$condition['_id'] = myMongoId(\$val);
        }
        if (\$id) {
            \$condition['_id'] = ['\$ne' => myMongoId(\$id)];
            \$condition[\$field] = \$val;
        } else {
            \$condition[\$field] = \$val;
        }
        return !!\$this->count(\$condition);
    }
    
    
    

STR;
        return $str;
    }

    /**
     * 获取删除数据字符串
     * @return string
     * @author Killua Chen
     */
    public function getDelDataStr()
    {
        return <<<D1
            
    /**
     * 删除数据
     * @param \$id
     * @return bool 
     * @author Killua Chen
     */
   public function delData(\$id, \$is_real = false)
    {
        \$id = \$id instanceof MongoId ? \$id : myMongoId(\$id);
        if (\$is_real) {
            return \$this->remove(['_id' => \$id])['n'];
        } else {
            return \$this->update(['_id' => \$id], ['\$set' => ['{$this->delete}' => true]]);
        }
    }
D1;

    }

    /**
     * 获取密码字符串
     * @return string
     * @author Killua Chen
     */
    public function getPwdStr()
    {
        $data = '';
        foreach ($this->structure as $item) {
            if ($item['type'] == 'MD5密码输入字段') {
                $str = <<<STR
                
        if (\$data['{$item['key']}']) {
            \$data['{$item['key']}'] = md5(\$data['{$item['key']}']);
        } else {
            unset(\$data['password']);
        }
STR;
                $data .= $str;
            } elseif ($item['type'] == 'SHA1密码输入字段') {
                $str = <<<STR
                
        if (\$data['{$item['key']}']) {
            \$data['{$item['key']}'] = hash('sha1', \$data['{$item['key']}']);
        } else {
            unset(\$data['password']);
        }
STR;
                $data .= $str;

            }
        }
        if (!empty($data)) {
            $new_line = <<<STR

STR;
            $data = $new_line . $data;
        }
        return $data;
    }

    /**
     * 生成控制器
     * @param $data
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @author Killua Chen
     */
    public function generateController($data)
    {
        $date = date('y/m/d');
        $time = date('H:i');
        $edit_str = $this->getGetInfoStr();
        $store_str = $this->getStoreStr();
        $delete_str = $this->getDeleteStr();
        $index_str = $this->getIndexStr();
        $import_str = $this->getImportStr();
        $upload_str = $this->getUploadStr();
        $ctrl_class = "{$data['module']}_{$data['ctrl']}";
        $model_class = "{$data['module']}_Model_{$data['model']}";

        $str = <<<STR
<?php

/**
 * Created by Killua Chen
 * User: killua
 * Date: $date
 * Time: $time
 */
class $ctrl_class extends iWebsite_Controller_Action
{
 
    /** @var $model_class */
    private \$service;

    public function init()
    {
        \$this->disableLayout();
        \$this->getHelper('viewRenderer')->setNoRender(true);
        \$this->service = new $model_class();
    }


    $index_str
    
    $edit_str
    
    $store_str
    
    $delete_str
    
    $import_str
    
    $upload_str
}
STR;
        return $this->putFile($data, $str, "{$data['ctrl']}.php");
    }


    public function getUploadStr()
    {
        $str = <<<STR
    /**
     * 上传文件
     * @author Killua Chen
     */
    public function uploadFileAction()
    {
        try {
            \$rules = [
                'file' => 'required'
            ];
            \$v = new iValidator(\$_REQUEST, \$rules);
            if (!\$v->validate()) {
                abort(-1, \$v->msg());
            }
            \$data = \$v->data(false);
            \$rst = \$this->service->upload(\$data['file']);
            if (\$rst) {
                echo \$this->result('OK', ['url' => \$rst]);
                return false;
            }
            abort(-1, '文件上传失败');
        } catch (Exception \$e) {
            echo \$this->error(\$e->getCode(), \$e->getMessage());
            return false;
        }
    }
STR;
        return $str;

    }

    /**
     * 获取编辑字符串
     * @return string
     * @author Killua Chen
     */
    public function getGetInfoStr()
    {
        $str = <<<STR
        
    /**
     * 获取信息
     * @author Killua Chen
     */
    public function getInfoAction()
    {
        try {
            \$rules = [
                'id' => 'required|exists' 
            ];
            \$v = new iValidator(\$_REQUEST, \$rules, \$this->service);
            if (!\$v->validate()) {
                abort(-1, \$v->msg());
            }
            \$d = \$v->data();
            \$fields = $this->keys;
            \$data = \$this->service->getInfo(\$d->id);
            echo \$this->result('OK', \$data);
            return false;
        } catch (Exception \$e) {
            echo \$this->error(\$e->getCode(), \$e->getMessage());
            return false;
        }
    }
STR;
        return $str;
    }


    public function getImportStr()
    {
        $data = '';
        foreach ($this->structure as $i => $item) {
            $format = $i === 0 ? '' : '                    ';
            switch ($item['type']) {
                case '单行文字输入框':
                case '多行文本输入框':
                    $data .= "$format'{$item['key']}' => (string)\$item[$i]," . PHP_EOL;
                    break;
                case '数字输入框':
                    $data .= "$format'{$item['key']}' => floatval(\$item[$i])," . PHP_EOL;
                    break;
                case '是非选择框':
                    $data .= "$format'{$item['key']}' => iboolval(\$item[$i])," . PHP_EOL;
                    break;
                case '数组':
                case '内嵌文档':
                    $data .= "$format'{$item['key']}' => json_decode(\$item[$i])," . PHP_EOL;
                    break;
                case '日期控件':
                    $data .= "$format'{$item['key']}' => new MongoDate(\$item[$i])," . PHP_EOL;
                    break;
                case 'MD5密码输入字段':
                    $data .= "$format'{$item['key']}' => md5(\$item[$i])," . PHP_EOL;
                    break;
                case 'SHA1密码输入字段':
                    $data .= "$format'{$item['key']}' => sha1(\$item[$i])," . PHP_EOL;
                    break;
                default:
                    $data .= "$format'{$item['key']}' => (string)\$item[$i]," . PHP_EOL;
                    break;
            }
        }
        $count = count($this->keys_arr);
        $data = rtrim($data, ',' . PHP_EOL);
        $str = <<<STR
 /**
     * 导入数据
     * @author Killua Chen
     */
    public function importAction()
    {
        try {
            \$rules = [
                'file' => 'required_file',
            ];
            \$v = new iValidator(\$_REQUEST, \$rules);
            if (!\$v->validate()) {
                abort(-1, \$v->msg());
            }
            \$d = \$v->data();
            \$data = loadExcelData(\$d->file['tmp_name'], 2, $count);
            \$batch_dta = [];
            foreach (\$data as \$item) {
                \$batch_dta[] = [
                    $data
                ];
            }
            \$rst = \$this->service->batchInsert(\$batch_dta);
            echo \$this->result('OK', \$rst);
            return false;

        } catch (Exception \$e) {
            echo \$this->error(\$e->getCode(), \$e->getMessage());
            return false;

        }
    }
STR;
        return $str;

    }

    /**
     * 获取保存字符串
     * @return string
     * @author Killua Chen
     */
    public function getStoreStr()
    {
        $rules = $this->getValidateStr();
        $str = <<<STR
        
    /**
     * 保存数据
     * @author Killua Chen
     */
    public function storeAction()
    {
        try {
            \$rules = [
                'id' => 'exists',
                $rules
            ];
            \$v = new iValidator(\$_REQUEST, \$rules, \$this->service);
            if (!\$v->validate()) {
                abort(-1, \$v->msg());
            }
            \$data = \$v->data(false);
            \$rst = \$this->service->saveData(\$data, \$data['id']);
            if (\$rst) {
                echo \$this->result('OK',['id' => \$rst]);
                return false;
            } else {
                abort(-1, '保存失败');
            }
        } catch (Exception \$e) {
            echo \$this->error(\$e->getCode(), \$e->getMessage());
            return false;
        } 
    }
STR;
        return $str;
    }

    /**
     * 获取验证字符
     * @param string $type
     * @return string
     * @author Killua Chen
     */
    public function getValidateStr($type = 'store')
    {
        $rules = '';

        foreach ($this->structure as $i => $field) {
            $rule = '';
            if ($type == 'store') {
                if ($field['required'] == '√') {
                    $rule .= 'required|';
                }
                if ($field['unique'] == '√') {
                    $rule .= 'unique|';
                }
                if ($field['type'] == '数组') {
                    $rule .= 'array|';
                }
            }
            if ($field['filter'] == '是否IP地址' || strstr($field['name'], 'IP')) {
                $rule .= 'ip|';
            }
            if ($field['filter'] == '是否Email') {
                $rule .= 'email|';
            }
            if ($field['filter'] == '是否URL') {
                $rule .= 'url|';
            }
            if (strstr($field['filter'], '是非验证')) {
                $rule .= 'boolean|';
            }
            if (strstr($field['filter'], 'HTML转义')) {
                $rule .= 'html|';
            }
            if (strstr($field['name'], '身份证')) {
                $rule .= 'idCard|';
            }

            if (strstr($field['name'], '手机')) {
                $rule .= 'mobile|';
            } elseif (strstr($field['name'], '生日')) {
                $rule .= 'dateFormat:Ymd|';
            } elseif (strstr($field['name'], '日期')) {
                $rule .= 'dateFormat:Ymd|';
            } elseif (strstr($field['name'], '时间')) {
                $rule .= 'date';
            }

            if ($field['filter'] == '整数验证') {
                $rule .= 'integer|';
            } elseif ($field['filter'] == '浮点验证' || $field['type'] == '数字输入框') {
                $rule .= 'numeric|';
            }
            $rule = rtrim($rule, '|');
            if ($i == 0) {
                $rules .= "'{$field['key']}' => '$rule',";
            } else {
                $rules .= "
                '{$field['key']}' => '$rule',";
            }
        }
        if ($type == 'index') {
            $rules .= "
                'page_index' => 'required:1|integer',
                'page_size' => 'required:20|integer',
                'begin_time' => 'date',
                'end_time' => 'date',
                'begin_date' => 'dateFormat:Ymd',
                'end_date' => 'dateFormat:Ymd',";
        }
        return $rules;
    }

    /**
     * 获取删除字符串
     * @return string
     * @author Killua Chen
     */
    public function getDeleteStr()
    {
        $str = <<<STR
        
    /**
     * 删除
     * @author Killua Chen
     */
    public function delAction()
    {
        try {
            \$id = \$this->get('id');
            \$rules = [
                'id' => 'required|exists' 
            ];
            \$v = new iValidator(\$_REQUEST, \$rules, \$this->service);
            if (!\$v->validate()) {
                abort(-1, \$v->msg());
            }
            \$rst = \$this->service->delData(\$id)['n'];
            if (\$rst) {
                echo \$this->result('OK');
                return false;
            } else {
                abort(-1, '删除失败');
            }
         } catch (Exception \$e) {
            echo \$this->error(\$e->getCode(), \$e->getMessage());
            return false;
         }
    }
STR;
        return $str;
    }

    /**
     * 获取列表
     * @return string
     * @author Killua Chen
     */
    public function getIndexStr()
    {
        $t3 = <<<T3
        
            //按时间精确到秒查询
            if (\$d->begin_time) {
                \$query['begin_time'] = ['\$gte' => new MongoDate(strtotime(\$d->begin_time))];
            }
            if (\$d->end_time) {
                \$query['end_time'] = ['\$lte' => new MongoDate(strtotime(\$d->end_time))];
            }
            
            //按日期按天查询
            if (\$d->begin_date) {
                \$query['time']['\$gte'] = strtotime(\$d->begin_date);
            }
            if (\$d->end_date) {
                \$query['time']['\$lte'] = strtotime('+1 days', strtotime($\$d->end_date));
            }

T3;

        $rules = $this->getValidateStr('index');

        $str = <<<STR
/**
     * 首页
     * @author Killua Chen
     */
    public function indexAction()
    {
        try {
            \$rules = [
                $rules
            ];
            \$v = new iValidator(\$_REQUEST, \$rules, \$this->service);
            if (!\$v->validate()) {
                abort(-1, \$v->msg());
            }
            \$d = \$v->data();
            
            \$query = [
                'is_delete' => ['\$ne' => true],
                'begin_time' => ['\$lte' => new MongoDate()],
                'end_time' => ['\$gte' => new MongoDate()],
            ];
            \$this->likeQuery(\$query, [
                $this->keys_like]
            );
            \$this->equalQuery(\$query, [
                $this->keys_int]
            );
            
            $t3
            
            if (\$d->is_export) {
                \$d->page_size = MAX_VALUE;
                \$d->page_index = 1;
            }
            
            \$data = \$this->service->find(
                \$query, ['sort' => 1],
                (\$d->page_index - 1) * \$d->page_size, \$d->page_size
            );
            foreach (\$data['datas'] as &\$item) {
            }
            if (\$d->is_export) {
                arrayToCVSPlus('', [
                    'title' => $this->attr_kv,
                    'result' => \$data['datas']
                ]);
            }
            calcPager(\$data, \$d->page_size);
            echo \$this->result('OK', \$data);
            return false;
        } catch (Exception \$e) {
            echo \$this->error(\$e->getCode(), \$e->getMessage());
            return false;
        }
    }
    
    
    /**
     * 获取全部数据
     * @author Killua Chen
     */
    public function getAllAction()
    {
        try {
            \$query = [
                'is_delete' => ['\$ne' => true],
                'begin_time' => ['\$lte' => new MongoDate()],
                'end_time' => ['\$gte' => new MongoDate()],
            ];
            \$data = \$this->service->findAll(
                \$query, ['sort' => 1],
                $this->keys
            );
            foreach (\$data['datas'] as &\$item) {
            }
            echo \$this->result('OK', \$data);
            return false;
        } catch (Exception \$e) {
            echo \$this->error(\$e->getCode(), \$e->getMessage());
            return false;
        }
    }
STR;
        return $str;
    }


    public function setKeysLike()
    {
        $keys = '';
        foreach ($this->structure as $item) {
            if ($item['type'] != '数字输入框') {
                $keys .= "'{$item['key']}' => \$d->{$item['key']}, \n";
            }
        }
        $this->keys_like = rtrim($keys, ", \n");
    }

    /**
     * 获取get字符串
     * @param string $type
     * @return string
     * @author Killua Chen
     */
    public function getGetStr($type = 'store')
    {
        $t1 = "";
        foreach ($this->structure as $i => $item) {
            switch ($item['type']) {
                case '数字输入框':
                case '是非选择框':
                    $t1 .= ($i > 0 ? "\t\t\t" : "") . "\${$item['key']} = intval(\$this->get('{$item['key']}'));\n";
                    break;
                case '日期控件':
                    if ($type == 'store') {
                        $t1 .= ($i > 0 ? "\t\t\t" : "") . "\${$item['key']} = new MongoDate(strtotime(\$this->get('{$item['key']}')));\n";
                    } else {
                        $t1 .= ($i > 0 ? "\t\t\t" : "") . "\${$item['key']} = \$this->get('{$item['key']}');\n";
                    }
                    break;
                default:
                    $t1 .= ($i > 0 ? "\t\t\t" : "") . "\${$item['key']} = \$this->get('{$item['key']}');\n";
                    break;
            }
        }
        $t1 = rtrim($t1, "\n");
        return $t1;
    }

    /**
     * @param $data
     * @param $str
     * @author Killua Chen
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function putFile($data, $str, $filename)
    {
        $model_file = public_path("tmp/tmp.php");
        $path_dir = dirname($model_file);
        $is_exists = file_exists($path_dir);
        if (!$is_exists) {
            mkdir($path_dir);
        }
        file_put_contents($model_file, $str);
        return response()->download($model_file, $filename);
    }

    /**
     * 首字母大写
     * @param $word
     * @return mixed
     * @author Killua Chen
     */
    private function getFirstUpper($word)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $word)));
    }
}
