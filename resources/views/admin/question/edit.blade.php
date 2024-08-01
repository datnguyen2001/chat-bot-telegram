@extends('admin.layout.index')
@section('main')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Cập nhật Câu hỏi</h1>
        </div><!-- End Page Title -->
        <section class="section dashboard">
            <div class="bg-white p-4">
                <form action="{{route('questions.update',$question->id)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row mt-3">
                        <div class="col-3">Câu Hỏi: </div>
                        <div class="col-8">
                            <div class="form-group">
                                <input type="text" class="form-control" value="{{$question->question ?? ''}}" name="question" required/>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3">Câu Trả Lời: </div>
                        <div class="col-8">
                            <div class="form-group">
                                <textarea name="answer" class="ckeditor" maxlength="3000">{!! $question->answer ?? '' !!}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-3"></div>
                        <div class="col-8">
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                            <a href="{{route('questions.index')}}" class="btn btn-danger">Hủy</a>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </main><!-- End #main -->
@endsection
@section('script')
    <script src="//cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
    <script type="text/javascript">
        CKEDITOR.replace('content', {
            filebrowserUploadUrl: "{{route('admin.ckeditor.image-upload', ['_token' => csrf_token() ])}}",
            filebrowserUploadMethod: 'form',
            height: '500px'
        });
    </script>
@endsection
