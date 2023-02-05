<?= view("includes/head"); ?>

<style type="text/css"> /* Styles of the 404 page of my website. */
    body {
        background: #C6ECFF;
        color: #d3d7de;
        font-family: "Courier new";
        font-size: 18px;
        line-height: 1.5em;
        cursor: default;
    }

    a {
        background: #3DA4FF;
        text-decoration: none;
        color: #fff;
        border: 1px solid #3DA4FF;
        padding: 6px 20px;
        border-radius: 16px;
        margin-top: 36px;
    }

    .code-area {
        position: absolute;
        width: 400px;
        min-width: 320px;
        top: 50%;
        left: 50%;
        -webkit-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
    }

    .code-area > span {
        display: block;
    }
    p{
        font-size: 15px;
        line-height: 20px;
        color: #0082FF;
        opacity: 0.8;
    }
    img{
        width: 250px;
    }

    @media screen and (max-width: 320px) {
        .code-area {
            font-size: 5vw;
            min-width: auto;
            width: 95%;
            margin: auto;
            padding: 5px;
            padding-left: 10px;
            line-height: 6.5vw;
        }
    } </style>

<body>
<div class="code-area">
    <img src="/uploads/app/logo.png" class="img-responsive">
    <span style="color: red;font-style: italic;font-size: 22px;opacity: 0.6;font-weight: bold;margin-top: 40px;">Page Not Found</span>
    <p>We looked everywhere for this page.</p>
    <p>Are you sure the Website URL is correct?</p>
    <p>Get in touch with the site owner</p><br>
    <a href="<?= url(""); ?>">Go Back Home</a>
</div>
<?php die; ?>
</body>


</html>