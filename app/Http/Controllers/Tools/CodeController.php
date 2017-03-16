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
        $this->setKeysExInt();
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
                $keys .= "'{$item['key']}', ";
            }
        }
        $this->keys_int = rtrim($keys, ', ');
    }

    public function setKeysExInt()
    {
        $keys = '';
        foreach ($this->structure as $item) {
            if ($item['type'] != '数字输入框') {
                $keys .= "'{$item['key']}', ";
            }
        }
        $this->keys_ex_int = rtrim($keys, ', ');
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
     * @param bool \$id
     * @return bool
     * @author Killua Chen
     */
    public function saveData(\$data, \$id = false)
    {
        $pwd_str
        if (\$id) {
            \$rst = \$this->update(['_id' => myMongoId(\$id)], ['\$set' => \$data])['n'];
        } else {
            \$rst = \$this->insert(\$data);
        }
        return \$rst ? true : false;
    }

    $unique
    
    /**
     * 获取数据
     * @param \$id
     * @param \$fields
     * @return mixed
     * @author Killua Chen
     */
    public function getInfo(\$id, \$fields = [])
    {
        return \$this->findOne([
            '_id' => \$id instanceof MongoId ? \$id : myMongoId(\$id)$t1
        ], \$fields);
    }
    
    $delete_str
}
STR;
        $model_file = public_path("tmp/temp.php");
        file_put_contents($model_file, $str);
        return response()->download($model_file, "{$data['model']}.php");
    }

    /**
     * 获取唯一字段函数
     * @return string
     * @author Killua Chen
     */
    private function getUniqueFun()
    {
        $t1 = '';
        $t2 = '';
        $t3 = '';
        if ($this->delete) {
            $t1 = "\$$this->delete = ['\$ne' => true];";
            $t2 = ", '$this->delete' => \$$this->delete";
            $t3 = ", '$this->delete'";
        }
        $str = <<<STR
        
    /**
     * 验证字段唯一性
     * @param \$field
     * @param \$val
     * @param \$id
     * @return bool
     * @author Killua Chen
     */
    public function validateUnique(\$field, \$val, \$id)
    {
        $t1
        if (\$id) {
            return !!\$this->count([\$field => \$val, '_id' => ['\$ne' => myMongoId(\$id)]$t2]);
        } else {
            return !!\$this->count([\$field => \$val$t3]);
        }
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
        if ($this->delete) {
            return <<<D1
            
    /**
     * 删除数据
     * @param \$id
     * @return bool 
     * @author Killua Chen
     */
    public function delData(\$id)
    {
        return \$this->update(['_id' => myMongoId(\$id)], ['\$set' => ['$this->delete' => true]]);
    }
D1;
        } else {
            return <<<D2

    /**
     * 删除数据
     * @param \$id
     * @return bool 
     * @author Killua Chen
     */
    public function delData(\$id)
    {
        return \$this->remove(['_id' => myMongoId(\$id)])['n'];
    }
D2;
        }
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
        $edit_str = $this->getEditStr();
        $store_str = $this->getStoreStr();
        $delete_str = $this->getDeleteStr();
        $index_str = $this->getIndexStr();
        $str = <<<STR
<?php

/**
 * Created by Killua Chen
 * User: killua
 * Date: $date
 * Time: $time
 */
class {$data['module']}_{$data['ctrl']} extends iWebsite_Controller_Action
{
    $index_str
    
    $edit_str
    
    $store_str
    
    $delete_str
}
STR;
        $model_file = public_path("tmp/temp.php");
        file_put_contents($model_file, $str);
        return response()->download($model_file, "{$data['ctrl']}.php");
    }

    /**
     * 获取编辑字符串
     * @return string
     * @author Killua Chen
     */
    public function getEditStr()
    {
        $str = <<<STR
        
    /**
     * 获取信息
     * @author Killua Chen
     */
    public function getInfoAction()
    {
        try {
            \$id = \$this->get('id');
            
            \$rules = [
                'id' => 'required' 
            ];
            \$$this->model_short = new $this->model_cls();
            \$v = new iValidator(\$_REQUEST, \$rules, \$$this->model_short);
            if (!\$v->validate()) {
                \$this->error(-1,\$v->msg());
            }
            \$fields = $this->keys;
            \$data = \$$this->model_short->getInfo(\$id, \$fields);
            if (!\$data) {
                abort(404,'数据不存在');
            }
            \$this->result('',\$data);
        } catch (Exception \$e) {
            \$this->error(\$e->getCode(), \$e->getMessage());
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
                $rules
            ];
            \$$this->model_short = new $this->model_cls();
            \$v = new iValidator(\$_REQUEST, \$rules, \$$this->model_short);
            if (!\$v->validate()) {
                \$this->error(-1,\$v->msg());
            }
            \$data = \$v->data();
            \$id = \$this->get('id');
            \$rst = \$$this->model_short->saveData(\$data, \$id);
            if (\$rst) {
                \$this->result('OK');
            } else {
                abort(-1, '保存失败');
            }
        } catch (Exception \$e) {
            \$this->error(\$e->getCode(), \$e->getMessage());
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
                'id' => 'required' 
            ];
            \$$this->model_short = new $this->model_cls();
            \$v = new iValidator(\$_REQUEST, \$rules, \$$this->model_short);
            if (!\$v->validate()) {
                \$this->error(-1,\$v->msg());
            }
            \$rst = \$$this->model_short->delData(\$id)['n'];
            if (\$rst) {
                \$this->result();
            } else {
                abort(-1, '删除失败');
            }
         } catch (Exception \$e) {
            \$this->error(\$e->getCode(), \$e->getMessage());
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
        $t1 = $this->getGetStr('index');
        $slh = $this->getSlh();
        $t3 = <<<T3
        
            //按时间精确到秒查询
            \$begin_time = \$this->get('begin_time');
            \$end_time = \$this->get('end_time');
            if (\$begin_time) {
                \$query['begin_time'] = ['\$gte' => new MongoDate(strtotime(\$begin_time))];
            }
            if (\$end_time) {
                \$query['end_time'] = ['\$lte' => new MongoDate(strtotime(\$end_time))];
            }
            
            //按日期按天查询
            \$begin_date = \$this->get('begin_date');
            \$end_date = \$this->get('end_date');
            if (\$begin_date) {
                \$query['time']['\$gte'] = strtotime(\$begin_date);
            }
            if (\$end_date) {
                \$query['time']['\$lte'] = strtotime('+1 days', strtotime($\$end_date));
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
            $slh
            \$v = new iValidator(\$_REQUEST, \$rules, \$$this->model_short);
            if (!\$v->validate()) {
                \$this->error(-1,\$v->msg());
            }
            
            \$page_size = \$this->get('page_size', 10);
            \$page_index = \$this->get('page_index', 1);
            $t1 
            \$query = ['is_delete' => ['\$ne'=>true]];
            \$this->likeQuery(\$query, compact($this->keys_ex_int));
            \$this->equalQuery(\$query, compact($this->keys_int));
            
            $t3
            
            \$is_export = \$this->get('is_export');
            if (\$is_export) {
                \$page_size = MAX_VALUE;
                \$page_index = 1;
            }
            
            \$data = \$$this->model_short->find(
                \$query, ['__MODIFY_TIME__' => -1],
                (\$page_index - 1) * \$page_size, \$page_size,
                $this->keys
            );
            foreach (\$data['datas'] as &\$item) {
            }
            if (\$is_export) {
                arrayToCVS2('', [
                    'title' => $this->attr_kv,
                    'result' => \$data['datas']
                ]);
            }
            \$page_total = floor(\$data['total'] / \$page_size);
            if (\$data['total'] % \$page_size != 0) {
                \$page_total++;
            }
            \$data['page_total'] = \$page_total;
            \$this->result('', \$data);
        } catch (Exception \$e) {
            \$this->error(\$e->getCode(), \$e->getMessage());
        }
    }
STR;
        return $str;
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
     * 获取实例化
     * @return string
     * @author Killua Chen
     */
    private function getSlh()
    {
        return "\$$this->model_short = new $this->model_cls();";
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
