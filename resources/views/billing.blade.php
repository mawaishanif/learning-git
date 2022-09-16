<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">

    <style>
        @import url(https://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:400,700,300);
        body {
            font-family: 'Yanone Kaffeesatz', sans-serif;
        }

        body a {
            text-decoration: none;
            color: white;
        }

        body .container {
            width: 938px;
            position: absolute;
            top: 50%;
            left: 30px;
            right: 0;
            -webkit-transform: translateY(-50%);
            transform: translateY(-50%);
            margin: auto;
        }

        body .container .card {
            margin: 0 auto;
            display: inline-block;
            margin-right: 30px;
            -webkit-transform: scale(0);
            transform: scale(0);
            width: 280px;
            text-align: center;
            position: relative;
            -webkit-transition: all .2s;
            transition: all .2s;
            cursor: pointer;
            opacity: 0.5;
            box-shadow: 0px 17px 46px -10px #777777;
            height: 470px;
            border-radius: 14px;
        }

        body .container .card:nth-of-type(1) {
            -webkit-animation: intro 1s 0.1s forwards;
            animation: intro 1s 0.1s forwards;
        }

        body .container .card:nth-of-type(2) {
            -webkit-animation: intro 1s 0.2s forwards;
            animation: intro 1s 0.2s forwards;
        }

        body .container .card:nth-of-type(3) {
            -webkit-animation: intro 1s 0.3s forwards;
            animation: intro 1s 0.3s forwards;
        }

        body .container .card:nth-of-type(1) {
            background: -webkit-linear-gradient(45deg, #c96881 0%, #f7b695 100%);
            background: -moz-linear-gradient(45deg, #c96881 0%, #f7b695 100%);
        }

        body .container .card:nth-of-type(2) {
            background: -webkit-linear-gradient(45deg, #6B6ECC 0%, #89BFDF 100%);
            background: -moz-linear-gradient(45deg, #6B6ECC 0%, #89BFDF 100%);
        }

        body .container .card:nth-of-type(3) {
            background: -webkit-linear-gradient(45deg, #81B77B 0%, #A3E3C3 100%);
            background: -moz-linear-gradient(45deg, #81B77B 0%, #A3E3C3 100%);
        }

        body .container .card:hover .card_inner__header img {
            left: -50px;
            -webkit-transition: all 3.4s linear;
            transition: all 3.4s linear;
        }

        body .container .card:hover .card_inner__cta button {
            -webkit-transform: scale(1);
            transform: scale(1);
        }

        body .container .card:nth-of-type(1):hover .card_inner__circle img {
            -webkit-animation: launch 1s forwards;
            animation: launch 1s forwards;
        }

        body .container .card:nth-of-type(1) .card_inner__circle img {
            top: 22px;
            left: 1px;
        }

        body .container .card:nth-of-type(2):hover .card_inner__circle img {
            -webkit-animation: spin 1s forwards;
            animation: spin 1s forwards;
        }

        body .container .card:nth-of-type(2) .card_inner__circle img {
            top: 22px;
        }

        body .container .card:nth-of-type(3):hover .card_inner__circle img {
            -webkit-animation: fly 1s forwards;
            animation: fly 1s forwards;
        }

        body .container .card:nth-of-type(3) .card_inner__circle img {
            top: 22px;
            left: 1px;
        }

        body .container .card:hover {
            opacity: 1;
        }

        body .container .card_inner__circle {
            overflow: hidden;
            width: 70px;
            position: absolute;
            background: #F1F0ED;
            z-index: 10;
            height: 70px;
            border-radius: 100px;
            left: 0;
            box-shadow: 0px 7px 20px rgba(0, 0, 0, 0.28);
            right: 0;
            margin: auto;
            border: 4px solid white;
            top: 82px;
        }

        body .container .card_inner__circle img {
            height: 26px;
            position: relative;
            top: 17px;
            -webkit-transition: all .2s;
            transition: all .2s;
        }

        body .container .card_inner__header {
            height: 120px;
            border-top-left-radius: 14px;
            border-top-right-radius: 14px;
            overflow: hidden;
        }

        body .container .card_inner__header img {
            width: 120%;
            position: relative;
            top: -30px;
            left: 0;
            -webkit-transition: all 0.1s linear;
            transition: all 0.1s linear;
        }

        body .container .card_inner__content {
            padding: 20px;
        }

        body .container .card_inner__content .price {
            color: white;
            font-weight: 800;
            font-size: 70px;
            text-shadow: 0px 0px 10px rgba(0, 0, 0, 0.42);
        }

        body .container .card_inner__content .text {
            color: rgba(255, 255, 255, 0.6);
            font-weight: 100;
            margin-top: 20px;
            font-size: 13px;
            line-height: 16px;
        }

        body .container .card_inner__content .title {
            font-weight: 800;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.64);
            margin-top: 40px;
            font-size: 25px;
            letter-spacing: 1px;
        }

        body .container .card_inner__cta {
            position: absolute;
            bottom: -24px;
            left: 0;
            right: 0;
            margin: auto;
            width: 200px;
        }

        body .container .card_inner__cta button {
            padding: 16px;
            box-shadow: 0px 0px 40px 4px #F76583, 0px 0px 0px 2px rgba(255, 255, 255, 0.19) inset;
            width: 100%;
            background: -webkit-linear-gradient(-90deg, #fe5e7d 0%, #e5375b 100%);
            background: -moz-linear-gradient(-90deg, #fe5e7d 0%, #e5375b 100%);
            border: none;
            font-family: 'Yanone Kaffeesatz', sans-serif;
            color: white;
            outline: none;
            font-size: 20px;
            border-radius: 6px;
            -webkit-transform: scale(0.94);
            transform: scale(0.94);
            cursor: pointer;
            -webkit-transition: box-shadow .3s, -webkit-transform .3s .1s;
            transition: box-shadow .3s, -webkit-transform .3s .1s;
            transition: box-shadow .3s, transform .3s .1s;
            transition: box-shadow .3s, transform .3s .1s, -webkit-transform .3s .1s;
        }

        body .container .card_inner__cta button span {
            text-shadow: 0px 4px 18px #BA3F57;
        }

        body .container .card_inner__cta button:hover {
            box-shadow: 0px 0px 60px 8px #F76583, 0px 0px 0px 2px rgba(255, 255, 255, 0.19) inset;
        }

        @-webkit-keyframes launch {
            0% {
                left: 1px;
            }
            25% {
                top: -50px;
                left: 1px;
            }
            50% {
                left: -100px;
            }
            75% {
                top: 100px;
                -webkit-transform: rotate(40deg);
                transform: rotate(40deg);
            }
            100% {
                left: 1px;
            }
        }

        @keyframes launch {
            0% {
                left: 1px;
            }
            25% {
                top: -50px;
                left: 1px;
            }
            50% {
                left: -100px;
            }
            75% {
                top: 100px;
                -webkit-transform: rotate(40deg);
                transform: rotate(40deg);
            }
            100% {
                left: 1px;
            }
        }

        @-webkit-keyframes fly {
            0% {
                left: 0px;
            }
            25% {
                top: -50px;
                left: 50px;
            }
            50% {
                left: -130px;
            }
            75% {
                top: 60px;
            }
            100% {
                left: 0px;
            }
        }

        @keyframes fly {
            0% {
                left: 0px;
            }
            25% {
                top: -50px;
                left: 50px;
            }
            50% {
                left: -130px;
            }
            75% {
                top: 60px;
            }
            100% {
                left: 0px;
            }
        }

        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(720deg);
                transform: rotate(720deg);
            }
        }

        @keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(720deg);
                transform: rotate(720deg);
            }
        }

        @-webkit-keyframes intro {
            0% {
                -webkit-transform: scale(0);
                transform: scale(0);
            }
            25% {
                -webkit-transform: scale(1.06);
                transform: scale(1.06);
            }
            50% {
                -webkit-transform: scale(0.965);
                transform: scale(0.965);
            }
            75% {
                -webkit-transform: scale(1.02);
                transform: scale(1.02);
            }
            100% {
                -webkit-transform: scale(1);
                transform: scale(1);
            }
        }

        @keyframes intro {
            0% {
                -webkit-transform: scale(0);
                transform: scale(0);
            }
            25% {
                -webkit-transform: scale(1.06);
                transform: scale(1.06);
            }
            50% {
                -webkit-transform: scale(0.965);
                transform: scale(0.965);
            }
            75% {
                -webkit-transform: scale(1.02);
                transform: scale(1.02);
            }
            100% {
                -webkit-transform: scale(1);
                transform: scale(1);
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <section class="card">
            <div class="card_inner">
                <div class="card_inner__circle">
                    <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/217233/rocket.png">
                </div>
                <div class="card_inner__header">
                    <img src="http://www.pixeden.com/media/k2/galleries/343/002-city-vector-background-town-vol2.jpg">
                </div>
                <div class="card_inner__content">
                    <div class="title">Free Plan</div>
                    <div class="price">$0.00</div>
                    <div class="text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur at posuere eros. Interdum et malesuada fames ac ante ipsum primis in faucibus.
                        <br>
                        <br>Fusce sed tortor in orci ultrices tempor quis ut leo. Fusce imperdiet eget ante eu faucibus. Nam rhoncus sapien</div>
                </div>
                <div class="card_inner__cta">
                    <button class="free-plan" >
                        <span><a href="{{ route('billing', ['plan' => $plans[0]->id]) }}">Get Access</a></span>
                    </button>
                </div>
            </div>
        </section>
        <section class="card">
            <div class="card_inner">
                <div class="card_inner__circle">
                    <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/217233/cog.png">
                </div>
                <div class="card_inner__header">
                    <img src="http://4vector.com/i/free-vector-modern-city_093317_bluecity.jpg">
                </div>
                <div class="card_inner__content">
                    <div class="title">Basic{{ $plan_status }}</div>
                    <div class="price">${{ $plans[0]->price }}</div>
                    <div class="text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur at posuere eros. Interdum et malesuada fames ac ante ipsum primis in faucibus.
                        <br>
                        <br>Fusce sed tortor in orci ultrices tempor quis ut leo. Fusce imperdiet eget ante eu faucibus. Nam rhoncus sapien</div>
                </div>
                <div class="card_inner__cta">
                    <button>
                        <span>
                            <a href="{{ route('billing', ['plan' => $plans[0]->id]) }}">Buy Now</a>
                        </span>
                    </button>
                </div>
            </div>
        </section>
        <section class="card">
            <div class="card_inner">
                <div class="card_inner__circle">
                    <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/217233/paperplane.png">
                </div>
                <div class="card_inner__header">
                    <img src="http://7428.net/wp-content/uploads/2013/06/Forest-Creek.jpg">
                </div>
                <div class="card_inner__content">
                    <div class="title">Premium</div>
                    <div class="price">${{ $plans[1]->price }}</div>
                    <div class="text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur at posuere eros. Interdum et malesuada fames ac ante ipsum primis in faucibus.
                        <br>
                        <br>Fusce sed tortor in orci ultrices tempor quis ut leo. Fusce imperdiet eget ante eu faucibus. Nam rhoncus sapien</div>
                </div>
                <div class="card_inner__cta">
                    <button>
                        <a href="{{ route('billing', ['plan' => $plans[1]->id]) }}">
                            <span>Buy now</span>
                        </a>
                    </button>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
