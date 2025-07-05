<?php


use App\Http\Controllers\API\V1\AuthenticationController;
use App\Http\Controllers\API\V1\MovementController;
use App\Http\Controllers\API\V1\PointController;
use App\Http\Controllers\API\V1\RangeController;
use App\Http\Controllers\API\V1\ReviewController;
use App\Http\Controllers\API\V1\SocietyReviewController;
use App\Http\Controllers\API\V1\EvaluationController;
use App\Http\Controllers\API\V1\ContestController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;

Route::group([
    'middleware' => [
        'api',
        'localization',
        'forceJson'
    ],
    'prefix' => 'v1',
    'name' => 'api.',
], function () {

    Route::group([], function () {

        Route::post('/login', [AuthenticationController::class, 'login'])->name('login');
        Route::post('/register', [AuthenticationController::class, 'register']);
        Route::post('/upload-attachments', [AuthenticationController::class, 'uploadAttachments']);
        Route::post('/evaluations', [EvaluationController::class, 'store']);
        Route::get('/evaluations/options', [EvaluationController::class, 'options']);
    });
    Route::get('/options/points-slugs', [PointController::class, 'getPointsSlugOptions']);
    Route::get('/options/cities', [RangeController::class, 'getCitiesOptions']);
    Route::get('/options/ranges', [RangeController::class, 'getRangesOptions']);
    Route::get('/options/points', [PointController::class, 'getPointsOptions']);

    Route::get('/get-point-byslug/{slug}', [PointController::class, 'getPointBySlug']);
    Route::group([
        'middleware' => [
            'auth:sanctum',
            'checkActiveStatus',
        ],
        'name' => 'auth.',
    ], function () {



        Route::post('/logout', [AuthenticationController::class, 'logout']);
        Route::post('/edit-user-profile', [AuthenticationController::class, 'editUserProfile'])->name('edit-user-profile');
        Route::get('/movements', [MovementController::class, 'index']);
        Route::get('/points/movements/{id}', [MovementController::class, 'show']);
        Route::get('/points/{id}/reviews', [PointController::class, 'getAllReviews']);
        Route::get('/points/{id}/media', [PointController::class, 'getAllMedia']);
        Route::get('/points/{id}/all-movments', [PointController::class, 'getAllMovements']);
        Route::post('/movements', [MovementController::class, 'store']);
        Route::post('/reviews', [ReviewController::class, 'store']);
        Route::get('/reviews/options', [ReviewController::class, 'options']);
        Route::apiResource('/ranges', RangeController::class);
        Route::get('/ranges/get-reviewers/{id}', [RangeController::class, 'getAllAverageByReviewersForPointsLatestDate']);
        Route::get('/ranges/get-review-details/{id}', [RangeController::class, 'getReviewDetails']);

        Route::get('/ranges/get-all-reviewers/{id}', [RangeController::class, 'getAllReviewersForPoints']);

        Route::post('/ranges/update-hours/{id}', [RangeController::class, 'update_hours']);
        Route::get('/ranges/update-movements/{id}', [RangeController::class, 'update_movements']);
        Route::get('/ranges/update-movements-count/{id}', [RangeController::class, 'update_movements_count']);
        Route::get('/ranges/all-current-balance-points/{id}', [RangeController::class, 'getAllCurrentBalancePercentagePoints']);
        Route::get('/ranges/top-current-balance-for-points/{id}', [RangeController::class, 'getTopCurrentBalancePercentagePoints']);
        Route::get('/ranges/top-average-reviewers-for-points-latest-date/{id}', [RangeController::class, 'getTopAverageByReviewersForPointsLatestDate']);
        Route::get('/ranges/top-average-by-reviewers-for-points/{id}', [RangeController::class, 'getTopAverageByReviewersForPoints']);




        Route::post('/points/update-hours/{id}', [PointController::class, 'update_hours']);
        Route::get('/points/update-movements/{id}', [PointController::class, 'update_movements']);

        Route::get('/points/get-movements-for-point/{id}', [PointController::class, 'getMovementsForPoint']);

        Route::get('/points/get-reviewers-for-point-with-sort/{id}', [PointController::class, 'getReviewersForPointWithSort']);

        Route::apiResource('/points', PointController::class);
        Route::get('/point/{id}', [PointController::class, 'getPoint']);
        Route::get('/cities/{id}/ranges', [RangeController::class, 'getRangesForCity']);
        Route::get('/ranges/{id}/points', [RangeController::class, 'getPointForRange']);
        Route::get('/contests/options', [ContestController::class, 'options']);
        Route::post('/contests', [ContestController::class, 'store']);
        Route::post('/society/reviews', [SocietyReviewController::class, 'store']);
        Route::get('/society/reviews/options', [SocietyReviewController::class, 'options']);
    });

    Route::get('/congestion-level/options', [MovementController::class, 'getCongestionLeveOptions']);

    Route::group([
        'middleware' => [
            // 'guest:sanctum'
        ],
        'name' => 'all.',
    ], function () {});
});
