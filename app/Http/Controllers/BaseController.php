<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message,$status = 200)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];
        return response()->json($response, $status);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];
        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendException($errors,$status = 500)
    {
        $response = [
            'success' => false,
            'message' => $errors,
            'exception' => 'Exception occurred.'
        ];
        return response()->json($response, $status);
    }

    public function redirectSuccess($route, $message)
    {
        return redirect($route)->with('success', $message);
    }

    public function redirectError($message)
    {
        return redirect()->back()->withErrors(['msg' => $message]);
    }
}
