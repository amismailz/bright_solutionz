<?php

namespace App\Traits;

use Illuminate\Validation\ValidationException;

trait ResponseTrait
{

    function getValidationErrorResponse(ValidationException $e)
    {
        return response()->json([
            'status' => 'error',
            //'message' => HtmlMessageResponseTrait::htmlMessageResponse( 'error', 'Validation Error' ),
            'text' => $e->errors(),
        ], 400);
    }

    function paginateResponse($data)
    {
        return response()->json([
            'status' => 'success',
            'text' => 'success',
            'msg_data' => $data->items(),
            'pagination' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total()
            ]
        ]);
    }
    function paginateResponseWithFilters($data, $filters, $name = 'data')
    {
        return response()->json([
            'status' => 'success',
            'text' => 'success',
            'msg_data' => [$name => $data->items(), 'filters' => $filters],
            'pagination' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total()
            ]
        ]);
    }
    function paginateResponseWithFiltersToOffice($data, $filters, $office)
    {
        return response()->json([
            'status' => 'success',
            'text' => 'success',
            'msg_data' => ['data' => $data->items(), 'filters' => $filters, 'office' => $office],
            'pagination' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total()
            ]
        ]);
    }

    function okResponse($message, $data = null)
    {
        return response()->json([
            'status' => 'success',
            'text' => $message,
            'msg_data' => $data,
        ], 200);
    }
    function exceptionFailed($exception)
    {
        return response()->json([
            'status' => 'error',
            'text' => __('Something Went Wrong'),
            'msg_data' => $exception,
        ], status: 500);
    }

    function createdResponse($model, $data)
    {
        return response()->json([
            'status' => 'success',
            'text' => __(':Model Created Successfully.', ['Model' => $model]),
            'msg_data' => $data,
        ], 201);
    }
    function createdResponseWithMessage($message, $data)
    {
        return response()->json([
            'status' => 'success',
            'text' => $message,
            'msg_data' => $data,
        ], 201);
    }


    function notAllowedMethodResponse()
    {
        return response()->json([
            'status' => 'error',
            'text' => __('Not Allowed Method'),
        ], 405);
    }

    function notFoundResponse($model)
    {
        return response()->json([
            'status' => 'error',
            'text' => __(':Model Not Found.', ['Model' => __($model)]),
        ], 404);
    }
    function unauthorized($message = null)
    {
        return response()->json([
            'status' => 'error',
            'text' => $message ?? __('User Unauthorized'),
        ], 401);
    }

    function badRequestResponse($message)
    {
        return response()->json([
            'status' => 'error',
            'text' => $message,
        ], 400);
    }
    function failedWithError($message, $statusCode)
    {
        return response()->json([
            'status' => 'error',
            'text' => $message,
        ], $statusCode);
    }
}
