<?php

namespace App\Http\Controllers;

use App\Models\Vacancies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class VacanciesController extends Controller
{
     // Get validation by id
    public function getVacanciesById(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 422);
        }

        // Retrieve Token
        $token = $request->input('token');

        // Relationship
        $vacancy = Vacancies::with('availablePositions','jobCategories')->find($id);

        if (!$vacancy) {
            return response()->json(['message' => 'Job vacancy not found'], 404);
        }

        //  Log::info($vacancy->toArray());

        $response = [
            'vacancy' => [
                'id' => $vacancy->id,
                'category' => [
                    'id' => $vacancy->jobCategories->id ?? null,
                    'job_category' => $vacancy->jobCategories->job_category ?? null,
                ],
                'company' => $vacancy->company,
                'address' => $vacancy->address,
                'description' => $vacancy->description,
                'available_position' => $vacancy->availablePositions->map(function ($position) {
                    return [
                        'position' => $position->position,
                        'capacity' => $position->capacity,
                        'apply_capacity' => $position->apply_capacity,
                        'apply_count' =>  $position->capacity + $position->apply_capacity,
                    ];
                }),
            ],
        ];

        return response()->json($response, 200);
    }

   

    // Get All Vacancies
    public function getVacancies(Request $request ){
        
        // Relationship
        $vacancies = Vacancies::with('availablePositions','jobCategories')->get();

        $response = [
                'vacancies' => $vacancies->map(function ($vacancy) {
                    return [
                        'id' => $vacancy->id,
                        'category' => [
                            'id' => $vacancy->jobCategories->id,
                            'job_category' => $vacancy->jobCategories->job_category,
                        ],
                        'company' => $vacancy->company,
                        'address' => $vacancy->address,
                        'description' => $vacancy->description,
                        'available_position' => $vacancy->availablePositions->map(function ($position) {
                            return [
                                'position' => $position->position,
                                'capacity' => $position->capacity,
                                'apply_capacity' => $position->apply_capacity,
                            ];
                        }),
                    ];
                }),
            ];


        return response()->json($response,200);

    }
}
