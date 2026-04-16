<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class BookController extends Controller
{
    #[OA\Get(
        path: '/api/books',
        summary: 'Get all books',
        tags: ['Books'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Books retrieved successfully'),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $query = Book::query()->with(['category', 'author']);

        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->string('title') . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }

        if ($request->filled('author_id')) {
            $query->where('author_id', $request->integer('author_id'));
        }

        $books = $query->latest()->paginate($request->integer('per_page', 10));

        return response()->json([
            'success' => true,
            'message' => 'Books retrieved successfully.',
            'data' => BookResource::collection($books->getCollection()),
            'meta' => [
                'current_page' => $books->currentPage(),
                'per_page' => $books->perPage(),
                'total' => $books->total(),
                'last_page' => $books->lastPage(),
            ],
        ]);
    }

    #[OA\Post(
        path: '/api/books',
        summary: 'Create a new book',
        tags: ['Books'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['category_id', 'author_id', 'title', 'slug', 'isbn', 'published_year', 'stock'],
                properties: [
                    new OA\Property(property: 'category_id', type: 'integer', example: 1),
                    new OA\Property(property: 'author_id', type: 'integer', example: 1),
                    new OA\Property(property: 'title', type: 'string', example: 'Laravel API Praktis'),
                    new OA\Property(property: 'slug', type: 'string', example: 'laravel-api-praktis'),
                    new OA\Property(property: 'isbn', type: 'string', example: '9780000000001'),
                    new OA\Property(property: 'published_year', type: 'integer', example: 2023),
                    new OA\Property(property: 'stock', type: 'integer', example: 10),
                    new OA\Property(property: 'synopsis', type: 'string', nullable: true, example: 'Panduan membangun REST API dengan Laravel.'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Book created successfully'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function store(StoreBookRequest $request): JsonResponse
    {
        $book = Book::create($request->validated());
        $book->load(['category', 'author']);

        return response()->json([
            'success' => true,
            'message' => 'Book created successfully.',
            'data' => new BookResource($book),
        ], 201);
    }

    #[OA\Get(
        path: '/api/books/{book}',
        summary: 'Get a book by id',
        tags: ['Books'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'book', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Book retrieved successfully'),
            new OA\Response(response: 404, description: 'Book not found'),
        ]
    )]
    public function show(Book $book): JsonResponse
    {
        $book->load(['category', 'author']);

        return response()->json([
            'success' => true,
            'message' => 'Book retrieved successfully.',
            'data' => new BookResource($book),
        ]);
    }

    #[OA\Put(
        path: '/api/books/{book}',
        summary: 'Update a book',
        tags: ['Books'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'book', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'category_id', type: 'integer', example: 1),
                    new OA\Property(property: 'author_id', type: 'integer', example: 1),
                    new OA\Property(property: 'title', type: 'string', example: 'Laravel API Praktis'),
                    new OA\Property(property: 'slug', type: 'string', example: 'laravel-api-praktis'),
                    new OA\Property(property: 'isbn', type: 'string', example: '9780000000001'),
                    new OA\Property(property: 'published_year', type: 'integer', example: 2023),
                    new OA\Property(property: 'stock', type: 'integer', example: 10),
                    new OA\Property(property: 'synopsis', type: 'string', nullable: true, example: 'Panduan membangun REST API dengan Laravel.'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Book updated successfully'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function update(UpdateBookRequest $request, Book $book): JsonResponse
    {
        $book->update($request->validated());
        $book->load(['category', 'author']);

        return response()->json([
            'success' => true,
            'message' => 'Book updated successfully.',
            'data' => new BookResource($book),
        ]);
    }

    #[OA\Delete(
        path: '/api/books/{book}',
        summary: 'Delete a book',
        tags: ['Books'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'book', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Book deleted successfully'),
            new OA\Response(response: 409, description: 'Book cannot be deleted because it is referenced by borrowings'),
            new OA\Response(response: 404, description: 'Book not found'),
        ]
    )]
    public function destroy(Book $book): JsonResponse
    {
        if ($book->borrowings()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Book cannot be deleted because it is still used in borrowing data.',
            ], 409);
        }

        $book->delete();

        return response()->json([
            'success' => true,
            'message' => 'Book deleted successfully.',
            'data' => null,
        ]);
    }
}
