<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PHPUnit\Framework\Exception;

class calendarController extends Controller
{
    public function index(){
        $beginDate = date('Y-m-01');
        //$beginDate = "2018-12-01";
        $id = 1;
        $calendar = $this->getArrayDayByYearMonth( $beginDate );
        $calendarHotel = $this->getCalendarHotel( $beginDate ,  $id );
        
        $html = $this->drawingHtml( $beginDate , $calendar , $calendarHotel  );

        $data = ['table' => $html ];
        return view('calendar', $data );
    }

    private function getArrayDayByYearMonth( $date ){
        $ym = $this->getValueFromFomat($date,'Y-m');
        $calendar = DB::table('calendars')
                        ->select('id', 'date_ymd','holiday_status','hotel_holiday_status')
                        ->where(DB::raw("(DATE_FORMAT(date_ymd,'%Y-%m'))"),$ym )->get();
        foreach($calendar as $item){
            $item->date_ymd = date_format( date_create($item->date_ymd) ,'Y-m-d');
        }
        return $calendar;
    }
    
    private function getCalendarHotel( $date , $id ){

        $ym = $this->getValueFromFomat($date,'Y-m');
        $calendar = DB::table('hotel_calendars')
                        ->select('id', 'hotel_id' ,'date_ymd','rank_status')
                        ->where('hotel_id',$id)
                        ->where(DB::raw("(DATE_FORMAT(date_ymd,'%Y-%m'))"),$ym )->get();
        foreach($calendar as $item){
            $item->date_ymd = date_format( date_create($item->date_ymd) ,'Y-m-d');
        }
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

    private function getStatusByDay(  $calendar , $option , $date ){
        foreach( $calendar as $item) {
            if ( $date == $item->date_ymd) {
                if( $option == 0){
                    return $item->holiday_status;
                }else{
                    return $item->hotel_holiday_status;
                }
            }
        }
        return 2;
    }

    private function getStatusRank(  $calendar , $date ){
        foreach( $calendar as $item) {
            if ( $date == $item->date_ymd) {
                return $item->rank_status;
            }
        }
        return "";
    }
    private function drawingHtml( $date , $calendar , $calendarHotel ){
        try{
            // declare available
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
            $class_Holiday = "holiday";
            $class_Holiday_Hotel = "holiday_hotel";
            $array_tag =  [ "日" , "月" , "火" , "水" , "木" , "金" , "土" ];
            $array_anPha =  [ "S" , "A" , "B" , "C" , "D" ];

            $nextDate = $this->getNextMonth($date);
            $previousDate = $this->getPreviousMonth($date);
            $nextMonth = $this->getValueFromFomat($nextDate , 'm月');
            $previousMonth = $this->getValueFromFomat($previousDate,'m月');
            $dateYM = $this->getValueFromFomat($date,'Y年m月');

            // header
            $header  =  "<div class='header-content'><div class='currentYD' data='{$date}'>";
            $header .= "<span>{$dateYM}</span>";
            $header .= "</div> <div class='nav-month'>";
            $header .= "<span data='{$previousDate}' class='privous' onclick='ChangeMonth(`$previousDate`)'>&lt;&lt;&nbsp;{$previousMonth}</span>";
            $header .= "<span data='{$nextDate}' class='next' onclick='ChangeMonth(`$nextDate`)'>{$nextMonth}&nbsp;&gt;&gt;</span>";
            $header .= "</div></div>";

            // Create header Calendar!!
            $table = "<div class='table-calendar'><table id='viewcalendar'><tr class='{$class_head}'>";
                foreach( $array_tag as $key=>$value){
                    if( $key == 0){
                        $table .= "<td class='empty {$class_first}'>$value</td>";
                    }else if($key == 6){
                        $table .= "<td class='empty {$class_last}'>$value</td>";
                    }else{
                        $table .= "<td class='empty {$class_mid}'>$value</td>";
                    }
                }
            $table .= '</tr>';

            $ym = $this->getValueFromFomat($date,'Y-m');
            $timestamp = strtotime( $date );
            $today = date('Y-m-d');
            $day_count = date('t', $timestamp);
            $str = date('w', mktime(0, 0, 0, date('m', $timestamp) , 1, date('Y', $timestamp)));
            $weeks = array();
            $week_number = "";
            $week_anpha = "";

            // Cell empty in head calendar
            for( $i = 0 ; $i< $str ; $i++ ){
                if( $i == 0 ){
                    $week_number .= "<td class='{$class_first} empty'>-</td>";
                    $week_anpha .= "<td class='{$class_first} empty'></td>";
                }else if( $i == 6 ){
                    $week_number .= "<td class='{$class_last} empty'>-</td>";
                    $week_anpha .= "<td class='{$class_last} empty'></td>";
                }else{
                    $week_number .= "<td class='{$class_mid} empty'>-</td>";
                    $week_anpha .= "<td class='{$class_mid} empty'></td>";
                }
            }
            // Cell in body calendar
            for ( $day = 1; $day <= $day_count; $day++, $str++) {

                $status_holiday = 0;
                $status_holiday_hotel = 0;
                $class = $class_mid;
                $date = $ym . '-' . $day;

                $status_holiday = $this->getStatusByDay($calendar,0,$date);
                $status_holiday_hotel = $this->getStatusByDay($calendar,1,$date);
                $rank_status = $this->getStatusRank( $calendarHotel , $date);

                if($str % 7 == 0 ){
                    $class = $class_first;
                }else if( $str % 7 == 6 ){
                    $class = $class_last;
                }

                if ($today == $date) {
                    $week_number .= "<td data='$day' class='{$class} today'>" . $day;
                } else {
                    $week_number .= "<td data='$day' class='{$class}'>" . $day;
                }
                $week_number .= "</td>";

                switch( $rank_status ){
                    case "S" : $class = $class_Color_s; break;
                    case "A" : $class = $class_Color_a; break;
                    case "B" : $class = $class_Color_b; break;
                    case "C" : $class = $class_Color_c; break;
                    case "D" : $class = $class_Color_d; break;
                    default: break;
                }
                
                $week_anpha .= "<td data='$day'  class='{$class} ";
                if( $status_holiday == 1){
                    $week_anpha .= " {$class_Holiday}";
                }
                if(  $status_holiday_hotel == 1){
                    $week_anpha .= " {$class_Holiday_Hotel}";
                }
                $week_anpha .= " '>";
                $week_anpha .= $rank_status ."</td> ";
            
                if ($str % 7 == 6 || $day == $day_count) {
                    // end cells empty in foot calendar
                    if ($day == $day_count) {
                        $ss = $str +1;
                        $limit = 6 - ($str % 7);
                        for( $j = 0; $j< $limit  ; $j++ , ++$ss){
                            if($ss % 7 == 0 ){
                                $class = $class_first;
                            }else if( $ss % 7 == 6 ){
                                $class = $class_last;
                            }else{
                                $class = $class_mid;
                            }
                            $week_number .= "<td class='{$class} empty'>-</td>";
                            $week_anpha .= "<td class='{$class} empty'></td>";
                        }
                    }  
                    // loop 
                    $weekRowAnpha = "<tr class='{$class_anphabe}'>" . $week_anpha . "</tr>";
                    $weekRowNumber = "<tr class='{$class_number}'>" . $week_number . "</tr>";
                    $weeks[] = $weekRowNumber.$weekRowAnpha;
                    
                    $week_number = "";
                    $week_anpha = "";
                }
            }

            foreach ($weeks as $week) {
                $table.=$week;
            }
            $html =  $header. $table;

            return  $html;
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getCalendarHtml( ){
        $date = $_GET['date'];
        $idHotel = $_GET['id'];
        $calendar = $this->getArrayDayByYearMonth( $date );
        $calendarHotel = $this->getCalendarHotel( $date ,  $idHotel );
        $html = $this->drawingHtml( $date , $calendar , $calendarHotel  );
        echo $html;

    }
}
