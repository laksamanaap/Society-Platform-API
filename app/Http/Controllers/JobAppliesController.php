<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\JobApplies;
use App\Models\JobPositions;
use Illuminate\Http\Request;
use App\Models\AvailablePosition;
use Illuminate\Support\Facades\Validator;


class JobAppliesController extends Controller
{
    // Store Job Apply
     public function storeJobApply(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'token' => 'required|string',
        //     'positions' => 'required|array',
        //     'vacancy_id' => 'required|integer',
        //     'notes' => 'string',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json(['message' => 'Invalid field', 'errors' => $validator->errors()], 401);
        // }

        // // Init user
        // $user = User::where('login_tokens', $request->input('token'))->first();

        // if (!$user) {
        //     return response()->json(['message' => 'Unauthorized user'], 401);
        // }

        // // Check if user already applied
        // // if ($user->applications()->where('job_vacancy_id', $request->input('vacancy_id'))->exists()) {
        // //     return response()->json(['message' => 'Application for a job can only be once'], 401);
        // // }

        // // Create a new application
        // $application = new JobApplies([
        //     'notes' => $request->input('notes'),
        //     'date' => Carbon::now(),
        //     'society_id' => $user->id,
        //     'job_vacancy_id' => $request->input('vacancy_id'),
        // ]);

        // // Check if position is an array
        // $positions = $request->input('positions');

        // if (!is_array($positions)) {
        //     return response()->json(['message' => 'Invalid positions data'], 400);
        // }

        // // Create a new position
        // foreach ($request->input('positions') as $key => $value) {
        
        //     $position = AvailablePosition::where('position', $value['position'])->first();

        //     if (!$position) {
        //         return response()->json(['message' => 'Position not found for name: ' . $value['position_name']], 404);
        //     }

        //     // Create a new position
        //     JobPositions::create([
        //         'date' => Carbon::now(),
        //         'society_id' => $user->id,
        //         'job_vacancy_id' => $request->input('vacancy_id'),
        //         'position_id' => $position->id,
        //         'job_apply_societies_id' => $application->id, 
        //     ]);
        // }

        // $user->applications()->save($application);

        // return response()->json([
        //     'message' => 'Applying for job successful',
        //     'data' => $application // Temporary for Debugging
        // ], 200);

        {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'notes' => 'string',
            'vacancy_id' => 'required|integer',
            'positions' => 'array', // Make sure 'positions' is an array
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid field', 'errors' => $validator->errors()], 401);
        }

        // Init user
        $user = User::where('login_tokens', $request->input('token'))->first();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized user'], 401);
        }

        // Check if user already applied
        // if ($user->applications()->where('job_vacancy_id', $request->input('vacancy_id'))->exists()) {
        //     return response()->json(['message' => 'Application for a job can only be once'], 401);
        // }

        // Create a new application
        $application = new JobApplies([
            'notes' => $request->input('notes'),
            'date' => Carbon::now(),
            'society_id' => $user->id,
            'job_vacancy_id' => $request->input('vacancy_id'),
        ]);

        // Save the application store to the database
        $application->save();

        $jobApplySocietiesId = $application->id;

        // Check if 'positions' is an array
        $positions = $request->input('positions');

        if (!is_array($positions)) {
            return response()->json(['message' => 'Invalid positions data'], 400);
        }

        foreach ($positions as $positionName) {
            // Find for position id by name
            $position = AvailablePosition::where('position', $positionName)
                ->where('job_vacancy_id', $request->input('vacancy_id'))
                ->first();

            if (!$position) {
                return response()->json(['message' => 'Position not found for name: ' . $positionName], 404);
            }

            // Create a new position
             $jobPosition =  JobPositions::create([
                    'date' => Carbon::now(),
                    'society_id' => $user->id,
                    'job_vacancy_id' => $request->input('vacancy_id'),
                    'position_id' => $position->id,
                    'job_apply_societies_id' => $jobApplySocietiesId, 
                ]);
            }

            return response()->json([
                'message' => 'Applying for job successful',
                'position_applied' => $jobPosition, // For Debugging
                'application' => $application // For Debugging
            ], 200);
        }
    }


    // Get All Job Apply
    public function getJobApply(Request $request)
    {
    $user = User::where('login_tokens', $request->input('token'))->first();

    $jobApplications = $user->applications()->with('positions')->get();

    // // Group job applications by job vacancy ID
    // $groupedApplications = $jobApplications->groupBy('job_vacancy_id');

    // $response = [
    // 'vacancies' => $groupedApplications->map(function ($applications, $vacancyId) {
    //     $firstApplication = $applications->first();

    //     return [
    //         'id' => $vacancyId,
    //         'category' => [
    //             'id' => $firstApplication->positions->job_category_id,
    //             'job_category' => $firstApplication->positions->job_category,
    //         ],
    //         'company' => $firstApplication->positions->company,
    //         'address' => $firstApplication->positions->address,
    //         'position' => $applications->map(function ($application) {
    //             return [
    //                 'positions' => $application->positions->positions,
    //                 'apply_status' => $application->apply_status,
    //                 'notes' => $application->notes,
    //             ];
    //         }),
    //     ];
    // })->filter(), // Filter out null values
    // ];

    return response()->json($jobApplications, 200);
    }
}