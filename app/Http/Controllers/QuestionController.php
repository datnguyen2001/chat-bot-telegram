<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $listData = Question::orderBy('created_at', 'desc')->paginate(10);
        $page_menu = 'Question';
        $page_sub = null;
        return view('admin.question.index', compact('listData', 'page_sub', 'page_menu'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.question.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required',
        ]);

        $question = new Question();
        $question->question = $request->input('question');
        $question->answer = $request->input('answer');

        $question->save();

        return redirect()->route('questions.index')->with('success', 'Tạo câu hỏi thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $question = Question::find($id);
        return view('admin.question.edit', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required',
        ]);

        $question = Question::findOrFail($id);

        $question->question = $request->input('question');
        $question->answer = $request->input('answer');
        $question->save();

        return redirect()->route('questions.index')->with('success', 'Cập nhật câu hỏi thành công');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        $question = Question::find($id);

        $question->delete();

        return redirect()->route('questions.index')->with(['success'=>"Xóa dữ liệu thành công"]);
    }

    public function upload(Request $request)
    {
        if($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName.'_'.time().'.'.$extension;
            $request->file('upload')->move(public_path('userfiles'), $fileName);
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('userfiles/'.$fileName);
            $msg = 'Image successfully uploaded';
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }
}
