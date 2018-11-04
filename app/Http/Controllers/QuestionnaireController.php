<?php
/**
 * Created by PhpStorm.
 * User: Killua Chen
 * Date: 18/11/4
 * Time: 16:04
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class QuestionnaireController extends Controller
{

    public function index()
    {
        return view('questionnaire.index');
    }

    public function save(Request $request)
    {
//        dd($request->all());
        return redirect()->to('/questionnaire/result');
    }

    public function result()
    {
        return '<h1>问卷调查完成</h1>';
    }
}