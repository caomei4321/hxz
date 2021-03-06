<?php

namespace App\Jobs;

use App\Models\User;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SendMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;
    protected $templateId;
    protected $id;
    protected $data;
    protected $page;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user = '',$templateId = '', $page = '', $data = [])
    {

        $this->userId = $user;
        $this->templateId = $templateId;
        $this->page = $page;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $client = new Client();

            $openId = User::find($this->userId)->open_id;

            $getAccessTokenUri = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WECHAT_MINI_PROGRAM_APPID').'&secret='.env('WECHAT_MINI_PROGRAM_SECRET');

            $res = $client->request('GET',$getAccessTokenUri)->getBody()->getContents();

            $accessToken = json_decode($res)->access_token;

            $wxMsg = [
                'touser' => $openId,
                'template_id' => $this->templateId,
                'page' => $this->page,
                'data' => $this->data,
                //'miniprogram_state' => 'trial'  体验版
                'miniprogram_state' => 'formal'
            ];
            $wxMsg = json_encode($wxMsg);
            $sendMsgUri = 'https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token='.$accessToken;
            $res = $client->post($sendMsgUri,[
                    'headers'=> [
                        'Content-type'=>'application/json;charset=UTF-8',
                        'Accept'=>'application/json',
                        'Cache-Control'=>'no-cache',
                        'Pragma'=>'no-cache'
                    ],
                    'body' => $wxMsg
                ])->getBody()->getContents();
            Log::warning('Queue'.$res);

        } catch (RequestException $e) {
            Log::warning('RequestException' . $e->getMessage());
        } catch (Exception $e) {
            Log::emergency('Exception' . $e->getMessage());
        }
    }
}
