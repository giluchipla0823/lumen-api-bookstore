<?php
namespace App\Http\Controllers\V1\Author;

use App\Http\Controllers\ApiController;
use App\Http\Requests\AuthorRequest;
use App\Models\Author;
use App\Services\AuthorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorController extends ApiController
{
    /**
     * @var AuthorService
     */
    private $authorService;

    public function __construct(AuthorService $authorService)
    {
        $this->authorService = $authorService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request){
        $authors = $this->authorService->filter($request);

        return $this->showAll($authors);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AuthorRequest $request
     * @return JsonResponse
     */
    public function store(AuthorRequest $request)
    {
        $this->authorService->create($request->all());

        return $this->showMessage('Author created successfully', Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param Author $author
     * @return JsonResponse
     */
    public function show(Author $author)
    {
        return $this->showOne($author);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AuthorRequest $request
     * @param Author $author
     * @return JsonResponse
     */
    public function update(AuthorRequest $request, Author $author)
    {
        $this->authorService->update($request->all(), $author->id);

        return $this->showMessage('Author updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Author $author
     * @return JsonResponse
     */
    public function destroy(Author $author)
    {
        $this->authorService->delete($author->id);

        return $this->showMessage('Author removed successfully');
    }

    /**
     * Enable the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function restore($id){

        $this->authorService->restore($id);

        return $this->showMessage('Author restored successfully');
    }
}