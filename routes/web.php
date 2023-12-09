<?php

use App\Helper\Helpers;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Models\Service;
use App\Models\Facility;
use App\Models\BasicTranslation;
use App\Models\Difficulty;
use App\Models\FieldType;
use App\Models\GolfCourseStyle;
use App\Models\Image;
use App\Models\RoomFieldType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return view('welcome');
});




Route::get('/davinci', function () {

    return view('davinci.index');
});

Route::get('/delete-logfiles', function () {

    $publicPath = public_path(); // Get the path to the public directory

    $filesToDelete = \File::glob($publicPath . '/*.log'); // Get an array of file paths ending with ".log"

    foreach ($filesToDelete as $file) {
        \File::delete($file); // Delete each file
    }

    echo "Done";
    return ;
});

Route::get('/davinci-custom-import', function () {
    return view('davinci.custom_import');
});

Route::post('import-davinci-packages', [ProductController::class, "manualImportDaVinciPackages"]);
Route::post('custom-import-packages', [ProductController::class, "manualCustomImport"]);

Route::post('/clean-davinci-packages', [ProductController::class, "manualCleanDaVinciPackages"]);
Route::delete('/davinci-delete-package' , [ProductController::class,"deleteDaVinciPackage"]);

Route::post('/get-import-info', [ProductController::class, "getImportInfo"]);



//========================================================================================
// =================================== Service ===========================================

Route::get('/services', function () {
    $services = Service::orderBy('type')->get();
    return view('services', compact('services'));
});

Route::post('save-service', function (Request $request) {

    $service  = Service::find($request->id);
    $service->type = $request->type;
    $service->active = isset($request->active) ? 1 : 0;
    $service->icon = $request->icon;
    $service->icon_name = $request->icon_name;
    $service->font_type = $request->font_type;
    $service->sorted = $request->sorted;
    $service->save();

    foreach ($request->translates as $key => $value) {
        $translation  = BasicTranslation::find($key);
        $translation->name = $value;
        $translation->save();
    }

    return ('<h1> Saved </h1>');
});

Route::post('new-service', function (Request $request) {
    $service  = new Service();
    $service->type = $request->type;
    $service->active = 0;
    $service->save();

    $service->translations()->create(['name' => 'title_en', 'language_id' => 1, 'locale' => 'en']);
    $service->translations()->create(['name' => 'title_de', 'language_id' => 2, 'locale' => 'de']);
    return ('<h1> Saved </h1>');
});



// =================================== Facility ===========================================

Route::get('facility', function () {
    $facilitys = Facility::orderBy('type')->get();
    return view('facility', compact('facilitys'));
});

Route::post('save-facility', function (Request $request) {

    $facility  = Facility::find($request->id);
    $facility->type = $request->type;
    $facility->status = isset($request->status) ? 1 : 0;
    $facility->icon = $request->icon;
    $facility->icon_name = $request->icon_name;
    $facility->font_type = $request->font_type;
    $facility->save();

    foreach ($request->translates as $key => $value) {
        $translation  = BasicTranslation::find($key);
        $translation->name = $value;
        $translation->save();
    }

    return ('<h1> Saved </h1>');
});

Route::post('new-facility', function (Request $request) {
    $facility  = new Facility();
    $facility->type = $request->type;
    $facility->status = 0;
    $facility->save();

    $facility->translations()->create(['name' => 'title_en', 'language_id' => 1, 'locale' => 'en']);
    $facility->translations()->create(['name' => 'title_de', 'language_id' => 2, 'locale' => 'de']);
    return ('<h1> Saved </h1>');
});




// =================================== Fields ===========================================

Route::get('field', function () {
    $items = FieldType::orderBy('category_id')->get();
    return view('field', compact('items'));
});

Route::post('save-field', function (Request $request) {

    $field  = FieldType::find($request->id);
    $field->category_id = $request->category_id;
    $field->status = isset($request->status) ? 1 : 0;

    $field->save();

    foreach ($request->translates as $key => $value) {
        $translation  = BasicTranslation::find($key);
        $translation->name = $value;
        $translation->save();
    }

    return ('<h1> Saved </h1>');
});

Route::post('new-field', function (Request $request) {
    $field  = new FieldType();
    $field->category_id = $request->category_id;
    $field->status = 0;
    $field->save();

    $field->translations()->create(['name' => 'title_en', 'language_id' => 1, 'locale' => 'en']);
    $field->translations()->create(['name' => 'title_de', 'language_id' => 2, 'locale' => 'de']);
    return ('<h1> Saved </h1>');
});



// =================================== Rome Fields ===========================================

Route::get('room-field', function () {
    $items = RoomFieldType::get();
    return view('room-field', compact('items'));
});

Route::post('save-room-field', function (Request $request) {

    $field  = RoomFieldType::find($request->id);
    // $field->category_id = $request->category_id;
    $field->status = isset($request->status) ? 1 : 0;

    $field->save();

    foreach ($request->translates as $key => $value) {
        $translation  = BasicTranslation::find($key);
        $translation->name = $value;
        $translation->save();
    }

    return ('<h1> Saved </h1>');
});

Route::post('new-room-field', function (Request $request) {
    $field  = new RoomFieldType();
    // $field->category_id = $request->category_id;
    $field->status = 0;
    $field->save();

    $field->translations()->create(['name' => 'title_en', 'language_id' => 1, 'locale' => 'en']);
    $field->translations()->create(['name' => 'title_de', 'language_id' => 2, 'locale' => 'de']);
    return ('<h1> Saved </h1>');
});




// =================================== Difficulty ===========================================


Route::get('difficulty', function () {
    $items = Difficulty::get();
    return view('difficulty', compact('items'));
});

Route::post('save-difficulty', function (Request $request) {

    $item  = Difficulty::find($request->id);
    $item->status = isset($request->status) ? 1 : 0;

    $item->save();

    foreach ($request->translates as $key => $value) {
        $translation  = BasicTranslation::find($key);
        $translation->name = $value;
        $translation->save();
    }

    return ('<h1> Saved </h1>');
});

Route::post('new-difficulty', function (Request $request) {
    $item  = new Difficulty();
    $item->status = 0;
    $item->save();

    $item->translations()->create(['name' => 'title_en', 'language_id' => 1, 'locale' => 'en']);
    $item->translations()->create(['name' => 'title_de', 'language_id' => 2, 'locale' => 'de']);
    return ('<h1> Saved </h1>');
});


// =================================== golf Style ===========================================


Route::get('golf-style', function () {
    $items = GolfCourseStyle::get();
    return view('golf-style', compact('items'));
});

Route::post('save-golf-style', function (Request $request) {

    $item  = GolfCourseStyle::find($request->id);
    $item->status = isset($request->status) ? 1 : 0;

    $item->save();

    foreach ($request->translates as $key => $value) {
        $translation  = BasicTranslation::find($key);
        $translation->name = $value;
        $translation->save();
    }

    return ('<h1> Saved </h1>');
});

Route::post('new-golf-style', function (Request $request) {
    $item  = new GolfCourseStyle();
    $item->status = 0;
    $item->save();

    $item->translations()->create(['name' => 'title_en', 'language_id' => 1, 'locale' => 'en']);
    $item->translations()->create(['name' => 'title_de', 'language_id' => 2, 'locale' => 'de']);
    return ('<h1> Saved </h1>');
});


Route::get('/pulk_publish', function () {
    return view('publish');
});
Route::post('/pulk_publish', function () {
    return Helpers::pulk_publish();
});

Route::get('show-log', function (Request $request) {
    return view('log');
});

Route::get('get-log', function (Request $request) {
   

    $log = file(storage_path('logs/' . $request->filename . '.log'));

    return view('log', compact('log'));
});

Route::get('wright-files-is-log', function (Request $request) {
    if (isset($request->the_path)) {
        $publicPath = public_path($request->the_path);
    } else {
        $publicPath = public_path();
    }

    // Get the list of files and directories in the public directory
    $contents = scandir($publicPath);

    // Remove '.' and '..' entries from the list
    $contents = array_diff($contents, ['.', '..']);

    // Loop through the contents and perform actions as needed
    foreach ($contents as $item) {
        // Process each file or directory in the public directory
        $path = $publicPath . '/' . $item;

        if (is_file($path)) {
            // It's a file
            \Log::info("$item\n");
        } elseif (is_dir($path)) {
            // It's a directory
            \Log::info("Directory: $item\n");
        }
    }

    $log = file(storage_path('logs/' . $request->filename . '.log'));

    return view('log', compact('log'));
});



Route::get('clear-log', function (Request $request) {

    \File::put(storage_path('logs/' . $request->filename . '.log'), '');

    $log = file(storage_path('logs/' . $request->filename . '.log'));

    return view('log', compact('log'));
});

Route::get('reset-rank', function (Request $request) {

    // Image::select('id','imageable_id','imageable_type')->groupBy('imageable_id')->groupBy('imageable_type')->get();
    $imgs =  \DB::select("select count(id),imageable_id,imageable_type from images group by imageable_id,imageable_type");

    foreach ($imgs as $img) {

        $item_imgs = Image::where('imageable_id', $img->imageable_id)->where('imageable_type', $img->imageable_type)->get();
        $x = 1;
        foreach ($item_imgs as $item_img) {
            $item_img->update(['rank' => $x]);
            $x++;
        }
    }
});



Route::get('api/docs', function () {
    return view('vendors.swaggar.index');
});
