<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vacancies;
use App\Models\Validation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidationController extends Controller
{

    // Store validation
    public function storeValidation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string', 
            'work_experience' => 'required|string',
            'job_category_id' => 'required|integer',
            'job_position' => 'required|string',
            'reason_accepted' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 422);
        }

        $inputToken = $request->input('token');
        $user = User::where('login_tokens', $inputToken)->first();

        $validation = new Validation([
            'work_experience' => $request->input('work_experience'),
            'job_category_id' => $request->input('job_category_id'),
            'job_position' => $request->input('job_position'),
            'reason_accepted' => $request->input('reason_accepted'),
            'society_id' => $user->id,
        ]);

        $validation->save();

        return response()->json(['message' => 'Request data validation sent successfully', 'data' => $validation], 200);
    }

     // Get All Validation
    public function getValidation(Request $request )
    { 

        $getAllValidation = Validation::all();

        return response()->json([
            'data' => $getAllValidation
        ], 200);

    }
   

}