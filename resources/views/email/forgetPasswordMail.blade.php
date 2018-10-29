{{--<!doctype html>--}}
{{--<html lang="en">--}}
{{--<head>--}}
    {{--<meta charset="UTF-8">--}}
    {{--<meta name="viewport"--}}
          {{--content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">--}}
    {{--<meta http-equiv="X-UA-Compatible" content="ie=edge">--}}
    {{--<title>Document</title>--}}
{{--</head>--}}
{{--<body>--}}
{{--<title>salam kiri</title>--}}
{{--<p>{{$user->name}}</p>--}}
{{--<a>http://localhost/payebash/public/api/v1/user/resetPassword/{{$token->token}}</a>--}}
{{--</body>--}}
{{--</html>--}}



        <!doctype html>
<html lang="fa">
<head>
    <meta charset="utf-8">
    <title>بازیابی پسورد</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href='http://www.fontonline.ir/css/BYekan.css' rel='stylesheet' type='text/css'>
</head>
<style>
    *{
        margin: 0;
        padding: 0;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }
    body{
        direction: rtl;
        font-size: 14px;
        font-family:BYekan,'BYekan',tahoma;
    }
    a{
        color: #00BCD4;
    }
    a:hover{
        color: #0097A7;
    }
    :before{
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }
    .container{
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
        margin-right: auto;
        margin-left: auto;
    }
    .container:before{
        display: table;
        content: " ";
    }
    .row{
        margin-right: -15px;
        margin-left: -15px;
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-flex-wrap: wrap;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        justify-content: flex-start;
        align-items: baseline;
        align-content: flex-start;
    }
    .header{
        background: #fff;
        padding: 15px 45px 30px 45px;
        width: 100%;
        /* width: calc(100% - 90px); */
        box-shadow: 0 0 9px 0px #bdbdbd;
    }
    .header>div{
        border-right: 4px solid #FFA000;
        padding: 5px 10px;

    }
    .header h1{
        display: block;
    }
    .header span{
        display: block;
        margin-top: 5px;
    }
    .title{
        text-align: center;
        width: 100%;
        margin: 15px 0;
        font-size: 1.14em;
        font-weight: bold;
    }
    .text{
        line-height: 2;

    }
    .wrong{
        line-height: 2;
    }

</style>
<body>
<div class="container">
    <div class="row">
        <div class="header">
            <div>
                <h1>تریپ +</h1>
                <span>بازیابی پسورد</span>
            </div>
        </div>
        <div class="container">
            <div class="title">
                <p>کد بازیابی پسورد برای شما ارسال گردیده است</p>
            </div>
            <div class="text">
                <p>باسلام {{$username}}
                    <br/>
                    کد بازیابی برای شما ارسال گردیده است برای بازیابی پسورد بر روی لینک زیر کلیک کرده.
                </p>
                <br/>
                {{--s TODO::have to fix this part and link it to front--}}
                <a href="#">{{$token}}</a>
            </div>
            <div class="wrong">
                درصورتی که این پیام مال شما نیس به آن توجه ای نکنید.
                <br/>
                تیم فنی تریپ +
            </div>
        </div>

    </div>
</div>
</body>

