<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Handlers\ImageUploadHandler;

class FileUploadController extends Controller
{
    public function saveImg(Request $request, ImageUploadHandler $uploadHandler)
    {
        $folder = $request->folder; // 文件夹
        $prefix = $request->prefix; // 图片前缀
        $res = $uploadHandler->save($request->file('img'), $folder, $prefix);

        if ($res['path']) {
            return $this->success([
                'message' => '上传成功',
                'imgPath' => $res['path']
            ]);
        } else {
            return $this->errorResponse(500, [
                'error' => '上传失败'
            ]);
        }
    }

    public function save(Request $request)
    {
        if ($request->chunks) {  //分片上传
            return $this->chunk($request);
        } else {
            $dir = public_path() . '/uploads/android_version/' . date('Ym', time()) . '/' . date('d', time());
            $file = $request->file;
            $file->move($dir, $request->name);
            return response()->json([
                'status' => 1,
                'data' => '上传完成',
                'address' => config('app.url') . '/uploads/android_version/' . date('Ym', time()) . '/' . date('d', time()) . '/' . $request->name
            ]);
        }
    }

    /*
     * 分片上传
     * */
    public function chunk(Request $request)
    {
        // 文件临时目录
        $tmp_file_path = storage_path('app/tmp/' . md5($request->name));
        //  临时文件名
        $tmp_name = '1000000' . $request->chunk . '.tmp';

        $file = $request->file;
        $file->move($tmp_file_path, $tmp_name);

        $files = Storage::disk('local')->files('tmp/' . md5($request->name));
        // 分片上传完成后开始处理临时目录内的文件
        if (count($files) == $request->chunks) {
            sort($files);

            // 目录不存在则创建目录
            $dir = public_path() . '/uploads/android_version/' . date('Ym', time()) . '/' . date('d', time());
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }

            $fp = fopen($dir . '/' . $request->name, "ab");
            foreach ($files as $file) {
                //return $file;
                $tempFile = storage_path('app/' . $file);
                $size = filesize($tempFile);
                $handle = fopen($tempFile, "rb");
                fwrite($fp, fread($handle, $size));
                fclose($handle);
            }
            fclose($fp);
            // 删除临时文件夹
            Storage::deleteDirectory($tmp_file_path);
            return response()->json([
                'status' => 1,
                'data' => '上传完成',
                'address' => config('app.url') . '/uploads/android_version/' . date('Ym', time()) . '/' . date('d', time()) . '/' . $request->name
            ]);
        }

        return response()->json(['status' => 2, 'data' => '上传中']);
    }
}