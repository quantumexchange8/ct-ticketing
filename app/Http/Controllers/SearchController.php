<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function searchSupportTools(Request $request)
    {
        $searchTerm = $request->input('searchTerm');

        $allSubCategoryIds = DB::table('support_sub_categories')
                                ->select('id as sub_category_id')
                                ->pluck('sub_category_id')
                                ->toArray();

        $matchedSubCategoryIds = DB::table('support_categories')
                                ->join('support_sub_categories', 'support_categories.id', 'support_sub_categories.category_id')
                                ->where('support_sub_categories.sub_name', 'LIKE', "%$searchTerm%")
                                ->orWhere('support_sub_categories.sub_description', 'LIKE', "%$searchTerm%")
                                ->select('support_sub_categories.id as sub_category_id')
                                ->pluck('sub_category_id')
                                ->toArray();

        $unmatchedSubCategoryIds = array_diff($allSubCategoryIds, $matchedSubCategoryIds);

        $response = [
            'allSubCategoryIds' => $allSubCategoryIds,
            'matchedSubCategoryIds' => $matchedSubCategoryIds,
            'unmatchedSubCategoryIds' => array_values($unmatchedSubCategoryIds), // Re-index the array
            'searchTerm' => $searchTerm,
        ];

        return response()->json($response);

        // return view('user.support', compact('supportCategories', 'searchTerm', 'matchedSubCategoryIds'));
    }

    public function searchDocumentation(Request $request)
    {
        $searchTerm = $request->input('searchTerm');

        $titleId = $request->input('titleId');

        // $allContentIds = DB::table('contents')
        //                 ->select('id as content_id', 'subtitle_id')
        //                 ->pluck('content_id', 'subtitle_id')
        //                 ->toArray();


        // $matchedContentIds = DB::table('titles')
        //                     ->join('subtitles', 'subtitles.title_id', 'titles.id')
        //                     ->join('contents', 'contents.subtitle_id', 'subtitles.id')
        //                     ->where('titles.id', $titleId)
        //                     ->where('subtitles.subtitle_name', 'LIKE', "%$searchTerm%")
        //                     ->orWhere('contents.content_name', 'LIKE', "%$searchTerm%")
        //                     ->select('contents.id as content_id', 'subtitle_id')
        //                     ->pluck('content_id', 'subtitle_id')
        //                     ->toArray();

        $allContentIds = DB::table('contents')
                            ->select('id as content_id')
                            ->pluck('content_id')
                            ->toArray();

        $matchedContentIds = DB::table('titles')
                            ->join('subtitles', 'subtitles.title_id', 'titles.id')
                            ->join('contents', 'contents.subtitle_id', 'subtitles.id')
                            ->where('titles.id', $titleId)
                            ->where('subtitles.subtitle_name', 'LIKE', "%$searchTerm%")
                            ->orWhere('contents.content_name', 'LIKE', "%$searchTerm%")
                            ->select('contents.id as content_id')
                            ->pluck('content_id')
                            ->toArray();

        $unmatchedContentIds = array_diff($allContentIds, $matchedContentIds);

        $response = [
            'allContentIds' => $allContentIds,
            'matchedContentIds' => $matchedContentIds,
            'unmatchedContentIds' => array_values($unmatchedContentIds), // Re-index the array
            'searchTerm' => $searchTerm,
        ];

        return response()->json($response);
    }

}
