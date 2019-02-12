<?php

namespace App\Http\Controllers;

use App\Authorship;
use App\Citation;
use App\Paper;
use App\Review;
use App\Reviewer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaperController extends Controller
{
    public function getAllPapers()
    {
        $papers = Paper::all();
        return response(json_encode($papers), 200);
    }

    public function getAcceptedPapers()
    {
        $papers = Paper::where('status', 1)->get();
        return response(json_encode($papers), 200);
    }

    public function publishNewPaper(Request $request)
    {
        $rules = array(
            'title' => 'required|string|max:100',
            'abstract' => 'required|string|max:150',
            'file' => 'required|string|max:190',
            'topic' => 'required',
            'author' => 'required',
        );
        $validator = Validator::make($request->json()->all(), $rules);
        if ($validator->fails())
            return response(json_encode($validator->getMessageBag()->toArray()), 200);
        else {

            /** Publish paper **/
            $paper = new Paper(array(
                'title' => $request->json('title'),
                'abstract' => $request->json('abstract'),
                'file' => $request->json('file'),
                'status' => 0,
                'topic_id' => $request->json('topic')));
            $paper->save();

            $id = $paper->getAttribute('id');

            /** Credit lead author **/
            $authorship = new Authorship(array(
                'author_id' => $request->json('author'),
                'paper_id' => $id,
                'is_lead_author' => true,
            ));
            $authorship->save();

            /** Credit co-authors **/
            $coauthors = $request->json('coauthors');
            if (!empty($coauthors)) {
                foreach ($coauthors as $coauthor) {
                    $authorship = new Authorship(array(
                        'author_id' => $coauthor,
                        'paper_id' => $id,
                        'is_lead_author' => false,
                    ));
                    $authorship->save();
                }
            }

            /** Add references **/
            $bibliography = $request->json('bibliography');
            if (!empty($bibliography)) {
                foreach ($bibliography as $item) {
                    $citation = new Citation(array(
                        'citing_paper_id' => $id,
                        'cited_paper_id' => $item['paper_id'],
                        'part' => $item['part'],
                    ));
                    $citation->save();
                }
            }

            /** Choose reviewers **/
            $reviewers = Reviewer::all()->toArray();
            $reviewer_indexes = array_rand($reviewers, 3);
            foreach ($reviewer_indexes as $reviewer_index) {
                $reviewer_id = $reviewers[$reviewer_index]['user_id'];
                $review = new Review(array(
                    'reviewer_id' => $reviewer_id,
                    'paper_id' => $id,
                    'score' => 0,
                    'comment' => null,
                    'passed' => 0,
                ));
                $review->save();
            }

            return response(json_encode(array('Paper successfully published.', $id)), 200);
        }
    }

    public function uploadPdfFile(Request $request)
    {
        $validator = Validator::make($request->all(),
            array('fileToUpload' => 'required|mimes:pdf|max:10000',));
        if (!$validator->fails()) {
            $file = $request->file('fileToUpload');
            $name = $request->get('id') . $file->getClientOriginalName();
            $destinationPath = public_path('/papers_files');
            $file->move($destinationPath, $name);
            return response(json_encode('PDF file uploaded successfully.'), 200);
        } else
            return response(json_encode('Invalid PDF file!'), 500);
    }

    public function updateStatusForPapers(Request $request)
    {
        $papers = $request->json('papersToUpdate');
        if (!empty($papers)) {
            foreach ($papers as $paper) {
                $p = Paper::find($paper['paper_id']);
                $p->status = $paper['status'];
                $p->save();
            }
        }
        return response(json_encode('Status of papers successfully updated.'), 200);
    }
}
