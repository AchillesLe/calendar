<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class calendarController extends Controller
{
    public function index(){
        $beginDate = date('Y-m-01');
        $beginDate = "2018-12-01";
        $caledar = $this->getArrayDayByYearMonth( $beginDate );
        $html = $this->drawingHtml( $beginDate );
        $data = ['table' => $html , 'ss' => $caledar ];
        return view('calendar', $data );
    }

    private function getArrayDayByYearMonth( $date ){
        $ym = $this->getValueFromFomat($date,'Y-m');
        $calendar = DB::table('calendars')->where(DB::raw("(DATE_FORMAT(date_ymd,'%Y-%m'))"),"2016-07")->get();
        return $calendar;
    }

    private function getNextMonth( $date ){
        return $date = date( 'Y-m-01' , strtotime( $date.'+1 month'));
    }

    private function getPreviousMonth( $date ){
        return $date = date( 'Y-m-01' , strtotime($date.'-1 month'));
    }

    private function getValueFromFomat(  $date , $format ){
        return $month = date( $format , strtotime( $date) );
    }

    private function drawingHtml( $date ){
        // declare 
        $html = "";
        $class_head = "head";
        $class_number = "number";
        $class_anphabe = "anphabe";
        $class_first = "first";
        $class_mid = "mid";
        $class_last = "last";
        $class_Color_s = "color-s";
        $class_Color_a = "color-a";
        $class_Color_b = "color-b";
        $class_Color_c = "color-c";
        $class_Color_d = "color-d";
        $array_tag =  [ "日" , "月" , "火" , "水" , "木" , "金" , "土" ];
        $array_anPha =  [ "S" , "A" , "B" , "C" , "D" ];

        $nextDate = $this->getNextMonth($date);
        $previousDate = $this->getPreviousMonth($date);
        $nextMonth = $this->getValueFromFomat($nextDate , 'm月');
        $previousMonth = $this->getValueFromFomat($previousDate,'m月');
        $dateYM = $this->getValueFromFomat($date,'Y年m月');

        // header
        $header  =  "<div class='header-content'><div class='currentYD'>";
        $header .= "<span>{$dateYM}</span>";
        $header .= "</div> <div class='nav-month'>";
        $header .= "<span class='privous'>&lt;&lt;&nbsp;{$previousMonth}</span>";
        $header .= "<span class='next'>{$nextMonth}&nbsp;&gt;&gt;</span>";
        $header .= "</div></div>";
        // body  Create Calendar!!
        $table = "<div class='table-calendar'><table><tr class='{$class_head}'>";
      
            foreach( $array_tag as $key=>$value){
                if( $key == 0){
                    $table .= "<td class='{$class_first}'>$value</td>";
                }else if($key == 6){
                    $table .= "<td class='{$class_last}'>$value</td>";
                }else{
                    $table .= "<td class='{$class_mid}'>$value</td>";
                }
            }
        $table .= '</tr>';

        $ym = $this->getValueFromFomat($date,'Y-m');
        $timestamp = strtotime( $date );
        $today = date('Y-m-d');
        $day_count = date('t', $timestamp);
        $str = date('w', mktime(0, 0, 0, date('m', $timestamp) , 1, date('Y', $timestamp)));
        $weeks = array();
        $week = '';

        for( $i =0 ; $i< $str ; $i++ ){
            if( $i == 0 ){
                $week .= "<td class='{$class_first}'>-</td>";
            }else if( $i == 6 ){
                $week .= "<td class='{$class_last}'>-</td>";
            }else{
                $week .= "<td class='{$class_mid}'>-</td>";
            }
        }

        for ( $day = 1; $day <= $day_count; $day++, $str++) {

            $class = $class_mid;
            $date = $ym . '-' . $day;

            if($str % 7 == 0 ){
                $class = $class_first;
            }else if( $str % 7 == 6 ){
                $class = $class_last;
            }

            if ($today == $date) {
                $week .= "<td class='{$class} today'>" . $day;
            } else {
                $week .= "<td  class={$class}>" . $day;
            }
            $week .= "</td>";
             

            if ($str % 7 == 6 || $day == $day_count) {
                // end cells empty
                if ($day == $day_count) {
                    $ss = $str +1;
                    $limit = 6 - ($str % 7);
                    for( $j = 0; $j< 6 - ($str % 7) ; $j++ , ++$ss){
                        if($ss % 7 == 0 ){
                            $class = $class_first;
                        }else if( $ss % 7 == 6 ){
                            $class = $class_last;
                        }else{
                            $class = $class_mid;
                        }
                        $week .= "<td class='{$class}'>-</td>";
                    }
                }  
                // loop 
                $weeks[] = "<tr class='{$class_number}'>" . $week . '</tr>';
                
                $week = '';
            }
        }

        foreach ($weeks as $week) {
            $table.=$week;
        }
        // $table.='<tr class="number">
        //                 <td class="first">-</td> <td class="mid">1</td> <td class="mid">2</td> <td class="mid">3</td> <td class="mid">4</td> <td class="mid">5</td> <td class="last">6</td>
        //             </tr>
        //             <tr class="anphabe">
        //                 <td class="first"></td> <td class="color-s">S</td> <td class= "color-a">A</td> <td class="color-b">B</td> <td class="color-c">C</td> <td class="color-d">D</td> <td class="color-s">S</td>
        //             </tr>
        //         </table></div>';
        $html =  $header. $table;

        return  $html;
    }
}
