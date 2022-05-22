<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\FileUpload;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class fileController extends Controller
{
    // 一覧表示処理
    public function index()
    {
        return view('index');
    }

    // CSVアップロード〜DBインポート処理
    // public function upload(Request $request)
    // {
    //     // アップロードされたCSVファイルを受け取り保存する
    //     $file = $request->file('csvdata'); // inputのnameはcsvdataとする
    //     // CSVファイルが存在するかの確認
    //     if ($file) {
    //         //拡張子がCSVであるかの確認
    //         if ($file->getClientOriginalExtension() !== "csv") {
    //             throw new Exception('不適切な拡張子です。');
    //         }
    //         //ファイルの保存
    //         $orgName = date('YmdHis') . "_" . $file->getClientOriginalName();
    //         $spath = storage_path('app\\');
    //         $path = $spath . $request->file('csvdata')->storeAs('', $orgName);
    //     } else {
    //         throw new Exception('CSVファイルの取得に失敗しました。');
    //     }
    //     // CSVファイル（エクセルファイルも可）を読み込む
    //     //$result = (new FastExcel)->importSheets($path); //エクセルファイルをアップロードする時はこちら
    //     $result = (new FastExcel)->configureCsv(',')->importSheets($path); // カンマ区切りのCSVファイル時

    //     // DB登録処理
    //     $count = 0; // 登録件数確認用
    //     foreach ($result as $row) {
    //         foreach ($row as $item) {
    //             // ここでCSV内データとテーブルのカラムを紐付ける（左側カラム名、右側CSV１行目の項目名）
    //             $param = [
    //                 'name' => $item["name"],
    //                 'email' => $item["email"],
    //                 'password' => $item["password"],
    //             ];
    //             // 次にDBにinsertする（更新フラグなどに対応するため１行ずつinsertする）
    //             DB::table('users')->insert($param);
    //             $count++;
    //         }
    //     }
    //     return redirect(route('csv', ['count' => $count]));
    // }


    //アップロード処理
    public function upload(Request $request)
    {
        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $file->move(public_path('files'),$fileName);

        $fileUpload = new FileUpload();
        $fileUpload->filename = $fileName;
        $fileUpload->save();
        return response()->json(['success'=>$fileName.'をアップロードしたました。']);
    }

    //削除処理
    public function destroy(Request $request)
    {
        $filename =  $request->get('filename');
        FileUpload::where('filename',$filename)->delete();
        $path=public_path().'/files/'.$filename;
        dump($path);
        if (file_exists($path)) {
            unlink($path);
        }
        return $filename;
    }
}
