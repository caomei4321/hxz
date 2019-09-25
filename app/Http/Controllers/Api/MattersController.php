<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\MatterCollection;
use App\Http\Resources\Api\MatterResource;
use App\Http\Resources\Api\UserResource;
use App\Models\Matter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Situation;

class MattersController extends Controller
{
    public function userHasMatters()
    {
        /*return $this->user()->whereHas('situation', function ($query) {
            $query->where('user_has_matters.status','0');
        })->get();*/
        return new MatterCollection($this->user()->situation()->where('user_has_matters.status',0)->orderBy('created_at', 'desc')->get());
    }

    public function userCompleteMatters()
    {
        /*return $this->user()->whereHas('situation', function ($query) {
            $query->where('user_has_matters.status','1');
        })->get();*/
        //return $this->user()->patrolMatters()->where('status', 1)->get();
        $matters = $this->user()->situation()->where('user_has_matters.status',1)->get(['title', 'content', 'matters.created_at','user_has_matters.see_image'])->toArray();

        $patrolMatters = $this->user()->patrolMatters()->where('status', 1)->get()->toArray();

        foreach ($patrolMatters as $patrolMatter) {
            $data = [
                'title' => $patrolMatter['title'],
                'content' => $patrolMatter['content'],
                'created_at' => $patrolMatter['created_at'],
                'pivot' => [
                    'see_image' => $patrolMatter['image'],
                ]
            ];
            $matters[] = $data;
        }

        $result = [
            'data' => $matters
        ];

        return $result;

        //return $matters;
        //return new MatterCollection($this->user()->situation()->where('user_has_matters.status',1)->get());
    }

    public function userMatters()
    {
        /*return $this->user()->whereHas('situation', function ($query) {
            $query->where('user_has_matters.status','1');
        })->get();*/
        return new MatterCollection($this->user()->situation()->get());
    }

    public function matter(Request $request)
    {
        $matter = Matter::find($request->id);

        return new MatterResource($matter);
    }


    /*
     * 巡查发现的问题处理
     * */
    public function findMatterAndEnd(Request $request,Matter $matter)
    {
        $imgdata = $request->img;
        //$base64_str = substr($imgdata, strpos($imgdata, ",") + 1);
        $image = base64_decode($imgdata);

        $imgname = 'mt' . '_' . time() . '_' . str_random(10) . '.jpg';
        Storage::disk('public')->put($imgname, $image);
        $imagePath = '/storage/' . $imgname;

        $data = $request->only(['title', 'content', 'latitude', 'longitude', 'suggest']);

        //$request->result; 0表示无法处理
        $data['image'] = $imagePath;
        $data['status'] = 1;
        $data['patrol_id'] = $request->id ? $request->id : null;

        $this->user()->patrolMatters()->create($data);

        return $this->success('提交成功');
    }


    /*
     * 12345导入的问题处理
     * */
    public function endImportMatter(Request $request, Situation $situation)
    {
        $situation = $situation->where('matter_id', $request->id)->first();

        $imgdata = $request->img;
        //$base64_str = substr($imgdata, strpos($imgdata, ",") + 1);
        $image = base64_decode($imgdata);

        $imgname = 'mt' . '_' . time() . '_' . str_random(10) . '.jpg';
        Storage::disk('public')->put($imgname, $image);
        $imagePath = '/storage/' . $imgname;

        if ($request->result === 1) {  // result 1表示处理完成，0表示无权处理
            $status = 1;
        } else {
            $status = 2;
        }

        $situation->update([
            'see_image' => $imagePath,
            'information' => $request->suggest,
            'status' => $status
        ]);

        Matter::find($request->id)->update([
            'status' => 1
        ]);

        return $this->success('提交成功');
    }
}
