<?php

namespace App\Http\Controllers\Backend;

use App\Models\Links;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;

class LinksController extends Controller
{
    /**
     * Display a listing of the resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $links = Links::orderBy('sort_num')->paginate(10);
        return view('backend.links.index', [
            'items' => $links
        ]);
    }

    /**
     * Show the form for creating a new resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new Links();
        $model->sort_num = 99;
        return view('backend.links.edit', [
            'model' => $model,
            'title' => '添加友链'
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
                'name' => 'required|max:50',
                'url' => 'required|max:255|url',
                'sort_num' => 'max:6',
            ],
            [
                'name.required' => '标题不能为空',
                'name.max' => '名称长度不能大于50个字符',
                'url.required' => '地址不能为空',
                'url.url' => '地址格式不正确',
                'url.max' => 'Url长度不能大于100个字符',
                'sort_num.max' => '排序长度不能大于6个字符',
            ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($data);
        }
        $model = new Links();
        if ($data['id']) {
            $model = $model->find($data['id']);
        }
        $model->fill($data);
        if ($model->save()) {
            self::flashState(true, '保存成功', "成功保存\"$model->name\"");
        } else {
            self::flashState(false, '保存失败', '请联系管理员');
        }
        return redirect()->route('admin.links.index');
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
        $model = Links::findOrFail($id);
        return view('backend.links.edit', [
            'model' => $model,
            'title' => "修改-$model->name "
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
        $post = Links::findOrFail($id);
        $post->delete();
        self::flashState(true, '删除成功', "已成功删除\"$post->name\"");
        return redirect()->back();
    }
}
