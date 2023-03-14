<?php
$frontend_url = url("views/frontend");
?>
<!DOCTYPE html>
<html>

<head>
    <!-- basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- mobile metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <!-- site metas -->
    <title><?= env("APP_NAME") ?></title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- bootstrap css -->
    <link rel="stylesheet" href="<?= $frontend_url; ?>css/bootstrap.min.css">
    <!-- style css -->
    <link rel="stylesheet" href="<?= $frontend_url; ?>css/style.css">
    <!-- Responsive-->
    <link rel="stylesheet" href="<?= $frontend_url; ?>css/responsive.css">
    <!-- fevicon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?= url("/"); ?>uploads/app/{{ env('APP_ICON'); }}">
    <!-- Tweaks for older IEs-->
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
</head>

<body class="main-layout">
    <div class="header">
        <div class="container">
            <div class="row d_flex">
                <div class=" col-md-2 col-sm-3 col logo_section">
                    <div class="full">
                        <div class="center-desk">
                            <div class="logo">
                                <a href="index.html"><img src="<?= url("/"); ?>uploads/app/{{ env('APP_LOGO'); }}" alt="#" /></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 col-sm-12">
                    <nav class="navigation navbar navbar-expand-md navbar-dark ">
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarsExample04">
                            <ul class="navbar-nav mr-auto">
                                <li class="nav-item nav-id-">
                                    <a class="nav-link" href="<?= url("Frontend@home"); ?>">Home</a>
                                </li>
                                <li class="nav-item nav-id-about">
                                    <a class="nav-link" href="<?= url("Frontend@about"); ?>">About</a>
                                </li>
                                <li class="nav-item nav-id-contact">
                                    <a class="nav-link" href="<?= url("Frontend@contact"); ?>">Contact Us</a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
                <div class="d_none" style="width: 195px;">
                    <ul class="email text_align_right">
                        @if( empty($user))
                        <li>
                            <a href="<?= url("Auth@get"); ?>">Sign In</a>
                        </li>
                        @else
                        <li>
                            <span class="avatar">
                                @if( !empty($user->avatar) )
                                <img width="30" src="<?= url("/"); ?>uploads/avatar/{{ $user->avatar }}" class="user-avatar img-circle">
                                @else
                                <img width="30" src="<?= url("/"); ?>assets/images/avatar.png" class="user-avatar">
                                @endif
                            </span>&nbsp;
                            <span>
                                <a href="<?= url("Dashboard@get"); ?>" style="font-size: inherit; text-transform: inherit; color: #666666;">{{ $user->fname }}</a>
                            </span>
                        </li>
                        <li>
                            <a href="<?= url("Auth@signout"); ?>">Logout</a>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- end header inner -->