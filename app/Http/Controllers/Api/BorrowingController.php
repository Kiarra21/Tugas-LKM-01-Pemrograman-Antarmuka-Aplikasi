<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBorrowingRequest;
use App\Http\Requests\UpdateBorrowingRequest;
use App\Http\Resources\BorrowingResource;
use App\Models\Borrowing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class BorrowingController extends Controller
{
    #[OA\Get(
        path: '/api/borrowings',
        summary: 'Get all borrowings',
        tags: ['Borrowings'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Borrowings retrieved successfully'),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $query = Borrowing::query()->with('book');

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('book_id')) {
            $query->where('book_id', $request->integer('book_id'));
        }

        if ($request->filled('borrower_name')) {
            $query->where('borrower_name', 'like', '%' . $request->string('borrower_name') . '%');
        }

        $borrowings = $query->latest()->paginate($request->integer('per_page', 10));

        return response()->json([
            'success' => true,
            'message' => 'Borrowings retrieved successfully.',
            'data' => BorrowingResource::collection($borrowings->getCollection()),
            'meta' => [
                'current_page' => $borrowings->currentPage(),
                'per_page' => $borrowings->perPage(),
                'total' => $borrowings->total(),
                'last_page' => $borrowings->lastPage(),
            ],
        ]);
    }

    #[OA\Post(
        path: '/api/borrowings',
        summary: 'Create a new borrowing',
        tags: ['Borrowings'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['book_id', 'borrower_name', 'borrower_email', 'borrowed_at', 'due_date', 'status'],
                properties: [
                    new OA\Property(property: 'book_id', type: 'integer', example: 1),
                    new OA\Property(property: 'borrower_name', type: 'string', example: 'Kiarra'),
                    new OA\Property(property: 'borrower_email', type: 'string', format: 'email', example: 'kiarra@example.com'),
                    new OA\Property(property: 'borrowed_at', type: 'string', format: 'date', example: '2026-04-16'),
                    new OA\Property(property: 'due_date', type: 'string', format: 'date', example: '2026-04-23'),
                    new OA\Property(property: 'returned_at', type: 'string', format: 'date', nullable: true, example: null),
                    new OA\Property(property: 'status', type: 'string', example: 'borrowed'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Borrowing created successfully'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function store(StoreBorrowingRequest $request): JsonResponse
    {
        $borrowing = Borrowing::create($request->validated());
        $borrowing->load('book');

        return response()->json([
            'success' => true,
            'message' => 'Borrowing created successfully.',
            'data' => new BorrowingResource($borrowing),
        ], 201);
    }

    #[OA\Get(
        path: '/api/borrowings/{borrowing}',
        summary: 'Get a borrowing by id',
        tags: ['Borrowings'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'borrowing', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Borrowing retrieved successfully'),
            new OA\Response(response: 404, description: 'Borrowing not found'),
        ]
    )]
    public function show(Borrowing $borrowing): JsonResponse
    {
        $borrowing->load('book');

        return response()->json([
            'success' => true,
            'message' => 'Borrowing retrieved successfully.',
            'data' => new BorrowingResource($borrowing),
        ]);
    }

    #[OA\Put(
        path: '/api/borrowings/{borrowing}',
        summary: 'Update a borrowing',
        tags: ['Borrowings'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'borrowing', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'book_id', type: 'integer', example: 1),
                    new OA\Property(property: 'borrower_name', type: 'string', example: 'Kiarra'),
                    new OA\Property(property: 'borrower_email', type: 'string', format: 'email', example: 'kiarra@example.com'),
                    new OA\Property(property: 'borrowed_at', type: 'string', format: 'date', example: '2026-04-16'),
                    new OA\Property(property: 'due_date', type: 'string', format: 'date', example: '2026-04-23'),
                    new OA\Property(property: 'returned_at', type: 'string', format: 'date', nullable: true, example: null),
                    new OA\Property(property: 'status', type: 'string', example: 'returned'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Borrowing updated successfully'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function update(UpdateBorrowingRequest $request, Borrowing $borrowing): JsonResponse
    {
        $borrowing->update($request->validated());
        $borrowing->load('book');

        return response()->json([
            'success' => true,
            'message' => 'Borrowing updated successfully.',
            'data' => new BorrowingResource($borrowing),
        ]);
    }

    #[OA\Delete(
        path: '/api/borrowings/{borrowing}',
        summary: 'Delete a borrowing',
        tags: ['Borrowings'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'borrowing', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Borrowing deleted successfully'),
            new OA\Response(response: 404, description: 'Borrowing not found'),
        ]
    )]
    public function destroy(Borrowing $borrowing): JsonResponse
    {
        $borrowing->delete();

        return response()->json([
            'success' => true,
            'message' => 'Borrowing deleted successfully.',
            'data' => null,
        ]);
    }
}
