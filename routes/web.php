<?php
use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::prefix('admin')->group(function () {
    Route::prefix('questions')->name('questions.')->group(function () {
        Route::get('/', [QuestionController::class, 'index'])->name('index');
        Route::get('create', [QuestionController::class, 'create'])->name('create');
        Route::post('store', [QuestionController::class, 'store'])->name('store');
        Route::get('delete/{id}', [QuestionController::class, 'delete'])->name('delete');
        Route::get('edit/{id}', [QuestionController::class, 'edit'])->name('edit');
        Route::post('update/{id}', [QuestionController::class, 'update'])->name('update');
    });
    Route::post('ckeditor/upload', [QuestionController::class, 'upload'])->name('admin.ckeditor.image-upload');
});
