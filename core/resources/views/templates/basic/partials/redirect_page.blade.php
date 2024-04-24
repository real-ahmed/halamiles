<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirect Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin: 100px;
        }

        .illustration {
            margin-top: 20px;
        }

        .gift-box {
            width: 100px;
            height: 100px;

            display: flex;
            align-items: center;
            justify-content: center;
        }

        .gift-box img {
            max-width: 80px;
            max-height: 80px;
        }

        .points {
            color: #1e90ff;
            font-weight: bold;
        }

        .button {
            margin: 10px;
            color: black;
            border: none;
            padding: 15px 20px;
            border: solid rgb(37, 35, 35) 1px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            /* Added */
            display: inline-block;
            /* Added */
        }

        .button:hover {
            background-color: #187bcd;
        }

        .info-text {
            font-size: 20px;
            color: #F56D11;
            margin-top: 10px;
        }

        .gifts {
            display: flex;
            flex-direction: row;
            justify-content: center;
            padding: 0 255px;
        }


        @media screen and (max-width: 760px) {
            body {
                margin: 23px;
            }

            .gifts {

                padding: unset;
            }
        }
    </style>
</head>

<body>
    <h1>@lang('You are being redirected...')</h1>
    <div class="illustration">
        <div class="gifts">
            <div class="gift-box">
                <img src="https://halamiles.com/assets/images/logoIcon/logo.png" alt="Gift Box">
            </div>
            <div class="gift-box" style="width: 150px; ">
                <svg width="300" height="60" viewBox="0 0 203 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M202.707 8.70711C203.098 8.31658 203.098 7.68342 202.707 7.29289L196.343 0.928932C195.953 0.538408 195.319 0.538408 194.929 0.928932C194.538 1.31946 194.538 1.95262 194.929 2.34315L200.586 8L194.929 13.6569C194.538 14.0474 194.538 14.6805 194.929 15.0711C195.319 15.4616 195.953 15.4616 196.343 15.0711L202.707 8.70711ZM0 9H2.02L2.02 7H0L0 9ZM6.06 9H10.1V7L6.06 7V9ZM14.14 9H18.18V7L14.14 7V9ZM22.22 9L26.26 9V7L22.22 7V9ZM30.3 9L34.34 9V7L30.3 7V9ZM38.38 9L42.42 9V7L38.38 7V9ZM46.46 9L50.5 9V7L46.46 7V9ZM54.54 9H58.58V7L54.54 7V9ZM62.62 9H66.66V7L62.62 7V9ZM70.7 9H74.74V7H70.7V9ZM78.78 9H82.82V7L78.78 7V9ZM86.86 9L90.9 9V7L86.86 7V9ZM94.94 9L98.98 9V7L94.94 7V9ZM103.02 9L107.06 9V7L103.02 7V9ZM111.1 9L115.14 9V7L111.1 7V9ZM119.18 9L123.22 9V7L119.18 7V9ZM127.26 9H131.3V7L127.26 7V9ZM135.34 9H139.38V7L135.34 7V9ZM143.42 9H147.46V7L143.42 7V9ZM151.5 9H155.54V7L151.5 7V9ZM159.58 9L163.62 9V7L159.58 7V9ZM167.66 9L171.7 9V7L167.66 7V9ZM175.74 9L179.78 9V7L175.74 7V9ZM183.82 9L187.86 9V7L183.82 7V9ZM191.9 9L195.94 9V7L191.9 7V9ZM199.98 9H202V7L199.98 7V9ZM202.707 8.70711C203.098 8.31658 203.098 7.68342 202.707 7.29289L196.343 0.928932C195.953 0.538408 195.319 0.538408 194.929 0.928932C194.538 1.31946 194.538 1.95262 194.929 2.34315L200.586 8L194.929 13.6569C194.538 14.0474 194.538 14.6805 194.929 15.0711C195.319 15.4616 195.953 15.4616 196.343 15.0711L202.707 8.70711ZM0 9H2.02L2.02 7H0L0 9ZM6.06 9H10.1V7L6.06 7V9ZM14.14 9H18.18V7L14.14 7V9ZM22.22 9L26.26 9V7L22.22 7V9ZM30.3 9L34.34 9V7L30.3 7V9ZM38.38 9L42.42 9V7L38.38 7V9ZM46.46 9L50.5 9V7L46.46 7V9ZM54.54 9H58.58V7L54.54 7V9ZM62.62 9H66.66V7L62.62 7V9ZM70.7 9H74.74V7H70.7V9ZM78.78 9H82.82V7L78.78 7V9ZM86.86 9L90.9 9V7L86.86 7V9ZM94.94 9L98.98 9V7L94.94 7V9ZM103.02 9L107.06 9V7L103.02 7V9ZM111.1 9L115.14 9V7L111.1 7V9ZM119.18 9L123.22 9V7L119.18 7V9ZM127.26 9H131.3V7L127.26 7V9ZM135.34 9H139.38V7L135.34 7V9ZM143.42 9H147.46V7L143.42 7V9ZM151.5 9H155.54V7L151.5 7V9ZM159.58 9L163.62 9V7L159.58 7V9ZM167.66 9L171.7 9V7L167.66 7V9ZM175.74 9L179.78 9V7L175.74 7V9ZM183.82 9L187.86 9V7L183.82 7V9ZM191.9 9L195.94 9V7L191.9 7V9ZM199.98 9H202V7L199.98 7V9Z"
                        fill="url(#paint0_linear_22_28)" />
                    <defs>
                        <linearGradient id="paint0_linear_22_28" x1="0" y1="8.5" x2="202" y2="8.5"
                            gradientUnits="userSpaceOnUse">
                            <stop stop-color="white" />
                            <stop offset="1" />
                        </linearGradient>
                    </defs>
                </svg>
            </div>
            <div class="gift-box">
                <img src="{{ $img }}" alt="Gift Box">
            </div>
        </div>
    </div>
    <div>
        <a class="button" href="{{ $url }}">@lang('Continue to Store')</a> <!-- Added href attribute -->
        <p class="info-text">@lang('It takes 3 - 7 days for your purchase to appear in your account.')</p>
        <p>@lang('Terms and conditions of the store within Halamiles are applicable once redirected.')</p>
    </div>

    <script>
        // JavaScript code for redirection after 5 seconds
        setTimeout(function() {
            window.location.href = '{{ $url }}';
        }, 3000); // 5000 milliseconds = 5 seconds
    </script>
</body>

</html>