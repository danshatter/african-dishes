<?php

namespace App\Error;

use Slim\Error\AbstractErrorRenderer;
use Throwable;

class HtmlErrorRenderer extends AbstractErrorRenderer
{
    
    public function __invoke(Throwable $exception, bool $displayErrorDetails): string
    {
        return '
            <!DOCTYPE html>
            <html lang="en">
                <head>
                    <!-- Required meta tags -->
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            
                    <!-- Bootstrap CSS -->
                    <link rel="stylesheet" href="/css/bootstrap.min.css">
                    <link rel="stylesheet" href="/fontawesome/css/all.min.css" />
                    <link rel="stylesheet" href="/css/europa-font.css" />
                    <link rel="stylesheet" href="/css/style.css">
                    <title>'.$exception->getTitle().'</title>
                </head>
                <body style="min-height:100%">
                    <div class="main-content">
                        <div class="error-sect">
                            <h1>'.$exception->getCode().'</h1>
                            <h2>'.strtoupper($exception->getMessage()).'</h2>
                            <p class="text-center">'.ucwords($exception->getDescription()).'</p>
                            <a href="/" class="btn btn-main">back Home</a>
                        </div>
                    </div>
                    <footer class="main-footer">
                        <div class="footer-sect">
                            <p class="text-center"><small>&copy; 2020. Chrystacripsy Kitchen Services. All Right Reserved</small></p>
                        </div>
                    </footer>
                    
                    <script src="/js/jquery.min.js"></script>
                    <script src="/js/bootstrap.bundle.min.js"></script>
                    <script src="/js/app.js"></script>
                </body>
            </html>';

    }

}
