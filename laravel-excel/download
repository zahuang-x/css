<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class fileController extends Controller
{
    // 一覧表示処理
    public function index(Request $request)
    {

        $data = DB::table('users')->get(); // データ登録対象のテーブルからデータを取得する
        $count = $request->input('count'); // 何件登録したか結果を返す

        return view('index', ['data' => $data, 'cnt' => $count]);
    }

    // CSVアップロード〜DBインポート処理
    public function upload(Request $request)
    {
        // アップロードされたCSVファイルを受け取り保存する
        $file = $request->file('csvdata'); // inputのnameはcsvdataとする
        // CSVファイルが存在するかの確認
        if ($file) {
            //拡張子がCSVであるかの確認
            if ($file->getClientOriginalExtension() !== "csv") {
                throw new Exception('不適切な拡張子です。');
            }
            //ファイルの保存
            $orgName = date('YmdHis') . "_" . $file->getClientOriginalName();
            $spath = storage_path('app\\');
            $path = $spath . $request->file('csvdata')->storeAs('', $orgName);
        } else {
            throw new Exception('CSVファイルの取得に失敗しました。');
        }
        // CSVファイル（エクセルファイルも可）を読み込む
        //$result = (new FastExcel)->importSheets($path); //エクセルファイルをアップロードする時はこちら
        $result = (new FastExcel)->configureCsv(',')->importSheets($path); // カンマ区切りのCSVファイル時

        // DB登録処理
        $count = 0; // 登録件数確認用
        foreach ($result as $row) {
            foreach ($row as $item) {
                // ここでCSV内データとテーブルのカラムを紐付ける（左側カラム名、右側CSV１行目の項目名）
                $param = [
                    'name' => $item["name"],
                    'email' => $item["email"],
                    'password' => $item["password"],
                ];
                // 次にDBにinsertする（更新フラグなどに対応するため１行ずつinsertする）
                DB::table('users')->insert($param);
                $count++;
            }
        }
        return redirect(route('csv', ['count' => $count]));
    }

    public function download()
    {
        $spath = storage_path('app/');
        $file_name = $spath.'templates/users.xlsx';
        // dd($file_name);
        //フォーマットを読み込む
        $spreadsheet = IOFactory::load($file_name);
        //データを呼び出す準備
        $worksheet = $spreadsheet->getActiveSheet();
        // 対象テーブルからデータを取得
        $users = User::all();

        // フォーマットのヘッダを避けて、2行目からテーブルデータを書き出す
        $i = 2;
        //ループ開始、対象テーブルデータを指定したセルにエクスポートする
        foreach ($users as $user) {
        $worksheet->setCellValue('A' . $i, $user->id);
        $worksheet->setCellValue('B' . $i, $user->name);
        $worksheet->setCellValue('C' . $i, $user->email);
        $worksheet->setCellValue('D' . $i, $user->created_at);
        $i++;
        }

        // $writer = new Writer($spreadSheet);
        // $outputPath = 'output.xlsx';
        // $writer->save( $outputPath );

        // Excelファイルをブラウザからダウンロードする
        $fileName = 'users' . date('Y_m_d') . '.xlsx';// 命名規則
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;'); // ヘッダ設定
        header("Content-Disposition: attachment; filename=\"{$fileName}\""); header('Cache-Control: max-age=0');
        //データをフォーマットに書き込み、ダウンロード実行
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
}
