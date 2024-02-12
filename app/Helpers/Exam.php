<?php


namespace App\Helpers;
use Request;
use App\Models\LogActivity as LogActivityModel;
use App\Models\QuizAnswers;
use App\Models\RedeemPoints;
use App\Models\Workshops;

class Exam
{


    public static function get_quiz_answers($id) {
        $user_id = auth()->user()->id;
        $sql = QuizAnswers::select('id','answer','marks')
            ->where('quiz_id', $id)
            ->orderBy('id', 'desc')
            ->get();

        if (!empty($sql)) {
            return $sql;
        } else {
            return NULL;
        }
    }
    public static function get_all_points()
    {
        $workshop_id = auth()->user()->workshopid;
        $total_points = Workshops::select('points')->where('id',$workshop_id)->first();
        
        return $total_points->points;
    }
    public static function cart_item_count()
    {
        $userID = auth()->user()->id;
        $cartCollection = \Cart::session($userID)->getContent();
        return $cartCollection->count();
    }

}