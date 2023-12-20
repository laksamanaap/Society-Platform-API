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


    // Get All Job Apply 
    public function getJobApply(Request $request)
    {
        // Validate the request
        $validator = validator($request->all(), [
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid token / fields', 'errors' => $validator->errors()], 401);
        }

        // Retrieve user based on the token
        $user = User::with(['applications' => function ($query) {
            $query->with(['vacancies','jobApplyPosition']);
        }])->where('login_tokens', $request->input('token'))->first();

        // $user = User::with(['applications.vacancies.availablePositions', 'positions'])
        // ->where('login_tokens', $request->input('token'))->first();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized user'], 401);
        }
        
        $response = [
        'vacancies' => $user->applications->map(function ($application) {
            return [
                'id' => $application->vacancies->id,
                'society_id' => $application->jobApplyPosition->society_id ?? 'null',
                'category' => [
                    'id' => $application->vacancies->job_category_id,
                    'job_category' => $application->vacancies->job_category, 
                ],
                'apply_date' => $application->date, 
                'notes' => $application->notes,
                'company' => $application->vacancies->company,
                'address' => $application->vacancies->address,
                'position' => $application->vacancies->availablePositions->map(function ($position) use ($application) {
                    return [
                        'position' => $position->position,
                        'apply_status' => $application->jobApplyPosition->status ?? 'null', // Assuming this property exists in your data
                        'notes' => $application->notes,
                    ];
                }),
            ];
        }),
     ];
        

        return response()->json($response , 200);
    }
}