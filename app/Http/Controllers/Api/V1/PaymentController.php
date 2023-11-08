<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\PaymentHelper;
use App\Http\Controllers\Controller;
use App\Jobs\SendMessageToSlack;
use App\Services\Orders\OrderGet;
use App\Services\Orders\OrderSet;
use App\Services\Users\UserGet;
use App\Services\Users\UserSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    function webhookPaypal(Request $request)
    {
        $packages = array_keys(config('payment.packages'));
        $params = $request->all();
        $header = $request->header();
        $payment_id = $params['resource']['id'];
        $event_type = $params['event_type'];
        try {
            $access_token = PaymentHelper::generateAccessToken();
            if (!$access_token){
                throw new \Exception("Generate Access Token Error");
            }
            $verify = PaymentHelper::verifyPaypalWebhookResponse($params, $header, $access_token);
//            if (!$verify) {
//                $ok = OrderSet::updateStatus($payment_id, 4);
//                if (!$ok){
//                    throw new \Exception("Update Status Order Verify Failed Error");
//                }
//                return $this->response(['message' => 'Verify payment failed!']);
//            }

//            if ($event_type == config('payment.event_types.success')) {
//                dispatch(new SendMessageToSlack("Paypal process", 'log'));

//                $ok = OrderSet::updateStatus($payment_id, 2);
//                if (!$ok){
//                    throw new \Exception("Update Status Order Success Error");
//                }
//                $order = OrderGet::getOrderByPaymentId($payment_id);
//
//                $user_id = $order->user_id;
//                $package = $order->package;
//                $user = UserGet::getUserById($user_id);
//                if (!empty($user->pro_exp)){
//                    if ($user->pro_exp < $order->time){
//                        $time = $order->package_exp_time;
//                    }else {
//                        if ($package == $packages[0]) {
//                            $time = strtotime("+30 days", $user->pro_exp);
//                        }
//
//                        if ($package == $packages[1]) {
//                            $time = strtotime("+356 days", $user->pro_exp);
//                        }
//                    }
//                }else{
//                    $time = $order->package_exp_time;
//                }
//                if ($package == $packages[2]){
//                    $time = -1;
//                }
//                $updated = UserSet::updateUserProExp($user_id, $package, $time);
//
//                if (!$updated) {
//                    throw new \Exception("Update User Pro Exp Error");
//                }
//            }

//            if ($event_type == config('payment.event_types.process')) {
//                $ok = OrderSet::updateStatus($payment_id, 1);
//                if (!$ok){
//                    throw new \Exception("Update Status Order Process Error");
//                }
//                dispatch(new SendMessageToSlack("Paypal process", 'log'));
//            }

            $params['request_methods'] = $request->method();
            $log_data = [
                "params" => $params,
                "headers" => $header,
            ];

            dispatch(new SendMessageToSlack($log_data, 'log'));
            return $this->response(['message' => 'ok']);
        }catch (\Exception $e){
//            Log::error($e->getMessage());
//            dispatch(new SendMessageToSlack($e->getMessage(), 'error'));
            return $this->response(['message' => $e->getMessage()], 500);
        }
    }
}
