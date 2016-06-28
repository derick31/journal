<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Post;

use App\Calendar;

use App\User;

use App\Tag;

use App\Background;

use Auth;
use Date;


class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(User $user)
    {
        if ($user->id == Auth::id()) {
            $posts = $user->posts()->orderBy('created_at', 'desc')->get();
        } else {
            $posts = $user->posts()->where('privacy','=','public')->orderBy('created_at', 'desc')->get();
        }
        $top = Tag::top();
        $data = array(
            'posts' => $posts,
            'user' => $user,
            'tops' => $top
        );
        return view('profile.index', $data);
    }

    public function listPosts(Request $request)
    {
        $date = $request->date;
        $posts = Auth::user()->posts()->whereDate('date', '=', date('Y-m-d',$date))->get();
        $table = "";
        if (count($posts) == 0) {
            return "<tr><td><h3>no posts found</h3></td></tr>";
        }
        foreach ($posts as $post) {
            $table .= "<tr>";
            $table .= "<td>".date('H:m',strtotime($post->date))."</td>";
            $table .= "<td><a href='post/".$post->id."'>".$post->title."</a></td>";

            $table .= "<td><a style='cursor:pointer' class='privacy-toggle' id='privacy-".$post->id."'>";
            if (strcmp($post->privacy, 'public') == 0) {
                $table .= '<span class="label label-success">'.$post->privacy.'</span>';
            } else {
                $table .= '<span class="label label-default">'.$post->privacy.'</span>';
            }
            $table .= "</a></td>";

            $table .= "
                        <td>
                            <div class='dropdown'>
                                <button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
                                <span class='caret'></span>
                                </button>
                                <ul class='dropdown-menu' aria-labelledby='dropdownMenu1'>
                                    <li><a href='post/".$post->id."/edit'>
                                        <span class='glyphicon glyphicon-pencil text-success' aria-hidden='true' title='remove'></span>
                                        Edit
                                        </a></li>
                                    <li role='separator' class='divider'></li>
                                    <li><a href='' data-toggle='modal' data-target='#modal' id=".$post->id.">
                                        <span class='glyphicon glyphicon-remove text-danger' aria-hidden='true' title='remove'></span>
                                        Remove
                                    </a></li>
                                </ul>
                            </div>
                        </td>";
            $table .= "<tr>";
        }
        echo $table;
    }


    public function calendar(Request $request)
    {
        if(strcmp($request->month,'') == 0 || strcmp($request->year,'') == 0) {
            $date = date('Y-m-d');
            $calendar = Calendar::draw_calendar(date('m'), date('Y'), date('d'));
        } else {
            $date = $request->year."-".$request->month."-1";
            $calendar = Calendar::draw_calendar($request->month, $request->year, 1);
        }
        $posts = Auth::user()->posts()->whereDate('date', '=', $date)->get();
        $data = array(
            'posts' => $posts,
            'calendar' => $calendar,
            'date' => strtotime($date),
        );
        return view('profile.calendar',$data);
    }

    public function background(Request $request)
    {
        $background = new Background();
        $background->image = strip_tags($request->image);
        $user = Auth::user();

        if ($user->background == null) {
            $user->background()->save($background);
        } else {
            $user->background()->update(['image' => strip_tags($request->image)]);
        }
        return strip_tags($request->image);
    }
}
