{{ view("includes/head", $data); }}
<body>

{{ view("includes/header", $data); }}
{{ view("includes/sidebar", $data); }}

<div class="content">
{{ view("payment/credit_card_form", $data); }}
</div>

{{ view("includes/footer"); }}

