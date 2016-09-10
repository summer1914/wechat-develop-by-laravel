<?php

namespace App\Http\Controllers\Companion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Planet;

class GetPeopleController extends Controller
{
    public function entarnce(Request $request)
    {
        Log::info("\nREMOTE_ADDR:".$_SERVER["REMOTE_ADDR"].(strstr($_SERVER["REMOTE_ADDR"],'101.226')? " FROM WeiXin": "Unknown IP"));
        Log::info("(".$request->method().")QUERY_STRING:".$_SERVER["QUERY_STRING"]);
        $weObj = new Planet();
        $weObj->valid();//明文或兼容模式可以在接口验证通过后注释此句，但加密模式一定不能注释，否则会验证失败
        $type = $weObj->getRev()->getRevType();
        switch($type) {
            case Planet::MSGTYPE_TEXT:
                $weObj->text("hello, I'm Planet")->reply();
                exit;
                break;
            case Planet::EVENT_SUBSCRIBE:
                $weObj->text($weObj->getRev()->getRevFrom())->reply();
                break;
            case Planet::EVENT_MENU_VIEW:
                $weObj->text("hello, I'm redirect")->reply();
                break;
            case Planet::EVENT_SCAN:
                $weObj->text($weObj->getRevSceneId ())->reply();
                break;
            default:
                $weObj->text("help info")->reply();
        }

        Log::info("(".$weObj->getRev().";".$type);

    }

    public function makeCode()
    {
        $weObj = new Planet();
        $QRcode = $weObj->getQRCode(123456789,0,604800);
        return $weObj->getQRUrl($QRcode['ticket']) ;
    }

}
