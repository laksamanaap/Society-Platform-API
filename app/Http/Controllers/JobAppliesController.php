<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\JobApplies;
use App\Models\JobPositions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class JobAppliesController extends Controller
{
    // Store Job Apply
     public function storeJobApply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'positions' => 'required|array',
            'vacancy_id' => 'required|integer',
            'notes' => 'string',
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
        if ($user->applications()->where('job_vacancy_id', $request->input('vacancy_id'))->exists()) {
            return response()->json(['message' => 'Application for a job can only be once'], 401);
        }


        // Create a new application
        $application = new JobApplies([
            'notes' => $request->input('notes'),
            'date' => Carbon::now(),
            'society_id' => $user->id,
            'job_vacancy_id' => $request->input('vacancy_id'),
        ]);

        // Input multiple (array)
        // Create a new position
        foreach ($request->input('positions') as $key => $value) {
            JobPositions::create([
                'date' => Carbon::now(),
                'society_id' => $user->id,
                'job_vacancy_id' => $request->input('vacancy_id'),
            ]);
        }

        $user->applications()->save($application);

        return response()->json([
            'message' => 'Applying for job successful',
            'data' => $application // Temporary for Debugging
        ], 200);
    }


    // Get All Job Apply
    public function getJobApply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid field', 'errors' => $validator->errors()], 401);
        }

        // Retrieve user based on the token
        $user = User::where('login_tokens', $request->input('token'))->first();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized user'], 401);
        }

        // Get all job applications for the user
        $jobApplications = $user->applications()->with(['position'])->get();

        $response = [
            'vacancies' => $jobApplications->map(function ($application) {
                return [
                    'id' => $application->job_vacancy_id,
                    'category' => [
                        'id' => $application->position->job_category_id,
                        'job_category' => $application->position->job_category,
                    ],
                    'company' => $application->position->company,
                    'address' => $application->position->address,
                    'position' => [
                        'position' => $application->position->position,
                        'apply_status' => $application->apply_status,
                        'notes' => $application->notes,
                    ],
                ];
            }),
        ];

        return response()->json($response, 200);
    }
}
