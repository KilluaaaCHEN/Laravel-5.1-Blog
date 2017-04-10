<?php

namespace App\Http\Controllers\Backend;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $posts = Post::orderBy('post_id', 'desc')->paginate(15);
        return view('backend.post.index', [
            'posts' => $posts
        ]);
    }


    /**
     * Show the form for creating a new resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new Post();
        return view('backend.post.edit', [
            'model' => $model,
            'title' => '添加文章'
        ]);
    }

    /**
     * Show the form for editing the specified resources.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = Post::findOrFail($id);
        return view('backend.post.edit', [
            'model' => $model,
            'title' => "修改-$model->title"
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
        $validator = Validator::make($data, [
            'title' => 'required|max:100',
            'tags' => 'required|max:100',
            'desc' => 'required|max:255',
            'content' => 'required',
        ],
            [
                'title.required' => '标题不能为空',
                'tags.required' => '标签不能为空',
                'desc.required' => '描述不能为空',
                'content.required' => '内容不能为空',
                'title.max' => '标题长度不能大于100个字符',
                'tags.max' => '标签长度不能大于100个字符',
                'desc.max' => '描述长度不能大于255个字符',
            ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($data);
        }
        $model = new Post();
        if ($data['post_id']) {
            $model = $model->find($data['post_id']);
        }
        $data['tags'] = rtrim($data['tags'], ',') . ',';
        $tags = explode(',', rtrim($data['tags'], ','));
        $data['tag_count'] = count($tags);
        $model->fill($data);
        if ($model->save()) {
            Tag::updateTag($model->post_id, $tags);
            \Cache::flush();
            self::flashState(true, '保存成功', "成功保存\"$model->title\"");
        } else {
            self::flashState(false, '保存失败', '请联系管理员');
        }
        return redirect()->route('post.index');
    }


    /**
     * Display the specified resources.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        var_dump(Post::findOrFail($id));
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        self::flashState(true, '删除成功', "已成功逻辑删除\"$post->title\",可以恢复哦~");
        return redirect()->back();
    }
}
