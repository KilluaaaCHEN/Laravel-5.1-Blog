<?php

namespace App\Http\Controllers\Backend;

use App\Models\Dict;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;

class DictController extends Controller
{
    /**
     * Display a listing of the resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dict = Dict::paginate(50);
        return view('backend.dict.index', [
            'items' => $dict
        ]);
    }

    /**
     * Show the form for creating a new resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new Dict();
        return view('backend.dict.edit', [
            'model' => $model,
            'title' => '添加字典'
        ]);
    }


    /**
     * Store a newly created resources in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data,
            [
                'key' => 'required',
                'val' => 'required',
            ],
            [
                'key.required' => '键不能为空',
                'val.required' => '值不能为空',
            ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($data);
        }
        dd(111,$validator->fails());
        $model = new Dict();
        if ($data['id']) {
            $model = $model->find($data['id']);
        }
        $model->fill($data);
        if ($model->save()) {
            \Cache::flush();
            self::flashState(true, '保存成功', "成功保存\"$model->key\"");
        } else {
            self::flashState(false, '保存失败', '请联系管理员');
        }
        return redirect()->route('dict.index');
    }

    /**
     * Display the specified resources.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resources.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = Dict::findOrFail($id);
        return view('backend.dict.edit', [
            'model' => $model,
            'title' => "修改-$model->key "
        ]);
    }

    /**
     * Update the specified resources in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $post = Dict::findOrFail($id);
        $post->delete();
        self::flashState(true, '删除成功', "已成功删除\"$post->name\"");
        return redirect()->back();
    }
}
