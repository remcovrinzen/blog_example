<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Helper;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sortMethod = $this->getPostSort($request);

        $now = Carbon::now()->toDateTimeString();
        $allPosts = Post::leftJoin('reviews',  'posts.post_id', '=', 'reviews.post_id')
            ->select('posts.*')
            ->filter($request)
            ->where('published', '<=', $now);
        $numberOfPosts = $allPosts->count();

        if ($sortMethod["variable"] == 'rating') {
            $sortMethod["variable"] = 'reviews.rating';
        };

        $pagerPosts = $allPosts
            ->orderBy($sortMethod["variable"], $sortMethod["way"])
            ->with('review:post_id,rating,book_summary')
            ->paginate(10);

        $pagerPosts = Helper::appendAllQueryParams($pagerPosts, $request->query());

        $postCategories = PostCategory::get();
        return view('posts.index', ['pagerPosts' => $pagerPosts, "postCategories" => $postCategories->toArray(), "numberOfPosts" => $numberOfPosts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }

    public function getPostSort($request)
    {
        $sortMethod = Helper::getDefaultPostSort();

        if ($request->query('sort') != null) {
            $sortMethod = Helper::splitSortQuery($request->query('sort'));
        }

        return $sortMethod;
    }
}
