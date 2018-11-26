<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Calendar</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <div class="content">
                {!! $table !!}
            </div>
        </div>
        <script >   
        function ChangeMonth( date ){
            //var date = $(this).attr('data');
            var id= "1";
            $.ajax({
                method: "GET",
                url: "/getCalendar",
                data: { date: date, id: id } ,
                success:function(result,status,xhr){
                    $('.content').html(result);
                },
                error:function(xhr,error){
                    console.log( error );
                }
            });
        }

        $(function() {
            $('#viewcalendar td:not(".empty")').on('click',function(){
                console.log( $(this).attr('data') );
            });
        });
            
        </script>
    </body>
</html>
