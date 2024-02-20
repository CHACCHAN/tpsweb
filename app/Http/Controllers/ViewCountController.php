<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use App\Models\ViewCount;

class ViewCountController extends Controller
{
    public function addViewCount(Request $request)
    {
        try {
            if(ViewCount::latest()->exists()) {
                $ViewCount = ViewCount::latest()->first();
            } else {
                ViewCount::create([
                    'january' => 0,
                    'february' => 0,
                    'march' => 0,
                    'april' => 0,
                    'may' => 0,
                    'june' => 0,
                    'july' => 0,
                    'august' => 0,
                    'september' => 0,
                    'october' => 0,
                    'november' => 0,
                    'december' => 0,
                ]);
                $ViewCount = ViewCount::latest()->first();
            }

            if($ViewCount->created_at->format('Y-m') != Date::now()->format('Y-m')) {
                ViewCount::create([
                    'january' => 0,
                    'february' => 0,
                    'march' => 0,
                    'april' => 0,
                    'may' => 0,
                    'june' => 0,
                    'july' => 0,
                    'august' => 0,
                    'september' => 0,
                    'october' => 0,
                    'november' => 0,
                    'december' => 0,
                ]);
                $ViewCount = ViewCount::latest()->first();
            }

            switch($request->month) {
                case 1:
                    ViewCount::where('created_at', $ViewCount->created_at)->update([ 'january' => $ViewCount->january + 1 ]);
                    break;
                case 2:
                    ViewCount::where('created_at', $ViewCount->created_at)->update([ 'february' => $ViewCount->february + 1 ]);
                    break;
                case 3:
                    ViewCount::where('created_at', $ViewCount->created_at)->update([ 'march' => $ViewCount->march + 1 ]);
                    break;
                case 4:
                    ViewCount::where('created_at', $ViewCount->created_at)->update([ 'april' => $ViewCount->april + 1 ]);
                    break;
                case 5:
                    ViewCount::where('created_at', $ViewCount->created_at)->update([ 'may' => $ViewCount->may + 1 ]);
                    break;
                case 6:
                    ViewCount::where('created_at', $ViewCount->created_at)->update([ 'june' => $ViewCount->june + 1 ]);
                    break;
                case 7:
                    ViewCount::where('created_at', $ViewCount->created_at)->update([ 'july' => $ViewCount->july + 1 ]);
                    break;
                case 8:
                    ViewCount::where('created_at', $ViewCount->created_at)->update([ 'august' => $ViewCount->august + 1 ]);
                    break;
                case 9:
                    ViewCount::where('created_at', $ViewCount->created_at)->update([ 'september' => $ViewCount->september + 1 ]);
                    break;
                case 10:
                    ViewCount::where('created_at', $ViewCount->created_at)->update([ 'october' => $ViewCount->october + 1 ]);
                    break;
                case 11:
                    ViewCount::where('created_at', $ViewCount->created_at)->update([ 'november' => $ViewCount->november + 1 ]);
                    break;
                case 12:
                    ViewCount::where('created_at', $ViewCount->created_at)->update([ 'december' => $ViewCount->december + 1 ]);
                    break;
                default:
                    break;
            }

            return response()->json([ 'responseData' => true ], 200);
        } catch(\Exception $e) {
            return response()->json([ 'responseData' => false ], 500);
        }
    }
}
