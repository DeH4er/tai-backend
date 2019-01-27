<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use App\User;
use App\Http\Controllers\UserController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
  public function getArticles(Request $request) {
    $per_page = $request->get('per_page');
    $articles = Article::paginate($per_page);
    return response()->json($articles, 200);
  }

  public function getArticle(Request $request, $id) {
    try {
      $article = Article::findOrFail($id);
    } catch (ModelNotFoundException $e) {
      return response()->json(['error' => 'article not found']);
    }

    return response()->json($article, 200);
  }

  public function createArticle(Request $request) {
    $validator = Validator::make($request->all(), [
      'title' => 'required|string|max:255',
      'content' => 'required|string',
      'description' => 'required|string|max:255'
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors()->toJson(), 400);
    }

    $user = UserController::getAuthenticatedUser();
    $article = Article::create([
      'title' => $request->get('title'),
      'content' => $request->get('content'),
      'description' => $request->get('description'),
      'author_id' => $user->id
    ]);

    return response()->json($article, 200);
  }

  public function editArticle(Request $request, $id) {
    $validator = Validator::make($request->all(), [
      'title' => 'required|string|max:255',
      'content' => 'required|string',
      'description' => 'required|string|max:255'
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors()->toJson(), 400);
    }

    $article = $this->_getAuthorsArticle($id);

    $article->title = $request->get('title');
    $article->content = $request->get('content');
    $article->description = $request->get('description');
    $article->save();
    
    return response()->json($article, 200);
  }

  public function deleteArticle(Request $request, $id) {
    $article = $this->_getAuthorsArticle($id);

    $article->delete();
    return response()->json([], 200);
  }

  private function _getAuthorsArticle($article_id) {
    $author = UserController::getAuthenticatedUser();
    
    try {
      $article = Article::where([
        'author_id' => $author->id,
        'id' => $article_id 
      ])->firstOrFail();
    } catch (ModelNotFoundException $e) {
      return response()->json(['error' => 'article not found']);
    }

    return $article;

  }

}
